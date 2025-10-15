<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Feedback;
use App\Models\FeedbackCategory;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class FeedbackSeeder extends Seeder
{
    public function run(): void
    {
        $pastEvents = Event::where('end_date', '<', now())->get();
        $participants = User::where('role', 'participant')->get();

        $comments = [
            'Excellent événement, très bien organisé!',
            'Contenu très intéressant, j\'ai beaucoup appris.',
            'Bonne organisation mais le lieu était un peu petit.',
            'Les intervenants étaient très compétents.',
            'Super ambiance, je reviendrai l\'année prochaine!',
            'Dommage que la nourriture ne soit pas incluse.',
            'Très professionnel, bravo aux organisateurs.',
            'Le programme était trop chargé, difficile de tout suivre.',
            'Excellente opportunité de networking.',
            'Prix un peu élevé pour le contenu proposé.',
            'Logistique impeccable, tout était parfait.',
            'Les ateliers pratiques étaient très utiles.',
            'Beaucoup de monde, difficile de circuler.',
            'Qualité audio perfectible dans certaines salles.',
            'Globalement satisfait, bon rapport qualité-prix.',
        ];

        foreach ($pastEvents as $event) {
            $eventParticipants = $participants->random(rand(3, 8));

            foreach ($eventParticipants as $participant) {
                $note = rand(3, 5); // Mostly positive feedback for testing
                $comment = $comments[array_rand($comments)];
                
                // Get random category or null (30% chance of no category)
                $categoryId = rand(1, 10) <= 7 ? FeedbackCategory::inRandomOrder()->first()->id : null;

                Feedback::create([
                    'id_evenement' => $event->id,
                    'id_participant' => $participant->id,
                    'category_id' => $categoryId,
                    'note' => $note,
                    'commentaire' => $comment,
                    'date_feedback' => $event->end_date->addDays(rand(1, 7)),
                ]);
            }
        }
    }
}