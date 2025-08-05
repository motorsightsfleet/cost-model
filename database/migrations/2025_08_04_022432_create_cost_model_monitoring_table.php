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
        Schema::create('cost_model_monitoring', function (Blueprint $table) {
            $table->id();
            
            $table->string('unit_police_number')->nullable(); // Nomor polisi unit
            $table->integer('year')->default(1); // Tahun monitoring
            $table->integer('week')->default(1); // Minggu (1-52)
            
            // Component monitoring - lebih fleksibel
            $table->string('component'); // Nama komponen (Service_PM, Service_GM, BBM, AdBlue, Driver_Cost, Ban, Downtime)
            $table->decimal('value', 15, 2)->default(0); // Nilai komponen
            $table->text('note')->nullable(); // Catatan untuk komponen
            
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['unit_police_number', 'year', 'week']);
            $table->index(['component']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cost_model_monitoring');
    }
};
