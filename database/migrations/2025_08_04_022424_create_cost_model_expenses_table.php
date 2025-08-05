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
        Schema::create('cost_model_expenses', function (Blueprint $table) {
            $table->id();
            
            // Actual Expenses
            $table->decimal('insurance_unit', 15, 2)->default(0); // Asuransi per unit per tahun
            $table->decimal('first_payment', 15, 2)->default(0); // Uang muka
            $table->decimal('leasing_payment', 15, 2)->default(0); // Pembayaran leasing (flat 3 tahun)
            $table->decimal('vehicle_tax', 15, 2)->default(0); // Pajak kendaraan per tahun
            $table->decimal('kir', 15, 2)->default(0); // KIR per tahun
            
            // Telematics Module
            $table->decimal('telematics_one_time_cost', 15, 2)->default(0); // Biaya satu kali telematics
            $table->decimal('telematics_recurring_cost', 15, 2)->default(0); // Biaya berulang telematics per bulan
            
            // Tyre Management
            $table->decimal('tire_price', 15, 2)->default(0); // Harga ban per unit
            $table->decimal('lifetime_tyre', 15, 2)->default(0); // Lifetime ban dalam km
            
            // Preventive Maintenance (PM) - 10 tahun
            $table->decimal('oil_price', 15, 2)->default(0); // Harga oli
            $table->decimal('pm_year_1', 15, 2)->default(0);
            $table->decimal('pm_year_2', 15, 2)->default(0);
            $table->decimal('pm_year_3', 15, 2)->default(0);
            $table->decimal('pm_year_4', 15, 2)->default(0);
            $table->decimal('pm_year_5', 15, 2)->default(0);
            $table->decimal('pm_year_6', 15, 2)->default(0);
            $table->decimal('pm_year_7', 15, 2)->default(0);
            $table->decimal('pm_year_8', 15, 2)->default(0);
            $table->decimal('pm_year_9', 15, 2)->default(0);
            $table->decimal('pm_year_10', 15, 2)->default(0);
            
            // General Maintenance (GM) - 10 tahun
            $table->decimal('gm_year_1', 15, 2)->default(0);
            $table->decimal('gm_year_2', 15, 2)->default(0);
            $table->decimal('gm_year_3', 15, 2)->default(0);
            $table->decimal('gm_year_4', 15, 2)->default(0);
            $table->decimal('gm_year_5', 15, 2)->default(0);
            $table->decimal('gm_year_6', 15, 2)->default(0);
            $table->decimal('gm_year_7', 15, 2)->default(0);
            $table->decimal('gm_year_8', 15, 2)->default(0);
            $table->decimal('gm_year_9', 15, 2)->default(0);
            $table->decimal('gm_year_10', 15, 2)->default(0);
            
            // Assumption Expenses
            $table->decimal('toll_cost', 15, 2)->default(0); // Biaya tol per hari
            $table->integer('driver_per_unit')->default(0); // Driver per unit
            $table->decimal('driver_cost', 15, 2)->default(0); // Biaya driver per retase
            $table->integer('tyre_per_unit')->default(0); // Ban per unit
            $table->decimal('downtime_percentage', 5, 2)->default(0); // Persentase downtime
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cost_model_expenses');
    }
};
