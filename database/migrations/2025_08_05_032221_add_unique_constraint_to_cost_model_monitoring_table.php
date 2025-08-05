<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cost_model_monitoring', function (Blueprint $table) {
            // Tambahkan unique constraint untuk kombinasi unit_police_number, year, week, dan component
            // Ini memastikan bahwa tidak ada duplikasi data untuk kombinasi yang sama
            $table->unique(['unit_police_number', 'year', 'week', 'component'], 'unique_monitoring_record');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cost_model_monitoring', function (Blueprint $table) {
            // Hapus unique constraint
            $table->dropUnique('unique_monitoring_record');
        });
    }
};
