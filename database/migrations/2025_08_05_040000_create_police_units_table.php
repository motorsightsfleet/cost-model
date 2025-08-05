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
        Schema::create('police_units', function (Blueprint $table) {
            $table->id();
            $table->string('police_number')->unique(); // Nomor polisi (B 1234 AB)
            $table->string('unit_name')->nullable(); // Nama unit (opsional)
            $table->string('unit_type')->nullable(); // Jenis unit (opsional)
            $table->text('description')->nullable(); // Deskripsi unit (opsional)
            $table->boolean('is_active')->default(true); // Status aktif unit
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['police_number']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('police_units');
    }
}; 