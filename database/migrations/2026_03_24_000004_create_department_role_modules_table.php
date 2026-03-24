<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('department_role_modules', function (Blueprint $table) {
            $table->id();
            $table->string('department_role');           // 'MAYOR', 'TRESR', 'HRMO'
            $table->string('module');                    // 'analytics', 'financial_module'
            $table->enum('access_level', ['read_only', 'write', 'full'])->default('read_only');
            $table->unique(['department_role', 'module']);
            $table->index('department_role');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('department_role_modules');
    }
};
