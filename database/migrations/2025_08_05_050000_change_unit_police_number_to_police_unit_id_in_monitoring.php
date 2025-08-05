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
        // Hapus unique constraint lama
        Schema::table('cost_model_monitoring', function (Blueprint $table) {
            $table->dropUnique('unique_monitoring_record_new');
        });

        // Hapus kolom police_unit_id yang sudah ada
        Schema::table('cost_model_monitoring', function (Blueprint $table) {
            $table->dropForeign(['police_unit_id']);
            $table->dropColumn('police_unit_id');
        });

        // Update data existing: ubah string police_number menjadi id dari police_units
        $policeUnits = DB::table('police_units')->get();
        $policeNumberToId = $policeUnits->pluck('id', 'police_number')->toArray();

        foreach ($policeNumberToId as $policeNumber => $id) {
            DB::table('cost_model_monitoring')
                ->where('unit_police_number', $policeNumber)
                ->update(['unit_police_number' => $id]);
        }

        // Ubah kolom unit_police_number dari string menjadi integer menggunakan raw SQL untuk PostgreSQL
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE cost_model_monitoring ALTER COLUMN unit_police_number TYPE INTEGER USING unit_police_number::INTEGER');
        } else {
            // Untuk database lain, gunakan cara yang lebih aman
            Schema::table('cost_model_monitoring', function (Blueprint $table) {
                $table->integer('unit_police_number')->nullable()->change();
            });
        }

        // Tambahkan foreign key constraint
        Schema::table('cost_model_monitoring', function (Blueprint $table) {
            $table->foreign('unit_police_number')->references('id')->on('police_units')->onDelete('set null');
        });

        // Tambahkan unique constraint baru
        Schema::table('cost_model_monitoring', function (Blueprint $table) {
            $table->unique(['unit_police_number', 'year', 'week', 'component'], 'unique_monitoring_record_final');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus unique constraint
        Schema::table('cost_model_monitoring', function (Blueprint $table) {
            $table->dropUnique('unique_monitoring_record_final');
        });

        // Hapus foreign key
        Schema::table('cost_model_monitoring', function (Blueprint $table) {
            $table->dropForeign(['unit_police_number']);
        });

        // Update data kembali: ubah id menjadi police_number
        $policeUnits = DB::table('police_units')->get();
        $idToPoliceNumber = $policeUnits->pluck('police_number', 'id')->toArray();

        foreach ($idToPoliceNumber as $id => $policeNumber) {
            DB::table('cost_model_monitoring')
                ->where('unit_police_number', $id)
                ->update(['unit_police_number' => $policeNumber]);
        }

        // Ubah kembali kolom unit_police_number menjadi string
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE cost_model_monitoring ALTER COLUMN unit_police_number TYPE VARCHAR(255) USING unit_police_number::VARCHAR(255)');
        } else {
            Schema::table('cost_model_monitoring', function (Blueprint $table) {
                $table->string('unit_police_number')->nullable()->change();
            });
        }

        // Tambahkan kembali kolom police_unit_id
        Schema::table('cost_model_monitoring', function (Blueprint $table) {
            $table->foreignId('police_unit_id')->nullable()->after('id')
                  ->constrained('police_units')->onDelete('set null');
        });

        // Tambahkan kembali unique constraint lama
        Schema::table('cost_model_monitoring', function (Blueprint $table) {
            $table->unique(['police_unit_id', 'year', 'week', 'component'], 'unique_monitoring_record_new');
        });
    }
}; 