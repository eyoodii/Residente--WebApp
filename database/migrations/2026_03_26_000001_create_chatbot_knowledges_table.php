<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_knowledges', function (Blueprint $table) {
            $table->id();
            $table->string('intent_name')->unique();          // e.g. business_permit_new
            $table->string('category');                        // Permits, Health, Civil Registry, etc.
            $table->json('trigger_keywords_en');              // ["business", "permit", "requirements"]
            $table->json('trigger_keywords_fil');             // ["negosyo", "permiso", "kailangan"]
            $table->text('official_response');                 // HTML-safe, admin-written
            $table->string('response_type')->default('text'); // text | guided_form | external_link
            $table->string('linked_form_flow')->nullable();   // e.g. "business_permit_new"
            $table->boolean('is_active')->default(true);
            $table->integer('times_matched')->default(0);
            $table->timestamp('last_verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_knowledges');
    }
};
