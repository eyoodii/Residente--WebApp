@extends('layouts.admin')

@section('title', 'Role Permissions')
@section('subtitle', 'Manage feature access for each role')

@section('content')
<div class="p-8 space-y-6">

    {{-- Page header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-deep-forest">Permissions Manager</h2>
            <p class="text-sm text-gray-500 mt-1">Enable or disable features for each role. Super Admin always has full access.</p>
        </div>
    </div>

    {{-- Role cards --}}
    @foreach($roles as $role)
    <form action="{{ route('admin.permissions.update', $role) }}" method="POST"
          x-data="{ dirty: false }" @change="dirty = true">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">

            {{-- Role header bar --}}
            <div class="flex items-center justify-between px-6 py-4 bg-slate-50 border-b border-slate-200">
                <div class="flex items-center gap-3">
                    {{-- Role badge --}}
                    @php
                        $badgeClasses = match($role->name) {
                            'SA'      => 'bg-red-100 text-red-700',
                            'admin'   => 'bg-orange-100 text-orange-700',
                            'citizen' => 'bg-green-100 text-green-700',
                            'visitor' => 'bg-gray-100 text-gray-600',
                            default   => 'bg-blue-100 text-blue-700',
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badgeClasses }}">
                        {{ $role->display_name }}
                    </span>
                    <span class="text-sm text-slate-500">
                        {{ $role->residents_count }} {{ Str::plural('user', $role->residents_count) }}
                    </span>
                </div>

                <div class="flex items-center gap-3">
                    @if($role->name === 'SA')
                        <span class="text-xs text-slate-400 italic">🔒 Protected — full access always</span>
                    @else
                        <span x-show="dirty" x-cloak class="text-xs text-amber-600 font-medium">Unsaved changes</span>
                        <button type="submit"
                            class="px-4 py-2 bg-deep-forest text-white text-sm font-semibold rounded-lg
                                   hover:bg-sea-green transition-colors duration-150 cursor-pointer">
                            Save Changes
                        </button>
                    @endif
                </div>
            </div>

            {{-- Permission grid grouped by module --}}
            <div class="divide-y divide-slate-100">
                @foreach($permissions as $module => $perms)
                <div class="px-6 py-4" x-data="{ open: true }">
                    {{-- Module header --}}
                    <button type="button" @click="open = !open"
                        class="flex items-center gap-2 mb-3 cursor-pointer group">
                        <span class="text-[10px] text-slate-400 transition-transform duration-200"
                              :class="{ 'rotate-180': !open }">▼</span>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest group-hover:text-slate-600 transition-colors">
                            {{ $module }}
                        </p>
                        <span class="text-[10px] text-slate-300 ml-1">
                            ({{ $perms->count() }})
                        </span>
                    </button>

                    {{-- Action checkboxes --}}
                    <div x-show="open" x-collapse class="flex flex-wrap gap-x-6 gap-y-2">
                        @foreach($perms as $perm)
                        <label class="flex items-center gap-2 cursor-pointer select-none
                            {{ $role->name === 'SA' ? 'opacity-50 pointer-events-none' : '' }}">
                            <input type="checkbox"
                                   name="permissions[]"
                                   value="{{ $perm->id }}"
                                   {{ $role->permissions->contains('id', $perm->id) ? 'checked' : '' }}
                                   {{ $role->name === 'SA' ? 'disabled' : '' }}
                                   class="h-4 w-4 rounded border-gray-300 text-sea-green
                                          focus:ring-sea-green transition">
                            <span class="text-sm text-slate-700">{{ ucfirst($perm->action) }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </form>
    @endforeach

</div>
@endsection
