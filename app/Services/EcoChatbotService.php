<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EcoChatbotService
{
    protected $apiKey;
    protected $baseUrl;
    protected $model;
    protected $conversationHistory = [];
    protected $maxHistoryLength = 10;

    public function __construct()
    {
        $this->apiKey = env('OPENROUTER_API_KEY', '');
        $this->baseUrl = env('OPENROUTER_API_URL', 'https://openrouter.ai/api/v1');
        $this->model = env('OPENROUTER_MODEL', 'meta-llama/llama-3.3-70b-instruct:free');
    }

    /**
     * Process a user message and get an eco-focused response
     *
     * @param string $message The user's message
     * @param array $history Optional conversation history
     * @return string The chatbot's response
     */
    public function processMessage(string $message, array $history = []): string
    {
        // Update conversation history
        if (!empty($history)) {
            $this->conversationHistory = $history;
        }
        
        // Add user message to history
        $this->addToHistory('user', $message);
        
        try {
            if (empty($this->apiKey)) {
                Log::warning('EcoChatbot: OpenRouter API key is not set');
                return $this->getFallbackResponse($message);
            }
            
            // Prepare messages for API call
            $messages = $this->prepareMessages();
            
            // Make API call
            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 30,
            ])->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'HTTP-Referer' => env('APP_URL', 'http://localhost:8000'),
                'X-Title' => 'EcoEvents Chatbot',
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/chat/completions', [
                'model' => $this->model,
                'messages' => $messages,
                'max_tokens' => 500,
                'temperature' => 0.7,
                'stream' => false,
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['choices'][0]['message']['content'])) {
                    $botResponse = $data['choices'][0]['message']['content'];
                    
                    // Add bot response to history
                    $this->addToHistory('assistant', $botResponse);
                    
                    return $botResponse;
                }
            }
            
            Log::error('EcoChatbot: OpenRouter API error', [
                'status' => $response->status(),
                'response' => $response->json()
            ]);
            
            return $this->getFallbackResponse($message);
        } catch (\Exception $e) {
            Log::error('EcoChatbot: Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->getFallbackResponse($message);
        }
    }
    
    /**
     * Get the conversation history
     *
     * @return array
     */
    public function getHistory(): array
    {
        return $this->conversationHistory;
    }
    
    /**
     * Add a message to the conversation history
     *
     * @param string $role 'user' or 'assistant'
     * @param string $content The message content
     */
    protected function addToHistory(string $role, string $content): void
    {
        $this->conversationHistory[] = [
            'role' => $role,
            'content' => $content
        ];
        
        // Limit history length
        if (count($this->conversationHistory) > $this->maxHistoryLength) {
            array_shift($this->conversationHistory);
        }
    }
    
    /**
     * Prepare messages for the API call
     *
     * @return array
     */
    protected function prepareMessages(): array
    {
        // Start with system message
        $messages = [
            [
                'role' => 'system',
                'content' => $this->getSystemPrompt()
            ]
        ];
        
        // Add conversation history
        foreach ($this->conversationHistory as $message) {
            $messages[] = $message;
        }
        
        return $messages;
    }
    
    /**
     * Get system prompt for the chatbot
     *
     * @return string
     */
    protected function getSystemPrompt(): string
    {
        return <<<EOT
Tu es un assistant écologique spécialisé dans le développement durable et l'écologie, conçu pour aider les participants d'événements éco-responsables. Voici tes directives :

EXPERTISE :
- Écologie et développement durable
- Pratiques éco-responsables au quotidien
- Événements et rassemblements écologiques
- Réduction de l'empreinte carbone
- Économie circulaire et zéro déchet

COMPORTEMENT :
- Réponds toujours en français
- Sois concis, précis et informatif
- Fournis des conseils pratiques et applicables
- Cite des sources fiables quand c'est pertinent
- Encourage les actions positives pour l'environnement
- Reste positif et inspirant, évite le catastrophisme
- Adapte ton langage pour être accessible à tous

LIMITES :
- Ne donne pas d'informations médicales ou juridiques spécialisées
- Évite les positions politiques controversées
- Ne prétends pas être humain
- Ne donne pas d'informations fausses ou non vérifiées

FORMAT DE RÉPONSE :
- Réponds de manière concise (maximum 3-4 phrases)
- Utilise un langage simple et accessible
- Organise l'information de façon claire
- Inclus des émojis pertinents pour une communication plus engageante (🌱, 🌍, ♻️, etc.)

Tu représentes EcoEvents, une plateforme dédiée à l'organisation d'événements écologiques et durables.
EOT;
    }
    
    /**
     * Get a fallback response when the API call fails
     *
     * @param string $message The user's message
     * @return string
     */
    protected function getFallbackResponse(string $message): string
    {
        $fallbackResponses = [
            "🌱 Je suis désolé, je n'ai pas pu traiter votre demande pour le moment. Pourriez-vous reformuler votre question sur l'écologie ou le développement durable ?",
            
            "🌍 Merci pour votre question sur l'environnement. Malheureusement, je rencontre des difficultés techniques. Essayez à nouveau dans quelques instants ou reformulez votre question.",
            
            "♻️ Je m'excuse pour ce contretemps. Pour les questions sur l'écologie et les pratiques durables, notre équipe est également disponible pendant l'événement pour vous aider directement.",
            
            "🌿 Votre intérêt pour les questions écologiques est important ! Je rencontre actuellement un problème technique. Pourriez-vous reformuler votre question différemment ?"
        ];
        
        return $fallbackResponses[array_rand($fallbackResponses)];
    }
}