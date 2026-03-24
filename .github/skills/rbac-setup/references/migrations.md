# Migrations — RBAC Database Schema

Create these four migrations in sequence.

## 1. Roles Table

```php
// database/migrations/xxxx_create_roles_table.php
Schema::create('roles', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique();        // 'admin', 'staff', 'resident'
    $table->string('display_name');          // 'Administrator', 'Staff'
    $table->string('color')->default('gray'); // for UI badges
    $table->timestamps();
});
```

## 2. Permissions Table

```php
// database/migrations/xxxx_create_permissions_table.php
Schema::create('permissions', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique();   // 'residents.view', 'reports.export'
    $table->string('display_name');     // 'View Residents'
    $table->string('module');           // 'Residents', 'Reports', 'Documents'
    $table->string('action');           // 'view', 'create', 'edit', 'delete', 'export'
    $table->text('description')->nullable();
    $table->timestamps();
});
```

## 3. Pivot Table (role_permission)

```php
// database/migrations/xxxx_create_role_permission_table.php
Schema::create('role_permission', function (Blueprint $table) {
    $table->foreignId('role_id')->constrained()->cascadeOnDelete();
    $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
    $table->primary(['role_id', 'permission_id']);
});
```

## 4. Add role_id to Users

```php
// In users migration or a new alter migration
$table->foreignId('role_id')->nullable()->constrained();
```

Run: `php artisan migrate`
