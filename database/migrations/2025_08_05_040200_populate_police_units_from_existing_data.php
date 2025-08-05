<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\PoliceUnit;
use App\Models\CostModelMonitoring;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ambil semua nomor polisi unik dari tabel cost_model_monitoring
        $uniquePoliceNumbers = DB::table('cost_model_monitoring')
            ->select('unit_police_number')
            ->whereNotNull('unit_police_number')
            ->where('unit_police_number', '!=', '')
            ->distinct()
            ->pluck('unit_police_number');

        // Masukkan ke tabel police_units
        foreach ($uniquePoliceNumbers as $policeNumber) {
            PoliceUnit::create([
                'police_number' => $policeNumber,
                'unit_name' => 'Unit ' . $policeNumber,
                'unit_type' => 'Kendaraan',
                'description' => 'Unit dengan nomor polisi ' . $policeNumber,
                'is_active' => true,
            ]);
        }

        // Update police_unit_id di tabel cost_model_monitoring
        foreach ($uniquePoliceNumbers as $policeNumber) {
            $policeUnit = PoliceUnit::where('police_number', $policeNumber)->first();
            
            if ($policeUnit) {
                DB::table('cost_model_monitoring')
                    ->where('unit_police_number', $policeNumber)
                    ->update(['police_unit_id' => $policeUnit->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus semua data dari tabel police_units
        DB::table('police_units')->truncate();
        
        // Reset police_unit_id di tabel cost_model_monitoring
        DB::table('cost_model_monitoring')->update(['police_unit_id' => null]);
    }
}; 