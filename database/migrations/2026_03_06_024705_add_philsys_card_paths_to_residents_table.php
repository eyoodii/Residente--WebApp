<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            // Store private paths to uploaded PhilSys card images
            $table->string('philsys_id_front')->nullable()->after('philsys_transaction_id');
            $table->string('philsys_id_back')->nullable()->after('philsys_id_front');
        });
    }

    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn(['philsys_id_front', 'philsys_id_back']);
        });
    }
};
