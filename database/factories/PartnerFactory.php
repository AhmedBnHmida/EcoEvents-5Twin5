<?php

namespace Database\Factories;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartnerFactory extends Factory
{
    protected $model = Partner::class;

    public function definition(): array
    {
        return [
            'user_id' => null, // Can be set when creating
            'nom' => fake()->company(),
            'type' => fake()->randomElement(['Entreprise', 'Association', 'Institution', 'ONG', 'Autre']),
            'contact' => fake()->name(),
            'email' => fake()->unique()->companyEmail(),
            'telephone' => fake()->phoneNumber(),
            'logo' => null,
        ];
    }

    /**
     * Indicate that the partner is linked to a user
     */
    public function withUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => User::factory(),
            'contact' => null,
            'email' => null,
        ]);
    }

    /**
     * Indicate that the partner has a logo
     */
    public function withLogo(): static
    {
        return $this->state(fn (array $attributes) => [
            'logo' => 'partners/logos/test-logo.jpg',
        ]);
    }
}
