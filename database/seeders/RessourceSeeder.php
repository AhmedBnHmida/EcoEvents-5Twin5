<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Fournisseur;
use App\Models\Ressource;
use Illuminate\Database\Seeder;

class RessourceSeeder extends Seeder
{
    public function run(): void
    {
        $events = Event::all();
        $fournisseurs = Fournisseur::all();

        $ressources = [
            [
                'nom' => 'Système Audio Professionnel',
                'type' => 'Électronique',
                'fournisseur_id' => $fournisseurs[0]->id,
                'event_id' => $events[0]->id,
            ],
            [
                'nom' => 'Projecteurs HD',
                'type' => 'Électronique',
                'fournisseur_id' => $fournisseurs[0]->id,
                'event_id' => $events[0]->id,
            ],
            [
                'nom' => 'Buffet Froid Premium',
                'type' => 'Nourriture',
                'fournisseur_id' => $fournisseurs[1]->id,
                'event_id' => $events[1]->id,
            ],
            [
                'nom' => 'Service de Traiteur',
                'type' => 'Nourriture',
                'fournisseur_id' => $fournisseurs[1]->id,
                'event_id' => $events[0]->id,
            ],
            [
                'nom' => 'Décoration Scénique',
                'type' => 'Décoration',
                'fournisseur_id' => $fournisseurs[2]->id,
                'event_id' => $events[1]->id,
            ],
            [
                'nom' => 'Éclairage LED',
                'type' => 'Décoration',
                'fournisseur_id' => $fournisseurs[2]->id,
                'event_id' => $events[2]->id,
            ],
            [
                'nom' => 'Agents de Sécurité',
                'type' => 'Sécurité',
                'fournisseur_id' => $fournisseurs[3]->id,
                'event_id' => $events[1]->id,
            ],
            [
                'nom' => 'Groupe Électrogène 50kVA',
                'type' => 'Énergie',
                'fournisseur_id' => $fournisseurs[4]->id,
                'event_id' => $events[2]->id,
            ],
            [
                'nom' => 'Tentes et Abris',
                'type' => 'Matériel',
                'fournisseur_id' => $fournisseurs[2]->id,
                'event_id' => $events[3]->id,
            ],
            [
                'nom' => 'Matériel de Bureau',
                'type' => 'Papeterie',
                'fournisseur_id' => $fournisseurs[0]->id,
                'event_id' => $events[4]->id,
            ],
        ];

        foreach ($ressources as $ressource) {
            Ressource::create($ressource);
        }
    }
}