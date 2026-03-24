# Models — Role, Permission, and User Relationships

## Role Model

```php
// app/Models/Role.php
class Role extends Model {
    protected $fillable = ['name', 'display_name', 'color'];

    public function permissions() {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function users() {
        return $this->hasMany(User::class);
    }
}
```

## Permission Model

```php
// app/Models/Permission.php
class Permission extends Model {
    protected $fillable = ['name', 'display_name', 'module', 'action', 'description'];

    public function roles() {
        return $this->belongsToMany(Role::class, 'role_permission');
    }
}
```

## User Model (add to existing)

```php
public function role() {
    return $this->belongsTo(Role::class);
}

public function hasPermission(string $permission): bool {
    return $this->role?->permissions
        ->pluck('name')
        ->contains($permission) ?? false;
}

public function hasRole(string $role): bool {
    return $this->role?->name === $role;
}
```

## Relationship Diagram

```
User → belongsTo → Role → belongsToMany → Permission
                           ↕ (role_permission pivot)
                   Permission → belongsToMany → Role
```
