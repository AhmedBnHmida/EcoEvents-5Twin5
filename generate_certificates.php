<?php

use App\Models\Registration;
use App\Models\Event;
use App\Services\CertificateService;
use Illuminate\Support\Facades\Log;

// Récupérer tous les événements complétés
$events = Event::where('status', 'COMPLETED')->get();
echo "Événements complétés trouvés: " . $events->count() . "\n";

// Pour chaque événement complété
foreach ($events as $event) {
    echo "Traitement de l'événement: {$event->title} (ID: {$event->id})\n";
    
    // Récupérer toutes les inscriptions avec status = ATTENDED
    $attendedRegistrations = Registration::where('event_id', $event->id)
        ->where('status', 'ATTENDED')
        ->get();
    
    echo "Inscriptions avec présence trouvées: " . $attendedRegistrations->count() . "\n";
    
    if ($attendedRegistrations->isEmpty()) {
        echo "Aucun participant n'a assisté à l'événement {$event->title}\n";
        continue;
    }
    
    // Créer le service de certificats
    $certificateService = app(CertificateService::class);
    $generatedCount = 0;
    
    // Générer un certificat pour chaque inscription
    foreach ($attendedRegistrations as $registration) {
        try {
            echo "Génération du certificat pour l'inscription ID: {$registration->id}\n";
            $certificate = $certificateService->generateCertificate($registration);
            $generatedCount++;
            echo "Certificat généré avec succès: ID {$certificate->id}\n";
        } catch (\Exception $e) {
            echo "Erreur lors de la génération du certificat: " . $e->getMessage() . "\n";
        }
    }
    
    echo "{$generatedCount} certificats générés pour l'événement {$event->title}\n";
}

echo "Traitement terminé.\n";
