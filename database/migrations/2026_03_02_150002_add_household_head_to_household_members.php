<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Updates household_members to link to household_heads instead of residents
     * HHM (Household Member) - Tied to HHN via surname recognition
     */
    public function up(): void
    {
        Schema::table('household_members', function (Blueprint $table) {
            // Add link to household head (the family unit they belong to)
            $table->foreignId('household_head_id')
                ->nullable()
                ->after('resident_id')
                ->constrained('household_heads')
                ->onDelete('cascade');
            
            // Member number within the family
            // Format: HHM-NNN (e.g., HHM-001, HHM-002)
            $table->string('member_number')->nullable()->after('household_head_id');
            
            // Flag for auto-linked members (via surname recognition)
            $table->boolean('is_auto_linked')->default(false)->after('member_number');
            
            // Link to resident account if member has registered
            $table->foreignId('linked_resident_id')
                ->nullable()
                ->after('is_auto_linked')
                ->constrained('residents')
                ->onDelete('set null');
            
            // Surname matching status
            $table->enum('link_status', [
                'auto_linked',      // Automatically matched via surname
                'confirmed',        // User confirmed the link
                'manual',           // Manually added by admin/secretary
                'pending_review'    // Needs verification
            ])->default('manual')->after('linked_resident_id');
            
            $table->index(['household_head_id', 'last_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('household_members', function (Blueprint $table) {
            $table->dropForeign(['household_head_id']);
            $table->dropForeign(['linked_resident_id']);
            $table->dropColumn([
                'household_head_id',
                'member_number',
                'is_auto_linked',
                'linked_resident_id',
                'link_status'
            ]);
        });
    }
};
