<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('department'); // e.g., Municipal Health Office, Mayor's Office
            $table->text('description')->nullable();
            $table->string('classification')->default('Simple'); // Simple, Complex
            $table->string('type')->default('G2C'); // G2C, G2B, etc.
            $table->string('who_may_avail')->nullable();
            $table->decimal('fee', 10, 2)->default(0.00);
            $table->string('fee_description')->nullable(); // e.g., "Variable", "₱50-₱150"
            $table->integer('processing_time_minutes')->nullable(); // Total processing time in minutes
            $table->string('icon')->nullable(); // emoji or icon class
            $table->string('color')->default('sea-green'); // Tailwind color for theming
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
