<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Category;
use App\EventStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('+1 week', '+3 months');
        $endDate = (clone $startDate)->modify('+' . fake()->numberBetween(1, 5) . ' days');

        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(3),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'location' => fake()->city() . ', ' . fake()->country(),
            'capacity_max' => fake()->numberBetween(50, 500),
            'categorie_id' => Category::factory(),
            'status' => fake()->randomElement([
                EventStatus::UPCOMING,
                EventStatus::ONGOING,
                EventStatus::COMPLETED,
            ]),
            'registration_deadline' => fake()->dateTimeBetween('now', $startDate),
            'price' => fake()->randomFloat(2, 0, 200),
            'is_public' => fake()->boolean(80), // 80% chance of being public
            'images' => null,
        ];
    }

    /**
     * Indicate that the event is upcoming
     */
    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => EventStatus::UPCOMING,
            'start_date' => fake()->dateTimeBetween('+1 week', '+2 months'),
        ]);
    }

    /**
     * Indicate that the event is ongoing
     */
    public function ongoing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => EventStatus::ONGOING,
            'start_date' => fake()->dateTimeBetween('-1 week', 'now'),
            'end_date' => fake()->dateTimeBetween('now', '+1 week'),
        ]);
    }

    /**
     * Indicate that the event is completed
     */
    public function completed(): static
    {
        $startDate = fake()->dateTimeBetween('-6 months', '-1 week');
        
        return $this->state(fn (array $attributes) => [
            'status' => EventStatus::COMPLETED,
            'start_date' => $startDate,
            'end_date' => (clone $startDate)->modify('+' . fake()->numberBetween(1, 3) . ' days'),
        ]);
    }

    /**
     * Indicate that the event is free
     */
    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => 0,
        ]);
    }

    /**
     * Indicate that the event is private
     */
    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => false,
        ]);
    }
}
