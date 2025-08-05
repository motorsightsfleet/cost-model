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
            $table->foreignId('setting_id')->nullable()->after('id')->constrained('cost_model_settings')->onDelete('cascade');
            $table->foreignId('expense_id')->nullable()->after('setting_id')->constrained('cost_model_expenses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cost_model_calculations', function (Blueprint $table) {
            $table->dropForeign(['setting_id']);
            $table->dropForeign(['expense_id']);
            $table->dropColumn(['setting_id', 'expense_id']);
        });
    }
};
