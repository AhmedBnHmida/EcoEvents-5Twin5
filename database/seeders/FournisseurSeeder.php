<?php

namespace Database\Seeders;

use App\Models\Fournisseur;
use Illuminate\Database\Seeder;

class FournisseurSeeder extends Seeder
{
    public function run(): void
    {
        $fournisseurs = [
            [
                'nom_societe' => 'Tech Solutions Inc',
                'domaine_service' => 'Équipements électroniques et audiovisuels',
                'adresse' => '123 Rue de la Technologie, Paris 75001',
                'email' => 'contact@techsolutions.com',
                'telephone' => '+33123456789',
            ],
            [
                'nom_societe' => 'Gourmet Catering',
                'domaine_service' => 'Services de restauration et traiteur',
                'adresse' => '456 Avenue des Gastronomes, Lyon 69002',
                'email' => 'info@gourmetcatering.com',
                'telephone' => '+33456789012',
            ],
            [
                'nom_societe' => 'Event Decor Pro',
                'domaine_service' => 'Décoration et aménagement d\'événements',
                'adresse' => '789 Boulevard des Artistes, Marseille 13001',
                'email' => 'decor@eventpro.com',
                'telephone' => '+33567890123',
            ],
            [
                'nom_societe' => 'Secure Events Ltd',
                'domaine_service' => 'Sécurité et services de surveillance',
                'adresse' => '321 Rue de la Sécurité, Lille 59000',
                'email' => 'security@secureevents.com',
                'telephone' => '+33678901234',
            ],
            [
                'nom_societe' => 'Power & Energy Solutions',
                'domaine_service' => 'Solutions énergétiques et groupes électrogènes',
                'adresse' => '654 Avenue de l\'Énergie, Toulouse 31000',
                'email' => 'energy@powersolutions.com',
                'telephone' => '+33789012345',
            ],
        ];

        foreach ($fournisseurs as $fournisseur) {
            Fournisseur::create($fournisseur);
        }
    }
}