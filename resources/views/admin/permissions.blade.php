@extends('layouts.admin')

@section('title', 'Permissions Manager')
@section('subtitle', 'Manage feature access for system roles and department offices')

@section('content')
<div class="px-7 py-6" x-data="permissionsManager()">

    {{-- Page header --}}
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-2xl font-bold text-deep-forest">🛡️ Permissions Manager</h2>
            <p class="text-sm text-gray-500 mt-1">Configure what each role and department office can access. Super Admin always has full access.</p>
        </div>
    </div>

    {{-- Top-level view selector --}}
    <div class="flex items-center gap-4 mb-6">
        <p class="text-xs text-gray-400 mr-1">View:</p>
        <div class="flex gap-1.5 bg-white rounded-xl p-1.5 shadow-sm border border-slate-200 w-fit">
            <button type="button"
                @click="view = 'system'"
                :class="view === 'system' ? 'bg-deep-forest text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'"
                class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 cursor-pointer">
                🔑 System Roles
            </button>
            <button type="button"
                @click="view = 'office'"
                :class="view === 'office' ? 'bg-deep-forest text-white shadow-md' : 'text-slate-600 hover:bg-slate-100'"
                class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 cursor-pointer">
                🏛️ Office Roles
            </button>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════
         SYSTEM ROLES SECTION
         ══════════════════════════════════════════════════════════════════ --}}
    <div x-show="view === 'system'" x-cloak>

        {{-- Role selector tabs + Create button --}}
        <div class="flex gap-2 mb-6 bg-white rounded-xl p-1.5 shadow-sm border border-slate-200 w-fit">
            @foreach($roles as $index => $role)
                @php
                    $dotColor = match($role->name) {
                        'SA'      => 'bg-red-500',
                        'admin'   => 'bg-orange-500',
                        'citizen' => 'bg-emerald-500',
                        'visitor' => 'bg-gray-400',
                        default   => 'bg-blue-500',
                    };
                @endphp
                <button type="button"
                    @click="activeRole = {{ $index }}"
                    :class="activeRole === {{ $index }}
                        ? 'bg-deep-forest text-white shadow-md'
                        : 'text-slate-600 hover:bg-slate-100'"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 cursor-pointer">
                    <span class="w-2 h-2 rounded-full {{ $dotColor }}"></span>
                    {{ $role->display_name }}
                    <span class="text-[10px] opacity-70">({{ $role->residents_count }})</span>
                </button>
            @endforeach
            <div class="w-px bg-slate-200 self-stretch mx-1"></div>
            <button type="button"
                @click="showCreateRole = true"
                class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold text-sea-green hover:bg-sea-green/10 transition-all duration-200 cursor-pointer whitespace-nowrap">
                ＋ New Role
            </button>
        </div>

        {{-- Role permission panels --}}
        @foreach($roles as $index => $role)
        <div x-show="activeRole === {{ $index }}" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0">
            @if($role->name === 'SA')
                {{-- SA: Read-only info card --}}
                <div class="bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 rounded-2xl p-8 text-center">
                    <div class="text-4xl mb-3">🔒</div>
                    <h3 class="text-lg font-bold text-red-800">Super Admin — Full Access</h3>
                    <p class="text-sm text-red-600 mt-1 max-w-md mx-auto">
                        This role has unrestricted access to all features and cannot be modified.
                        All permissions are permanently enabled.
                    </p>
                    <div class="flex flex-wrap justify-center gap-2 mt-5">
                        @foreach($permissions as $module => $perms)
                            <span class="px-3 py-1 bg-white/80 border border-red-200 text-red-700 text-xs font-semibold rounded-full">
                                ✓ {{ $module }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @else
                @php $builtInRoles = ['SA', 'admin', 'citizen', 'visitor']; @endphp

                {{-- Role meta header --}}
                <div class="flex items-center justify-between bg-white rounded-xl border border-slate-200 px-5 py-3.5 mb-4 shadow-sm">
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full {{ $dotColor }}"></span>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">{{ $role->display_name }}</h3>
                            <p class="text-[11px] text-slate-400">
                                Role: <code class="font-mono bg-slate-100 px-1 rounded text-[10px]">{{ $role->name }}</code>
                                &nbsp;·&nbsp; {{ $role->residents_count }} user(s)
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button"
                            @click="editRole = {id: {{ $role->id }}, name: '{{ addslashes($role->name) }}', display_name: '{{ addslashes($role->display_name) }}', color: '{{ addslashes($role->color ?? '#3b82f6') }}'}"
                            class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors cursor-pointer">
                            ✏️ Edit
                        </button>
                        @if(!in_array($role->name, $builtInRoles))
                        <form action="{{ route('admin.permissions.destroy', $role) }}" method="POST" class="inline"
                              onsubmit="return confirm('Delete the {{ addslashes($role->display_name) }} role?\nUsers with this role will not be deleted, but will lose their access.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors cursor-pointer">
                                🗑️ Delete
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                {{-- Permissions form --}}
                <form action="{{ route('admin.permissions.update', $role) }}" method="POST"
                      x-data="{ dirty: false }" @change="dirty = true">
                    @csrf
                    @method('PUT')

                    {{-- Sticky save bar --}}
                    <div x-show="dirty" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="sticky top-0 z-10 mb-4 flex items-center justify-between bg-amber-50 border border-amber-300 rounded-xl px-5 py-3 shadow-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-amber-600">⚠️</span>
                            <span class="text-sm font-medium text-amber-800">You have unsaved changes for <strong>{{ $role->display_name }}</strong></span>
                        </div>
                        <button type="submit"
                            class="px-5 py-2 bg-deep-forest text-white text-sm font-bold rounded-lg
                                   hover:bg-sea-green transition-colors duration-150 cursor-pointer shadow-sm">
                            💾 Save Changes
                        </button>
                    </div>

                    {{-- Module cards grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                        @foreach($permissions as $module => $perms)
                            @php
                                $moduleIcons = [
                                    'Dashboard'       => '📊',
                                    'Residents'       => '👥',
                                    'Services'        => '⚙️',
                                    'Households'      => '🏠',
                                    'Documents'       => '📄',
                                    'Reports'         => '📈',
                                    'Activity Logs'   => '📝',
                                    'Verification'    => '✅',
                                    'Data Collection' => '📋',
                                    'ID Scanner'      => '🪪',
                                    'Settings'        => '🔧',
                                    'Roles'           => '🛡️',
                                ];
                                $icon = $moduleIcons[$module] ?? '📦';
                                $enabledCount = $perms->filter(fn($p) => $role->permissions->contains('id', $p->id))->count();
                                $totalCount = $perms->count();
                                $allEnabled = $enabledCount === $totalCount;
                            @endphp
                            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200"
                                 x-data="{ allChecked: {{ $allEnabled ? 'true' : 'false' }} }">

                                {{-- Module header --}}
                                <div class="flex items-center justify-between px-4 py-3 bg-slate-50 border-b border-slate-200">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-lg">{{ $icon }}</span>
                                        <div>
                                            <h4 class="text-sm font-bold text-slate-800">{{ $module }}</h4>
                                            <p class="text-[10px] text-slate-400 font-medium">{{ $enabledCount }}/{{ $totalCount }} enabled</p>
                                        </div>
                                    </div>
                                    {{-- Toggle all --}}
                                    <button type="button"
                                        title="Toggle all {{ $module }} permissions"
                                        @click="
                                            allChecked = !allChecked;
                                            $el.closest('[x-data]').querySelectorAll('input[data-module]').forEach(cb => cb.checked = allChecked);
                                        "
                                        class="relative w-9 h-5 rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-sea-green/40 flex-shrink-0 cursor-pointer"
                                        :class="allChecked ? 'bg-sea-green' : 'bg-gray-300'">
                                        <span class="absolute top-[2px] left-[2px] w-4 h-4 bg-white rounded-full shadow transition-transform duration-200"
                                              :class="allChecked ? 'translate-x-4' : 'translate-x-0'"></span>
                                    </button>
                                </div>

                                {{-- Action items --}}
                                <div class="p-4 space-y-2">
                                    @foreach($perms as $perm)
                                        @php
                                            $isEnabled = $role->permissions->contains('id', $perm->id);
                                            $actionColors = [
                                                'view'    => 'text-blue-600 bg-blue-50',
                                                'create'  => 'text-emerald-600 bg-emerald-50',
                                                'edit'    => 'text-amber-600 bg-amber-50',
                                                'delete'  => 'text-red-600 bg-red-50',
                                                'verify'  => 'text-indigo-600 bg-indigo-50',
                                                'promote' => 'text-purple-600 bg-purple-50',
                                                'toggle'  => 'text-cyan-600 bg-cyan-50',
                                                'approve' => 'text-teal-600 bg-teal-50',
                                                'export'  => 'text-pink-600 bg-pink-50',
                                            ];
                                            $actionClass = $actionColors[$perm->action] ?? 'text-slate-600 bg-slate-50';
                                        @endphp
                                        <label class="flex items-center justify-between py-1.5 px-3 rounded-lg hover:bg-slate-50 cursor-pointer select-none transition-colors group">
                                            <div class="flex items-center gap-2.5">
                                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded {{ $actionClass }}">
                                                    {{ $perm->action }}
                                                </span>
                                                <span class="text-sm text-slate-700 group-hover:text-slate-900">{{ $perm->display_name }}</span>
                                            </div>
                                            <input type="checkbox"
                                                   name="permissions[]"
                                                   value="{{ $perm->id }}"
                                                   data-module="{{ $module }}"
                                                   {{ $isEnabled ? 'checked' : '' }}
                                                   class="h-4 w-4 rounded border-gray-300 text-sea-green
                                                          focus:ring-sea-green transition cursor-pointer">
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Bottom save button --}}
                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                            class="px-6 py-2.5 bg-deep-forest text-white text-sm font-bold rounded-xl
                                   hover:bg-sea-green transition-colors duration-150 cursor-pointer shadow-sm">
                            💾 Save {{ $role->display_name }} Permissions
                        </button>
                    </div>
                </form>
            @endif
        </div>
        @endforeach

        {{-- ── Create Role Modal ── --}}
        <div x-show="showCreateRole" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
             @click.self="showCreateRole = false">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 m-4" @click.stop>
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-800">＋ Create New Role</h3>
                    <button @click="showCreateRole = false" class="text-slate-400 hover:text-slate-600 text-xl cursor-pointer leading-none">✕</button>
                </div>
                <form action="{{ route('admin.permissions.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Role Slug <span class="text-red-400">*</span></label>
                        <input type="text" name="name" required pattern="[a-z0-9_]+"
                               title="Lowercase letters, numbers, and underscores only"
                               placeholder="e.g., moderator"
                               class="w-full px-3 py-2.5 border border-slate-300 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-sea-green/40 focus:border-sea-green">
                        <p class="text-[10px] text-slate-400 mt-1">Lowercase letters, numbers, underscores. Cannot be changed later.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Display Name <span class="text-red-400">*</span></label>
                        <input type="text" name="display_name" required placeholder="e.g., Moderator"
                               class="w-full px-3 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-sea-green/40 focus:border-sea-green">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Color</label>
                        <input type="color" name="color" value="#3b82f6"
                               class="h-9 w-24 rounded-lg border border-slate-300 cursor-pointer p-0.5">
                    </div>
                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" @click="showCreateRole = false"
                            class="px-4 py-2 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-5 py-2 text-sm font-bold text-white bg-deep-forest hover:bg-sea-green rounded-xl transition-colors shadow-sm cursor-pointer">
                            Create Role
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── Edit Role Modal ── --}}
        <div x-show="editRole !== null" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
             @click.self="editRole = null">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 m-4" @click.stop>
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-800">✏️ Edit Role</h3>
                    <button @click="editRole = null" class="text-slate-400 hover:text-slate-600 text-xl cursor-pointer leading-none">✕</button>
                </div>
                <template x-if="editRole">
                    <form :action="'{{ url('admin/permissions') }}/' + editRole.id + '/meta'" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Role Slug</label>
                            <input type="text" :value="editRole.name" disabled
                                   class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm font-mono bg-slate-50 text-slate-400 cursor-not-allowed">
                            <p class="text-[10px] text-slate-400 mt-1">Slug cannot be changed after creation.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Display Name <span class="text-red-400">*</span></label>
                            <input type="text" name="display_name" :value="editRole.display_name" required
                                   class="w-full px-3 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-sea-green/40 focus:border-sea-green">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Color</label>
                            <input type="color" name="color" :value="editRole.color || '#3b82f6'"
                                   class="h-9 w-24 rounded-lg border border-slate-300 cursor-pointer p-0.5">
                        </div>
                        <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                            <button type="button" @click="editRole = null"
                                class="px-4 py-2 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors cursor-pointer">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-5 py-2 text-sm font-bold text-white bg-deep-forest hover:bg-sea-green rounded-xl transition-colors shadow-sm cursor-pointer">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>

    </div>{{-- /system --}}

    {{-- ══════════════════════════════════════════════════════════════════
         OFFICE ROLES SECTION
         ══════════════════════════════════════════════════════════════════ --}}
    <div x-show="view === 'office'" x-cloak>

        <div class="flex gap-5">

            {{-- ── Left sidebar: department group list ── --}}
            <div class="w-72 shrink-0 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden self-start sticky top-4">
                <div class="px-4 py-3 bg-slate-50 border-b border-slate-200">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Department Groups</h3>
                </div>
                <div class="overflow-y-auto max-h-[75vh]">
                    @foreach($deptGroups as $gi => $group)
                        <div x-data="{ open: {{ $gi === 0 ? 'true' : 'false' }} }">
                            {{-- Group header --}}
                            <button type="button"
                                @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-2.5 text-xs font-bold text-slate-600 uppercase tracking-wider bg-slate-50 hover:bg-slate-100 transition-colors border-b border-slate-100 cursor-pointer">
                                <span class="flex items-center gap-1.5">
                                    <span>{{ $group['icon'] ?? '🏢' }}</span>
                                    <span>{{ $group['label'] }}</span>
                                </span>
                                <svg :class="open ? 'rotate-180' : ''" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            {{-- Group roles --}}
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="border-b border-slate-100">
                                @foreach($group['roles'] as $code)
                                    @php $deptInfo = $deptConfig[$code] ?? null; @endphp
                                    @if($deptInfo)
                                        <button type="button"
                                            @click="activeDept = '{{ $code }}'"
                                            :class="activeDept === '{{ $code }}'
                                                ? 'bg-deep-forest/5 border-r-[3px] border-deep-forest text-deep-forest font-semibold'
                                                : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800'"
                                            class="w-full flex items-center gap-2.5 pl-5 pr-3 py-2.5 text-sm transition-all cursor-pointer">
                                            <span class="font-mono text-[10px] font-bold bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded shrink-0">{{ $code }}</span>
                                            <span class="truncate text-left text-xs">{{ $deptInfo['label'] }}</span>
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ── Right panel: per-dept forms ── --}}
            <div class="flex-1 min-w-0">
                @foreach($deptGroups as $group)
                    @foreach($group['roles'] as $code)
                        @php
                            $deptInfo = $deptConfig[$code] ?? null;
                            if (!$deptInfo) { continue; }
                            $enabledModules  = $deptModules[$code]?->pluck('module')->toArray() ?? [];
                            $currentAccess   = $deptModules[$code]?->first()?->access_level ?? ($deptInfo['access'] ?? 'read_only');
                            $enabledFallback = empty($enabledModules) ? ($deptInfo['modules'] ?? []) : $enabledModules;
                        @endphp

                        <div x-show="activeDept === '{{ $code }}'" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0">

                            <form action="{{ route('admin.permissions.department.update', $code) }}" method="POST"
                                  x-data="{ dirty: false }" @change="dirty = true">
                                @csrf
                                @method('PUT')

                                {{-- Dept header card --}}
                                <div class="bg-gradient-to-br from-deep-forest/5 via-white to-sea-green/5 border border-sea-green/20 rounded-2xl p-5 mb-5">
                                    <div class="flex items-start justify-between gap-4 flex-wrap">
                                        <div>
                                            <div class="flex items-center gap-2 mb-0.5">
                                                <span class="font-mono text-xs font-bold bg-deep-forest text-white px-2 py-1 rounded-lg tracking-widest">{{ $code }}</span>
                                                <h3 class="text-lg font-bold text-deep-forest">{{ $deptInfo['label'] }}</h3>
                                            </div>
                                            @if(!empty($deptInfo['department']))
                                                <p class="text-sm text-slate-500">{{ $deptInfo['department'] }}</p>
                                            @endif
                                            @if(!empty($deptInfo['description']))
                                                <p class="text-xs text-slate-400 mt-1 italic">{{ $deptInfo['description'] }}</p>
                                            @endif
                                        </div>

                                        {{-- Access level selector --}}
                                        <div class="flex flex-col gap-1.5">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Access Level</span>
                                            <div class="flex items-center gap-1.5 bg-white border border-slate-200 rounded-xl p-1">
                                                @foreach([
                                                    'read_only' => ['label' => '👁 Read Only', 'classes' => 'text-blue-600 hover:bg-blue-50 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:shadow-sm'],
                                                    'write'     => ['label' => '✏️ Write',     'classes' => 'text-amber-600 hover:bg-amber-50 peer-checked:bg-amber-500 peer-checked:text-white peer-checked:shadow-sm'],
                                                    'full'      => ['label' => '⚡ Full',      'classes' => 'text-emerald-700 hover:bg-emerald-50 peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:shadow-sm'],
                                                ] as $level => $lvlInfo)
                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="access_level" value="{{ $level }}"
                                                               {{ $currentAccess === $level ? 'checked' : '' }}
                                                               class="sr-only peer">
                                                        <span class="block text-xs font-bold px-3 py-1.5 rounded-lg transition-all {{ $lvlInfo['classes'] }}">
                                                            {{ $lvlInfo['label'] }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Sticky unsaved bar --}}
                                <div x-show="dirty" x-cloak
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 -translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     class="sticky top-0 z-10 mb-4 flex items-center justify-between bg-amber-50 border border-amber-300 rounded-xl px-5 py-3 shadow-sm">
                                    <div class="flex items-center gap-2">
                                        <span class="text-amber-600">⚠️</span>
                                        <span class="text-sm font-medium text-amber-800">
                                            Unsaved changes for <strong>{{ $deptInfo['label'] }}</strong>
                                        </span>
                                    </div>
                                    <button type="submit"
                                        class="px-5 py-2 bg-deep-forest text-white text-sm font-bold rounded-lg
                                               hover:bg-sea-green transition-colors cursor-pointer shadow-sm">
                                        💾 Save Changes
                                    </button>
                                </div>

                                {{-- Module grid --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
                                    @foreach($allModules as $moduleKey => $moduleInfo)
                                        @php $isEnabled = in_array($moduleKey, $enabledFallback); @endphp
                                        <label class="relative flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer select-none transition-all duration-150 group
                                                       {{ $isEnabled ? 'border-sea-green/40 bg-sea-green/5' : 'border-slate-200 bg-white hover:border-slate-300' }}">
                                            <input type="checkbox"
                                                   name="modules[]"
                                                   value="{{ $moduleKey }}"
                                                   {{ $isEnabled ? 'checked' : '' }}
                                                   class="h-4 w-4 rounded border-gray-300 text-sea-green focus:ring-sea-green transition cursor-pointer shrink-0">
                                            <span class="text-xl">{{ $moduleInfo['icon'] }}</span>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-slate-800 truncate">{{ $moduleInfo['label'] }}</p>
                                                <p class="text-[10px] font-mono text-slate-400 truncate">{{ $moduleKey }}</p>
                                            </div>
                                            @if($isEnabled)
                                                <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-sea-green"></span>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>

                                {{-- Select all / None shortcuts --}}
                                <div class="mt-4 flex items-center gap-3">
                                    <button type="button"
                                        onclick="this.closest('form').querySelectorAll('input[name=\'modules[]\']').forEach(cb => cb.checked = true)"
                                        class="text-xs font-semibold text-sea-green hover:text-deep-forest underline underline-offset-2 cursor-pointer transition-colors">
                                        ✓ Select All Modules
                                    </button>
                                    <span class="text-slate-300">|</span>
                                    <button type="button"
                                        onclick="this.closest('form').querySelectorAll('input[name=\'modules[]\']').forEach(cb => cb.checked = false)"
                                        class="text-xs font-semibold text-slate-400 hover:text-red-500 underline underline-offset-2 cursor-pointer transition-colors">
                                        ✗ Deselect All
                                    </button>
                                    <span class="ml-auto text-xs text-slate-400">
                                        {{ count($enabledFallback) }} / {{ count($allModules) }} modules enabled
                                    </span>
                                </div>

                                {{-- Bottom save --}}
                                <div class="mt-5 flex justify-end">
                                    <button type="submit"
                                        class="px-6 py-2.5 bg-deep-forest text-white text-sm font-bold rounded-xl
                                               hover:bg-sea-green transition-colors cursor-pointer shadow-sm">
                                        💾 Save {{ $deptInfo['label'] }} Access
                                    </button>
                                </div>
                            </form>
                        </div>

                    @endforeach
                @endforeach
            </div>{{-- /right panel --}}

        </div>{{-- /flex --}}
    </div>{{-- /office --}}

    {{-- Flash messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show"
             x-init="setTimeout(() => show = false, 4000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2"
             class="fixed bottom-5 right-5 z-50 flex items-center gap-3 bg-white border border-emerald-200 text-emerald-800
                    px-5 py-3.5 rounded-xl shadow-lg text-sm font-medium">
            <span class="text-emerald-500 text-lg">✅</span>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show"
             x-init="setTimeout(() => show = false, 5000)"
             class="fixed bottom-5 right-5 z-50 flex items-center gap-3 bg-white border border-red-200 text-red-800
                    px-5 py-3.5 rounded-xl shadow-lg text-sm font-medium">
            <span class="text-red-500 text-lg">❌</span>
            {{ session('error') }}
        </div>
    @endif

</div>

<script>
function permissionsManager() {
    return {
        view: 'system',
        activeRole: {{ $roles->search(fn($r) => $r->name !== 'SA') ?: 0 }},
        activeDept: '{{ array_key_first(config('department_permissions', ['MAYOR' => []])) }}',
        showCreateRole: false,
        editRole: null,
    }
}
</script>
@endsection
