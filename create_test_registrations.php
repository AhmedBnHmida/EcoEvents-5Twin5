<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Registration;
use App\Models\Event;
use App\Models\User;
use App\RegistrationStatus;
use Illuminate\Support\Str;
use Carbon\Carbon;

echo "Création de nouvelles inscriptions avec statut 'attended'...\n";

// Trouver un événement complété
$event = Event::where('status', 'COMPLETED')->first();
if (!$event) {
    echo "Aucun événement complété trouvé.\n";
    exit(1);
}

echo "Événement trouvé: {$event->title} (ID: {$event->id})\n";

// Trouver quelques utilisateurs
$users = User::where('role', 'participant')->take(3)->get();
if ($users->isEmpty()) {
    echo "Aucun utilisateur participant trouvé.\n";
    exit(1);
}

echo "Utilisateurs trouvés: " . $users->count() . "\n";

// Créer de nouvelles inscriptions
$createdCount = 0;
foreach ($users as $user) {
    // Vérifier si l'inscription existe déjà
    $existingRegistration = Registration::where('user_id', $user->id)
        ->where('event_id', $event->id)
        ->first();
    
    if ($existingRegistration) {
        echo "L'utilisateur {$user->name} est déjà inscrit à cet événement.\n";
        continue;
    }
    
    // Créer une nouvelle inscription
    $ticketCode = strtoupper(Str::random(8));
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
    $createdCount++;
    
    echo "Inscription créée pour {$user->name} (ID: {$registration->id})\n";
}

echo "{$createdCount} nouvelles inscriptions créées avec statut 'attended'.\n";
echo "Terminé.\n";
