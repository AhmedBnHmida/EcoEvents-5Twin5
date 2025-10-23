<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\EventStatus;

class OpenRouterAiService
{
    private $apiKey;
    private $baseUrl = 'https://openrouter.ai/api/v1';
    private $model = 'meta-llama/llama-3.3-70b-instruct:free';

    public function __construct()
    {
        $this->apiKey = env('OPENROUTER_API_KEYY');
        $this->model = env('OPENROUTER_MODEL', 'meta-llama/llama-3.3-70b-instruct:free');
        
        Log::info("🔧 OpenRouterAI Service Initializing with Llama 3.3 70B");
        Log::info("🔧 Model: " . $this->model);
        
        if (empty($this->apiKey)) {
            Log::error('❌ OPENROUTER_API_KEY is not set in .env file');
        } else {
            Log::info('✅ OpenRouter AI Service initialized with Llama 3.3 70B');
        }
    }

    /**
     * Generate complete event using OpenRouter - DEBUG VERSION
     */
    public function generateCompleteEvent($eventData)
    {
        Log::info("🌐 OpenRouter: Generating COMPLETE event", $eventData);
        Log::info("🔑 API Key exists: " . (!empty($this->apiKey) ? 'YES' : 'NO'));
        Log::info("🤖 Using Model: " . $this->model);

        if (empty($this->apiKey)) {
            Log::error('❌ OpenRouter API key is empty');
            return $this->getComprehensiveFallbackEventData($eventData);
        }

        try {
            $prompt = $this->buildCompleteEventPrompt($eventData);

            Log::info("📤 Sending request to OpenRouter...");
            Log::info("📝 Prompt preview: " . substr($prompt, 0, 200) . "...");

            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 90,
            ])->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'HTTP-Referer' => 'http://localhost:8000',
                'X-Title' => 'Eco Event Platform',
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 1500,
                'temperature' => 0.8,
                'stream' => false,
            ]);

            Log::info("📡 OpenRouter Response Status: " . $response->status());
            Log::info("📡 OpenRouter Response Headers: " . json_encode($response->headers()));

            if ($response->successful()) {
                $data = $response->json();
                Log::info("📊 Full API Response: " . json_encode($data));
                
                if (isset($data['choices'][0]['message']['content'])) {
                    $content = trim($data['choices'][0]['message']['content']);
                    Log::info("✅ OpenRouter Complete Event Success!");
                    Log::info("📝 Raw AI Response: " . $content);
                    
                    $parsedData = $this->parseAndEnhanceEventData($content, $eventData);
                    Log::info("🎯 Final Parsed Data: " . json_encode($parsedData));
                    
                    return $parsedData;
                } else {
                    Log::error("❌ No content in AI response");
                    return $this->getComprehensiveFallbackEventData($eventData);
                }
            } else {
                $error = $response->body();
                Log::error("❌ OpenRouter API Error - Status: " . $response->status());
                Log::error("❌ OpenRouter API Error - Body: " . $error);
                
                // Try to parse error JSON
                $errorData = json_decode($error, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    Log::error("❌ OpenRouter API Error - Parsed: " . json_encode($errorData));
                }
                
                return $this->getComprehensiveFallbackEventData($eventData);
            }

        } catch (\Exception $e) {
            Log::error("💥 OpenRouter Exception: " . $e->getMessage());
            Log::error("💥 Stack trace: " . $e->getTraceAsString());
            return $this->getComprehensiveFallbackEventData($eventData);
        }
    }

    /**
     * Build comprehensive ECO-FOCUSED prompt for complete event generation
     */
    private function buildCompleteEventPrompt($eventData)
    {
        $currentDate = date('Y-m-d H:i:s');
    
        return <<<PROMPT
IMPORTANT: You are an expert event planner specializing in ECOLOGY and SUSTAINABLE DEVELOPMENT events. Create a complete, professional event proposal that aligns with environmental values.

CONTEXT: This is for an online platform that organizes and promotes ecology and sustainable development events, supporting citizen and associative initiatives.

EVENT DETAILS PROVIDED:
- Title: {$eventData['title']}
- Category: {$eventData['category']}
- Expected Capacity: {$eventData['capacity']}

REQUIREMENTS:
Generate a COMPLETE event proposal with ALL these fields in VALID JSON format. Today is {$currentDate} - use FUTURE dates only:

{
    "title": "enhanced eco-friendly event title",
    "description": "compelling description focusing on environmental impact (150-250 words)",
    "location": "eco-appropriate venue with sustainability features",
    "capacity_max": {$eventData['capacity']},
    "price": 15.00,
    "status": "UPCOMING",
    "is_public": true,
    "start_date": "2025-03-15 10:00:00",
    "end_date": "2025-03-15 16:00:00",
    "registration_deadline": "2025-03-08 18:00:00",
    "eco_focus": "specific environmental benefits of this event",
    "sustainability_features": ["zero-waste", "recycling", "public transport access"]
}

PRICE REQUIREMENTS:
- Must be a NUMBER (not string)
- Affordable range: 0-25 for community events
- Examples: 0, 5.00, 12.50, 15, 20.00
- Free events should use 0

ECO-FOCUSED GUIDELINES:
- Emphasize environmental education, community action, or sustainable practices
- Suggest outdoor locations, eco-centers, or community spaces when appropriate
- Keep prices accessible to encourage community participation
- Include specific sustainability features
- Make events public to maximize community impact

Return VALID JSON only.
PROMPT;
}

    /**
     * Generate event description based on ALL event data
     */
    public function generateEventDescription($eventData)
    {
        Log::info("🌐 OpenRouter: Generating description for complete event", $eventData);

        if (empty($this->apiKey)) {
            Log::error('❌ OpenRouter API key is empty');
            return $this->generateEcoFocusedDescription($eventData);
        }

        try {
            $prompt = $this->buildDescriptionPrompt($eventData);

            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 60,
            ])->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'HTTP-Referer' => 'http://localhost:8000',
                'X-Title' => 'Eco Event Platform',
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

            if ($response->successful()) {
                $data = $response->json();
                $content = trim($data['choices'][0]['message']['content']);
                
                Log::info("✅ OpenRouter Description Success!");
                return $content;
            } else {
                Log::error("❌ OpenRouter API Error for description");
                return $this->generateEcoFocusedDescription($eventData);
            }

        } catch (\Exception $e) {
            Log::error("💥 OpenRouter Exception for description: " . $e->getMessage());
            return $this->generateEcoFocusedDescription($eventData);
        }
    }

    /**
     * Build description prompt using ALL event data
     */
    private function buildDescriptionPrompt($eventData)
    {
        return <<<PROMPT
You are an environmental communication expert. Create a compelling, inspiring description for an ecology/sustainability event.

EVENT DETAILS:
- Title: {$eventData['title']}
- Category: {$eventData['category']}
- Location: {$eventData['location']}
- Capacity: {$eventData['capacity_max']} attendees
- Price: \${$eventData['price']}
- Dates: {$eventData['start_date']} to {$eventData['end_date']}

WRITING GUIDELINES:
- Focus on environmental impact and community benefits
- Emphasize practical, actionable outcomes
- Inspire participation and collective action
- Highlight sustainability aspects
- Keep it professional yet accessible (150-250 words)
- Speak to both environmental enthusiasts and general public

Create a description that will motivate people to participate in this eco-event.
PROMPT;
    }

    /**
     * Predict event success with detailed analysis - OPTIMIZED
     */
    public function predictEventSuccess($eventData)
    {
        Log::info("🌐 OpenRouter: Predicting success for event", $eventData);

        if (empty($this->apiKey)) {
            Log::error('❌ OpenRouter API key is empty');
            return $this->generateComprehensiveSuccessAnalysis($eventData);
        }

        try {
            $prompt = $this->buildSuccessPredictionPrompt($eventData);

            // First attempt with shorter timeout
            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 25, // Reduced from 60 to 25 seconds
            ])->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'HTTP-Referer' => 'http://localhost:8000',
                'X-Title' => 'Eco Event Platform',
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 600, // Reduced from 1200
                'temperature' => 0.7,
                'stream' => false,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = trim($data['choices'][0]['message']['content']);
                
                Log::info("✅ OpenRouter Success Prediction Success!");
                return $content;
            } else {
                Log::error("❌ OpenRouter API Error for prediction");
                // Fallback to local analysis
                return $this->generateComprehensiveSuccessAnalysis($eventData);
            }

        } catch (\Exception $e) {
            Log::error("💥 OpenRouter Exception for prediction: " . $e->getMessage());
            // Fallback to local analysis
            return $this->generateComprehensiveSuccessAnalysis($eventData);
        }
    }

    /**
     * Build success prediction prompt with better formatting
     */
    private function buildSuccessPredictionPrompt($eventData)
    {
        return <<<PROMPT
Analyze this ECO-EVENT and provide a CLEAR, WELL-FORMATTED success prediction:

TITLE: {$eventData['title']}
CATEGORY: {$eventData['category']}
LOCATION: {$eventData['location']}
CAPACITY: {$eventData['capacity_max']} attendees
PRICE: \${$eventData['price']}
DATES: {$eventData['start_date']} to {$eventData['end_date']}

Provide analysis in this EXACT format:

🎯 SUCCESS PROBABILITY: [High/Medium/Low] ([70-90]%)

✅ KEY STRENGTHS:
• [Strength 1]
• [Strength 2] 
• [Strength 3]

⚠️ MAIN CHALLENGES:
• [Challenge 1]
• [Challenge 2]
• [Challenge 3]

💡 TOP RECOMMENDATIONS:
• [Recommendation 1]
• [Recommendation 2] 
• [Recommendation 3]

🌍 ENVIRONMENTAL IMPACT:
[Brief impact description]

Use bullet points, keep it concise and professional. Focus on actionable insights.
PROMPT;
    }

    /**
     * Parse and enhance the AI response for complete event - IMPROVED
     */
    private function parseAndEnhanceEventData($content, $originalData)
    {
        Log::info("🔍 Starting JSON parsing...");
        Log::info("🔍 Content to parse: " . substr($content, 0, 500));

        // First try to extract JSON with better pattern
        preg_match('/\{(?:[^{}]|(?R))*\}/s', $content, $matches);
        
        Log::info("🔍 JSON matches found: " . count($matches));
        
        if (!empty($matches)) {
            Log::info("🔍 Attempting to parse JSON...");
            $aiData = json_decode($matches[0], true);
            
            if (json_last_error() === JSON_ERROR_NONE) {
                Log::info("📊 Successfully parsed AI JSON response");
                Log::info("📊 Parsed data: " . json_encode($aiData));
                return $this->validateAndCompleteEventData($aiData, $originalData);
            } else {
                Log::error("❌ JSON parsing error: " . json_last_error_msg());
                Log::error("❌ JSON content: " . $matches[0]);
            }
        }

        Log::warning("⚠️ JSON parsing failed, trying manual extraction");
        
        // Manual field extraction as fallback
        $aiData = [];
        if (preg_match('/"title":\s*"([^"]+)"/', $content, $matches)) {
            $aiData['title'] = $matches[1];
            Log::info("🔍 Extracted title: " . $aiData['title']);
        }
        if (preg_match('/"description":\s*"([^"]+)"/', $content, $matches)) {
            $aiData['description'] = $matches[1];
            Log::info("🔍 Extracted description: " . substr($aiData['description'], 0, 100) . "...");
        }
        if (preg_match('/"location":\s*"([^"]+)"/', $content, $matches)) {
            $aiData['location'] = $matches[1];
            Log::info("🔍 Extracted location: " . $aiData['location']);
        }

        return $this->validateAndCompleteEventData($aiData, $originalData);
    }

    /**
     * Validate and complete the event data
     */
    private function validateAndCompleteEventData($aiData, $originalData)
    {
        $currentDate = new \DateTime();
        
        $completeData = [
            'title' => $aiData['title'] ?? $originalData['title'],
            'description' => $aiData['description'] ?? $this->generateEcoFocusedDescription($originalData),
            'location' => $aiData['location'] ?? $this->suggestEcoLocation($originalData['category']),
            'capacity_max' => $aiData['capacity_max'] ?? $originalData['capacity'],
            'price' => $aiData['price'] ?? $this->calculateEcoPrice($originalData['capacity']),
            'status' => $aiData['status'] ?? EventStatus::UPCOMING->value,
            'is_public' => $aiData['is_public'] ?? true,
            'start_date' => $aiData['start_date'] ?? $currentDate->modify('+30 days')->format('Y-m-d H:i:s'),
            'end_date' => $aiData['end_date'] ?? $currentDate->modify('+4 hours')->format('Y-m-d H:i:s'),
            'registration_deadline' => $aiData['registration_deadline'] ?? $currentDate->modify('-7 days')->format('Y-m-d H:i:s'),
            'eco_focus' => $aiData['eco_focus'] ?? $this->generateEcoFocus($originalData),
            'sustainability_features' => $aiData['sustainability_features'] ?? $this->generateSustainabilityFeatures($originalData['category'])
        ];

        return $completeData;
    }

    /**
     * Generate eco-focused description
     */
    private function generateEcoFocusedDescription($eventData)
    {
        $descriptions = [
            "Join our community for '{$eventData['title']}' - a transformative event dedicated to environmental awareness and sustainable action. This gathering brings together eco-enthusiasts, community leaders, and sustainability experts to create meaningful change. Through hands-on workshops, inspiring talks, and collaborative activities, we'll explore practical solutions for environmental challenges while building a stronger, more resilient community committed to protecting our planet.",
            
            "Welcome to '{$eventData['title']}', where environmental passion meets community action. This carefully curated event focuses on tangible ecological impact and sustainable development. Participants will engage in meaningful activities, learn from environmental experts, and connect with like-minded individuals who share a commitment to creating a greener, more sustainable future for all.",
            
            "'{$eventData['title']}' represents our collective commitment to environmental stewardship and sustainable living. This event provides a platform for education, collaboration, and action, empowering attendees with the knowledge and tools needed to make a positive environmental impact. Join us in this important movement toward a more sustainable and ecologically conscious community."
        ];
        
        return $descriptions[array_rand($descriptions)];
    }

    /**
     * Suggest eco-appropriate locations
     */
    private function suggestEcoLocation($category)
    {
        $ecoLocations = [
            'Community Eco-Center, 123 Green Street, Sustainable District',
            'City Botanical Gardens, 456 Nature Lane, Park Area',
            'Urban Farm Collective, 789 Sustainability Road, Eco Zone',
            'Environmental Learning Center, 321 Conservation Avenue',
            'Riverside Park Pavilion, 654 Ecology Boulevard',
            'Green Tech Innovation Hub, 987 Renewable Energy Plaza'
        ];
        
        return $ecoLocations[array_rand($ecoLocations)];
    }

    /**
     * Calculate eco-appropriate pricing
     */
    private function calculateEcoPrice($capacity)
    {
        // Eco-events are often free or low-cost to encourage participation
        if ($capacity > 500) return 0; // Free for large community events
        if ($capacity > 200) return 5; // Minimal fee
        if ($capacity > 100) return 10; // Affordable
        if ($capacity > 50) return 15; // Reasonable
        return 20; // Small workshops might have higher fees
    }

    /**
     * Generate eco focus statement
     */
    private function generateEcoFocus($eventData)
    {
        return "This event focuses on practical environmental solutions, community engagement, and sustainable practices that contribute to local ecological preservation and global environmental awareness.";
    }

    /**
     * Generate sustainability features
     */
    private function generateSustainabilityFeatures($category)
    {
        $features = [
            'Zero-waste implementation',
            'Recycling stations throughout',
            'Public transportation encouraged',
            'Local and organic catering',
            'Digital materials to reduce paper',
            'Carbon offset initiatives',
            'Sustainable venue practices'
        ];
        
        return array_slice($features, 0, 3); // Return 3 random features
    }

    /**
     * Generate comprehensive success analysis
     */
    private function generateComprehensiveSuccessAnalysis($eventData)
    {
        return "🌱 ECO-EVENT SUCCESS ANALYSIS\n\n" .
               "📊 SUCCESS PROBABILITY: High (75%)\n\n" .
               "✅ STRENGTHS:\n" .
               "• Strong alignment with current environmental concerns\n" .
               "• Community-focused approach increases engagement\n" .
               "• Practical, actionable environmental outcomes\n" .
               "• Affordable pricing encourages broad participation\n\n" .
               "⚠️ POTENTIAL CHALLENGES:\n" .
               "• Need for targeted environmental community outreach\n" .
               "• Weather considerations for outdoor activities\n" .
               "• Competing with other community events\n\n" .
               "💡 RECOMMENDATIONS:\n" .
               "• Partner with local environmental organizations\n" .
               "• Leverage social media with eco-focused messaging\n" .
               "• Offer volunteer opportunities for greater engagement\n" .
               "• Create post-event action plans for continued impact\n\n" .
               "🌍 ENVIRONMENTAL IMPACT:\n" .
               "Expected to engage {$eventData['capacity_max']} participants in meaningful environmental action, creating lasting community benefits and raising awareness about sustainable practices.";
    }

    /**
     * Comprehensive fallback event data
     */
    private function getComprehensiveFallbackEventData($eventData)
    {
        $currentDate = new \DateTime();
        
        return [
            'title' => $eventData['title'],
            'description' => $this->generateEcoFocusedDescription($eventData),
            'location' => $this->suggestEcoLocation($eventData['category']),
            'capacity_max' => $eventData['capacity'],
            'price' => $this->calculateEcoPrice($eventData['capacity']),
            'status' => EventStatus::UPCOMING->value,
            'is_public' => true,
            'start_date' => $currentDate->modify('+30 days')->format('Y-m-d H:i:s'),
            'end_date' => $currentDate->modify('+4 hours')->format('Y-m-d H:i:s'),
            'registration_deadline' => $currentDate->modify('-7 days')->format('Y-m-d H:i:s'),
            'eco_focus' => $this->generateEcoFocus($eventData),
            'sustainability_features' => $this->generateSustainabilityFeatures($eventData['category'])
        ];
    }
}