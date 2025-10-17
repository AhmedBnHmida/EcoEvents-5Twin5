<?php

namespace App\Observers;

use App\Models\Event;
use App\Models\Registration;
use App\Notifications\EventThankYouNotification;
use App\Services\CertificateService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class EventObserver
{
    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        // Vérifier si le statut de l'événement a été changé en "completed"
        if ($event->isDirty('status') && $event->status->value === 'COMPLETED') {
            // Générer les certificats pour les participants
            $this->generateCertificatesForAttendees($event);
            
            // Envoyer les emails de remerciement aux participants
            $this->sendThankYouEmails($event);
        }
    }
    
    /**
     * Génère des certificats pour tous les participants qui ont assisté à l'événement
     */
    private function generateCertificatesForAttendees(Event $event): void
    {
        try {
            // Récupérer tous les participants qui ont assisté à l'événement
            $attendedRegistrations = Registration::where('event_id', $event->id)
                ->where('status', 'attended')
                ->get();
                
            if ($attendedRegistrations->isEmpty()) {
                Log::info("Aucun participant n'a assisté à l'événement {$event->title} (ID: {$event->id})");
                return;
            }
            
            $certificateService = app(CertificateService::class);
            $generatedCount = 0;
            $failedCount = 0;
            
            foreach ($attendedRegistrations as $registration) {
                try {
                    // Générer le certificat pour chaque participant
                    $certificate = $certificateService->generateCertificate($registration);
                    $generatedCount++;
                    Log::info("Certificat généré avec succès pour l'inscription ID: {$registration->id}, certificat ID: {$certificate->id}");
                } catch (\Exception $e) {
                    $failedCount++;
                    Log::error("Erreur lors de la génération du certificat pour l'inscription ID: {$registration->id}: " . $e->getMessage());
                    
                    // Retry with command line for better error handling
                    try {
                        \Illuminate\Support\Facades\Artisan::call('certificates:generate');
                        Log::info("Tentative de génération via commande Artisan pour l'événement ID: {$event->id}");
                    } catch (\Exception $innerException) {
                        Log::error("Échec de la tentative via Artisan: " . $innerException->getMessage());
                    }
                }
            }
            
            Log::info("{$generatedCount} certificats générés pour l'événement {$event->title} (ID: {$event->id}), {$failedCount} échecs");
        } catch (\Exception $e) {
            Log::error("Erreur lors de la génération des certificats pour l'événement ID: {$event->id}: " . $e->getMessage());
            
            // Fallback to command line generation which has better error handling
            try {
                \Illuminate\Support\Facades\Artisan::call('certificates:generate');
                Log::info("Tentative de génération via commande Artisan pour l'événement ID: {$event->id}");
            } catch (\Exception $innerException) {
                Log::error("Échec de la tentative via Artisan: " . $innerException->getMessage());
            }
        }
    }

    /**
     * Handle the Event "deleted" event.
     */
    public function deleted(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "restored" event.
     */
    public function restored(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "force deleted" event.
     */
    public function forceDeleted(Event $event): void
    {
        //
    }
    
    /**
     * Envoie des emails de remerciement aux participants qui ont assisté à l'événement
     */
    private function sendThankYouEmails(Event $event): void
    {
        try {
            Log::info("Envoi des emails de remerciement pour l'événement {$event->title} (ID: {$event->id})");
            
            // Utiliser la commande Artisan pour envoyer les emails de remerciement
            $exitCode = Artisan::call('events:send-thank-you', [
                'event_id' => $event->id
            ]);
            
            if ($exitCode === 0) {
                Log::info("Emails de remerciement envoyés avec succès pour l'événement {$event->title} (ID: {$event->id})");
            } else {
                Log::error("Erreur lors de l'envoi des emails de remerciement pour l'événement {$event->title} (ID: {$event->id})");
            }
        } catch (\Exception $e) {
            Log::error("Exception lors de l'envoi des emails de remerciement pour l'événement ID: {$event->id}: " . $e->getMessage());
        }
    }
}
