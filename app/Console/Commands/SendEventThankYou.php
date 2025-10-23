<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use App\Models\Event;
use App\Models\Registration;
use App\Notifications\EventThankYouNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendEventThankYou extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:send-thank-you {event_id? : ID de l\'événement spécifique (optionnel)} {--force : Forcer l\'envoi même si déjà envoyé}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie des emails de remerciement aux participants après un événement';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $eventId = $this->argument('event_id');
        $force = $this->option('force');
        
        if ($eventId) {
            // Traiter un événement spécifique
            $event = Event::find($eventId);
            
            if (!$event) {
                $this->error("Événement avec ID {$eventId} non trouvé.");
                return 1;
            }
            
            $events = collect([$event]);
        } else {
            // Trouver les événements qui viennent de se terminer (dans les dernières 24h)
            $endTime = Carbon::now();
            $startTime = Carbon::now()->subHours(24);
            
            $events = Event::where('status', 'COMPLETED')
                ->where('end_date', '>=', $startTime)
                ->where('end_date', '<=', $endTime)
                ->get();
                
            $this->info("Nombre d'événements récemment terminés : " . $events->count());
        }
        
        $thankYouCount = 0;
        
        foreach ($events as $event) {
            $this->info("Traitement de l'événement : {$event->title} (ID: {$event->id})");
            
            // Récupérer les inscriptions avec présence confirmée
            $registrations = Registration::where('event_id', $event->id)
                ->where('status', 'attended')
                ->get();
            
            $this->info("Nombre de participants présents : " . $registrations->count());
            
            foreach ($registrations as $registration) {
                try {
                    $user = $registration->user;
                    
                    if ($user) {
                        // Vérifier si un certificat existe pour cette inscription
                        $hasCertificate = Certificate::where('registration_id', $registration->id)->exists();
                        
                        $this->line("Envoi du remerciement à {$user->name} ({$user->email})");
                        $user->notify(new EventThankYouNotification($registration, $hasCertificate));
                        $thankYouCount++;
                    }
                } catch (\Exception $e) {
                    $this->error("Erreur lors de l'envoi du remerciement pour l'inscription ID {$registration->id}: " . $e->getMessage());
                    Log::error("Erreur lors de l'envoi du remerciement pour l'inscription ID {$registration->id}: " . $e->getMessage());
                }
            }
        }
        
        $this->info("Emails de remerciement envoyés avec succès : {$thankYouCount}");
        return 0;
    }
}
