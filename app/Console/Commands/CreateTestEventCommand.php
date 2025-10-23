<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\User;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateTestEventCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:create-test-event {--hours=24 : Nombre d\'heures avant le début de l\'événement}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crée un événement test pour vérifier les notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = (int) $this->option('hours');
        $this->info("Création d'un événement test qui commencera dans {$hours} heures...");

        // Créer un événement test
        $event = new Event();
        $event->title = 'Événement Test Notification';
        $event->description = 'Cet événement a été créé pour tester les notifications de rappel';
        $event->start_date = Carbon::now()->addHours($hours);
        $event->end_date = Carbon::now()->addHours($hours + 2);
        $event->location = 'Emplacement Test';
        $event->capacity_max = 50;
        $event->categorie_id = 1;
        $event->status = 'UPCOMING';
        $event->registration_deadline = Carbon::now()->addHours($hours - 1);
        $event->price = 0;
        $event->is_public = true;
        $event->save();

        $this->info("Événement test créé avec succès (ID: {$event->id})");

        // Créer une inscription test
        $users = User::limit(1)->get();
        
        if ($users->isEmpty()) {
            $this->error("Aucun utilisateur trouvé pour créer une inscription test");
            return Command::FAILURE;
        }

        $user = $users->first();
        
        $registration = new Registration();
        $registration->event_id = $event->id;
        $registration->user_id = $user->id;
        $registration->status = 'confirmed';
        $registration->ticket_code = 'TEST-' . rand(10000, 99999);
        $registration->qr_code_path = 'test-qr-code.png';
        $registration->registered_at = Carbon::now();
        $registration->save();

        $this->info("Inscription test créée pour l'utilisateur {$user->name} (ID: {$user->id})");
        $this->info("Vous pouvez maintenant exécuter la commande 'php artisan events:send-reminders --hours={$hours}' pour tester les notifications");

        return Command::SUCCESS;
    }
}
