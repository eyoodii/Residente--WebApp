<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HouseholdMember>
 */
class HouseholdMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = $this->faker->randomElement(['Male', 'Female']);
        return [
            'household_head_id' => \App\Models\HouseholdHead::factory(),
            'first_name' => $this->faker->firstName($gender == 'Male' ? 'male' : 'female'),
            'last_name' => $this->faker->lastName(),
            'date_of_birth' => $this->faker->dateTimeBetween('-80 years', '-1 years')->format('Y-m-d'),
            'gender' => $gender,
            'relationship' => $this->faker->randomElement(['Spouse', 'Son', 'Daughter', 'Other Relative']),
            'civil_status' => $this->faker->randomElement(['Single', 'Married']),
            'link_status' => 'manual',
        ];
    }
}
