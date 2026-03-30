<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();         // Laravel session ID
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('current_flow')->nullable();     // e.g. "business_permit_new"
            $table->integer('current_step')->default(0);
            $table->json('collected_data')->nullable();     // answers gathered so far
            $table->string('status')->default('active');  // active | completed | handed_off
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_sessions');
    }
};
