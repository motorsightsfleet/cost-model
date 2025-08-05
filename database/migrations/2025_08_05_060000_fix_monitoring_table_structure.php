<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hapus unique constraint yang bermasalah
        try {
            Schema::table('cost_model_monitoring', function (Blueprint $table) {
                $table->dropUnique('unique_monitoring_record_final');
            });
        } catch (\Exception $e) {
            // Ignore jika constraint tidak ada
        }

        // Hapus foreign key yang bermasalah
        try {
            Schema::table('cost_model_monitoring', function (Blueprint $table) {
                $table->dropForeign(['unit_police_number']);
            });
        } catch (\Exception $e) {
            // Ignore jika foreign key tidak ada
        }

        // Ubah kembali kolom unit_police_number menjadi string
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE cost_model_monitoring ALTER COLUMN unit_police_number TYPE VARCHAR(255) USING unit_police_number::VARCHAR(255)');
        } else {
            Schema::table('cost_model_monitoring', function (Blueprint $table) {
                $table->string('unit_police_number')->nullable()->change();
            });
        }

        // Update data kembali ke format string
        $policeUnits = DB::table('police_units')->get();
        $idToPoliceNumber = $policeUnits->pluck('police_number', 'id')->toArray();

        foreach ($idToPoliceNumber as $id => $policeNumber) {
            DB::table('cost_model_monitoring')
                ->where('unit_police_number', $id)
                ->update(['unit_police_number' => $policeNumber]);
        }

        // Tambahkan kembali kolom police_unit_id
        Schema::table('cost_model_monitoring', function (Blueprint $table) {
            $table->foreignId('police_unit_id')->nullable()->after('id')
                  ->constrained('police_units')->onDelete('set null');
        });

        // Update police_unit_id berdasarkan unit_police_number
        foreach ($idToPoliceNumber as $id => $policeNumber) {
            DB::table('cost_model_monitoring')
                ->where('unit_police_number', $policeNumber)
                ->update(['police_unit_id' => $id]);
        }

        // Tambahkan unique constraint yang benar
        Schema::table('cost_model_monitoring', function (Blueprint $table) {
            $table->unique(['police_unit_id', 'year', 'week', 'component'], 'unique_monitoring_record_fixed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus unique constraint
        try {
            Schema::table('cost_model_monitoring', function (Blueprint $table) {
                $table->dropUnique('unique_monitoring_record_fixed');
            });
        } catch (\Exception $e) {
            // Ignore jika constraint tidak ada
        }

        // Hapus kolom police_unit_id
        Schema::table('cost_model_monitoring', function (Blueprint $table) {
            $table->dropForeign(['police_unit_id']);
            $table->dropColumn('police_unit_id');
        });
    }
}; 