<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Household>
 */
class HouseholdFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'household_number' => \App\Models\Household::generateHouseholdNumber(),
            'house_number' => $this->faker->buildingNumber(),
            'street' => $this->faker->streetName(),
            'purok' => $this->faker->numberBetween(1, 7),
            'barangay' => $this->faker->randomElement(['Centro', 'Antipolo', 'Baliza']),
            'municipality' => 'Buguey',
            'province' => 'Cagayan',
            'latitude' => $this->faker->latitude(18.28, 18.35),
            'longitude' => $this->faker->longitude(121.80, 121.85),
            'housing_type' => $this->faker->randomElement(['Owned', 'Rented', 'Rent-Free with Consent']),
            'is_active' => true,
        ];
    }
}
