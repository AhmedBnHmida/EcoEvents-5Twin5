<?php

namespace App\Services;

use App\Models\Partner;
use App\Models\Event;
use App\Services\OpenRouterService;

class SponsoringProposalGeneratorService
{
    protected $openRouterService;

    public function __construct(OpenRouterService $openRouterService)
    {
        $this->openRouterService = $openRouterService;
    }

    /**
     * Génère une proposition de sponsoring personnalisée
     */
    public function generate(array $data): array
    {
        $partner = $data['partner'];
        $event = $data['event'];
        $amount = $data['amount'];
        $type = $data['type'];

        // Construire le prompt pour l'IA
        $prompt = $this->buildProposalPrompt($partner, $event, $amount, $type);

        // Appeler l'API OpenRouter via le service existant
        $aiResponse = $this->callOpenRouterAPI($prompt);

        // Parser la réponse de l'IA
        $proposal = $this->parseProposalResponse($aiResponse);

        return $proposal;
    }

    /**
     * Construit le prompt pour la génération de proposition
     */
    private function buildProposalPrompt($partner, $event, $amount, $type): string
    {
        $typeLabels = [
            'argent' => 'Sponsoring financier',
            'materiel' => 'Sponsoring matériel',
            'logistique' => 'Sponsoring logistique',
            'autre' => 'Autre type de sponsoring'
        ];

        $typeLabel = $typeLabels[$type] ?? 'Sponsoring';

        return "Tu es un expert en rédaction de propositions commerciales de sponsoring.

PARTENAIRE:
- Nom: {$partner->nom}
- Contact: {$partner->contact}
- Email: {$partner->email}
- Description: " . ($partner->description ?? 'Partenaire de confiance') . "

ÉVÉNEMENT:
- Titre: {$event->title}
- Date: {$event->start_date}
- Catégorie: " . ($event->category ? $event->category->name : 'Général') . "
- Description: " . ($event->description ?? 'Événement de qualité') . "

PROPOSITION:
- Type: {$typeLabel}
- Montant: {$amount}€

Tâche: Génère une proposition commerciale professionnelle et personnalisée.

Réponds UNIQUEMENT avec un JSON valide dans ce format:
{
  \"subject\": \"Sujet de l'email\",
  \"greeting\": \"Salutation personnalisée\",
  \"introduction\": \"Paragraphe d'introduction\",
  \"proposal_details\": \"Détails de la proposition\",
  \"benefits\": \"Avantages pour le partenaire\",
  \"call_to_action\": \"Appel à l'action\",
  \"closing\": \"Formule de politesse\",
  \"signature\": \"Signature\"
}

Le ton doit être:
- Professionnel mais chaleureux
- Personnalisé selon le partenaire et l'événement
- Convaincant sans être agressif
- En français
- Adapté au montant proposé";
    }

    /**
     * Appelle l'API OpenRouter via le service existant
     */
    private function callOpenRouterAPI(string $prompt): string
    {
        try {
            // Utiliser la méthode existante du service OpenRouter
            $response = $this->openRouterService->generateFeedbackRecommendation('Proposal Generation', $prompt);
            
            if ($response && isset($response['suggestion'])) {
                return $response['suggestion'];
            }

            throw new \Exception('Erreur API OpenRouter: Pas de réponse');
        } catch (\Exception $e) {
            // Fallback en cas d'erreur API
            return $this->getFallbackProposal();
        }
    }

    /**
     * Parse la réponse de l'IA
     */
    private function parseProposalResponse(string $aiResponse): array
    {
        try {
            // Nettoyer la réponse pour extraire le JSON
            $jsonStart = strpos($aiResponse, '{');
            $jsonEnd = strrpos($aiResponse, '}') + 1;
            
            if ($jsonStart !== false && $jsonEnd !== false) {
                $jsonString = substr($aiResponse, $jsonStart, $jsonEnd - $jsonStart);
                $data = json_decode($jsonString, true);
                
                if ($data && isset($data['subject'])) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            // En cas d'erreur de parsing, utiliser le fallback
        }

        return json_decode($this->getFallbackProposal(), true);
    }

    /**
     * Proposition de fallback en cas d'erreur API
     */
    private function getFallbackProposal(): string
    {
        return json_encode([
            'subject' => 'Proposition de partenariat - Événement',
            'greeting' => 'Bonjour,',
            'introduction' => 'Nous vous contactons pour vous proposer une opportunité de partenariat exceptionnelle.',
            'proposal_details' => 'Nous souhaiterions vous proposer un sponsoring pour notre événement.',
            'benefits' => 'Ce partenariat vous permettra d\'augmenter votre visibilité et de toucher notre audience.',
            'call_to_action' => 'Nous serions ravis de discuter de cette opportunité avec vous.',
            'closing' => 'Dans l\'attente de votre retour,',
            'signature' => 'L\'équipe EcoEvents'
        ]);
    }
}