<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TravelRequest>
 */
class TravelRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'        => 1,
            'status'         => 'pending',
            'destination'    => $this->faker->city,
            'departure_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'return_date'    => $this->faker->dateTimeBetween('+1 year', '+2 years'),
        ];
    }
}
