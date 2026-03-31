<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RESIDENTE App')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-[#f8f9fb] antialiased font-sans min-h-screen" x-data="{ mobileNav: false, userMenu: false }">
    @include('partials.loader')

    @php
        $resident = $resident ?? auth()->user();
        $canAccess = $resident->canAccessServices();
        $currentRoute = Route::currentRouteName();

        $navItems = [
            ['route' => 'dashboard', 'match' => ['dashboard'], 'label' => 'Dashboard', 'always' => true,
             'icon' => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z'],
            ['route' => 'services.index', 'match' => ['services.index', 'services.show'], 'label' => 'E-Services', 'always' => false,
             'icon' => 'M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 01-1.125-1.125v-3.75zM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-8.25zM2.25 16.875c0-.621.504-1.125 1.125-1.125h6c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 01-1.125-1.125v-2.25z'],
            ['route' => 'services.my-requests', 'match' => ['services.my-requests', 'service-request.show'], 'label' => 'My Requests', 'always' => false,
             'icon' => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z'],
            ['route' => 'citizen.profile.index', 'match' => ['citizen.profile.index', 'citizen.profile.personal.edit', 'citizen.profile.address.edit', 'citizen.profile.household.edit', 'citizen.profile.members.add'], 'label' => 'My Profile', 'always' => true,
             'icon' => 'M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z'],
        ];

        $quickServices = [
            ['route' => 'services.show', 'param' => 'mayors-clearance', 'label' => 'Clearance',
             'icon' => 'M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z',
             'color' => 'text-emerald-500 bg-emerald-50'],
            ['route' => 'services.index', 'param' => null, 'label' => 'CEDULA',
             'icon' => 'M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z',
             'color' => 'text-blue-500 bg-blue-50'],
            ['route' => 'services.show', 'param' => 'sanitary-permit', 'label' => 'Permit',
             'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
             'color' => 'text-amber-500 bg-amber-50'],
            ['route' => 'services.show', 'param' => 'laboratory-services', 'label' => 'Health Service',
             'icon' => 'M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z',
             'color' => 'text-rose-500 bg-rose-50'],
        ];
    @endphp

    {{-- ====== TOP NAVBAR ====== --}}
    <nav class="bg-white fixed w-full z-50 top-0 start-0 border-b border-gray-100">
        <div class="max-w-screen-2xl h-14 flex items-center justify-between mx-auto px-4 lg:px-6 gap-4">

            {{-- Left: Brand + Desktop Nav --}}
            <div class="flex items-center gap-6 min-w-0 flex-1">

                {{-- Brand --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 flex-shrink-0">
                    <img src="{{ asset('logo_buguey.png') }}" alt="Buguey" class="w-8 h-8 object-contain rounded-full">
                    <div class="hidden sm:block leading-none">
                        <p class="text-[13px] font-black text-deep-forest tracking-tight leading-none">RESIDENTE</p>
                        <p class="text-[9px] text-gray-400 leading-none mt-0.5 font-semibold uppercase tracking-widest">Citizen Portal</p>
                    </div>
                </a>

                {{-- Divider --}}
                <div class="hidden lg:block w-px h-5 bg-gray-200 flex-shrink-0"></div>

                {{-- Desktop Navigation --}}
                <div class="hidden lg:flex items-stretch h-14 gap-0.5">
                    @foreach($navItems as $item)
                        @php $isActive = in_array($currentRoute, $item['match']); @endphp
                        @if($item['always'] || $canAccess)
                            <a href="{{ route($item['route']) }}"
                               class="relative flex items-center gap-2 px-3.5 text-[13px] font-medium transition-colors duration-150 border-b-2
                               {{ $isActive
                                   ? 'text-deep-forest border-sea-green'
                                   : 'text-gray-500 border-transparent hover:text-gray-800 hover:border-gray-200' }}">
                                <svg class="w-4 h-4 flex-shrink-0 {{ $isActive ? 'text-sea-green' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
                                {{ $item['label'] }}
                            </a>
                        @else
                            <div class="relative flex items-center gap-2 px-3.5 text-[13px] font-medium text-gray-300 cursor-not-allowed border-b-2 border-transparent group">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
                                {{ $item['label'] }}
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                <div class="hidden group-hover:block absolute top-full left-1/2 -translate-x-1/2 mt-2 w-40 bg-gray-900 text-white text-[11px] rounded-lg px-3 py-2 shadow-xl z-50 font-medium text-center whitespace-nowrap">
                                    Requires verification
                                    <div class="absolute -top-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-gray-900 rotate-45"></div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Right: Actions + User Menu --}}
            <div class="flex items-center gap-2 flex-shrink-0">

                {{-- Header Actions slot --}}
                <div class="hidden sm:flex items-center gap-2">
                    @yield('header-actions')
                </div>

                {{-- Verification badge --}}
                @if($canAccess)
                    <span class="hidden lg:inline-flex items-center gap-1.5 text-[10px] font-bold bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full border border-emerald-200/80 uppercase tracking-wide">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Verified
                    </span>
                @else
                    <span class="hidden lg:inline-flex items-center gap-1.5 text-[10px] font-bold bg-amber-50 text-amber-600 px-2.5 py-1 rounded-full border border-amber-200/80 uppercase tracking-wide">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        Pending
                    </span>
                @endif

                {{-- Divider --}}
                <div class="hidden sm:block w-px h-5 bg-gray-200"></div>

                {{-- User Dropdown --}}
                <div class="relative" @click.away="userMenu = false">
                    <button @click="userMenu = !userMenu" type="button"
                            class="flex items-center gap-2 pl-1 pr-2.5 py-1.5 rounded-xl hover:bg-gray-50 border border-transparent hover:border-gray-200 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-sea-green/20">
                        <div class="w-7 h-7 rounded-lg bg-deep-forest flex items-center justify-center flex-shrink-0">
                            <span class="text-[10px] font-bold text-white">{{ strtoupper(substr($resident->first_name, 0, 1)) }}{{ strtoupper(substr($resident->last_name, 0, 1)) }}</span>
                        </div>
                        <span class="hidden md:block text-[13px] font-semibold text-gray-700 max-w-[100px] truncate leading-none">{{ $resident->first_name }}</span>
                        <svg class="w-3 h-3 text-gray-400 hidden md:block transition-transform duration-200" :class="{ 'rotate-180': userMenu }" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </button>

                    {{-- Dropdown Panel --}}
                    <div x-show="userMenu"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-64 bg-white border border-gray-200/80 rounded-xl shadow-lg shadow-black/8 z-50 overflow-hidden"
                         style="display: none;">

                        {{-- User Info Header --}}
                        <div class="px-4 py-3.5 border-b border-gray-100 bg-gray-50/50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-deep-forest flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <span class="text-xs font-bold text-white">{{ strtoupper(substr($resident->first_name, 0, 1)) }}{{ strtoupper(substr($resident->last_name, 0, 1)) }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[13px] font-bold text-gray-800 truncate">{{ $resident->first_name }} {{ $resident->last_name }}</p>
                                    <p class="text-[11px] text-gray-400 truncate">{{ $resident->email }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Quick Navigation --}}
                        <div class="p-1.5">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 text-[13px] text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-lg transition font-medium">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75z"/></svg>
                                Dashboard
                            </a>
                            <a href="{{ route('citizen.profile.index') }}" class="flex items-center gap-2.5 px-3 py-2 text-[13px] text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-lg transition font-medium">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                My Profile
                            </a>
                        </div>

                        {{-- Quick Services --}}
                        @if($canAccess)
                        <div class="border-t border-gray-100 p-1.5">
                            <p class="text-[10px] uppercase text-gray-300 font-bold tracking-widest px-3 pt-1.5 pb-1">Quick Services</p>
                            @foreach($quickServices as $qs)
                                <a href="{{ $qs['param'] ? route($qs['route'], $qs['param']) : route($qs['route']) }}"
                                   class="flex items-center gap-2.5 px-3 py-2 text-[13px] text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-lg transition font-medium">
                                    <span class="w-5 h-5 rounded flex items-center justify-center flex-shrink-0 {{ $qs['color'] }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $qs['icon'] }}"/></svg>
                                    </span>
                                    {{ $qs['label'] }}
                                </a>
                            @endforeach
                        </div>
                        @endif

                        {{-- Sign Out --}}
                        <div class="border-t border-gray-100 p-1.5">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-2.5 w-full px-3 py-2 text-[13px] text-red-500 hover:bg-red-50 rounded-lg transition font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Mobile Hamburger --}}
                <button @click="mobileNav = !mobileNav" type="button"
                        class="lg:hidden inline-flex items-center p-2 w-9 h-9 justify-center text-gray-500 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 transition">
                    <svg x-show="!mobileNav" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                    <svg x-show="mobileNav" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Navigation Menu --}}
        <div x-show="mobileNav"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="lg:hidden border-t border-gray-100 bg-white"
             style="display: none;">
            <div class="px-3 py-3 space-y-1">
                @foreach($navItems as $item)
                    @php $isActive = in_array($currentRoute, $item['match']); @endphp
                    @if($item['always'] || $canAccess)
                        <a href="{{ route($item['route']) }}" @click="mobileNav = false"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium transition
                           {{ $isActive
                               ? 'bg-deep-forest text-white shadow-sm'
                               : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-[18px] h-[18px] {{ $isActive ? 'text-white/70' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
                            {{ $item['label'] }}
                        </a>
                    @else
                        <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium text-gray-300 cursor-not-allowed">
                            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
                            <span class="flex-1">{{ $item['label'] }}</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- Mobile Header Actions --}}
            <div class="sm:hidden px-4 pb-3 flex items-center gap-2">
                @yield('header-actions')
            </div>

            {{-- Mobile Quick Services --}}
            @if($canAccess)
            <div class="border-t border-gray-100 px-3 py-3">
                <p class="text-[10px] uppercase text-gray-300 font-bold tracking-widest px-3 mb-1.5">Quick Services</p>
                <div class="grid grid-cols-2 gap-1.5">
                    @foreach($quickServices as $qs)
                        <a href="{{ $qs['param'] ? route($qs['route'], $qs['param']) : route($qs['route']) }}" @click="mobileNav = false"
                           class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-[13px] text-gray-600 hover:bg-gray-50 transition font-medium">
                            <span class="w-6 h-6 rounded-md flex items-center justify-center flex-shrink-0 {{ $qs['color'] }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $qs['icon'] }}"/></svg>
                            </span>
                            {{ $qs['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </nav>

    {{-- Navbar spacer --}}
    <div class="h-14"></div>

    {{-- ====== PAGE HEADER ====== --}}
    <header class="bg-white border-b border-gray-100 sticky top-14 z-30">
        <div class="max-w-screen-2xl mx-auto px-4 lg:px-6 h-12 flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0 flex-1">
                <div class="w-1 h-6 rounded-full bg-sea-green flex-shrink-0"></div>
                <div class="min-w-0">
                    <h1 class="text-[14px] font-bold text-gray-800 leading-tight truncate">@yield('page-title', 'Dashboard')</h1>
                    @hasSection('page-subtitle')
                        <p class="text-[10px] text-gray-400 leading-tight font-medium truncate">@yield('page-subtitle')</p>
                    @endif
                </div>
            </div>
            {{-- Desktop header actions are in navbar; this is a fallback slot --}}
            @hasSection('page-actions')
                <div class="flex items-center gap-2">
                    @yield('page-actions')
                </div>
            @endif
        </div>
    </header>

    {{-- ====== MAIN CONTENT ====== --}}
    <main class="min-h-[calc(100vh-109px)]">
        <div class="max-w-screen-2xl mx-auto">
            @if(session('success'))
                <div class="mx-4 lg:mx-6 mt-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-[13px] font-medium flex items-center gap-2.5" x-data="{ show: true }" x-show="show" x-transition>
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="flex-1">{{ session('success') }}</span>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="mx-4 lg:mx-6 mt-5 p-3.5 bg-red-50 border border-red-200 text-red-700 rounded-xl text-[13px] font-medium flex items-center gap-2.5" x-data="{ show: true }" x-show="show" x-transition>
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    <span class="flex-1">{{ session('error') }}</span>
                    <button @click="show = false" class="text-red-400 hover:text-red-600 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    @hasSection('sidebar-extra')
        @yield('sidebar-extra')
    @endif

    @stack('scripts')
</body>
</html>
