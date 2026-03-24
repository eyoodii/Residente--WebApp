<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('household_heads', function (Blueprint $table) {
            $table->string('head_extension_name', 20)->nullable()->after('head_last_name');
        });
    }

    public function down(): void
    {
        Schema::table('household_heads', function (Blueprint $table) {
            $table->dropColumn('head_extension_name');
        });
    }
};
