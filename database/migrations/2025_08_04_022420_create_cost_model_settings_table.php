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
        Schema::create('cost_model_settings', function (Blueprint $table) {
            $table->id();
            
            // Actual Settings
            $table->decimal('units_price', 15, 2)->default(0); // Harga unit
            $table->integer('qty_units')->default(0); // Jumlah unit
            $table->integer('net_book_value')->default(0); // Net book value dalam tahun
            $table->decimal('solar_price', 15, 2)->default(0); // Harga solar per liter
            $table->decimal('adblue_price', 15, 2)->default(0); // Harga AdBlue per liter
            
            // Assumption Settings
            $table->integer('retase_per_day')->default(0); // Jumlah retase per hari
            $table->decimal('avg_ritase_per_day', 10, 2)->default(0); // Rata-rata ritase per hari (km)
            $table->decimal('fuel_consumption', 10, 2)->default(0); // Konsumsi BBM (km/L)
            $table->decimal('adblue_consumption', 10, 2)->default(0); // Konsumsi AdBlue (km/L)
            $table->integer('day_operation')->default(0); // Hari operasi per bulan
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cost_model_settings');
    }
};
