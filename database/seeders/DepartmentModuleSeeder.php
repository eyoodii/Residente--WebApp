<?php

namespace Database\Seeders;

use App\Models\DepartmentRoleModule;
use Illuminate\Database\Seeder;

class DepartmentModuleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('department_permissions', []) as $code => $config) {
            foreach ($config['modules'] ?? [] as $module) {
                DepartmentRoleModule::firstOrCreate(
                    ['department_role' => $code, 'module' => $module],
                    ['access_level'    => $config['access'] ?? 'read_only']
                );
            }
        }
    }
}
