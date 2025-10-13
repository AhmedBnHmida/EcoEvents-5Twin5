<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Interaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class InteractionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $events = Event::all();

        $interactionTypes = ['view', 'click', 'share', 'save', 'rating'];

        foreach ($users as $user) {
            $userEvents = $events->random(rand(2, 5));

            foreach ($userEvents as $event) {
                $sessionId = Str::uuid();

                // Create multiple interactions per user per event
                for ($i = 0; $i < rand(1, 3); $i++) {
                    $type = $interactionTypes[array_rand($interactionTypes)];
                    
                    $metadata = [];
                    switch ($type) {
                        case 'view':
                            $metadata = ['duration' => rand(30, 300), 'page' => 'event_details'];
                            $value = rand(1, 10);
                            break;
                        case 'click':
                            $metadata = ['element' => 'register_button', 'position' => rand(1, 5)];
                            $value = 1;
                            break;
                        case 'share':
                            $metadata = ['platform' => ['facebook', 'twitter', 'linkedin'][array_rand(['facebook', 'twitter', 'linkedin'])]];
                            $value = 1;
                            break;
                        case 'save':
                            $metadata = ['list' => 'favorites'];
                            $value = 1;
                            break;
                        case 'rating':
                            $metadata = ['scale' => 5];
                            $value = rand(3, 5);
                            break;
                        default:
                            $value = 1;
                    }

                    Interaction::create([
                        'user_id' => $user->id,
                        'session_id' => $sessionId,
                        'event_id' => $event->id,
                        'type' => $type,
                        'value' => $value,
                        'metadata' => $metadata,
                        'created_at' => Carbon::now()->subDays(rand(0, 60)),
                    ]);
                }
            }
        }
    }
}