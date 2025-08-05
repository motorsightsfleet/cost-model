<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CostModelMonitoring>
 */
class CostModelMonitoringFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $components = [
            'Service_Berkala/PM',
            'Service_General/GM', 
            'BBM',
            'AdBlue',
            'Driver_Cost',
            'Ban',
            'Downtime'
        ];

        return [
            'unit_police_number' => null, // Akan diisi dengan ID dari police_units
            'year' => $this->faker->numberBetween(1, 10),
            'week' => $this->faker->numberBetween(1, 52),
            'component' => $this->faker->randomElement($components),
            'value' => $this->faker->randomFloat(2, 1000, 10000000),
            'note' => $this->faker->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the monitoring record is for a specific police unit.
     */
    public function forPoliceUnit($policeUnitId): static
    {
        return $this->state(fn (array $attributes) => [
            'unit_police_number' => $policeUnitId,
        ]);
    }

    /**
     * Indicate that the monitoring record is for metadata.
     */
    public function metadata(): static
    {
        return $this->state(fn (array $attributes) => [
            'week' => 0,
            'component' => 'unit_police_number',
            'value' => null,
        ]);
    }
} 