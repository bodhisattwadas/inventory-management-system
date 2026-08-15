<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '+91 '.fake()->numberBetween(60000, 99999).' '.fake()->numberBetween(10000, 99999),
            'address' => fake()->address(),
            'notes' => fake()->optional(0.3)->sentence(), // 30% chance of having notes
        ];
    }
}
