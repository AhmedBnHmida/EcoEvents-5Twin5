<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::where('type', \App\TypeCategorie::EVENT->value)->get();

        $events = [
            [
                'title' => 'Conférence IA 2024',
                'description' => 'Une conférence majeure sur l\'intelligence artificielle et ses applications futures.',
                'start_date' => Carbon::now()->addDays(30),
                'end_date' => Carbon::now()->addDays(32),
                'location' => 'Centre des Congrès, Paris',
                'capacity_max' => 500,
                'categorie_id' => $categories->where('name', 'Conférence Technologique')->first()->id,
                'status' => \App\EventStatus::UPCOMING->value,
                'registration_deadline' => Carbon::now()->addDays(20),
                'price' => 199.99,
                'is_public' => true,
                'images' => [
                    'https://picsum.photos/800/600?random=1',
                    'https://picsum.photos/800/600?random=11',
                    'https://picsum.photos/800/600?random=111'
                ],
            ],
            [
                'title' => 'Festival de Musique Électronique',
                'description' => 'Un festival de musique électronique avec des DJ internationaux.',
                'start_date' => Carbon::now()->addDays(45),
                'end_date' => Carbon::now()->addDays(47),
                'location' => 'Parc des Expositions, Lyon',
                'capacity_max' => 5000,
                'categorie_id' => $categories->where('name', 'Festival Culturel')->first()->id,
                'status' => \App\EventStatus::UPCOMING->value,
                'registration_deadline' => Carbon::now()->addDays(35),
                'price' => 75.00,
                'is_public' => true,
                'images' => [
                    'https://picsum.photos/800/600?random=2',
                    'https://picsum.photos/800/600?random=22',
                    'https://picsum.photos/800/600?random=222'
                ],
            ],
            [
                'title' => 'Marathon International',
                'description' => 'Course à pied de 42km à travers la ville avec participants internationaux.',
                'start_date' => Carbon::now()->addDays(60),
                'end_date' => Carbon::now()->addDays(60),
                'location' => 'Centre-ville, Marseille',
                'capacity_max' => 10000,
                'categorie_id' => $categories->where('name', 'Compétition Sportive')->first()->id,
                'status' => \App\EventStatus::UPCOMING->value,
                'registration_deadline' => Carbon::now()->addDays(45),
                'price' => 50.00,
                'is_public' => true,
                'images' => [
                    'https://picsum.photos/800/600?random=3',
                    'https://picsum.photos/800/600?random=33'
                ],
            ],
            [
                'title' => 'Atelier Développement Web',
                'description' => 'Atelier pratique sur les dernières technologies web.',
                'start_date' => Carbon::now()->addDays(15),
                'end_date' => Carbon::now()->addDays(15),
                'location' => 'Espace Coworking, Toulouse',
                'capacity_max' => 50,
                'categorie_id' => $categories->where('name', 'Atelier de Formation')->first()->id,
                'status' => \App\EventStatus::UPCOMING->value,
                'registration_deadline' => Carbon::now()->addDays(10),
                'price' => 120.00,
                'is_public' => false,
                'images' => [
                    'https://picsum.photos/800/600?random=4',
                    'https://picsum.photos/800/600?random=44',
                    'https://picsum.photos/800/600?random=444'
                ],
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }

        // Create some past events for testing feedback and analytics
        $pastEvents = [
            [
                'title' => 'Conférence Blockchain 2023',
                'description' => 'Conférence sur les technologies blockchain et crypto-monnaies.',
                'start_date' => Carbon::now()->subDays(60),
                'end_date' => Carbon::now()->subDays(58),
                'location' => 'Palais des Congrès, Paris',
                'capacity_max' => 300,
                'categorie_id' => $categories->where('name', 'Conférence Technologique')->first()->id,
                'status' => \App\EventStatus::COMPLETED->value,
                'registration_deadline' => Carbon::now()->subDays(75),
                'price' => 179.99,
                'is_public' => true,
                'images' => [
                    'https://picsum.photos/800/600?random=5',
                    'https://picsum.photos/800/600?random=55'
                ],
            ],
            [
                'title' => 'Workshop UX Design',
                'description' => 'Atelier intensif sur les principes du design d\'expérience utilisateur.',
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->subDays(30),
                'location' => 'Studio Design, Lyon',
                'capacity_max' => 40,
                'categorie_id' => $categories->where('name', 'Atelier de Formation')->first()->id,
                'status' => \App\EventStatus::COMPLETED->value,
                'registration_deadline' => Carbon::now()->subDays(40),
                'price' => 95.00,
                'is_public' => true,
                'images' => [
                    'https://picsum.photos/800/600?random=6',
                    'https://picsum.photos/800/600?random=66',
                    'https://picsum.photos/800/600?random=666'
                ],
            ],
        ];

        foreach ($pastEvents as $event) {
            Event::create($event);
        }
    }
}