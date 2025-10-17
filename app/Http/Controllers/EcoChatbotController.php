<?php

namespace App\Http\Controllers;

use App\Services\EcoChatbotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EcoChatbotController extends Controller
{
    protected $chatbotService;
    
    public function __construct(EcoChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }
    
    /**
     * Process a chatbot message
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'history' => 'nullable|array'
        ]);
        
        try {
            $message = $request->input('message');
            $history = $request->input('history', []);
            
            Log::info('EcoChatbot: Processing message', [
                'message' => $message,
                'history_count' => count($history)
            ]);
            
            $response = $this->chatbotService->processMessage($message, $history);
            
            return response()->json([
                'success' => true,
                'response' => $response,
                'history' => $this->chatbotService->getHistory()
            ]);
        } catch (\Exception $e) {
            Log::error('EcoChatbot: Error processing message', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'response' => "Je suis désolé, je n'ai pas pu traiter votre demande. Veuillez réessayer plus tard.",
                'error' => $e->getMessage()
            ], 500);
        }
    }
}