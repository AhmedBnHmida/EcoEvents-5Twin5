<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Feedback;
use App\Models\GlobalEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    /**
     * Display evaluations for all events (Admin)
     */
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }

        $evaluations = GlobalEvaluation::with('event')
            ->orderBy('moyenne_notes', 'desc')
            ->paginate(15);

        // Statistics
        $totalFeedbacks = Feedback::count();
        $averageRating = Feedback::avg('note');
        $eventsWithFeedback = GlobalEvaluation::count();

        return view('evaluations.index', compact('evaluations', 'totalFeedbacks', 'averageRating', 'eventsWithFeedback'));
    }

    /**
     * Show detailed evaluation for a specific event
     */
    public function show($eventId)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }

        $event = Event::with(['registrations', 'feedbacks.participant'])->findOrFail($eventId);
        $evaluation = GlobalEvaluation::where('id_evenement', $eventId)->first();

        // Get feedbacks grouped by rating
        $feedbacksByRating = Feedback::where('id_evenement', $eventId)
            ->selectRaw('note, count(*) as count')
            ->groupBy('note')
            ->pluck('count', 'note')
            ->toArray();

        // Fill missing ratings with 0
        for ($i = 1; $i <= 5; $i++) {
            if (!isset($feedbacksByRating[$i])) {
                $feedbacksByRating[$i] = 0;
            }
        }
        ksort($feedbacksByRating);

        $feedbacks = Feedback::with('participant')
            ->where('id_evenement', $eventId)
            ->orderBy('date_feedback', 'desc')
            ->get();

        return view('evaluations.show', compact('event', 'evaluation', 'feedbacksByRating', 'feedbacks'));
    }
}