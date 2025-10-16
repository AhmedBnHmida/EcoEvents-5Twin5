<?php

use App\Models\Registration;
use App\Models\Event;
use App\RegistrationStatus;
use Carbon\Carbon;

// Mettre à jour quelques événements comme complétés
$events = Event::take(3)->get();
foreach ($events as $event) {
    $event->status = 'completed';
    $event->save();
    echo "Événement ID {$event->id} ({$event->title}) marqué comme complété.\n";
}

// Mettre à jour quelques inscriptions comme attended
$registrations = Registration::take(10)->get();
foreach ($registrations as $registration) {
    $registration->status = RegistrationStatus::ATTENDED;
    $registration->attended_at = Carbon::now()->subDays(rand(1, 30));
    $registration->save();
    echo "Inscription ID {$registration->id} marquée comme présente.\n";
}

echo "Mise à jour terminée.\n";
