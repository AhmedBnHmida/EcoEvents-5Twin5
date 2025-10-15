<?php

namespace App\Http\Controllers;

use App\Models\FeedbackCategory;
use App\Services\OpenRouterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackRecommendationController extends Controller
{
    protected $openRouterService;

    public function __construct(OpenRouterService $openRouterService)
    {
        $this->openRouterService = $openRouterService;
    }

    /**
     * Generate a feedback recommendation based on category ID
     */
    public function generateRecommendation(Request $request)
    {
        // Validate request
        $request->validate([
            'category_id' => 'required|exists:feedback_categories,id',
        ]);

        // Get the category
        $category = FeedbackCategory::findOrFail($request->category_id);

        // Generate recommendation
        $recommendation = $this->openRouterService->generateFeedbackRecommendation(
            $category->name,
            $category->description
        );

        if (!$recommendation) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate recommendation. Please try again later.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $recommendation
        ]);
    }
}
