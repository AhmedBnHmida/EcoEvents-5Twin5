<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Feedback;
use App\Models\GlobalEvaluation;
use Illuminate\Database\Seeder;

class GlobalEvaluationSeeder extends Seeder
{
    public function run(): void
    {
        $pastEvents = Event::where('end_date', '<', now())->get();

        foreach ($pastEvents as $event) {
            $feedbacks = Feedback::where('id_evenement', $event->id)->get();
            
            if ($feedbacks->count() > 0) {
                $moyenneNotes = $feedbacks->avg('note');
                $nbFeedbacks = $feedbacks->count();
                $tauxSatisfaction = ($moyenneNotes / 5) * 100;

                GlobalEvaluation::create([
                    'id_evenement' => $event->id,
                    'moyenne_notes' => round($moyenneNotes, 2),
                    'nb_feedbacks' => $nbFeedbacks,
                    'taux_satisfaction' => round($tauxSatisfaction, 2),
                ]);
            }
        }
    }
}