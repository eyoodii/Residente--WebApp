# Seeder — Modules, Permissions, and Role Assignment

## PermissionSeeder

```php
// database/seeders/PermissionSeeder.php
$modules = [
    'Residents'  => ['view', 'create', 'edit', 'delete'],
    'Documents'  => ['view', 'create', 'edit', 'delete', 'approve'],
    'Reports'    => ['view', 'export'],
    'Barangay'   => ['view', 'edit'],
    'Users'      => ['view', 'create', 'edit', 'delete'],
    'Settings'   => ['view', 'edit'],
    'Roles'      => ['view', 'edit'],
];

foreach ($modules as $module => $actions) {
    foreach ($actions as $action) {
        Permission::create([
            'name'         => strtolower($module) . '.' . $action,
            'display_name' => ucfirst($action) . ' ' . $module,
            'module'       => $module,
            'action'       => $action,
        ]);
    }
}
```

## Seed Roles and Attach Permissions

```php
// Admin — full access
$admin = Role::create(['name' => 'admin', 'display_name' => 'Administrator', 'color' => 'red']);
$admin->permissions()->attach(Permission::all());

// Staff — everything except Roles and Users management
$staff = Role::create(['name' => 'staff', 'display_name' => 'Staff', 'color' => 'blue']);
$staff->permissions()->attach(
    Permission::whereNotIn('module', ['Roles', 'Users'])->get()
);

// Resident — read-only on documents and residents
$resident = Role::create(['name' => 'resident', 'display_name' => 'Resident', 'color' => 'green']);
$resident->permissions()->attach(
    Permission::whereIn('name', ['documents.view', 'residents.view'])->get()
);
```

Run: `php artisan db:seed --class=PermissionSeeder`

## Convention

Permission slug format: `module.action` (e.g. `residents.view`, `reports.export`)
