<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PoliceUnit>
 */
class PoliceUnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unitTypes = ['Kendaraan', 'Motor', 'Truk', 'Bus', 'Ambulans', 'Lainnya'];
        
        return [
            'police_number' => 'B ' . $this->faker->numberBetween(1000, 9999) . ' ' . strtoupper($this->faker->lexify('??')),
            'unit_name' => 'Unit ' . $this->faker->word() . ' ' . $this->faker->numberBetween(1, 100),
            'unit_type' => $this->faker->randomElement($unitTypes),
            'description' => $this->faker->sentence(),
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }

    /**
     * Indicate that the unit is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the unit is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
} 