<?php

namespace Database\Seeders;

use App\Models\FeedbackCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeedbackCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Organisation',
                'description' => 'Avis concernant l\'organisation générale de l\'événement',
                'icon' => 'fas fa-tasks',
                'color' => '#3b82f6',
                'active' => true,
                'display_order' => 1
            ],
            [
                'name' => 'Contenu',
                'description' => 'Avis sur le contenu et la qualité des présentations',
                'icon' => 'fas fa-file-alt',
                'color' => '#10b981',
                'active' => true,
                'display_order' => 2
            ],
            [
                'name' => 'Lieu',
                'description' => 'Avis sur le lieu de l\'événement',
                'icon' => 'fas fa-map-marker-alt',
                'color' => '#f59e0b',
                'active' => true,
                'display_order' => 3
            ],
            [
                'name' => 'Intervenants',
                'description' => 'Avis sur les intervenants et présentateurs',
                'icon' => 'fas fa-user-tie',
                'color' => '#8b5cf6',
                'active' => true,
                'display_order' => 4
            ],
            [
                'name' => 'Restauration',
                'description' => 'Avis sur les repas et boissons',
                'icon' => 'fas fa-utensils',
                'color' => '#ec4899',
                'active' => true,
                'display_order' => 5
            ],
            [
                'name' => 'Ambiance',
                'description' => 'Avis sur l\'ambiance générale',
                'icon' => 'fas fa-smile',
                'color' => '#6366f1',
                'active' => true,
                'display_order' => 6
            ],
        ];

        foreach ($categories as $category) {
            FeedbackCategory::create($category);
        }
    }
}
