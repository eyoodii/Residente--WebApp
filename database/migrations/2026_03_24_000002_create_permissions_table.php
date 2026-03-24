<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();   // 'residents.view', 'reports.export'
            $table->string('display_name');      // 'View Residents'
            $table->string('module');            // 'Residents', 'Reports', 'Documents'
            $table->string('action');            // 'view', 'create', 'edit', 'delete', 'export'
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
