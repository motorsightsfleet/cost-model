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
        Schema::table('cost_model_calculations', function (Blueprint $table) {
            $table->json('dashboard_data')->nullable()->after('yearly_breakdown');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cost_model_calculations', function (Blueprint $table) {
            $table->dropColumn('dashboard_data');
        });
    }
};
