<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Conférence',
                'Atelier',
                'Séminaire',
                'Formation',
                'Networking',
                'Concert',
                'Exposition',
                'Festival',
                'Webinaire',
                'Salon professionnel',
            ]),
            'description' => fake()->sentence(),
        ];
    }
}
