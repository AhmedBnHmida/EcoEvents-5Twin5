<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;
use App\Models\Registration;
use App\RegistrationStatus;
use App\EventStatus;
use Carbon\Carbon;

echo "Test de génération automatique de certificats lors du changement de statut d'un événement...\n";

// Trouver un événement qui n'est pas encore complété
$event = Event::where('status', '!=', 'COMPLETED')->first();
if (!$event) {
    echo "Aucun événement non-complété trouvé. Création d'un nouvel événement...\n";
    
    // Créer un nouvel événement
    $event = new Event([
        'title' => 'Événement de test pour certificats',
        'description' => 'Événement créé pour tester la génération automatique de certificats',
        'start_date' => Carbon::now()->subDays(10),
        'end_date' => Carbon::now()->subDays(5),
        'location' => 'Salle de test',
        'capacity_max' => 50,
        'status' => 'ONGOING',
        'categorie_id' => 1,
        'user_id' => 1,
    ]);
    
    $event->save();
    echo "Nouvel événement créé: {$event->title} (ID: {$event->id})\n";
}

echo "Événement trouvé: {$event->title} (ID: {$event->id})\n";
echo "Statut actuel: {$event->status->value}\n";

// Vérifier s'il y a des inscriptions avec status = attended pour cet événement
$attendedCount = Registration::where('event_id', $event->id)
    ->where('status', 'attended')
    ->count();

echo "Inscriptions avec présence pour cet événement: {$attendedCount}\n";

if ($attendedCount == 0) {
    echo "Création de quelques inscriptions avec présence pour cet événement...\n";
    
    // Trouver quelques utilisateurs
    $users = \App\Models\User::where('role', 'participant')->take(3)->get();
    
    foreach ($users as $user) {
        // Vérifier si l'inscription existe déjà
        $existingRegistration = Registration::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();
        
        if ($existingRegistration) {
            echo "L'utilisateur {$user->name} est déjà inscrit à cet événement. Mise à jour du statut...\n";
            $existingRegistration->status = RegistrationStatus::ATTENDED;
            $existingRegistration->attended_at = Carbon::now();
            $existingRegistration->save();
        } else {
            // Créer une nouvelle inscription
            $ticketCode = strtoupper(\Illuminate\Support\Str::random(8));
            $qrCodePath = 'qrcodes/' . $ticketCode . '.svg';
            
            $registration = new Registration([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'ticket_code' => $ticketCode,
                'qr_code_path' => $qrCodePath,
                'status' => RegistrationStatus::ATTENDED,
                'registered_at' => Carbon::now()->subDays(rand(10, 30)),
                'attended_at' => Carbon::now()->subDays(rand(1, 5)),
            ]);
            
            $registration->save();
            echo "Nouvelle inscription créée pour {$user->name} (ID: {$registration->id})\n";
        }
    }
}

// Compter les certificats avant le changement de statut
$certificatesBeforeCount = \App\Models\Certificate::count();
echo "Nombre de certificats avant le changement de statut: {$certificatesBeforeCount}\n";

// Changer le statut de l'événement à COMPLETED
echo "Changement du statut de l'événement à COMPLETED...\n";
$event->status = EventStatus::COMPLETED;
$event->save();

// Attendre un peu pour que l'observateur ait le temps de s'exécuter
echo "Attente de 2 secondes pour l'exécution de l'observateur...\n";
sleep(2);

// Compter les certificats après le changement de statut
$certificatesAfterCount = \App\Models\Certificate::count();
echo "Nombre de certificats après le changement de statut: {$certificatesAfterCount}\n";

// Vérifier si de nouveaux certificats ont été générés
$newCertificatesCount = $certificatesAfterCount - $certificatesBeforeCount;
echo "Nouveaux certificats générés: {$newCertificatesCount}\n";

if ($newCertificatesCount > 0) {
    echo "La génération automatique de certificats fonctionne correctement!\n";
} else {
    echo "Aucun nouveau certificat n'a été généré. Il y a peut-être un problème avec l'observateur.\n";
}

echo "Test terminé.\n";
