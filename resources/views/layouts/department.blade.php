<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Department Portal') | RESIDENTE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 antialiased font-sans flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside class="w-64 bg-gradient-to-b from-slate-900 via-slate-900 to-blue-950 text-white flex flex-col shadow-2xl flex-shrink-0">

        {{-- Header --}}
        <div class="h-16 flex items-center px-5 border-b border-white/10 flex-shrink-0">
            <div class="w-9 h-9 bg-gradient-to-br from-emerald-400 to-blue-500 rounded-xl flex items-center justify-center text-white font-extrabold text-xs mr-3 shadow-lg shadow-emerald-500/30 flex-shrink-0 ring-1 ring-white/20">
                {{ Auth::user()->department_role ?? '??' }}
            </div>
            <div>
                <p class="font-extrabold text-sm tracking-wide leading-tight">DEPT. PORTAL</p>
                <p class="text-xs text-emerald-400 leading-tight truncate max-w-[130px] font-medium">
                    {{ Auth::user()->department_label ?? 'Department Access' }}
                </p>
            </div>
        </div>

        {{-- Navigation --}}
        <div class="flex-1 overflow-y-auto px-3 py-4">
            <p class="text-[10px] uppercase text-emerald-400/80 font-bold tracking-widest mb-3 px-2">My Modules</p>
            <nav class="space-y-0.5">

                {{-- Dashboard is always visible --}}
                <a href="{{ route('department.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                   {{ request()->routeIs('department.dashboard') ? 'bg-white/15 text-white font-bold border-l-2 border-emerald-400 pl-[10px]' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">🏛️</span>
                    <span>My Dashboard</span>
                </a>

                {{-- Dynamically render links based on allowed modules --}}
                @php $modules = auth()->user()->getDepartmentConfig()['modules'] ?? []; @endphp

                @if(in_array('analytics', $modules) || in_array('executive_dashboard', $modules))
                <a href="{{ route('department.analytics.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                   {{ request()->routeIs('department.analytics.*') ? 'bg-white/15 text-white font-bold border-l-2 border-emerald-400 pl-[10px]' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">📈</span>
                    <span>Analytics</span>
                </a>
                @endif

                @if(in_array('master_collections', $modules))
                <a href="{{ route('department.master-collections.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                   {{ request()->routeIs('department.master-collections.*') ? 'bg-white/15 text-white font-bold border-l-2 border-emerald-400 pl-[10px]' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">📊</span>
                    <span>Master Collections</span>
                </a>
                @endif

                @if(in_array('household_management', $modules))
                <a href="{{ route('department.households.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                   {{ request()->routeIs('department.households.*') ? 'bg-white/15 text-white font-bold border-l-2 border-emerald-400 pl-[10px]' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">🏠</span>
                    <span>Household Management</span>
                </a>
                @endif

                @if(in_array('activity_logs', $modules))
                <a href="{{ route('department.activity-logs.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                   {{ request()->routeIs('department.activity-logs.*') ? 'bg-white/15 text-white font-bold border-l-2 border-emerald-400 pl-[10px]' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">📝</span>
                    <span>Activity Logs</span>
                </a>
                @endif

                @if(in_array('financial_module', $modules))
                <a href="{{ route('department.finance.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                   {{ request()->routeIs('department.finance.*') ? 'bg-white/15 text-white font-bold border-l-2 border-emerald-400 pl-[10px]' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">💰</span>
                    <span>Financial Module</span>
                </a>
                @endif

                @if(in_array('service_management', $modules))
                <a href="{{ route('department.service-requests.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                   {{ request()->routeIs('department.service-requests.*') ? 'bg-white/15 text-white font-bold border-l-2 border-emerald-400 pl-[10px]' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">⚙️</span>
                    <span>Service Requests</span>
                </a>
                @endif

                @if(in_array('welfare_targeting', $modules))
                <a href="{{ route('department.welfare.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                   {{ request()->routeIs('department.welfare.*') ? 'bg-white/15 text-white font-bold border-l-2 border-emerald-400 pl-[10px]' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">🛡️</span>
                    <span>Welfare Targeting</span>
                </a>
                @endif

                @if(in_array('health_services', $modules))
                <a href="{{ route('department.health.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                   {{ request()->routeIs('department.health.*') ? 'bg-white/15 text-white font-bold border-l-2 border-emerald-400 pl-[10px]' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">🏥</span>
                    <span>Health Services</span>
                </a>
                @endif

                @if(in_array('emergency_alerts', $modules))
                <a href="{{ route('department.emergency.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                   {{ request()->routeIs('department.emergency.*') ? 'bg-white/15 text-white font-bold border-l-2 border-emerald-400 pl-[10px]' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">🚨</span>
                    <span>Emergency Alerts</span>
                </a>
                @endif

                @if(in_array('locational_clearance', $modules))
                <a href="{{ route('department.locational-clearance.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                   {{ request()->routeIs('department.locational-clearance.*') ? 'bg-white/15 text-white font-bold border-l-2 border-emerald-400 pl-[10px]' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">📍</span>
                    <span>Locational Clearance</span>
                </a>
                @endif

                @if(in_array('building_permits', $modules))
                <a href="{{ route('department.building-permits.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                   {{ request()->routeIs('department.building-permits.*') ? 'bg-white/15 text-white font-bold border-l-2 border-emerald-400 pl-[10px]' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">🏗️</span>
                    <span>Building Permits</span>
                </a>
                @endif

                @if(in_array('business_permits', $modules))
                <a href="{{ route('department.business-permits.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                   {{ request()->routeIs('department.business-permits.*') ? 'bg-white/15 text-white font-bold border-l-2 border-emerald-400 pl-[10px]' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">🏢</span>
                    <span>Business Permits</span>
                </a>
                @endif

                @if(in_array('civil_registry', $modules))
                <a href="{{ route('department.civil-registry.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                   {{ request()->routeIs('department.civil-registry.*') ? 'bg-white/15 text-white font-bold border-l-2 border-emerald-400 pl-[10px]' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">📜</span>
                    <span>Civil Registry</span>
                </a>
                @endif

                @if(in_array('verification_dashboard', $modules))
                <a href="{{ route('department.civil-registry.verification') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                   {{ request()->routeIs('department.civil-registry.verification') ? 'bg-white/15 text-white font-bold border-l-2 border-emerald-400 pl-[10px]' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">✅</span>
                    <span>Verification Dashboard</span>
                </a>
                @endif

                @if(in_array('blotter', $modules))
                <a href="{{ route('department.blotter.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                   {{ request()->routeIs('department.blotter.*') ? 'bg-white/15 text-white font-bold border-l-2 border-emerald-400 pl-[10px]' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">🚓</span>
                    <span>Blotter Records</span>
                </a>
                @endif

                @if(in_array('transparency_board', $modules) || in_array('announcements', $modules))
                <a href="{{ route('department.transparency-board.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                   {{ request()->routeIs('department.transparency-board.*') ? 'bg-white/15 text-white font-bold border-l-2 border-emerald-400 pl-[10px]' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">📢</span>
                    <span>Transparency Board</span>
                </a>
                @endif

                @if(in_array('livelihood_programs', $modules))
                <a href="{{ route('department.livelihood.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                   {{ request()->routeIs('department.livelihood.*') ? 'bg-white/15 text-white font-bold border-l-2 border-emerald-400 pl-[10px]' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">🌾</span>
                    <span>Livelihood Programs</span>
                </a>
                @endif

                @if(in_array('role_assignment', $modules))
                <a href="{{ route('department.role-assignment.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                   {{ request()->routeIs('department.role-assignment.*') ? 'bg-white/15 text-white font-bold border-l-2 border-emerald-400 pl-[10px]' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">🔑</span>
                    <span>Role Assignment</span>
                </a>
                @endif

                @if(in_array('staff_management', $modules))
                <a href="{{ route('department.staff.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                   {{ request()->routeIs('department.staff.*') ? 'bg-white/15 text-white font-bold border-l-2 border-emerald-400 pl-[10px]' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">👥</span>
                    <span>Staff Management</span>
                </a>
                @endif

            </nav>
        </div>

        {{-- Footer --}}
        <div class="flex-shrink-0 px-4 py-4 border-t border-white/10">
            <p class="text-[10px] text-emerald-400/70 font-bold uppercase tracking-widest leading-none mb-1">Logged in as</p>
            <p class="text-sm font-extrabold text-white truncate leading-snug">{{ Auth::user()->full_name }}</p>
            <p class="text-xs text-slate-400 font-medium truncate mb-3">{{ Auth::user()->department_label ?? Auth::user()->role }}</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-2 text-slate-400 hover:text-white text-xs font-bold transition-colors w-full">
                    <span>🚪</span> Sign Out
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">

        {{-- Top Bar --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 flex-shrink-0 shadow-sm">
            <div>
                <h1 class="text-lg font-extrabold text-slate-900 leading-tight">@yield('title', 'Department Dashboard')</h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">@yield('subtitle', Auth::user()->department_label ?? 'Department Portal')</p>
            </div>
            <div class="flex items-center gap-4">
                @if(Auth::user()->isDepartmentReadOnly())
                    <span class="px-3 py-1 bg-yellow-50 text-yellow-700 text-xs font-bold rounded-full border border-yellow-200">
                        👁️ Read-Only Access
                    </span>
                @endif
                <span class="text-xs text-slate-400 font-medium">{{ now()->format('F d, Y · g:i A') }}</span>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto bg-slate-100 p-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-bold flex items-center gap-2 shadow-sm">
                    <span class="text-emerald-500">✅</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl text-sm font-bold flex items-center gap-2 shadow-sm">
                    <span>❌</span> {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>

</body>
</html>
