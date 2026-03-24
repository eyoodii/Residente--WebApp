# Controller & Routes — Permissions Management Page

## RolePermissionController

```php
// app/Http/Controllers/Admin/RolePermissionController.php
class RolePermissionController extends Controller
{
    public function index() {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy('module');
        return view('admin.permissions', compact('roles', 'permissions'));
    }

    public function update(Request $request, Role $role) {
        // Prevent editing admin role permissions
        if ($role->name === 'admin') {
            return back()->with('error', 'Admin permissions cannot be modified.');
        }

        $role->permissions()->sync($request->input('permissions', []));

        return back()->with('success', "Permissions for {$role->display_name} updated.");
    }
}
```

## Routes

```php
Route::middleware(['auth', 'permission:roles.edit'])->group(function () {
    Route::get('/admin/permissions', [RolePermissionController::class, 'index'])
         ->name('admin.permissions');
    Route::put('/admin/permissions/{role}', [RolePermissionController::class, 'update'])
         ->name('admin.permissions.update');
});
```

## Key Behaviors

- Admin role is **protected** — controller rejects updates to it
- Route itself is guarded by `permission:roles.edit`
- `permissions()->sync()` replaces all existing permissions with the submitted set
