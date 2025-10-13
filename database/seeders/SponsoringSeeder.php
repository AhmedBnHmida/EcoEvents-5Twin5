<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Partner;
use App\Models\Sponsoring;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SponsoringSeeder extends Seeder
{
    public function run(): void
    {
        $events = Event::all();
        $partners = Partner::all();

        $sponsorings = [
            [
                'montant' => 10000.00,
                'type_sponsoring' => \App\TypeSponsoring::ARGENT->value,
                'date' => Carbon::now()->subDays(30),
                'partenaire_id' => $partners[0]->id,
                'evenement_id' => $events[0]->id,
            ],
            [
                'montant' => 5000.00,
                'type_sponsoring' => \App\TypeSponsoring::MATERIEL->value,
                'date' => Carbon::now()->subDays(25),
                'partenaire_id' => $partners[1]->id,
                'evenement_id' => $events[0]->id,
            ],
            [
                'montant' => 7500.00,
                'type_sponsoring' => \App\TypeSponsoring::LOGISTIQUE->value,
                'date' => Carbon::now()->subDays(20),
                'partenaire_id' => $partners[2]->id,
                'evenement_id' => $events[1]->id,
            ],
            [
                'montant' => 3000.00,
                'type_sponsoring' => \App\TypeSponsoring::ARGENT->value,
                'date' => Carbon::now()->subDays(15),
                'partenaire_id' => $partners[3]->id,
                'evenement_id' => $events[2]->id,
            ],
            [
                'montant' => 2000.00,
                'type_sponsoring' => \App\TypeSponsoring::AUTRE->value,
                'date' => Carbon::now()->subDays(10),
                'partenaire_id' => $partners[4]->id,
                'evenement_id' => $events[3]->id,
            ],
        ];

        foreach ($sponsorings as $sponsoring) {
            Sponsoring::create($sponsoring);
        }

        // Add multiple sponsors for some events
        $additionalSponsorings = [
            [
                'montant' => 4000.00,
                'type_sponsoring' => \App\TypeSponsoring::MATERIEL->value,
                'date' => Carbon::now()->subDays(5),
                'partenaire_id' => $partners[0]->id,
                'evenement_id' => $events[1]->id,
            ],
            [
                'montant' => 6000.00,
                'type_sponsoring' => \App\TypeSponsoring::LOGISTIQUE->value,
                'date' => Carbon::now()->subDays(3),
                'partenaire_id' => $partners[1]->id,
                'evenement_id' => $events[2]->id,
            ],
        ];

        foreach ($additionalSponsorings as $sponsoring) {
            Sponsoring::create($sponsoring);
        }
    }
}