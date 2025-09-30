<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Event;
use App\Models\Registration;
use App\Models\GlobalEvaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Display all feedbacks (Admin)
     */
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }

        $feedbacks = Feedback::with(['event', 'participant'])
            ->orderBy('date_feedback', 'desc')
            ->paginate(15);

        return view('feedback.index', compact('feedbacks'));
    }

    /**
     * Display participant's own feedbacks
     */
    public function myFeedbacks()
    {
        $feedbacks = Feedback::with('event')
            ->where('id_participant', Auth::id())
            ->orderBy('date_feedback', 'desc')
            ->paginate(10);

        return view('feedback.my-feedbacks', compact('feedbacks'));
    }

    /**
     * Show the form for creating feedback for an event
     */
    public function create(Request $request)
    {
        if (!$request->has('event_id')) {
            return redirect()->back()->with('error', 'Événement non spécifié.');
        }

        $event = Event::findOrFail($request->event_id);

        // Check if user has a confirmed or attended registration for this event
        $registration = Registration::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->whereIn('status', ['confirmed', 'attended'])
            ->first();

        if (!$registration) {
            return redirect()->route('events.public.show', $event->id)
                ->with('error', 'Vous devez être inscrit et confirmé pour donner un avis sur cet événement.');
        }

        // Check if user already gave feedback
        $existingFeedback = Feedback::where('id_participant', Auth::id())
            ->where('id_evenement', $event->id)
            ->first();

        if ($existingFeedback) {
            return redirect()->route('feedback.edit', $existingFeedback->id_feedback)
                ->with('info', 'Vous avez déjà donné un avis sur cet événement. Vous pouvez le modifier ici.');
        }

        return view('feedback.create', compact('event'));
    }

    /**
     * Store a newly created feedback
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string|max:1000',
        ]);

        $event = Event::findOrFail($request->event_id);

        // Check if user has a confirmed or attended registration
        $registration = Registration::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->whereIn('status', ['confirmed', 'attended'])
            ->first();

        if (!$registration) {
            return redirect()->back()
                ->with('error', 'Vous devez être inscrit pour donner un avis.');
        }

        // Check if feedback already exists
        $existingFeedback = Feedback::where('id_participant', Auth::id())
            ->where('id_evenement', $event->id)
            ->first();

        if ($existingFeedback) {
            return redirect()->back()
                ->with('error', 'Vous avez déjà donné un avis sur cet événement.');
        }

        // Create feedback
        $feedback = Feedback::create([
            'id_evenement' => $event->id,
            'id_participant' => Auth::id(),
            'note' => $request->note,
            'commentaire' => $request->commentaire,
            'date_feedback' => now(),
        ]);

        // Update global evaluation
        $this->updateGlobalEvaluation($event->id);

        return redirect()->route('feedback.my')
            ->with('success', 'Merci pour votre avis! Il a été enregistré avec succès.');
    }

    /**
     * Show the form for editing feedback
     */
    public function edit($id)
    {
        $feedback = Feedback::findOrFail($id);

        // Check if user owns this feedback
        if ($feedback->id_participant !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Vous ne pouvez pas modifier cet avis.');
        }

        $feedback->load('event');

        return view('feedback.edit', compact('feedback'));
    }

    /**
     * Update feedback
     */
    public function update(Request $request, $id)
    {
        $feedback = Feedback::findOrFail($id);

        // Check if user owns this feedback
        if ($feedback->id_participant !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Vous ne pouvez pas modifier cet avis.');
        }

        $request->validate([
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string|max:1000',
        ]);

        $feedback->update([
            'note' => $request->note,
            'commentaire' => $request->commentaire,
        ]);

        // Update global evaluation
        $this->updateGlobalEvaluation($feedback->id_evenement);

        return redirect()->route('feedback.my')
            ->with('success', 'Votre avis a été modifié avec succès.');
    }

    /**
     * Remove feedback
     */
    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);

        // Check if user owns this feedback
        if ($feedback->id_participant !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Vous ne pouvez pas supprimer cet avis.');
        }

        $eventId = $feedback->id_evenement;
        $feedback->delete();

        // Update global evaluation
        $this->updateGlobalEvaluation($eventId);

        return redirect()->back()
            ->with('success', 'Votre avis a été supprimé avec succès.');
    }

    /**
     * Update global evaluation for an event
     */
    private function updateGlobalEvaluation($eventId)
    {
        $feedbacks = Feedback::where('id_evenement', $eventId)->get();
        
        if ($feedbacks->count() > 0) {
            $moyenneNotes = $feedbacks->avg('note');
            $nbFeedbacks = $feedbacks->count();
            $tauxSatisfaction = ($moyenneNotes / 5) * 100;

            GlobalEvaluation::updateOrCreate(
                ['id_evenement' => $eventId],
                [
                    'moyenne_notes' => round($moyenneNotes, 2),
                    'nb_feedbacks' => $nbFeedbacks,
                    'taux_satisfaction' => round($tauxSatisfaction, 2),
                ]
            );
        } else {
            // If no feedbacks, delete the evaluation
            GlobalEvaluation::where('id_evenement', $eventId)->delete();
        }
    }
}