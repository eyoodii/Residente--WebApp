<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HouseholdHead>
 */
class HouseholdHeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'household_id' => \App\Models\Household::factory(),
            'resident_id' => \App\Models\Resident::factory(),
            'surname' => $this->faker->lastName(),
            'family_name' => function (array $attributes) {
                return $attributes['surname'] . ' Family';
            },
            'family_size' => 1,
            'is_primary_family' => true,
            'is_active' => true,
            'is_4ps_beneficiary' => $this->faker->boolean(20),
        ];
    }
}
