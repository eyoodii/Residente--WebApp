<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resident>
 */
class ResidentFactory extends Factory
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
            'first_name' => $this->faker->firstName($gender == 'Male' ? 'male' : 'female'),
            'last_name' => $this->faker->lastName(),
            'date_of_birth' => $this->faker->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'),
            'place_of_birth' => $this->faker->city(),
            'gender' => $gender,
            'civil_status' => $this->faker->randomElement(['Single', 'Married', 'Widowed']),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'password', // The model casts this to hashed string automatically
            'role' => 'citizen',
            'is_verified' => true,
            'email_verified_at' => now(),
            'is_onboarding_complete' => true,
            'purok' => $this->faker->numberBetween(1, 7),
            'barangay' => $this->faker->randomElement(['Centro', 'Antipolo', 'Baliza']),
            'municipality' => 'Buguey',
            'province' => 'Cagayan',
        ];
    }
}
