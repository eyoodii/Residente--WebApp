<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | RESIDENTE</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased font-sans flex h-screen overflow-hidden">
    @include('partials.loader')

    <!-- Sidebar -->
    <aside class="w-64 bg-[#0d2418] text-white flex flex-col shadow-2xl flex-shrink-0">

        <!-- Logo Header -->
        <div class="h-16 flex items-center px-4 border-b border-white/8 flex-shrink-0 bg-white/[0.04]">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-golden-glow to-yellow-500 flex items-center justify-center text-[#0d2418] font-black text-[11px] mr-3 shadow-lg flex-shrink-0 tracking-tight">
                {{ Auth::user()->role === 'SA' ? 'SA' : 'AD' }}
            </div>
            <div class="min-w-0">
                <p class="font-black text-[13px] tracking-widest leading-tight text-white">RESIDENTE</p>
                <p class="text-[10px] leading-tight font-semibold
                    {{ Auth::user()->role === 'SA' ? 'text-golden-glow' : 'text-emerald-400' }}">
                    {{ Auth::user()->role === 'SA' ? 'Super Administrator' : 'Administrator' }}
                </p>
            </div>
        </div>

        <!-- RESIDENTE Acronym Legend -->
        <div class="px-4 pt-3 pb-1">
            <div class="flex items-center justify-between gap-0.5">
                @foreach ([['R','Records'],['E','Evals'],['S','Svc'],['I','Info'],['D','Dir'],['E','Eddie'],['N','Net'],['T','Trust'],['E','E2E']] as [$letter, $tip])
                <div class="flex-1 flex flex-col items-center gap-0.5" title="{{ $tip }}">
                    <span class="w-full text-center text-[11px] font-black text-golden-glow/80 leading-none">{{ $letter }}</span>
                    <span class="w-full h-0.5 rounded-full bg-golden-glow/20"></span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex-1 overflow-y-auto px-3 py-3 space-y-4">

            {{-- OVERVIEW (I) --}}
            <div>
                <p class="text-[9px] uppercase text-white/25 font-bold tracking-[0.18em] mb-1 px-2">Overview</p>
                <nav class="space-y-0.5">
                    @php $active = request()->routeIs('admin.dashboard'); @endphp
                    <a href="{{ route('admin.dashboard') }}"
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                       {{ $active ? 'bg-golden-glow/20 text-white font-semibold shadow-sm' : 'text-white/55 hover:bg-white/8 hover:text-white' }}">
                        <span class="w-5 h-5 rounded-md flex items-center justify-center text-[10px] font-black flex-shrink-0
                            {{ $active ? 'bg-golden-glow/30 text-golden-glow' : 'bg-white/10 text-white/50 group-hover:bg-white/15 group-hover:text-white/90' }}">I</span>
                        <span class="flex-1">Information Hub</span>
                        @if($active)<span class="w-2 h-2 rounded-full bg-golden-glow flex-shrink-0 shadow shadow-yellow-400/50"></span>@endif
                    </a>
                </nav>
            </div>

            {{-- DATA MANAGEMENT (R, D) --}}
            <div>
                <p class="text-[9px] uppercase text-white/25 font-bold tracking-[0.18em] mb-1 px-2">Data Management</p>
                <nav class="space-y-0.5">

                    @php $mcActive = request()->routeIs('admin.master-collections') || request()->routeIs('admin.barangay-overview') || request()->routeIs('admin.validation-flags') || request()->routeIs('admin.data-collection.*') || request()->routeIs('admin.residents.*'); @endphp
                    <div x-data="{ expanded: {{ $mcActive ? 'true' : 'false' }} }">
                        <button @click="expanded = !expanded"
                            class="group flex items-center justify-between w-full px-3 py-2.5 rounded-xl text-sm transition-all duration-150 cursor-pointer
                            {{ $mcActive ? 'bg-golden-glow/20 text-white font-semibold shadow-sm' : 'text-white/55 hover:bg-white/8 hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <span class="w-5 h-5 rounded-md flex items-center justify-center text-[10px] font-black flex-shrink-0
                                    {{ $mcActive ? 'bg-golden-glow/30 text-golden-glow' : 'bg-white/10 text-white/50 group-hover:bg-white/15 group-hover:text-white/90' }}">R</span>
                                <span>Records</span>
                            </div>
                            <span class="text-[9px] opacity-40 transition-transform duration-200 flex-shrink-0" :class="{'rotate-180': expanded}">▼</span>
                        </button>
                        <div x-show="expanded" x-collapse class="mt-0.5 ml-4 pl-3 border-l border-white/10 space-y-0.5 py-0.5">
                            <a href="{{ route('admin.master-collections') }}" class="block py-1.5 px-3 text-xs rounded-lg transition-colors {{ request()->routeIs('admin.master-collections') ? 'text-golden-glow font-semibold' : 'text-white/45 hover:text-white hover:bg-white/5' }}">Overview</a>
                            <a href="{{ route('admin.barangay-overview') }}" class="block py-1.5 px-3 text-xs rounded-lg transition-colors {{ request()->routeIs('admin.barangay-overview') ? 'text-golden-glow font-semibold' : 'text-white/45 hover:text-white hover:bg-white/5' }}">Barangay Overview</a>
                            <a href="{{ route('admin.validation-flags') }}" class="block py-1.5 px-3 text-xs rounded-lg transition-colors {{ request()->routeIs('admin.validation-flags') ? 'text-golden-glow font-semibold' : 'text-white/45 hover:text-white hover:bg-white/5' }}">Validation Flags</a>
                            <a href="{{ route('admin.data-collection.index') }}" class="block py-1.5 px-3 text-xs rounded-lg transition-colors {{ request()->routeIs('admin.data-collection.*') ? 'text-golden-glow font-semibold' : 'text-white/45 hover:text-white hover:bg-white/5' }}">Data Collection</a>
                            <a href="{{ route('admin.residents.index') }}" class="block py-1.5 px-3 text-xs rounded-lg transition-colors {{ request()->routeIs('admin.residents.*') ? 'text-golden-glow font-semibold' : 'text-white/45 hover:text-white hover:bg-white/5' }}">Resident Management</a>
                        </div>
                    </div>

                    @php $active = request()->routeIs('admin.households.*'); @endphp
                    <a href="{{ route('admin.households.index') }}"
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                       {{ $active ? 'bg-golden-glow/20 text-white font-semibold shadow-sm' : 'text-white/55 hover:bg-white/8 hover:text-white' }}">
                        <span class="w-5 h-5 rounded-md flex items-center justify-center text-[10px] font-black flex-shrink-0
                            {{ $active ? 'bg-golden-glow/30 text-golden-glow' : 'bg-white/10 text-white/50 group-hover:bg-white/15 group-hover:text-white/90' }}">D</span>
                        <span class="flex-1">Directory</span>
                        @if($active)<span class="w-2 h-2 rounded-full bg-golden-glow flex-shrink-0 shadow shadow-yellow-400/50"></span>@endif
                    </a>

                </nav>
            </div>

            {{-- OPERATIONS (E, S, N) --}}
            <div>
                <p class="text-[9px] uppercase text-white/25 font-bold tracking-[0.18em] mb-1 px-2">Operations</p>
                <nav class="space-y-0.5">

                    @php $active = request()->routeIs('admin.verification.*'); @endphp
                    <a href="{{ route('admin.verification.dashboard') }}"
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                       {{ $active ? 'bg-golden-glow/20 text-white font-semibold shadow-sm' : 'text-white/55 hover:bg-white/8 hover:text-white' }}">
                        <span class="w-5 h-5 rounded-md flex items-center justify-center text-[10px] font-black flex-shrink-0
                            {{ $active ? 'bg-golden-glow/30 text-golden-glow' : 'bg-white/10 text-white/50 group-hover:bg-white/15 group-hover:text-white/90' }}">E</span>
                        <span class="flex-1">Evaluations</span>
                        @if($active)<span class="w-2 h-2 rounded-full bg-golden-glow flex-shrink-0 shadow shadow-yellow-400/50"></span>@endif
                    </a>

                    @php $active = request()->routeIs('admin.services.*'); @endphp
                    <a href="{{ route('admin.services.index') }}"
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                       {{ $active ? 'bg-golden-glow/20 text-white font-semibold shadow-sm' : 'text-white/55 hover:bg-white/8 hover:text-white' }}">
                        <span class="w-5 h-5 rounded-md flex items-center justify-center text-[10px] font-black flex-shrink-0
                            {{ $active ? 'bg-golden-glow/30 text-golden-glow' : 'bg-white/10 text-white/50 group-hover:bg-white/15 group-hover:text-white/90' }}">S</span>
                        <span class="flex-1">Services</span>
                        @if($active)<span class="w-2 h-2 rounded-full bg-golden-glow flex-shrink-0 shadow shadow-yellow-400/50"></span>@endif
                    </a>

                    @php $active = request()->routeIs('admin.activity-logs.*'); @endphp
                    <a href="{{ route('admin.activity-logs.index') }}"
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                       {{ $active ? 'bg-golden-glow/20 text-white font-semibold shadow-sm' : 'text-white/55 hover:bg-white/8 hover:text-white' }}">
                        <span class="w-5 h-5 rounded-md flex items-center justify-center text-[10px] font-black flex-shrink-0
                            {{ $active ? 'bg-golden-glow/30 text-golden-glow' : 'bg-white/10 text-white/50 group-hover:bg-white/15 group-hover:text-white/90' }}">N</span>
                        <span class="flex-1">Network Logs</span>
                        @if($active)<span class="w-2 h-2 rounded-full bg-golden-glow flex-shrink-0 shadow shadow-yellow-400/50"></span>@endif
                    </a>

                </nav>
            </div>

            {{-- SYSTEM (T, E, E) --}}
            <div>
                <p class="text-[9px] uppercase text-white/25 font-bold tracking-[0.18em] mb-1 px-2">System</p>
                <nav class="space-y-0.5">

                    @if(Auth::user()->isSuperAdmin())
                    @php $active = request()->routeIs('admin.permissions.*'); @endphp
                    <a href="{{ route('admin.permissions.index') }}"
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                       {{ $active ? 'bg-golden-glow/20 text-white font-semibold shadow-sm' : 'text-white/55 hover:bg-white/8 hover:text-white' }}">
                        <span class="w-5 h-5 rounded-md flex items-center justify-center text-[10px] font-black flex-shrink-0
                            {{ $active ? 'bg-golden-glow/30 text-golden-glow' : 'bg-white/10 text-white/50 group-hover:bg-white/15 group-hover:text-white/90' }}">T</span>
                        <span class="flex-1">Trust & Access</span>
                        @if($active)<span class="w-2 h-2 rounded-full bg-golden-glow flex-shrink-0 shadow shadow-yellow-400/50"></span>@endif
                    </a>
                    @endif

                    @php $active = request()->routeIs('admin.chatbot.index') || request()->routeIs('admin.chatbot.create') || request()->routeIs('admin.chatbot.edit') || request()->routeIs('admin.chatbot.handoffs') || request()->routeIs('admin.chatbot.unanswered'); @endphp
                    <a href="{{ route('admin.chatbot.index') }}"
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                       {{ $active ? 'bg-golden-glow/20 text-white font-semibold shadow-sm' : 'text-white/55 hover:bg-white/8 hover:text-white' }}">
                        <span class="w-5 h-5 rounded-md flex items-center justify-center text-[10px] font-black flex-shrink-0
                            {{ $active ? 'bg-golden-glow/30 text-golden-glow' : 'bg-white/10 text-white/50 group-hover:bg-white/15 group-hover:text-white/90' }}">E</span>
                        <span class="flex-1">Eddie AI</span>
                        @if($active)<span class="w-2 h-2 rounded-full bg-golden-glow flex-shrink-0 shadow shadow-yellow-400/50"></span>@endif
                    </a>

                    @if(Auth::user()->isSuperAdmin())
                    @php $active = request()->routeIs('admin.chatbot.api-keys.*'); @endphp
                    <a href="{{ route('admin.chatbot.api-keys.index') }}"
                       class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-150
                       {{ $active ? 'bg-golden-glow/20 text-white font-semibold shadow-sm' : 'text-white/55 hover:bg-white/8 hover:text-white' }}">
                        <span class="w-5 h-5 rounded-md flex items-center justify-center text-[10px] font-black flex-shrink-0
                            {{ $active ? 'bg-golden-glow/30 text-golden-glow' : 'bg-white/10 text-white/50 group-hover:bg-white/15 group-hover:text-white/90' }}">E</span>
                        <span class="flex-1">End-to-End Keys</span>
                        @if($active)<span class="w-2 h-2 rounded-full bg-golden-glow flex-shrink-0 shadow shadow-yellow-400/50"></span>@endif
                    </a>
                    @endif

                </nav>
            </div>

        </div>

        <!-- User Info + Logout -->
        <div class="flex-shrink-0 px-3 py-3 border-t border-white/8 bg-white/[0.03]">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
                    <span class="text-xs font-bold text-white/80">{{ strtoupper(substr(Auth::user()->full_name, 0, 1)) }}</span>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[12px] font-bold text-white truncate leading-tight">{{ Auth::user()->full_name }}</p>
                    <p class="text-[10px] text-white/40 truncate leading-tight">{{ Auth::user()->role === 'SA' ? 'Super Admin' : 'Administrator' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-xs font-medium text-white/50 hover:text-white hover:bg-white/8 transition-all duration-150">
                    <span class="text-sm">🚪</span> Sign Out
                </button>
            </form>
        </div>

    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">

        <!-- Top Bar -->
        <header class="h-14 bg-white border-b border-gray-200 flex items-center justify-between px-7 flex-shrink-0 shadow-sm">
            <div>
                <h1 class="text-lg font-bold text-deep-forest leading-tight">@yield('title', 'Admin Panel')</h1>
                <p class="text-[11px] text-gray-400 leading-tight">@yield('subtitle', 'System Administration')</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <p class="text-[11px] text-gray-400 leading-none">{{ now()->format('F d, Y') }}</p>
                    <p class="text-[11px] font-semibold text-gray-600 leading-none mt-0.5">{{ now()->format('g:i A') }}</p>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="flex-1 overflow-y-auto bg-gray-50/80">
            @if(session('success'))
                <div class="mx-7 mt-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-medium flex items-center gap-2">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mx-7 mt-5 p-3.5 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium flex items-center gap-2">
                    <span>❌</span> {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>

    {{-- Chatbot widget --}}
    @include('components.chatbot-widget')
</body>
</html>
