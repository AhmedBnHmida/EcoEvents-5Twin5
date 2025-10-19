<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Feedback;
use App\Models\User;
use App\Notifications\EventAtRiskNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EventRiskDetectionService
{
    protected $apiKey;
    protected $baseUrl;
    protected $model;

    public function __construct()
    {
        $this->apiKey = env('OPENROUTER_API_KEY', '');
        $this->baseUrl = env('OPENROUTER_API_URL', 'https://openrouter.ai/api/v1');
        $this->model = env('OPENROUTER_MODEL', 'meta-llama/llama-3.3-70b-instruct:free');
        
        Log::info("🔍 EventRiskDetectionService initialized");
    }

    /**
     * Detect events at risk based on negative feedback
     * 
     * @return array Array of events at risk with their analysis
     */
    public function detectEventsAtRisk(): array
    {
        $eventsAtRisk = [];
        
        // Get all events with more than 3 feedback entries
        $events = Event::withCount('feedbacks')
            ->having('feedbacks_count', '>=', 3)
            ->get();
        
        foreach ($events as $event) {
            // Get all feedback for this event
            $feedbacks = $event->feedbacks()->with('participant')->get();
            
            // Count negative feedback (note < 3)
            $negativeFeedbacks = $feedbacks->filter(function ($feedback) {
                return $feedback->note < 3;
            });
            
            // If there are more than 3 negative feedbacks, mark as at risk
            if ($negativeFeedbacks->count() >= 3) {
                $analysis = $this->analyzeNegativeFeedback($event, $negativeFeedbacks);
                
                // Update event status
                $event->at_risk = true;
                $event->risk_analysis = $analysis;
                $event->save();
                
                // Notify organizers
                $this->notifyOrganizers($event, $analysis);
                
                $eventsAtRisk[] = [
                    'event' => $event,
                    'negative_count' => $negativeFeedbacks->count(),
                    'analysis' => $analysis
                ];
            }
        }
        
        return $eventsAtRisk;
    }
    
    /**
     * Analyze negative feedback using OpenRouter API
     * 
     * @param Event $event
     * @param \Illuminate\Support\Collection $negativeFeedbacks
     * @return string Analysis of the negative feedback
     */
    protected function analyzeNegativeFeedback(Event $event, $negativeFeedbacks): string
    {
        // If no API key, return basic analysis
        if (empty($this->apiKey)) {
            Log::warning('OpenRouter API key not set. Using basic analysis.');
            return $this->generateBasicAnalysis($negativeFeedbacks);
        }
        
        try {
            $prompt = $this->buildAnalysisPrompt($event, $negativeFeedbacks);
            
            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 30,
            ])->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'HTTP-Referer' => env('APP_URL', 'http://localhost'),
                'X-Title' => 'EcoEvents Risk Analysis',
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an event analysis expert who helps identify issues in events based on feedback. Your analysis should be concise, actionable, and in French language.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 500,
                'temperature' => 0.7,
                'stream' => false,
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['choices'][0]['message']['content'])) {
                    return trim($data['choices'][0]['message']['content']);
                }
            }
            
            Log::error('OpenRouter API error: ' . $response->body());
            return $this->generateBasicAnalysis($negativeFeedbacks);
            
        } catch (\Exception $e) {
            Log::error('OpenRouter API exception: ' . $e->getMessage());
            return $this->generateBasicAnalysis($negativeFeedbacks);
        }
    }
    
    /**
     * Build prompt for analysis
     * 
     * @param Event $event
     * @param \Illuminate\Support\Collection $negativeFeedbacks
     * @return string
     */
    protected function buildAnalysisPrompt(Event $event, $negativeFeedbacks): string
    {
        $feedbackTexts = $negativeFeedbacks->map(function ($feedback) {
            return "- Note: {$feedback->note}/5, Commentaire: \"{$feedback->commentaire}\"";
        })->join("\n");
        
        return <<<PROMPT
Analyse les commentaires négatifs pour l'événement écologique suivant:

ÉVÉNEMENT: {$event->title}
DESCRIPTION: {$event->description}
DATE: {$event->start_date}
LIEU: {$event->location}

COMMENTAIRES NÉGATIFS:
{$feedbackTexts}

INSTRUCTIONS:
1. Identifie les problèmes principaux mentionnés dans les commentaires
2. Groupe les problèmes par catégories (organisation, contenu, logistique, etc.)
3. Suggère 3-5 actions concrètes pour améliorer l'événement
4. Fournis une analyse concise et constructive

FORMAT DE RÉPONSE:
🚨 PROBLÈMES PRINCIPAUX:
• [Problème 1]
• [Problème 2]
• [Problème 3]

📊 ANALYSE:
[2-3 phrases d'analyse générale]

✅ ACTIONS RECOMMANDÉES:
1. [Action concrète 1]
2. [Action concrète 2]
3. [Action concrète 3]

Ton analyse doit être en français, concise (maximum 300 mots) et orientée vers des solutions pratiques.
PROMPT;
    }
    
    /**
     * Generate basic analysis without AI
     * 
     * @param \Illuminate\Support\Collection $negativeFeedbacks
     * @return string
     */
    protected function generateBasicAnalysis($negativeFeedbacks): string
    {
        $totalFeedbacks = $negativeFeedbacks->count();
        $averageRating = $negativeFeedbacks->avg('note');
        
        $commonIssues = [
            "Organisation insuffisante",
            "Problèmes logistiques",
            "Contenu ne correspondant pas aux attentes",
            "Manque d'interactivité",
            "Problèmes techniques"
        ];
        
        $randomIssues = array_slice($commonIssues, 0, min(3, count($commonIssues)));
        $issuesList = implode("\n• ", $randomIssues);
        
        return <<<ANALYSIS
🚨 PROBLÈMES PRINCIPAUX:
• {$issuesList}

📊 ANALYSE:
Cet événement a reçu {$totalFeedbacks} commentaires négatifs avec une note moyenne de {$averageRating}/5. Une analyse des commentaires montre des problèmes récurrents qui nécessitent une attention immédiate.

✅ ACTIONS RECOMMANDÉES:
1. Organiser une réunion de débriefing avec l'équipe organisatrice
2. Contacter les participants pour obtenir des détails supplémentaires
3. Réviser le format et le contenu de l'événement pour les prochaines éditions
ANALYSIS;
    }
    
    /**
     * Notify organizers about the event at risk
     * 
     * @param Event $event
     * @param string $analysis
     */
    protected function notifyOrganizers(Event $event, string $analysis): void
    {
        // Find organizers (users with 'organisateur' role)
        $organizers = User::where('role', 'organisateur')->get();
        
        // Also notify admins
        $admins = User::where('role', 'admin')->get();
        
        $recipients = $organizers->merge($admins);
        
        foreach ($recipients as $user) {
            try {
                // Envoyer l'email via la notification
                $user->notify(new EventAtRiskNotification($event, $analysis));
                
                // Créer manuellement l'entrée dans la table des notifications
                \Illuminate\Support\Facades\DB::table('notifications')->insert([
                    'id' => \Illuminate\Support\Str::uuid()->toString(),
                    'type' => 'App\\Notifications\\EventAtRiskNotification',
                    'notifiable_type' => get_class($user),
                    'notifiable_id' => $user->id,
                    'data' => json_encode([
                        'event_id' => $event->id,
                        'event_title' => $event->title,
                        'analysis' => $analysis,
                        'at_risk' => true,
                        'notification_type' => 'event_risk',
                    ]),
                    'read_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                Log::info("Notification créée pour l'utilisateur {$user->name} (ID: {$user->id})");
            } catch (\Exception $e) {
                Log::error("Erreur lors de la création de la notification pour {$user->name}: " . $e->getMessage());
            }
        }
        
        Log::info("Notifications sent to " . $recipients->count() . " organizers/admins about event at risk: " . $event->title);
    }
}
