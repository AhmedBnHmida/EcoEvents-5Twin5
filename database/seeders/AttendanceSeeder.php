<?php

namespace Database\Seeders;

use App\Models\Registration;
use App\Models\Event;
use App\RegistrationStatus;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mettre à jour quelques événements comme complétés
        $events = Event::take(3)->get();
        foreach ($events as $event) {
            $event->status = 'COMPLETED';
            $event->save();
            $this->command->info("Événement ID {$event->id} ({$event->title}) marqué comme complété.");
        }

        // Mettre à jour quelques inscriptions comme attended
        $registrations = Registration::take(10)->get();
        foreach ($registrations as $registration) {
            $registration->status = RegistrationStatus::ATTENDED;
            $registration->attended_at = Carbon::now()->subDays(rand(1, 30));
            $registration->save();
            $this->command->info("Inscription ID {$registration->id} marquée comme présente.");
        }

        $this->command->info("Mise à jour des présences terminée.");
    }
}
