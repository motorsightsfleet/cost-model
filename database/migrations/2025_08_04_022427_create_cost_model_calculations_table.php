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
        Schema::create('cost_model_calculations', function (Blueprint $table) {
            $table->id();
            
            // Unit Payment
            $table->decimal('unit_down_payment', 15, 2)->default(0); // Uang muka unit (30%)
            $table->decimal('financing', 15, 2)->default(0); // Pembiayaan (70%)
            $table->decimal('leasing_payment_yearly', 15, 2)->default(0); // Pembayaran leasing tahunan
            
            // Retase
            $table->decimal('avg_ret_per_month', 15, 2)->default(0); // Rata-rata retase per bulan
            $table->decimal('avg_ret_per_year', 15, 2)->default(0); // Rata-rata retase per tahun
            
            // Fuel Consumption
            $table->decimal('fuel_consumption_per_ret', 15, 2)->default(0); // Konsumsi BBM per retase
            $table->decimal('fuel_consumption_per_month', 15, 2)->default(0); // Konsumsi BBM per bulan
            $table->decimal('fuel_consumption_per_year', 15, 2)->default(0); // Konsumsi BBM per tahun
            $table->decimal('solar_per_year', 15, 2)->default(0); // Biaya solar per tahun
            
            // AdBlue
            $table->decimal('adblue_consumption_per_day', 15, 2)->default(0); // Konsumsi AdBlue per hari
            $table->decimal('adblue_consumption_per_month', 15, 2)->default(0); // Konsumsi AdBlue per bulan
            $table->decimal('adblue_consumption_per_year', 15, 2)->default(0); // Konsumsi AdBlue per tahun
            
            // Operation
            $table->decimal('driver_cost_per_month', 15, 2)->default(0); // Biaya driver per bulan
            $table->decimal('driver_cost_per_year', 15, 2)->default(0); // Biaya driver per tahun
            
            // Tyre Management
            $table->decimal('cost_per_unit', 15, 2)->default(0); // Biaya per unit
            $table->decimal('idr_per_km', 15, 2)->default(0); // IDR per km per ban
            $table->decimal('idr_per_km_unit', 15, 2)->default(0); // IDR per km per unit
            $table->decimal('cost_days', 15, 2)->default(0); // Biaya per hari
            $table->decimal('cost_month', 15, 2)->default(0); // Biaya per bulan
            $table->decimal('cost_year', 15, 2)->default(0); // Biaya per tahun
            
            // Telematics Module
            $table->decimal('telematics_cost_per_month', 15, 2)->default(0); // Biaya telematics per bulan
            $table->decimal('telematics_cost_first_year', 15, 2)->default(0); // Biaya telematics tahun pertama
            $table->decimal('telematics_cost_subsequent_years', 15, 2)->default(0); // Biaya telematics tahun berikutnya
            
            // Total Calculations
            $table->decimal('total_cost_non_units', 15, 2)->default(0); // Total biaya non unit
            $table->decimal('downtime_cost_estimate', 15, 2)->default(0); // Estimasi biaya downtime
            
            // Yearly breakdowns (10 tahun)
            $table->json('yearly_breakdown')->nullable(); // Breakdown per tahun dalam format JSON
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cost_model_calculations');
    }
};
