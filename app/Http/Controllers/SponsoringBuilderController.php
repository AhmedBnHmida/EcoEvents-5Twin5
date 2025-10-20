<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SponsoringBudgetOptimizerService;
use App\Services\SponsoringProposalGeneratorService;
use App\Models\Event;
use App\Models\Partner;
use App\TypeSponsoring;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class SponsoringBuilderController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    
    protected $budgetOptimizerService;
    protected $proposalGeneratorService;

    public function __construct(SponsoringBudgetOptimizerService $budgetOptimizerService, SponsoringProposalGeneratorService $proposalGeneratorService)
    {
        $this->budgetOptimizerService = $budgetOptimizerService;
        $this->proposalGeneratorService = $proposalGeneratorService;
    }

    public function index()
    {
        // Vérifier que l'utilisateur est admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent accéder à cette fonctionnalité.');
        }

        $events = Event::all();
        $partners = Partner::all();
        $sponsoringTypes = TypeSponsoring::cases();

        return view('sponsoring-builder.index', compact('events', 'partners', 'sponsoringTypes'));
    }

    public function optimizeBudget(Request $request)
    {
        // Vérifier que l'utilisateur est admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent accéder à cette fonctionnalité.');
        }

        try {
            $request->validate([
                'total_budget' => 'required|numeric|min:1',
                'event_ids' => 'required|array|min:1',
                'event_ids.*' => 'exists:events,id',
                'preferences' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->header('Content-Type') === 'application/json' || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        $totalBudget = $request->input('total_budget');
        $eventIds = $request->input('event_ids');
        $preferences = $request->input('preferences');

        try {
            // Récupérer les événements complets
            $events = Event::whereIn('id', $eventIds)->get();
            
            $optimizationResults = $this->budgetOptimizerService->optimize([
                'total_budget' => $totalBudget,
                'events' => $events,
                'preferences' => $preferences,
            ]);

            Session::put('optimization_results', $optimizationResults);
            Session::put('builder_total_budget', $totalBudget);

            // Forcer la réponse JSON pour les requêtes AJAX
            if ($request->header('Content-Type') === 'application/json' || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'optimization' => $optimizationResults,
                    'message' => 'Optimisation du budget effectuée avec succès !'
                ]);
            }

            return redirect()->route('sponsoring-builder.results')->with('success', 'Optimisation du budget effectuée avec succès !');
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'optimisation du budget: " . $e->getMessage());
            
            if ($request->header('Content-Type') === 'application/json' || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => "Erreur lors de l'optimisation du budget: " . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', "Erreur lors de l'optimisation du budget: " . $e->getMessage());
        }
    }

    public function results()
    {
        $optimizationResults = Session::get('optimization_results');
        $totalBudget = Session::get('builder_total_budget');

        if (!$optimizationResults) {
            return redirect()->route('sponsoring-builder.index')->with('error', 'Aucun résultat d\'optimisation trouvé. Veuillez d\'abord optimiser un budget.');
        }

        return view('sponsoring-builder.results', compact('optimizationResults', 'totalBudget'));
    }

    public function generateProposals(Request $request)
    {
        try {
            $request->validate([
                'allocations' => 'required|array|min:1',
                'allocations.*.partner_id' => 'required|exists:partners,id',
                'allocations.*.event_id' => 'required|exists:events,id',
                'allocations.*.amount' => 'required|numeric|min:0',
                'allocations.*.type' => ['required', 'string', new \App\Rules\ValidSponsoringType],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->header('Content-Type') === 'application/json' || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        $allocations = $request->input('allocations');
        $generatedProposals = [];

        foreach ($allocations as $allocation) {
            $partner = Partner::find($allocation['partner_id']);
            $event = Event::find($allocation['event_id']);

            if ($partner && $event) {
                try {
                    $proposal = $this->proposalGeneratorService->generate([
                        'partner' => $partner,
                        'event' => $event,
                        'amount' => $allocation['amount'],
                        'type' => $allocation['type'],
                    ]);
                    $generatedProposals[] = [
                        'allocation' => $allocation,
                        'partner' => $partner,
                        'event' => $event,
                        'proposal' => $proposal
                    ];
                } catch (\Exception $e) {
                    Log::error("Erreur lors de la génération de proposition pour {$partner->nom} et {$event->title}: " . $e->getMessage());
                    $generatedProposals[] = [
                        'allocation' => $allocation,
                        'partner' => $partner,
                        'event' => $event,
                        'proposal' => ['content' => 'Erreur de génération de proposition.']
                    ];
                }
            }
        }

        Session::put('generated_proposals', $generatedProposals);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'proposals' => $generatedProposals,
                'message' => 'Propositions générées avec succès !'
            ]);
        }

        return redirect()->route('sponsoring-builder.results')->with('success', 'Propositions générées avec succès !');
    }

    public function exportProposals()
    {
        $generatedProposals = Session::get('generated_proposals');

        if (!$generatedProposals) {
            return redirect()->route('sponsoring-builder.results')->with('error', 'Aucune proposition à exporter. Veuillez d\'abord générer des propositions.');
        }

        $pdf = Pdf::loadView('sponsoring-builder.proposals-pdf', ['proposals' => $generatedProposals]);
        return $pdf->download('propositions_sponsoring.pdf');
    }
}