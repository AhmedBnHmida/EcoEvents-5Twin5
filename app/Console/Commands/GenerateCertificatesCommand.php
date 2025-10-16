<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Registration;
use App\Services\CertificateService;
use Illuminate\Console\Command;

class GenerateCertificatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'certificates:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère des certificats pour les participants ayant assisté aux événements complétés';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Début de la génération des certificats...');

        // Récupérer tous les événements complétés
        $events = Event::where('status', 'COMPLETED')->get();
        $this->info("Événements complétés trouvés: " . $events->count());

        // Pour chaque événement complété
        foreach ($events as $event) {
            $this->info("Traitement de l'événement: {$event->title} (ID: {$event->id})");
            
            // Récupérer toutes les inscriptions avec status = ATTENDED
            $attendedRegistrations = Registration::where('event_id', $event->id)
                ->where('status', 'ATTENDED')
                ->get();
            
            $this->info("Inscriptions avec présence trouvées: " . $attendedRegistrations->count());
            
            if ($attendedRegistrations->isEmpty()) {
                $this->warn("Aucun participant n'a assisté à l'événement {$event->title}");
                continue;
            }
            
            // Créer le service de certificats
            $certificateService = app(CertificateService::class);
            $generatedCount = 0;
            
            // Générer un certificat pour chaque inscription
            foreach ($attendedRegistrations as $registration) {
                try {
                    $this->line("Génération du certificat pour l'inscription ID: {$registration->id}");
                    
                    // Afficher les valeurs actuelles pour le débogage
                    $this->line("Status de l'inscription: " . $registration->status->value);
                    $this->line("Status de l'événement: " . $registration->event->status->value);
                    
                    $certificate = $certificateService->generateCertificate($registration);
                    $generatedCount++;
                    $this->info("Certificat généré avec succès: ID {$certificate->id}");
                } catch (\Exception $e) {
                    $this->error("Erreur lors de la génération du certificat: " . $e->getMessage());
                }
            }
            
            $this->info("{$generatedCount} certificats générés pour l'événement {$event->title}");
        }

        $this->info('Génération des certificats terminée.');
    }
}
