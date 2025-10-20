<?php

namespace App\Http\Controllers;

use App\Models\Sponsoring;
use App\Models\Partner;
use App\Models\Event;
use App\TypeSponsoring;
use Illuminate\Http\Request;

class SponsoringController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Sponsoring::with(['partner', 'event']);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('montant', 'like', "%{$search}%")
                  ->orWhereHas('partner', function($q2) use ($search) {
                      $q2->where('nom', 'like', "%{$search}%");
                  })
                  ->orWhereHas('event', function($q2) use ($search) {
                      $q2->where('title', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->where('type_sponsoring', $request->type);
        }

        // Filter by partner
        if ($request->has('partner_id') && $request->partner_id != '') {
            $query->where('partenaire_id', $request->partner_id);
        }

        // Filter by event
        if ($request->has('event_id') && $request->event_id != '') {
            $query->where('evenement_id', $request->event_id);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $sponsorings = $query->paginate(10)->appends($request->except('page'));
        
        // Get data for filters
        $types = TypeSponsoring::cases();
        $partners = Partner::all();
        $events = Event::all();

        return view('sponsoring.index', compact('sponsorings', 'types', 'partners', 'events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only organizer and admin can create sponsorings
        if (!in_array(auth()->user()->role, ['admin', 'organisateur'])) {
            abort(403, 'Unauthorized action.');
        }

        $partners = Partner::all();
        $events = Event::all();
        $typesSponsorings = TypeSponsoring::cases();
        
        return view('sponsoring.create', compact('partners', 'events', 'typesSponsorings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only organizer and admin can create sponsorings
        if (!in_array(auth()->user()->role, ['admin', 'organisateur'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'montant' => 'required|numeric|min:0',
            'type_sponsoring' => 'required|in:argent,materiel,logistique,autre',
            'date' => 'required|date',
            'partenaire_id' => 'required|exists:partners,id',
            'evenement_id' => 'required|exists:events,id',
        ]);

        Sponsoring::create($request->all());

        return redirect()->route('sponsoring.index')
            ->with('success', 'Sponsoring créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sponsoring = Sponsoring::with(['partner', 'event'])->findOrFail($id);
        return view('sponsoring.show', compact('sponsoring'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Only organizer and admin can edit sponsorings
        if (!in_array(auth()->user()->role, ['admin', 'organisateur'])) {
            abort(403, 'Unauthorized action.');
        }

        $sponsoring = Sponsoring::findOrFail($id);
        $partners = Partner::all();
        $events = Event::all();
        $typesSponsorings = TypeSponsoring::cases();
        
        return view('sponsoring.edit', compact('sponsoring', 'partners', 'events', 'typesSponsorings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Only organizer and admin can update sponsorings
        if (!in_array(auth()->user()->role, ['admin', 'organisateur'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'montant' => 'required|numeric|min:0',
            'type_sponsoring' => 'required|in:argent,materiel,logistique,autre',
            'date' => 'required|date',
            'partenaire_id' => 'required|exists:partners,id',
            'evenement_id' => 'required|exists:events,id',
        ]);

        $sponsoring = Sponsoring::findOrFail($id);
        $sponsoring->update($request->all());

        return redirect()->route('sponsoring.index')
            ->with('success', 'Sponsoring modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Only admin can delete sponsorings
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $sponsoring = Sponsoring::findOrFail($id);
        $sponsoring->delete();

        return redirect()->route('sponsoring.index')
            ->with('success', 'Sponsoring supprimé avec succès.');
    }

    /**
     * Export sponsoring as PDF
     */
    public function exportPdf($id)
    {
        $sponsoring = Sponsoring::with(['partner', 'event'])->findOrFail($id);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('sponsoring.pdf', compact('sponsoring'));
        
        return $pdf->download('sponsoring-' . $sponsoring->id . '-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Display statistics about sponsorings
     */
    public function statistics()
    {
        $stats = $this->getStatisticsData();
        return view('sponsoring.statistics', compact('stats'));
    }

    /**
     * Export statistics as PDF
     */
    public function statisticsPdf()
    {
        $stats = $this->getStatisticsData();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('sponsoring.statistics-pdf', compact('stats'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('statistiques-sponsorings-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Get statistics data (shared between view and PDF)
     */
    private function getStatisticsData()
    {
        return [
            'total_sponsorings' => Sponsoring::count(),
            'total_montant' => Sponsoring::sum('montant'),
            'average_montant' => Sponsoring::avg('montant'),
            
            // By type - using DB query to get raw string values
            'by_type' => \DB::table('sponsorings')
                ->select('type_sponsoring', \DB::raw('COUNT(*) as count'), \DB::raw('SUM(montant) as total'))
                ->groupBy('type_sponsoring')
                ->get(),
            
            // Top partners
            'top_partners' => Partner::withCount('sponsorings')
                ->withSum('sponsorings', 'montant')
                ->orderByDesc('sponsorings_sum_montant')
                ->limit(5)
                ->get(),
            
            // Recent sponsorings
            'recent_sponsorings' => Sponsoring::with(['partner', 'event'])
                ->orderByDesc('created_at')
                ->limit(10)
                ->get(),
            
            // Events with most sponsorings
            'top_events' => Event::withCount('sponsorings')
                ->withSum('sponsorings', 'montant')
                ->orderByDesc('sponsorings_sum_montant')
                ->limit(5)
                ->get(),
            
            // Monthly trend (last 6 months)
            'monthly_trend' => \DB::table('sponsorings')
                ->select(\DB::raw('DATE_FORMAT(date, "%Y-%m") as month'), \DB::raw('COUNT(*) as count'), \DB::raw('SUM(montant) as total'))
                ->where('date', '>=', now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
        ];
    }
}
