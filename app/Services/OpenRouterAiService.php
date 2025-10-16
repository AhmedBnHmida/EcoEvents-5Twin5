<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\EventStatus;
use App\TypeCategorie;

class OpenRouterAiService
{
    private $apiKey;
    private $baseUrl = 'https://openrouter.ai/api/v1';
    private $model = 'openai/gpt-oss-20b:free';

    public function __construct()
    {
        $this->apiKey = env('OPENROUTER_API_KEY');
        $this->model = env('OPENROUTER_MODEL', 'openai/gpt-oss-20b:free');
        
        if (empty($this->apiKey)) {
            Log::error('❌ OPENROUTER_API_KEY is not set in .env file');
        } else {
            Log::info('✅ OpenRouter AI Service initialized');
        }
    }

    /**
     * Generate complete event using OpenRouter
     */
    public function generateCompleteEvent($eventData)
    {
        Log::info("🌐 OpenRouter: Generating COMPLETE event", $eventData);

        if (empty($this->apiKey)) {
            Log::error('❌ OpenRouter API key is empty');
            return $this->getComprehensiveFallbackEventData($eventData);
        }

        try {
            $prompt = $this->buildCompleteEventPrompt($eventData);

            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 90,
            ])->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'HTTP-Referer' => 'http://localhost:8000',
                'X-Title' => 'Event Management System',
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 800,
                'temperature' => 0.7,
                'stream' => false,
            ]);

            Log::info("📡 OpenRouter Response Status: " . $response->status());

            if ($response->successful()) {
                $data = $response->json();
                $content = trim($data['choices'][0]['message']['content']);
                
                Log::info("✅ OpenRouter Complete Event Success!");
                
                return $this->parseAndEnhanceEventData($content, $eventData);
            } else {
                $error = $response->json();
                Log::error("❌ OpenRouter API Error: " . json_encode($error));
                return $this->getComprehensiveFallbackEventData($eventData);
            }

        } catch (\Exception $e) {
            Log::error("💥 OpenRouter Exception: " . $e->getMessage());
            return $this->getComprehensiveFallbackEventData($eventData);
        }
    }

    /**
     * Build comprehensive prompt for complete event generation
     */
    private function buildCompleteEventPrompt($eventData)
    {
        return "Generate a COMPLETE event proposal with ALL necessary details for a professional event management system.

EVENT DETAILS:
- Title: {$eventData['title']}
- Category: {$eventData['category']}
- Expected Capacity: {$eventData['capacity']}

REQUIRED FIELDS (return as VALID JSON only):
{
    \"title\": \"enhanced event title\",
    \"description\": \"compelling and detailed event description (150-200 words)\",
    \"location\": \"specific and appropriate venue name with address details\",
    \"capacity_max\": \"realistic capacity number\",
    \"price\": \"appropriate ticket price\",
    \"status\": \"UPCOMING|ONGOING|COMPLETED|CANCELLED\",
    \"is_public\": true|false,
    \"start_date\": \"YYYY-MM-DD HH:MM:SS\",
    \"end_date\": \"YYYY-MM-DD HH:MM:SS\", 
    \"registration_deadline\": \"YYYY-MM-DD HH:MM:SS\",
    \"success_prediction\": \"professional analysis of event success potential\"
}

GUIDELINES:
- Make ALL data realistic and professional
- Dates should be logical (registration before start, start before end)
- Capacity should match the event type
- Price should be appropriate for the category and capacity
- Status should be UPCOMING for new events
- Location should be specific and realistic";
    }

    /**
     * Parse and enhance the AI response
     */
    private function parseAndEnhanceEventData($content, $originalData)
    {
        // Extract JSON from response
        preg_match('/\{[^}]+\}/s', $content, $matches);
        
        if (!empty($matches)) {
            $aiData = json_decode($matches[0], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                Log::info("📊 Successfully parsed AI JSON response");
                return $this->validateAndCompleteEventData($aiData, $originalData);
            }
        }

        Log::warning("⚠️ JSON parsing failed, using comprehensive fallback");
        return $this->getComprehensiveFallbackEventData($originalData);
    }

    /**
     * Validate and complete the event data
     */
    private function validateAndCompleteEventData($aiData, $originalData)
    {
        $currentDate = new \DateTime();
        
        $completeData = [
            'title' => $aiData['title'] ?? $originalData['title'],
            'description' => $aiData['description'] ?? $this->generateProfessionalDescription($originalData['title'], $originalData['category']),
            'location' => $aiData['location'] ?? $this->suggestProfessionalLocation($originalData['category']),
            'capacity_max' => $aiData['capacity_max'] ?? $originalData['capacity'],
            'price' => $aiData['price'] ?? $this->calculateSmartPrice($originalData['capacity']),
            'status' => $aiData['status'] ?? EventStatus::UPCOMING->value,
            'is_public' => $aiData['is_public'] ?? true,
            'start_date' => $aiData['start_date'] ?? $currentDate->modify('+30 days')->format('Y-m-d H:i:s'),
            'end_date' => $aiData['end_date'] ?? $currentDate->modify('+1 day')->format('Y-m-d H:i:s'),
            'registration_deadline' => $aiData['registration_deadline'] ?? $currentDate->modify('-5 days')->format('Y-m-d H:i:s'),
            'success_prediction' => $aiData['success_prediction'] ?? $this->generateSuccessPrediction($originalData)
        ];

        return $completeData;
    }

    /**
     * Generate professional description
     */
    private function generateProfessionalDescription($title, $category)
    {
        $descriptions = [
            "Join us for an exceptional {$title} - the premier {$category} event of the season! This professionally curated gathering brings together industry leaders, innovative thinkers, and passionate professionals for a day of inspiration, networking, and growth. Featuring expert speakers, interactive workshops, and unparalleled networking opportunities, this event is designed to provide actionable insights and meaningful connections that will propel your career or business forward.",
            
            "Welcome to {$title}, where excellence meets innovation in the world of {$category}. This meticulously planned event offers a unique platform for learning, collaboration, and professional development. With a carefully crafted agenda that balances education, networking, and practical application, attendees will leave equipped with new skills, valuable contacts, and fresh perspectives to tackle current challenges and seize emerging opportunities.",
            
            "Experience {$title} - a transformative {$category} event designed for forward-thinking professionals. Our comprehensive program combines cutting-edge content with practical applications, delivered by renowned experts and industry pioneers. Whether you're looking to expand your knowledge, grow your network, or discover new opportunities, this event provides the perfect environment for professional growth and personal development."
        ];
        
        return $descriptions[array_rand($descriptions)];
    }

    /**
     * Suggest professional location
     */
    private function suggestProfessionalLocation($category)
    {
        $locations = [
            'Grand Conference Center, 123 Business District, Downtown',
            'Prestige Event Hall, 456 Innovation Avenue, Tech Park',
            'Elegance Convention Center, 789 Professional Plaza, City Center',
            'Visionary Summit Venue, 321 Excellence Boulevard, Corporate Zone',
            'Premier Meeting Complex, 654 Success Street, Business Quarter'
        ];
        
        return $locations[array_rand($locations)];
    }

    /**
     * Calculate smart pricing - SIMPLIFIED based only on capacity
     */
    private function calculateSmartPrice($capacity)
    {
        // Simple capacity-based pricing
        if ($capacity > 1000) return 199;
        if ($capacity > 500) return 149;
        if ($capacity > 200) return 99;
        if ($capacity > 100) return 69;
        if ($capacity > 50) return 49;
        
        return 25;
    }

    /**
     * Generate success prediction
     */
    private function generateSuccessPrediction($eventData)
    {
        return "🎯 PROFESSIONAL EVENT ANALYSIS:\n\n📊 Success Probability: HIGH\n• Strong market demand for {$eventData['category']} events\n• Appropriate capacity planning for target audience\n• Competitive pricing strategy\n\n💡 KEY RECOMMENDATIONS:\n• Focus on targeted digital marketing campaigns\n• Leverage social media for audience engagement\n• Implement referral programs for organic growth\n• Ensure excellent attendee experience for repeat business\n• Partner with industry associations for credibility\n\n🚀 GROWTH OPPORTUNITIES:\n• Consider premium VIP packages for added revenue\n• Explore sponsorship opportunities\n• Create post-event content for ongoing engagement";
    }

    /**
     * Comprehensive fallback event data
     */
    private function getComprehensiveFallbackEventData($eventData)
    {
        $currentDate = new \DateTime();
        
        return [
            'title' => $eventData['title'],
            'description' => $this->generateProfessionalDescription($eventData['title'], $eventData['category']),
            'location' => $this->suggestProfessionalLocation($eventData['category']),
            'capacity_max' => $eventData['capacity'],
            'price' => $this->calculateSmartPrice($eventData['capacity']),
            'status' => EventStatus::UPCOMING->value,
            'is_public' => true,
            'start_date' => $currentDate->modify('+30 days')->format('Y-m-d H:i:s'),
            'end_date' => $currentDate->modify('+1 day')->format('Y-m-d H:i:s'),
            'registration_deadline' => $currentDate->modify('-5 days')->format('Y-m-d H:i:s'),
            'success_prediction' => $this->generateSuccessPrediction($eventData)
        ];
    }

    /**
     * Generate event description (for separate description generation)
     */
    public function generateEventDescription($title, $categoryName)
    {
        return $this->generateProfessionalDescription($title, $categoryName);
    }

    /**
     * Predict event success (standalone function)
     */
    public function predictEventSuccess($eventData)
    {
        return $this->generateSuccessPrediction($eventData);
    }
}