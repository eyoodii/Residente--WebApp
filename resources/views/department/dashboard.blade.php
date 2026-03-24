@extends('layouts.department')

@section('title', 'My Dashboard')
@section('subtitle', $user->department_label ?? 'Department Portal')

@section('content')
<div class="space-y-6">

    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-br from-emerald-600 to-blue-700 text-white rounded-3xl p-8 flex items-center justify-between shadow-xl shadow-emerald-500/20 relative overflow-hidden">
        {{-- Background glow orb --}}
        <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
        <div class="relative z-10">
            <p class="text-emerald-200 text-xs font-bold uppercase tracking-widest mb-2 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                {{ $config['department'] ?? 'LGU Department' }}
            </p>
            <h2 class="text-3xl font-extrabold tracking-tight">Welcome, {{ $user->first_name }}!</h2>
            <p class="text-emerald-100/80 mt-1.5 text-sm font-medium max-w-xl">{{ $config['description'] ?? 'Access your authorized modules from the sidebar.' }}</p>
        </div>
        <div class="text-right hidden md:block relative z-10 flex-shrink-0">
            <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center text-xl font-extrabold border border-white/20 shadow-inner">
                {{ $user->department_role }}
            </div>
            @if($user->isDepartmentReadOnly())
                <span class="mt-2 inline-block px-3 py-1 bg-yellow-400/20 text-yellow-200 text-xs font-bold rounded-full border border-yellow-400/30">
                    👁️ Read-Only
                </span>
            @else
                <span class="mt-2 inline-block px-3 py-1 bg-white/15 text-white text-xs font-bold rounded-full border border-white/20">
                    ✏️ {{ ucfirst($config['access'] ?? 'write') }} Access
                </span>
            @endif
        </div>
    </div>

    {{-- Statistics Cards --}}
    @if(!empty($stats))
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        @isset($stats['total_residents'])
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 flex items-center gap-5 relative overflow-hidden group hover:shadow-2xl transition duration-300">
            <div class="absolute left-0 top-0 w-1.5 h-full bg-blue-500 group-hover:w-2 transition-all rounded-l-3xl"></div>
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-2xl shadow-inner">👤</div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Citizens</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ number_format($stats['total_residents']) }}</p>
            </div>
        </div>
        @endisset

        @isset($stats['verified_residents'])
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 flex items-center gap-5 relative overflow-hidden group hover:shadow-2xl transition duration-300">
            <div class="absolute left-0 top-0 w-1.5 h-full bg-emerald-500 group-hover:w-2 transition-all rounded-l-3xl"></div>
            <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-2xl shadow-inner">✅</div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Verified Residents</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ number_format($stats['verified_residents']) }}</p>
            </div>
        </div>
        @endisset

        @isset($stats['total_households'])
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 flex items-center gap-5 relative overflow-hidden group hover:shadow-2xl transition duration-300">
            <div class="absolute left-0 top-0 w-1.5 h-full bg-purple-500 group-hover:w-2 transition-all rounded-l-3xl"></div>
            <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center text-2xl shadow-inner">🏠</div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Households</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ number_format($stats['total_households']) }}</p>
            </div>
        </div>
        @endisset

        @isset($stats['vulnerable_residents'])
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 flex items-center gap-5 relative overflow-hidden group hover:shadow-2xl transition duration-300">
            <div class="absolute left-0 top-0 w-1.5 h-full bg-red-500 group-hover:w-2 transition-all rounded-l-3xl"></div>
            <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center text-2xl shadow-inner">🛡️</div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Vulnerable Residents</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ number_format($stats['vulnerable_residents']) }}</p>
            </div>
        </div>
        @endisset

        @isset($stats['flood_prone_households'])
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 flex items-center gap-5 relative overflow-hidden group hover:shadow-2xl transition duration-300">
            <div class="absolute left-0 top-0 w-1.5 h-full bg-orange-500 group-hover:w-2 transition-all rounded-l-3xl"></div>
            <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-2xl shadow-inner">🌊</div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Flood-Prone Households</p>
                <p class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ number_format($stats['flood_prone_households']) }}</p>
            </div>
        </div>
        @endisset

    </div>
    @endif

    {{-- Module Quick Access --}}
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-50 rounded-full blur-3xl opacity-60 -mr-20 -mt-20 pointer-events-none"></div>

        <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-white/50 backdrop-blur-sm relative z-10">
            <div>
                <h3 class="text-xl font-extrabold text-slate-900">Your Authorized Modules</h3>
                <p class="text-sm text-slate-500 font-medium mt-1">Quick access to all permitted functions.</p>
            </div>
            <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            </div>
        </div>

        <div class="p-8 relative z-10">
        @php
            $moduleLabels = [
                'executive_dashboard'    => ['icon' => '📊', 'label' => 'Executive Dashboard',      'route' => 'department.analytics.index'],
                'analytics'              => ['icon' => '📈', 'label' => 'Analytics & Reports',       'route' => 'department.analytics.index'],
                'master_collections'     => ['icon' => '🗂️', 'label' => 'Master Collections',       'route' => 'department.master-collections.index'],
                'household_management'   => ['icon' => '🏠', 'label' => 'Household Management',      'route' => 'department.households.index'],
                'activity_logs'          => ['icon' => '📝', 'label' => 'Activity Logs',             'route' => 'department.activity-logs.index'],
                'service_management'     => ['icon' => '⚙️', 'label' => 'Service Requests',         'route' => 'department.service-requests.index'],
                'verification_dashboard' => ['icon' => '✅', 'label' => 'Verification Dashboard',    'route' => 'department.civil-registry.verification'],
                'welfare_targeting'      => ['icon' => '🛡️', 'label' => 'Welfare Targeting',        'route' => 'department.welfare.index'],
                'staff_management'       => ['icon' => '👥', 'label' => 'Staff Management',          'route' => 'department.staff.index'],
                'announcements'          => ['icon' => '📢', 'label' => 'Transparency Board',        'route' => 'department.transparency-board.index'],
                'emergency_alerts'       => ['icon' => '🚨', 'label' => 'Emergency Alerts',          'route' => 'department.emergency.index'],
                'financial_module'       => ['icon' => '💰', 'label' => 'Financial Module',          'route' => 'department.finance.index'],
                'building_permits'       => ['icon' => '🏗️', 'label' => 'Building Permits',         'route' => 'department.building-permits.index'],
                'health_services'        => ['icon' => '🏥', 'label' => 'Health Services',           'route' => 'department.health.index'],
                'civil_registry'         => ['icon' => '📜', 'label' => 'Civil Registry',            'route' => 'department.civil-registry.index'],
                'blotter'                => ['icon' => '🚓', 'label' => 'Blotter / Incident Reports', 'route' => 'department.blotter.index'],
                'transparency_board'     => ['icon' => '📋', 'label' => 'Transparency Board',        'route' => 'department.transparency-board.index'],
                'role_assignment'        => ['icon' => '🔑', 'label' => 'Role Assignment',            'route' => 'department.role-assignment.index'],
                'livelihood_programs'    => ['icon' => '🌾', 'label' => 'Livelihood Programs',        'route' => 'department.livelihood.index'],
                'locational_clearance'   => ['icon' => '📍', 'label' => 'Locational Clearance',       'route' => 'department.locational-clearance.index'],
                'business_permits'       => ['icon' => '🏢', 'label' => 'Business Permits',           'route' => 'department.business-permits.index'],
            ];
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($modules as $module)
                @php $m = $moduleLabels[$module] ?? ['icon' => '🔲', 'label' => ucwords(str_replace('_', ' ', $module)), 'route' => null]; @endphp
                @if($m['route'])
                    <a href="{{ route($m['route']) }}"
                       class="flex items-center gap-3 px-4 py-4 bg-slate-50 hover:bg-gradient-to-br hover:from-emerald-500 hover:to-blue-600 hover:text-white rounded-2xl border border-slate-200 hover:border-transparent transition-all duration-200 group shadow-sm hover:shadow-lg hover:shadow-emerald-500/20 hover:-translate-y-0.5">
                        <span class="text-2xl flex-shrink-0">{{ $m['icon'] }}</span>
                        <span class="text-sm font-bold text-slate-700 group-hover:text-white leading-tight">{{ $m['label'] }}</span>
                    </a>
                @else
                    <div class="flex items-center gap-3 px-4 py-4 bg-slate-50 rounded-2xl border border-slate-200 opacity-50 cursor-default">
                        <span class="text-2xl flex-shrink-0">{{ $m['icon'] }}</span>
                        <div>
                            <span class="text-sm font-bold text-slate-600">{{ $m['label'] }}</span>
                            <p class="text-xs text-slate-400 font-medium">Coming soon</p>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        </div>
    </div>

    {{-- Recent Activity Logs --}}
    @isset($stats['recent_activities'])
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
            <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-slate-900">Recent System Activity</h3>
                <p class="text-xs text-slate-500 font-medium">Latest logged events in your department.</p>
            </div>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($stats['recent_activities'] as $log)
            <div class="px-8 py-4 flex items-start gap-4 hover:bg-slate-50 transition">
                <div class="w-2 h-2 rounded-full bg-emerald-500 mt-2 flex-shrink-0"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-800">{{ $log->description }}</p>
                    <p class="text-xs text-slate-400 mt-0.5 font-medium">{{ $log->created_at->diffForHumans() }} &middot; {{ $log->user_email }}</p>
                </div>
                <span class="px-2.5 py-1 text-xs font-bold rounded-full flex-shrink-0
                    {{ $log->severity === 'critical' ? 'bg-red-50 text-red-700 border border-red-100' : ($log->severity === 'warning' ? 'bg-yellow-50 text-yellow-700 border border-yellow-100' : 'bg-blue-50 text-blue-700 border border-blue-100') }}">
                    {{ ucfirst($log->severity) }}
                </span>
            </div>
            @empty
            <div class="px-8 py-12 flex flex-col items-center justify-center text-center">
                <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center text-2xl mb-3">✨</div>
                <p class="font-bold text-slate-700">No Recent Activity</p>
                <p class="text-slate-400 text-sm mt-1 font-medium">No system events have been logged yet.</p>
            </div>
            @endforelse
        </div>
    </div>
    @endisset

</div>
@endsection
