<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Event categories
            [
                'name' => 'Conférence Technologique',
                'description' => 'Événements liés aux nouvelles technologies et innovations',
                'type' => \App\TypeCategorie::EVENT->value,
            ],
            [
                'name' => 'Séminaire d\'Entreprise',
                'description' => 'Événements professionnels et séminaires corporatifs',
                'type' => \App\TypeCategorie::EVENT->value,
            ],
            [
                'name' => 'Atelier de Formation',
                'description' => 'Sessions de formation et ateliers pratiques',
                'type' => \App\TypeCategorie::EVENT->value,
            ],
            [
                'name' => 'Festival Culturel',
                'description' => 'Événements culturels et festivals artistiques',
                'type' => \App\TypeCategorie::EVENT->value,
            ],
            [
                'name' => 'Compétition Sportive',
                'description' => 'Événements sportifs et compétitions',
                'type' => \App\TypeCategorie::EVENT->value,
            ],

            // Association categories
            [
                'name' => 'Organisme à but non lucratif',
                'description' => 'Associations caritatives et organisations non lucratives',
                'type' => \App\TypeCategorie::ASSOCIATION->value,
            ],
            [
                'name' => 'Association Étudiante',
                'description' => 'Organisations et clubs étudiants',
                'type' => \App\TypeCategorie::ASSOCIATION->value,
            ],

            // General categories
            [
                'name' => 'Divers',
                'description' => 'Catégorie générale pour divers événements',
                'type' => \App\TypeCategorie::GENERAL->value,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}