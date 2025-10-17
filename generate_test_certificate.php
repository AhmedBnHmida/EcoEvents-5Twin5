<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Registration;
use App\Models\Event;
use App\Services\CertificateService;
use Illuminate\Support\Facades\DB;

echo "Début du test de génération de certificat...\n";

// Trouver un événement complété
$event = Event::where('status', 'COMPLETED')->first();
if (!$event) {
    echo "Aucun événement complété trouvé.\n";
    exit(1);
}

echo "Événement trouvé: {$event->title} (ID: {$event->id})\n";

// Trouver une inscription avec status = attended
$registration = Registration::where('event_id', $event->id)
    ->where('status', 'attended')
    ->whereDoesntHave('certificate')
    ->first();

if (!$registration) {
    echo "Aucune inscription avec présence trouvée pour cet événement sans certificat.\n";
    exit(1);
}

echo "Inscription trouvée: ID {$registration->id}\n";
echo "Status de l'inscription: {$registration->status->value}\n";
echo "Status de l'événement: {$event->status->value}\n";

try {
    // Créer le service de certificats
    $certificateService = new CertificateService();
    
    // Générer le certificat
    echo "Génération du certificat...\n";
    $certificate = $certificateService->generateCertificate($registration);
    
    echo "Certificat généré avec succès: ID {$certificate->id}\n";
    echo "Chemin du fichier: {$certificate->file_path}\n";
} catch (\Exception $e) {
    echo "Erreur lors de la génération du certificat: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "Test terminé.\n";
