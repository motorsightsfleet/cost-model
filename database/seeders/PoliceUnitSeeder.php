<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PoliceUnit;

class PoliceUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $policeUnits = [
            [
                'police_number' => 'B 1234 AB',
                'unit_name' => 'Unit Patroli 1',
                'unit_type' => 'Kendaraan',
                'description' => 'Kendaraan patroli utama untuk area Jakarta Pusat',
                'is_active' => true,
            ],
            [
                'police_number' => 'B 5678 CD',
                'unit_name' => 'Unit Patroli 2',
                'unit_type' => 'Kendaraan',
                'description' => 'Kendaraan patroli untuk area Jakarta Selatan',
                'is_active' => true,
            ],
            [
                'police_number' => 'B 9012 EF',
                'unit_name' => 'Unit Patroli 3',
                'unit_type' => 'Motor',
                'description' => 'Motor patroli untuk area Jakarta Barat',
                'is_active' => true,
            ],
            [
                'police_number' => 'B 3456 GH',
                'unit_name' => 'Unit Patroli 4',
                'unit_type' => 'Truk',
                'description' => 'Truk patroli untuk area Jakarta Utara',
                'is_active' => true,
            ],
            [
                'police_number' => 'B 7890 IJ',
                'unit_name' => 'Unit Patroli 5',
                'unit_type' => 'Bus',
                'description' => 'Bus patroli untuk area Jakarta Timur',
                'is_active' => false,
            ],
        ];

        foreach ($policeUnits as $unit) {
            PoliceUnit::firstOrCreate(
                ['police_number' => $unit['police_number']],
                $unit
            );
        }

        $this->command->info('Police units seeded successfully!');
    }
} 