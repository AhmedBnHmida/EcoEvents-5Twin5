<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Registration;
use App\Notifications\EventReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:send-reminders {--hours=24 : Nombre d\'heures avant l\'événement pour envoyer le rappel}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie des rappels par email aux participants des événements à venir';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = (int) $this->option('hours');
        $this->info("Recherche des événements qui commencent dans environ {$hours} heures...");

        // Calculer la plage de dates pour les événements
        $startTime = Carbon::now()->addHours($hours)->subHours(1); // -1 heure de marge
        $endTime = Carbon::now()->addHours($hours)->addHours(1);   // +1 heure de marge

        // Trouver les événements qui commencent dans la plage spécifiée
        $events = Event::where('status', 'UPCOMING')
            ->whereBetween('start_date', [$startTime, $endTime])
            ->get();

        $this->info("Nombre d'événements trouvés : " . $events->count());

        $reminderCount = 0;

        foreach ($events as $event) {
            $this->info("Traitement de l'événement : {$event->title} (ID: {$event->id})");
            
            // Récupérer les inscriptions confirmées pour cet événement
            $registrations = Registration::where('event_id', $event->id)
                ->where('status', 'confirmed')
                ->get();
            
            $this->info("Nombre d'inscriptions confirmées : " . $registrations->count());

            foreach ($registrations as $registration) {
                try {
                    $user = $registration->user;
                    
                    if ($user) {
                        $this->line("Envoi du rappel à {$user->name} ({$user->email})");
                        $user->notify(new EventReminderNotification($registration));
                        $reminderCount++;
                    }
                } catch (\Exception $e) {
                    $this->error("Erreur lors de l'envoi du rappel pour l'inscription ID {$registration->id}: " . $e->getMessage());
                    Log::error("Erreur lors de l'envoi du rappel pour l'inscription ID {$registration->id}: " . $e->getMessage());
                }
            }
        }

        $this->info("Rappels envoyés avec succès : {$reminderCount}");
    }
}
