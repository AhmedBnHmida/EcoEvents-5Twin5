<?php

namespace App\Services;

use App\Models\Partner;
use App\Models\Event;
use App\Models\Sponsoring;
use App\Services\OpenRouterService;

class SponsoringBudgetOptimizerService
{
    protected $openRouterService;

    public function __construct(OpenRouterService $openRouterService)
    {
        $this->openRouterService = $openRouterService;
    }

    /**
     * Optimise la répartition du budget de sponsoring
     */
    public function optimize(array $data): array
    {
        $totalBudget = $data['total_budget'];
        $events = $data['events'];
        $preferences = $data['preferences'] ?? '';

        // Récupérer tous les partenaires disponibles
        $partners = Partner::with('user')->get();
        
        // Récupérer l'historique des sponsorings pour l'analyse
        $historicalData = $this->getHistoricalData();

        // Construire le prompt pour l'IA
        $prompt = $this->buildOptimizationPrompt($totalBudget, $events, $partners, $historicalData, $preferences);

        // Appeler l'API OpenRouter via le service existant
        $aiResponse = $this->callOpenRouterAPI($prompt);

        // Parser la réponse de l'IA
        $optimization = $this->parseOptimizationResponse($aiResponse, $partners, $events, $totalBudget);

        return $optimization;
    }

    /**
     * Construit le prompt pour l'optimisation
     */
    private function buildOptimizationPrompt($totalBudget, $events, $partners, $historicalData, $preferences): string
    {
        $eventsInfo = $events->map(function($event) {
            $categoryName = $event->category ? $event->category->name : 'Sans catégorie';
            return "- {$event->title} ({$categoryName}) - {$event->start_date}";
        })->join("\n");

        $partnersInfo = $partners->map(function($partner) {
            return "- {$partner->nom} (Contact: {$partner->contact})";
        })->join("\n");

        return "Tu es un expert en marketing et sponsoring d'événements. 

BUDGET TOTAL: {$totalBudget}€

ÉVÉNEMENTS À SPONSORISER:
{$eventsInfo}

PARTENAIRES DISPONIBLES:
{$partnersInfo}

HISTORIQUE DES SPONSORINGS:
{$historicalData}

PRÉFÉRENCES: {$preferences}

Tâche: Propose une répartition optimale du budget entre les partenaires pour maximiser le ROI et la visibilité.

Réponds UNIQUEMENT avec un JSON valide dans ce format:
{
  \"allocations\": [
    {
      \"partner_id\": 1,
      \"event_id\": 1,
      \"amount\": 5000,
      \"type\": \"argent\",
      \"reasoning\": \"Explication de pourquoi ce partenaire pour cet événement\"
    }
  ],
  \"total_allocated\": 50000,
  \"roi_estimate\": \"Excellent\",
  \"strategy_summary\": \"Résumé de la stratégie globale\"
}

Assure-toi que:
- Le total alloué ne dépasse pas {$totalBudget}€
- Chaque allocation a un partner_id et event_id valides
- Le type est: argent, materiel, logistique, ou autre
- Inclus une explication claire pour chaque choix";
    }

    /**
     * Récupère les données historiques des sponsorings
     */
    private function getHistoricalData(): string
    {
        $sponsorings = Sponsoring::with(['partner', 'event'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        if ($sponsorings->isEmpty()) {
            return "Aucun historique de sponsoring disponible.";
        }

        $data = $sponsorings->map(function($sponsoring) {
            return "- {$sponsoring->partner->nom} → {$sponsoring->event->title}: {$sponsoring->montant}€ ({$sponsoring->type_sponsoring->value})";
        })->join("\n");

        return "Historique récent:\n{$data}";
    }

    /**
     * Appelle l'API OpenRouter via le service existant
     */
    private function callOpenRouterAPI(string $prompt): string
    {
        try {
            // Utiliser la méthode existante du service OpenRouter
            $response = $this->openRouterService->generateFeedbackRecommendation('Budget Optimization', $prompt);
            
            if ($response && isset($response['suggestion'])) {
                return $response['suggestion'];
            }

            throw new \Exception('Erreur API OpenRouter: Pas de réponse');
        } catch (\Exception $e) {
            // Fallback en cas d'erreur API
            return $this->getFallbackOptimization();
        }
    }

    /**
     * Parse la réponse de l'IA
     */
    private function parseOptimizationResponse(string $aiResponse, $partners, $events, $totalBudget): array
    {
        try {
            // Nettoyer la réponse pour extraire le JSON
            $jsonStart = strpos($aiResponse, '{');
            $jsonEnd = strrpos($aiResponse, '}') + 1;
            
            if ($jsonStart !== false && $jsonEnd !== false) {
                $jsonString = substr($aiResponse, $jsonStart, $jsonEnd - $jsonStart);
                $data = json_decode($jsonString, true);
                
                if ($data && isset($data['allocations'])) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            // En cas d'erreur de parsing, utiliser le fallback
        }

        return json_decode($this->getFallbackOptimization($partners, $events, $totalBudget), true);
    }

    /**
     * Optimisation de fallback en cas d'erreur API
     */
    private function getFallbackOptimization($partners = null, $events = null, $totalBudget = 5000): string
    {
        // Si on a des données réelles, les utiliser pour le fallback
        if ($partners && $events) {
            $allocations = [];
            $budgetPerEvent = $totalBudget / $events->count();
            
            foreach ($events as $index => $event) {
                $partner = $partners->get($index % $partners->count());
                $allocations[] = [
                    'partner_id' => $partner->id,
                    'partner_name' => $partner->nom,
                    'event_id' => $event->id,
                    'event_name' => $event->title,
                    'amount' => round($budgetPerEvent),
                    'type' => 'argent',
                    'reasoning' => "Répartition équilibrée pour {$partner->nom} et {$event->title}"
                ];
            }
            
            return json_encode([
                'allocations' => $allocations,
                'total_allocated' => $totalBudget,
                'roi_estimate' => 'Bon',
                'strategy_summary' => 'Répartition équilibrée automatique entre tous les partenaires et événements sélectionnés'
            ]);
        }
        
        // Fallback par défaut si pas de données
        return json_encode([
            'allocations' => [
                [
                    'partner_id' => 1,
                    'partner_name' => 'Partenaire par défaut',
                    'event_id' => 1,
                    'event_name' => 'Événement par défaut',
                    'amount' => 5000,
                    'type' => 'argent',
                    'reasoning' => 'Répartition équilibrée basée sur l\'historique'
                ]
            ],
            'total_allocated' => 5000,
            'roi_estimate' => 'Bon',
            'strategy_summary' => 'Stratégie de base appliquée en cas d\'erreur API'
        ]);
    }
}