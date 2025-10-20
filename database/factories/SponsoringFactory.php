<?php

namespace Database\Factories;

use App\Models\Sponsoring;
use App\Models\Partner;
use App\Models\Event;
use App\TypeSponsoring;
use Illuminate\Database\Eloquent\Factories\Factory;

class SponsoringFactory extends Factory
{
    protected $model = Sponsoring::class;

    public function definition(): array
    {
        return [
            'montant' => fake()->randomFloat(2, 500, 50000),
            'type_sponsoring' => fake()->randomElement([
                TypeSponsoring::ARGENT,
                TypeSponsoring::MATERIEL,
                TypeSponsoring::LOGISTIQUE,
                TypeSponsoring::AUTRE,
            ]),
            'date' => fake()->dateTimeBetween('-1 year', '+1 year'),
            'partenaire_id' => Partner::factory(),
            'evenement_id' => Event::factory(),
        ];
    }

    /**
     * Indicate that the sponsoring is monetary
     */
    public function argent(): static
    {
        return $this->state(fn (array $attributes) => [
            'type_sponsoring' => TypeSponsoring::ARGENT,
            'montant' => fake()->randomFloat(2, 1000, 100000),
        ]);
    }

    /**
     * Indicate that the sponsoring is material
     */
    public function materiel(): static
    {
        return $this->state(fn (array $attributes) => [
            'type_sponsoring' => TypeSponsoring::MATERIEL,
            'montant' => fake()->randomFloat(2, 500, 20000),
        ]);
    }

    /**
     * Indicate that the sponsoring is logistics
     */
    public function logistique(): static
    {
        return $this->state(fn (array $attributes) => [
            'type_sponsoring' => TypeSponsoring::LOGISTIQUE,
            'montant' => fake()->randomFloat(2, 1000, 30000),
        ]);
    }

    /**
     * Indicate high value sponsoring
     */
    public function highValue(): static
    {
        return $this->state(fn (array $attributes) => [
            'montant' => fake()->randomFloat(2, 50000, 200000),
        ]);
    }

    /**
     * Indicate low value sponsoring
     */
    public function lowValue(): static
    {
        return $this->state(fn (array $attributes) => [
            'montant' => fake()->randomFloat(2, 100, 1000),
        ]);
    }
}
