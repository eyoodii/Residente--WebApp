<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_api_keys', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // e.g. "Mobile App", "Kiosk Terminal"
            $table->string('key', 64)->unique();             // hashed API key
            $table->string('plain_key_prefix', 8);           // first 8 chars for identification
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['key', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_api_keys');
    }
};
