<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    private $apiKey;
    private $baseUrl = 'https://api.openai.com/v1';

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
        
        if (empty($this->apiKey)) {
            Log::error('OPENAI_API_KEY is not set in .env file');
        } else {
            Log::info('OpenAI API Key is set. First 10 chars: ' . substr($this->apiKey, 0, 10) . '...');
        }
    }

    /**
     * Generate event description based on title and category
     */
    public function generateEventDescription($title, $categoryName, $maxLength = 150)
    {
        Log::info("AI Service: Generating description for '{$title}' in category '{$categoryName}'");

        if (empty($this->apiKey)) {
            Log::error('OpenAI API key is empty!');
            return $this->getFallbackDescription($title, $categoryName);
        }

        try {
            $prompt = "Create a compelling event description for a '{$title}' event in the '{$categoryName}' category. Make it engaging, professional, and suitable for potential attendees. Keep it under {$maxLength} words.";

            Log::info('Sending request to OpenAI API...');

            $response = Http::withOptions([
                'verify' => false, // Disable SSL verification for local development
            ])->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->baseUrl . '/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 200,
                'temperature' => 0.7,
            ]);

            Log::info('OpenAI API Response Status: ' . $response->status());

            if ($response->successful()) {
                $description = trim($response->json()['choices'][0]['message']['content']);
                Log::info('AI generated description successfully!');
                return $description;
            }

            $errorBody = $response->body();
            Log::error('OpenAI API Error: ' . $errorBody);
            return $this->getFallbackDescription($title, $categoryName);

        } catch (\Exception $e) {
            Log::error('AI Service Exception: ' . $e->getMessage());
            return $this->getFallbackDescription($title, $categoryName);
        }
    }

    /**
     * Generate complete event based on minimal input
     */
    public function generateCompleteEvent($eventData)
    {
        Log::info("AI Service: Generating complete event", $eventData);

        if (empty($this->apiKey)) {
            Log::error('OpenAI API key is empty!');
            return $this->getFallbackEventData($eventData);
        }

        try {
            $prompt = "Generate a complete event proposal based on:
Event Title: {$eventData['title']}
Category: {$eventData['category']}
Expected Attendees: {$eventData['capacity']}

Provide JSON with: description, suggested_location, recommended_price, key_resources, tags";

            Log::info('Sending complete event request to OpenAI API...');

            $response = Http::withOptions([
                'verify' => false, // Disable SSL verification for local development
            ])->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->baseUrl . '/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 500,
                'temperature' => 0.7,
            ]);

            Log::info('OpenAI API Response Status: ' . $response->status());

            if ($response->successful()) {
                $content = $response->json()['choices'][0]['message']['content'];
                Log::info('AI generated complete event successfully!');
                
                // Try to extract JSON
                preg_match('/\{[^}]*\}/s', $content, $matches);
                if (!empty($matches)) {
                    $result = json_decode($matches[0], true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        return $result;
                    }
                }
                
                // If no JSON, use the content as description
                return [
                    'description' => $content,
                    'suggested_location' => 'AI Generated Venue',
                    'recommended_price' => 50,
                    'key_resources' => ['Sound System', 'Projector', 'Seating'],
                    'tags' => [$eventData['category'], 'ai-generated', 'professional']
                ];
            }

            $errorBody = $response->body();
            Log::error('OpenAI API Error: ' . $errorBody);
            return $this->getFallbackEventData($eventData);

        } catch (\Exception $e) {
            Log::error('AI Service Exception: ' . $e->getMessage());
            return $this->getFallbackEventData($eventData);
        }
    }

    /**
     * Fallback description when AI service fails
     */
    private function getFallbackDescription($title, $categoryName)
    {
        Log::warning('Using FALLBACK description - AI service failed');
        return "Join us for an exciting {$title} event! This {$categoryName} event promises to be an unforgettable experience with engaging activities, networking opportunities, and valuable insights. Don't miss out on this amazing opportunity!";
    }

    /**
     * Fallback event data when AI service fails
     */
    private function getFallbackEventData($eventData)
    {
        Log::warning('Using FALLBACK event data - AI service failed');
        return [
            'description' => $this->getFallbackDescription($eventData['title'], $eventData['category']),
            'suggested_location' => 'City Convention Center',
            'recommended_price' => 75,
            'key_resources' => ['Audio Equipment', 'Projector', 'Chairs', 'Tables'],
            'tags' => [$eventData['category'], 'professional', 'networking']
        ];
    }
}