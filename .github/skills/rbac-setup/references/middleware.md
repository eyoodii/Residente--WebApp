# Middleware — Route-Level Permission Guard

## CheckPermission Middleware

```php
// app/Http/Middleware/CheckPermission.php
class CheckPermission {
    public function handle(Request $request, Closure $next, string $permission) {
        if (!$request->user()?->hasPermission($permission)) {
            abort(403, 'You do not have permission to access this page.');
        }
        return $next($request);
    }
}
```

## Register Alias (Laravel 11)

```php
// bootstrap/app.php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'permission' => CheckPermission::class,
    ]);
})
```

## Usage on Routes

```php
Route::get('/residents', [ResidentController::class, 'index'])
    ->middleware('permission:residents.view');

Route::delete('/residents/{id}', [ResidentController::class, 'destroy'])
    ->middleware('permission:residents.delete');
```

## Pattern

`->middleware('permission:{module}.{action}')` — uses the same slug format from the seeder.
