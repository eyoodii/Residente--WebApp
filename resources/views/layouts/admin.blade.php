<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | RESIDENTE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased font-sans flex h-screen overflow-hidden">
    @include('partials.loader')

    <!-- Sidebar -->
    <aside class="w-64 bg-deep-forest text-white flex flex-col shadow-xl flex-shrink-0">

        <!-- Logo / Role Header -->
        <div class="h-16 flex items-center px-5 border-b border-white/10 flex-shrink-0">
            <div class="w-9 h-9 bg-golden-glow rounded-full flex items-center justify-center text-deep-forest font-extrabold text-xs mr-3 shadow flex-shrink-0">
                {{ Auth::user()->role === 'SA' ? 'SA' : 'AD' }}
            </div>
            <div>
                <p class="font-bold text-sm tracking-wide leading-tight">ADMIN PANEL</p>
                <p class="text-xs text-golden-glow leading-tight">{{ Auth::user()->role === 'SA' ? 'Super Administrator' : 'Administrator' }}</p>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex-1 overflow-y-auto px-3 py-4">
            <p class="text-[10px] uppercase text-golden-glow/70 font-bold tracking-widest mb-3 px-2">Admin Menu</p>
            <nav class="space-y-0.5">

                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-150
                   {{ request()->routeIs('admin.dashboard') ? 'bg-white/15 text-white font-semibold border-l-2 border-golden-glow pl-[10px]' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">📊</span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.services.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-150
                   {{ request()->routeIs('admin.services.*') ? 'bg-white/15 text-white font-semibold border-l-2 border-golden-glow pl-[10px]' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">⚙️</span>
                    <span>Service Management</span>
                </a>

                <!-- Master Collections Dropdown -->
                <div x-data="{ expanded: {{ request()->routeIs('admin.master-collections') || request()->routeIs('admin.barangay-overview') || request()->routeIs('admin.validation-flags') || request()->routeIs('admin.data-collection.*') || request()->routeIs('admin.residents.*') ? 'true' : 'false' }} }">
                    <button @click="expanded = !expanded"
                        class="flex items-center justify-between w-full px-3 py-2.5 rounded-lg text-sm transition-all duration-150 cursor-pointer
                        {{ request()->routeIs('admin.master-collections') || request()->routeIs('admin.barangay-overview') || request()->routeIs('admin.validation-flags') || request()->routeIs('admin.data-collection.*') || request()->routeIs('admin.residents.*') ? 'bg-white/15 text-white font-semibold border-l-2 border-golden-glow pl-[10px]' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <span class="text-base w-5 text-center flex-shrink-0">📋</span>
                            <span>Master Collections</span>
                        </div>
                        <span class="text-[10px] opacity-50 transition-transform duration-200" :class="{'rotate-180': expanded}">▼</span>
                    </button>
                    <div x-show="expanded" x-collapse class="mt-0.5 ml-4 border-l border-white/10 pl-3 space-y-0.5">
                        <a href="{{ route('admin.master-collections') }}" class="block py-1.5 px-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.master-collections') ? 'text-golden-glow font-semibold' : 'text-gray-400 hover:text-white' }}">Overview</a>
                        <a href="{{ route('admin.barangay-overview') }}" class="block py-1.5 px-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.barangay-overview') ? 'text-golden-glow font-semibold' : 'text-gray-400 hover:text-white' }}">Barangay Overview</a>
                        <a href="{{ route('admin.validation-flags') }}" class="block py-1.5 px-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.validation-flags') ? 'text-golden-glow font-semibold' : 'text-gray-400 hover:text-white' }}">Validation Flags</a>
                        <a href="{{ route('admin.data-collection.index') }}" class="block py-1.5 px-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.data-collection.*') ? 'text-golden-glow font-semibold' : 'text-gray-400 hover:text-white' }}">Data Collection</a>
                        <a href="{{ route('admin.residents.index') }}" class="block py-1.5 px-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.residents.*') ? 'text-golden-glow font-semibold' : 'text-gray-400 hover:text-white' }}">Resident Management</a>
                    </div>
                </div>

                <a href="{{ route('admin.activity-logs.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-150
                   {{ request()->routeIs('admin.activity-logs.*') ? 'bg-white/15 text-white font-semibold border-l-2 border-golden-glow pl-[10px]' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">📝</span>
                    <span>Activity Logs</span>
                </a>

                <a href="{{ route('admin.households.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-150
                   {{ request()->routeIs('admin.households.*') ? 'bg-white/15 text-white font-semibold border-l-2 border-golden-glow pl-[10px]' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">🏠</span>
                    <span>Household Management</span>
                </a>

                <a href="{{ route('admin.verification.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-150
                   {{ request()->routeIs('admin.verification.*') ? 'bg-white/15 text-white font-semibold border-l-2 border-golden-glow pl-[10px]' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">✅</span>
                    <span>Verification Dashboard</span>
                </a>

                @if(Auth::user()->isSuperAdmin())
                <a href="{{ route('admin.permissions.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-150
                   {{ request()->routeIs('admin.permissions.*') ? 'bg-white/15 text-white font-semibold border-l-2 border-golden-glow pl-[10px]' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-base w-5 text-center flex-shrink-0">🛡️</span>
                    <span>Role Permissions</span>
                </a>
                @endif

            </nav>
        </div>

        <!-- User Info + Logout (pinned to bottom) -->
        <div class="flex-shrink-0 px-4 py-4 border-t border-white/10">
            <p class="text-[10px] text-golden-glow/70 leading-none mb-1">Logged in as</p>
            <p class="text-sm font-bold text-white truncate leading-snug">{{ Auth::user()->full_name }}</p>
            <p class="text-xs text-gray-400 truncate mb-3">{{ ucfirst(Auth::user()->role) }}</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-2 text-gray-400 hover:text-white text-xs transition-colors w-full">
                    <span>🚪</span> Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">

        <!-- Top Bar -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 flex-shrink-0 shadow-sm">
            <div>
                <h1 class="text-xl font-bold text-deep-forest leading-tight">@yield('title', 'Admin Panel')</h1>
                <p class="text-xs text-gray-500 mt-0.5">@yield('subtitle', 'System Administration')</p>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-xs text-gray-400">{{ now()->format('F d, Y · g:i A') }}</span>
            </div>
        </header>

        <!-- Content Area -->
        <main class="flex-1 overflow-y-auto bg-gray-50">
            @if(session('success'))
                <div class="mx-8 mt-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-xl text-sm font-medium">
                    ✅ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mx-8 mt-6 p-4 bg-red-100 border border-red-300 text-red-700 rounded-xl text-sm font-medium">
                    ❌ {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>

</body>
</html>
