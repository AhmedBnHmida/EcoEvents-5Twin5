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
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }

        $query = GlobalEvaluation::with('event');

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('event', function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter by minimum rating
        if ($request->has('min_rating') && $request->min_rating != '') {
            $query->where('moyenne_notes', '>=', $request->min_rating);
        }

        // Filter by event
        if ($request->has('event_id') && $request->event_id != '') {
            $query->where('id_evenement', $request->event_id);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'moyenne_notes');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $evaluations = $query->paginate(15)->appends($request->except('page'));

        // Statistics
        $totalFeedbacks = Feedback::count();
        $averageRating = Feedback::avg('note');
        $eventsWithFeedback = GlobalEvaluation::count();
        
        // Get events for filter
        $events = Event::all();

        return view('evaluations.index', compact('evaluations', 'totalFeedbacks', 'averageRating', 'eventsWithFeedback', 'events'));
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