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
        // Make cost_model_calculations fields nullable
        Schema::table('cost_model_calculations', function (Blueprint $table) {
            $table->decimal('unit_down_payment', 15, 2)->nullable()->change();
            $table->decimal('financing', 15, 2)->nullable()->change();
            $table->decimal('leasing_payment_yearly', 15, 2)->nullable()->change();
            $table->decimal('avg_ret_per_month', 15, 2)->nullable()->change();
            $table->decimal('avg_ret_per_year', 15, 2)->nullable()->change();
            $table->decimal('fuel_consumption_per_ret', 15, 2)->nullable()->change();
            $table->decimal('fuel_consumption_per_month', 15, 2)->nullable()->change();
            $table->decimal('fuel_consumption_per_year', 15, 2)->nullable()->change();
            $table->decimal('solar_per_year', 15, 2)->nullable()->change();
            $table->decimal('adblue_consumption_per_day', 15, 2)->nullable()->change();
            $table->decimal('adblue_consumption_per_month', 15, 2)->nullable()->change();
            $table->decimal('adblue_consumption_per_year', 15, 2)->nullable()->change();
            $table->decimal('driver_cost_per_month', 15, 2)->nullable()->change();
            $table->decimal('driver_cost_per_year', 15, 2)->nullable()->change();
            $table->decimal('cost_per_unit', 15, 2)->nullable()->change();
            $table->decimal('idr_per_km', 15, 2)->nullable()->change();
            $table->decimal('idr_per_km_unit', 15, 2)->nullable()->change();
            $table->decimal('cost_days', 15, 2)->nullable()->change();
            $table->decimal('cost_month', 15, 2)->nullable()->change();
            $table->decimal('cost_year', 15, 2)->nullable()->change();
            $table->decimal('telematics_cost_per_month', 15, 2)->nullable()->change();
            $table->decimal('telematics_cost_first_year', 15, 2)->nullable()->change();
            $table->decimal('telematics_cost_subsequent_years', 15, 2)->nullable()->change();
            $table->decimal('total_cost_non_units', 15, 2)->nullable()->change();
            $table->decimal('downtime_cost_estimate', 15, 2)->nullable()->change();
            $table->json('yearly_breakdown')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert cost_model_calculations fields
        Schema::table('cost_model_calculations', function (Blueprint $table) {
            $table->decimal('unit_down_payment', 15, 2)->default(0)->change();
            $table->decimal('financing', 15, 2)->default(0)->change();
            $table->decimal('leasing_payment_yearly', 15, 2)->default(0)->change();
            $table->decimal('avg_ret_per_month', 15, 2)->default(0)->change();
            $table->decimal('avg_ret_per_year', 15, 2)->default(0)->change();
            $table->decimal('fuel_consumption_per_ret', 15, 2)->default(0)->change();
            $table->decimal('fuel_consumption_per_month', 15, 2)->default(0)->change();
            $table->decimal('fuel_consumption_per_year', 15, 2)->default(0)->change();
            $table->decimal('solar_per_year', 15, 2)->default(0)->change();
            $table->decimal('adblue_consumption_per_day', 15, 2)->default(0)->change();
            $table->decimal('adblue_consumption_per_month', 15, 2)->default(0)->change();
            $table->decimal('adblue_consumption_per_year', 15, 2)->default(0)->change();
            $table->decimal('driver_cost_per_month', 15, 2)->default(0)->change();
            $table->decimal('driver_cost_per_year', 15, 2)->default(0)->change();
            $table->decimal('cost_per_unit', 15, 2)->default(0)->change();
            $table->decimal('idr_per_km', 15, 2)->default(0)->change();
            $table->decimal('idr_per_km_unit', 15, 2)->default(0)->change();
            $table->decimal('cost_days', 15, 2)->default(0)->change();
            $table->decimal('cost_month', 15, 2)->default(0)->change();
            $table->decimal('cost_year', 15, 2)->default(0)->change();
            $table->decimal('telematics_cost_per_month', 15, 2)->default(0)->change();
            $table->decimal('telematics_cost_first_year', 15, 2)->default(0)->change();
            $table->decimal('telematics_cost_subsequent_years', 15, 2)->default(0)->change();
            $table->decimal('total_cost_non_units', 15, 2)->default(0)->change();
            $table->decimal('downtime_cost_estimate', 15, 2)->default(0)->change();
            $table->json('yearly_breakdown')->default('{}')->change();
        });
    }
};
