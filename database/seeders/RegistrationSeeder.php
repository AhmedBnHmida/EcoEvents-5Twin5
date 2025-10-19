<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class RegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $events = Event::all();
        $participants = User::where('role', 'participant')->get();

        // Check if we have events and participants
        if ($events->isEmpty() || $participants->isEmpty()) {
            $this->command->info('No events or participants found for registration seeding.');
            return;
        }

        foreach ($events as $event) {
            // Calculate how many participants to assign to this event (1 to max available)
            $maxParticipants = min(8, $participants->count());
            $numParticipants = rand(1, $maxParticipants);
            
            // Get unique random participants safely
            $eventParticipants = $participants->shuffle()->take($numParticipants);

            foreach ($eventParticipants as $participant) {
                $status = $this->getRandomStatus($event);
                
                Registration::create([
                    'user_id' => $participant->id,
                    'event_id' => $event->id,
                    'ticket_code' => 'TICKET-' . Str::upper(Str::random(8)),
                    'qr_code_path' => 'qr-codes/' . Str::random(10) . '.png',
                    'status' => $status,
                    'registered_at' => Carbon::now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }

    private function getRandomStatus($event): string
    {
        $statuses = [
            \App\RegistrationStatus::PENDING->value,
            \App\RegistrationStatus::CONFIRMED->value,
            \App\RegistrationStatus::CANCELED->value,
        ];

        // For past events, include attended status
        if ($event->end_date < now()) {
            $statuses[] = \App\RegistrationStatus::ATTENDED->value;
        }

        return $statuses[array_rand($statuses)];
    }
}