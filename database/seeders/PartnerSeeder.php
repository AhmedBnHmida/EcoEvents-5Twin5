<?php

namespace Database\Seeders;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $supplierUsers = User::where('role', 'fournisseur')->get();

        $partners = [
            [
                'nom' => 'Microsoft France',
                'type' => 'Technologie',
                'contact' => 'Jean Dupont',
                'email' => 'partenariats@microsoft.fr',
                'telephone' => '+33123456780',
            ],
            [
                'nom' => 'Google Cloud',
                'type' => 'Technologie',
                'contact' => 'Marie Laurent',
                'email' => 'events@google.com',
                'telephone' => '+33123456781',
            ],
            [
                'nom' => 'Red Bull',
                'type' => 'Boissons',
                'contact' => 'Paul Martin',
                'email' => 'events@redbull.fr',
                'telephone' => '+33123456782',
            ],
            [
                'nom' => 'Nike Sports',
                'type' => 'Équipement Sportif',
                'contact' => 'Sophie Bernard',
                'email' => 'sponsorship@nike.com',
                'telephone' => '+33123456783',
            ],
            [
                'nom' => 'Air France',
                'type' => 'Transport',
                'contact' => 'Luc Dubois',
                'email' => 'partenariats@airfrance.fr',
                'telephone' => '+33123456784',
            ],
        ];

        foreach ($partners as $index => $partner) {
            Partner::create(array_merge($partner, [
                'user_id' => $supplierUsers->count() > $index ? $supplierUsers[$index]->id : null,
            ]));
        }
    }
}