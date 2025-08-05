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
        // Make cost_model_settings fields nullable
        Schema::table('cost_model_settings', function (Blueprint $table) {
            $table->decimal('units_price', 15, 2)->nullable()->change();
            $table->integer('qty_units')->nullable()->change();
            $table->integer('net_book_value')->nullable()->change();
            $table->decimal('solar_price', 15, 2)->nullable()->change();
            $table->decimal('adblue_price', 15, 2)->nullable()->change();
            $table->integer('retase_per_day')->nullable()->change();
            $table->decimal('avg_ritase_per_day', 10, 2)->nullable()->change();
            $table->decimal('fuel_consumption', 10, 2)->nullable()->change();
            $table->decimal('adblue_consumption', 10, 2)->nullable()->change();
            $table->integer('day_operation')->nullable()->change();
        });

        // Make cost_model_expenses fields nullable
        Schema::table('cost_model_expenses', function (Blueprint $table) {
            $table->decimal('insurance_unit', 15, 2)->nullable()->change();
            $table->decimal('first_payment', 15, 2)->nullable()->change();
            $table->decimal('leasing_payment', 15, 2)->nullable()->change();
            $table->decimal('vehicle_tax', 15, 2)->nullable()->change();
            $table->decimal('kir', 15, 2)->nullable()->change();
            $table->decimal('telematics_one_time_cost', 15, 2)->nullable()->change();
            $table->decimal('telematics_recurring_cost', 15, 2)->nullable()->change();
            $table->decimal('tire_price', 15, 2)->nullable()->change();
            $table->decimal('lifetime_tyre', 15, 2)->nullable()->change();
            $table->decimal('oil_price', 15, 2)->nullable()->change();
            
            // PM Costs
            for ($i = 1; $i <= 10; $i++) {
                $table->decimal("pm_year_{$i}", 15, 2)->nullable()->change();
            }
            
            // GM Costs
            for ($i = 1; $i <= 10; $i++) {
                $table->decimal("gm_year_{$i}", 15, 2)->nullable()->change();
            }
            
            $table->decimal('toll_cost', 15, 2)->nullable()->change();
            $table->integer('driver_per_unit')->nullable()->change();
            $table->decimal('driver_cost', 15, 2)->nullable()->change();
            $table->integer('tyre_per_unit')->nullable()->change();
            $table->decimal('downtime_percentage', 5, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert cost_model_settings fields
        Schema::table('cost_model_settings', function (Blueprint $table) {
            $table->decimal('units_price', 15, 2)->default(0)->change();
            $table->integer('qty_units')->default(0)->change();
            $table->integer('net_book_value')->default(0)->change();
            $table->decimal('solar_price', 15, 2)->default(0)->change();
            $table->decimal('adblue_price', 15, 2)->default(0)->change();
            $table->integer('retase_per_day')->default(0)->change();
            $table->decimal('avg_ritase_per_day', 10, 2)->default(0)->change();
            $table->decimal('fuel_consumption', 10, 2)->default(0)->change();
            $table->decimal('adblue_consumption', 10, 2)->default(0)->change();
            $table->integer('day_operation')->default(0)->change();
        });

        // Revert cost_model_expenses fields
        Schema::table('cost_model_expenses', function (Blueprint $table) {
            $table->decimal('insurance_unit', 15, 2)->default(0)->change();
            $table->decimal('first_payment', 15, 2)->default(0)->change();
            $table->decimal('leasing_payment', 15, 2)->default(0)->change();
            $table->decimal('vehicle_tax', 15, 2)->default(0)->change();
            $table->decimal('kir', 15, 2)->default(0)->change();
            $table->decimal('telematics_one_time_cost', 15, 2)->default(0)->change();
            $table->decimal('telematics_recurring_cost', 15, 2)->default(0)->change();
            $table->decimal('tire_price', 15, 2)->default(0)->change();
            $table->decimal('lifetime_tyre', 15, 2)->default(0)->change();
            $table->decimal('oil_price', 15, 2)->default(0)->change();
            
            // PM Costs
            for ($i = 1; $i <= 10; $i++) {
                $table->decimal("pm_year_{$i}", 15, 2)->default(0)->change();
            }
            
            // GM Costs
            for ($i = 1; $i <= 10; $i++) {
                $table->decimal("gm_year_{$i}", 15, 2)->default(0)->change();
            }
            
            $table->decimal('toll_cost', 15, 2)->default(0)->change();
            $table->integer('driver_per_unit')->default(0)->change();
            $table->decimal('driver_cost', 15, 2)->default(0)->change();
            $table->integer('tyre_per_unit')->default(0)->change();
            $table->decimal('downtime_percentage', 5, 2)->default(0)->change();
        });
    }
};
