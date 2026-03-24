<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Custom notification system for real-time alerts to residents
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained()->onDelete('cascade');
            
            // Notification Content
            $table->string('title');
            $table->text('message');
            $table->enum('type', [
                'service_update',
                'announcement',
                'document_ready',
                'payment_required',
                'account_update',
                'system_alert'
            ])->default('announcement');
            
            // Related Entity (optional)
            $table->string('related_entity_type')->nullable(); // 'ServiceRequest', 'Announcement', etc.
            $table->unsignedBigInteger('related_entity_id')->nullable();
            
            // Action & Navigation
            $table->string('action_url')->nullable(); // Deep link to relevant page
            $table->string('action_label')->nullable(); // Button text like "View Request"
            
            // Status & Priority
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            
            // Delivery Status
            $table->boolean('email_sent')->default(false);
            $table->timestamp('email_sent_at')->nullable();
            
            $table->timestamps();
            
            $table->index(['resident_id', 'is_read']);
            $table->index('created_at');
            $table->index(['related_entity_type', 'related_entity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
