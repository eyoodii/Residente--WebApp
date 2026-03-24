<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Modules mapped to actions — matches actual admin features
        $modules = [
            'Dashboard'       => ['view'],
            'Residents'       => ['view', 'create', 'edit', 'delete', 'verify', 'promote'],
            'Services'        => ['view', 'create', 'edit', 'delete', 'toggle'],
            'Households'      => ['view', 'create', 'edit', 'delete'],
            'Documents'       => ['view', 'create', 'edit', 'delete', 'approve'],
            'Reports'         => ['view', 'export'],
            'Activity Logs'   => ['view'],
            'Verification'    => ['view', 'edit'],
            'Data Collection' => ['view', 'edit'],
            'ID Scanner'      => ['view', 'create'],
            'Settings'        => ['view', 'edit'],
            'Roles'           => ['view', 'edit'],
        ];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                $slug = str_replace(' ', '-', strtolower($module));
                Permission::firstOrCreate(
                    ['name' => $slug . '.' . $action],
                    [
                        'display_name' => ucfirst($action) . ' ' . $module,
                        'module'       => $module,
                        'action'       => $action,
                    ]
                );
            }
        }

        // --- Roles ---

        // SA — Super Admin (gets everything via hasPermission() bypass, but also attach all)
        $sa = Role::firstOrCreate(
            ['name' => 'SA'],
            ['display_name' => 'Super Administrator', 'color' => 'red']
        );
        $sa->permissions()->sync(Permission::pluck('id'));

        // Admin — full access except Roles management
        $admin = Role::firstOrCreate(
            ['name' => 'admin'],
            ['display_name' => 'Administrator', 'color' => 'orange']
        );
        $admin->permissions()->sync(
            Permission::where('module', '!=', 'Roles')->pluck('id')
        );

        // Citizen — read-only on dashboard, own documents, view residents
        $citizen = Role::firstOrCreate(
            ['name' => 'citizen'],
            ['display_name' => 'Citizen', 'color' => 'green']
        );
        $citizen->permissions()->sync(
            Permission::whereIn('name', [
                'dashboard.view',
                'documents.view',
                'residents.view',
            ])->pluck('id')
        );

        // Visitor — minimal, dashboard only
        $visitor = Role::firstOrCreate(
            ['name' => 'visitor'],
            ['display_name' => 'Visitor', 'color' => 'gray']
        );
        $visitor->permissions()->sync(
            Permission::whereIn('name', [
                'dashboard.view',
            ])->pluck('id')
        );
    }
}
