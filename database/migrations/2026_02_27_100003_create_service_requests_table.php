<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->foreignId('resident_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, in-progress, completed, cancelled
            $table->integer('current_step')->default(1);
            $table->text('notes')->nullable();
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('resident_id'); // Fallback index instead of compound with status
            $table->index('request_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
