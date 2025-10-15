<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterService
{
    protected $apiKey;
    protected $baseUrl;
    protected $defaultModel;

    public function __construct()
    {
        // Initialize with environment variables or default values
        $this->apiKey = env('OPENROUTER_API_KEY', '');
        $this->baseUrl = env('OPENROUTER_API_URL', 'https://openrouter.ai/api/v1');
        $this->defaultModel = env('OPENROUTER_DEFAULT_MODEL', 'gpt-3.5-turbo');
    }

    /**
     * Generate feedback recommendation based on category
     * 
     * @param string $categoryName The name of the feedback category
     * @param string|null $categoryDescription The description of the feedback category (optional)
     * @return array|null The recommendation data or null on error
     */
    public function generateFeedbackRecommendation(string $categoryName, ?string $categoryDescription = null): ?array
    {
        try {
            $prompt = $this->buildFeedbackPrompt($categoryName, $categoryDescription);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'HTTP-Referer' => env('APP_URL', 'http://localhost'),
                'X-Title' => 'EcoEvents Feedback Assistant'
            ])->post($this->baseUrl . '/chat/completions', [
                'model' => $this->defaultModel,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful assistant that provides feedback suggestions for eco-friendly events. Your suggestions should be constructive, specific, and relevant to the category.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 150
            ]);

            $data = $response->json();
            
            if ($response->successful() && isset($data['choices'][0]['message']['content'])) {
                return [
                    'suggestion' => $data['choices'][0]['message']['content'],
                    'model' => $data['model'] ?? $this->defaultModel,
                    'category' => $categoryName
                ];
            }
            
            Log::error('OpenRouter API error: ' . json_encode($data));
            return null;
        } catch (\Exception $e) {
            Log::error('OpenRouter API exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Build the prompt for feedback recommendation
     */
    protected function buildFeedbackPrompt(string $categoryName, ?string $categoryDescription): string
    {
        $prompt = "Please suggest a constructive and specific feedback for an eco-friendly event related to the category: {$categoryName}.";
        
        if ($categoryDescription) {
            $prompt .= " Category description: {$categoryDescription}.";
        }
        
        $prompt .= " The feedback should be 2-3 sentences long, constructive, and specific. It should highlight both positive aspects and areas for improvement. The feedback should be in French language.";
        
        return $prompt;
    }
}
