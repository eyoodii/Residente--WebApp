<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Implements comprehensive audit trail for security and transparency
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            
            // Who performed the action
            $table->foreignId('resident_id')->nullable()->constrained()->onDelete('set null');
            $table->string('user_email')->nullable(); // Preserved even if user deleted
            $table->string('user_role')->nullable();
            
            // What was the action
            $table->string('action'); // 'login', 'logout', 'create', 'update', 'delete', 'request_service', etc.
            $table->string('entity_type')->nullable(); // Model name: 'ServiceRequest', 'Resident', etc.
            $table->unsignedBigInteger('entity_id')->nullable(); // ID of the affected record
            $table->text('description'); // Human-readable description
            
            // Details & Context
            $table->json('old_values')->nullable(); // Before state
            $table->json('new_values')->nullable(); // After state
            $table->json('metadata')->nullable(); // Additional context (e.g., browser, device)
            
            // Request Information
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('request_url')->nullable();
            $table->string('request_method')->nullable(); // GET, POST, PUT, DELETE
            
            // Status & Classification
            $table->enum('severity', ['info', 'warning', 'critical'])->default('info');
            $table->boolean('is_suspicious')->default(false); // Flag for security monitoring
            
            $table->timestamps();
            
            // Indexes for efficient querying
            $table->index('resident_id');
            $table->index('action');
            $table->index(['entity_type', 'entity_id']);
            $table->index('created_at');
            $table->index('is_suspicious');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
