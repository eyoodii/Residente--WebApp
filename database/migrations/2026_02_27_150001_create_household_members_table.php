<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Manages socio-economic household data for analytics and targeted assistance
     */
    public function up(): void
    {
        Schema::create('household_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained()->onDelete('cascade'); // Head of household
            
            // Household Member Information
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->enum('relationship', [
                'Spouse',
                'Son',
                'Daughter',
                'Father',
                'Mother',
                'Brother',
                'Sister',
                'Grandchild',
                'Other Relative'
            ]);
            
            // Socio-Economic Data
            $table->enum('civil_status', ['Single', 'Married', 'Widowed', 'Legally Separated'])->default('Single');
            $table->string('occupation')->nullable();
            $table->decimal('monthly_income', 10, 2)->nullable();
            $table->enum('educational_attainment', [
                'No Formal Education',
                'Elementary Undergraduate',
                'Elementary Graduate',
                'High School Undergraduate',
                'High School Graduate',
                'College Undergraduate',
                'College Graduate',
                'Vocational',
                'Post Graduate'
            ])->nullable();
            
            // Health & Assistance Programs
            $table->boolean('is_pwd')->default(false);
            $table->boolean('is_senior_citizen')->default(false);
            $table->boolean('is_solo_parent')->default(false);
            $table->boolean('is_indigenous_people')->default(false);
            $table->boolean('is_4ps_beneficiary')->default(false); // Pantawid Pamilyang Pilipino Program
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('resident_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('household_members');
    }
};
