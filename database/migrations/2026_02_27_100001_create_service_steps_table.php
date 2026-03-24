<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->integer('step_number');
            $table->string('step_type'); // 'client' or 'agency'
            $table->text('description');
            $table->integer('processing_time_minutes')->nullable();
            $table->string('responsible_person')->nullable();
            $table->decimal('fee', 10, 2)->default(0.00);
            $table->timestamps();

            $table->index(['service_id', 'step_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_steps');
    }
};
