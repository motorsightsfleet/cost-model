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
            // Tambahkan kolom police_unit_id sebagai foreign key
            $table->foreignId('police_unit_id')->nullable()->after('id')
                  ->constrained('police_units')->onDelete('set null');
            
            // Hapus unique constraint lama yang menggunakan unit_police_number
            $table->dropUnique('unique_monitoring_record');
            
            // Tambahkan unique constraint baru dengan police_unit_id
            $table->unique(['police_unit_id', 'year', 'week', 'component'], 'unique_monitoring_record_new');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cost_model_monitoring', function (Blueprint $table) {
            // Hapus unique constraint baru
            $table->dropUnique('unique_monitoring_record_new');
            
            // Hapus foreign key
            $table->dropForeign(['police_unit_id']);
            $table->dropColumn('police_unit_id');
            
            // Kembalikan unique constraint lama
            $table->unique(['unit_police_number', 'year', 'week', 'component'], 'unique_monitoring_record');
        });
    }
}; 