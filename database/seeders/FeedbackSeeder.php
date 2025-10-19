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

        // Check if we have past events and participants
        if ($pastEvents->isEmpty() || $participants->isEmpty()) {
            $this->command->info('No past events or participants found for feedback seeding.');
            return;
        }

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
            // Get participants who actually attended this event
            $attendedParticipants = Registration::where('event_id', $event->id)
                ->where('status', \App\RegistrationStatus::ATTENDED->value)
                ->with('user')
                ->get()
                ->pluck('user');

            // If no attended participants, use random participants
            if ($attendedParticipants->isEmpty()) {
                $maxParticipants = min(5, $participants->count());
                $eventParticipants = $participants->shuffle()->take($maxParticipants);
            } else {
                $maxParticipants = min(5, $attendedParticipants->count());
                $eventParticipants = $attendedParticipants->shuffle()->take($maxParticipants);
            }

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