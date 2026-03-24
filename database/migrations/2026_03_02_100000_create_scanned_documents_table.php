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
        Schema::create('scanned_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->nullable()->constrained('residents')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('document_type')->default('id_card'); // id_card, passport, driver_license, etc.
            $table->string('document_path'); // Path to uploaded image
            $table->text('raw_text')->nullable(); // Raw OCR text
            $table->json('extracted_fields')->nullable(); // Structured data
            $table->decimal('confidence_score', 3, 2)->nullable(); // OCR confidence (0.00 to 1.00)
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index('resident_id');
            $table->index('user_id');
            $table->index('verification_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scanned_documents');
    }
};
