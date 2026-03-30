<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_handoffs', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('conversation_transcript');          // full chat so staff have context
            $table->text('citizen_concern');                  // what triggered the handoff
            $table->string('assigned_to')->nullable();        // department or staff
            $table->string('status')->default('pending');   // pending | in_progress | resolved
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_handoffs');
    }
};
