# Blade UI — Permissions Page and In-View Checks

## Permissions Management Page

```blade
{{-- resources/views/admin/permissions.blade.php --}}
@foreach($roles as $role)
<form action="{{ route('admin.permissions.update', $role) }}" method="POST">
    @csrf @method('PUT')

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden mb-6">

        {{-- Role header --}}
        <div class="flex items-center justify-between px-6 py-4 bg-slate-50 border-b border-slate-200">
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 rounded-full text-xs font-bold
                    {{ $role->name === 'admin' ? 'bg-red-100 text-red-700' :
                      ($role->name === 'staff' ? 'bg-blue-100 text-blue-700' :
                       'bg-green-100 text-green-700') }}">
                    {{ $role->display_name }}
                </span>
                <span class="text-sm text-slate-500">
                    {{ $role->users_count ?? 0 }} users
                </span>
            </div>
            @if($role->name !== 'admin')
            <button type="submit"
                class="px-4 py-2 bg-deep-forest text-white text-sm font-semibold rounded-lg hover:bg-emerald-800 transition">
                Save Changes
            </button>
            @else
            <span class="text-xs text-slate-400 italic">Protected — cannot be modified</span>
            @endif
        </div>

        {{-- Permission grid by module --}}
        <div class="divide-y divide-slate-100">
            @foreach($permissions as $module => $perms)
            <div class="px-6 py-4">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">
                    {{ $module }}
                </p>
                <div class="flex flex-wrap gap-3">
                    @foreach($perms as $perm)
                    <label class="flex items-center gap-2 cursor-pointer
                        {{ $role->name === 'admin' ? 'opacity-50 pointer-events-none' : '' }}">
                        <input type="checkbox"
                               name="permissions[]"
                               value="{{ $perm->id }}"
                               {{ $role->permissions->contains($perm->id) ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300 text-sea-green focus:ring-sea-green transition">
                        <span class="text-sm text-slate-700">{{ $perm->display_name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

    </div>
</form>
@endforeach
```

## In-View Permission Checks

### Direct method call
```blade
@if(auth()->user()->hasPermission('residents.delete'))
    <button>Delete Resident</button>
@endif

@if(auth()->user()->hasPermission('reports.export'))
    <a href="/reports/export">Export PDF</a>
@endif
```

### Custom Blade directive (register in `AppServiceProvider::boot()`)

```php
Blade::if('can', function (string $permission) {
    return auth()->user()?->hasPermission($permission);
});
```

```blade
@can('residents.edit')
    <button>Edit</button>
@endcan
```
