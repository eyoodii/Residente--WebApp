<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'RESIDENTE APP') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-800 selection:bg-emerald-500 selection:text-white flex flex-col min-h-screen">
        @include('partials.loader')

        <nav class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-50">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-600 via-emerald-500 to-emerald-400"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('logo_buguey.png') }}" alt="Buguey Logo" class="w-12 h-12 object-contain rounded-2xl shadow-lg ring-2 ring-white transform transition hover:scale-105 bg-white">
                        <div>
                            <span class="font-extrabold text-2xl tracking-tight text-slate-900 block leading-none">RESIDENTE APP</span>
                            <span class="text-xs font-bold text-emerald-600 tracking-widest uppercase">Municipality of Buguey</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-6">
                        <div class="hidden md:flex items-center gap-2 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-100">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-xs font-bold text-emerald-700">Connection Secured</span>
                        </div>
                        @include('layouts.navigation')
                    </div>
                </div>
            </div>
        </nav>

        @if (isset($header) || View::hasSection('header'))
            <header class="bg-white border-b border-slate-100 relative overflow-hidden shadow-sm">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 relative z-10">
                    @isset($header)
                        {{ $header }}
                    @else
                        @yield('header')
                    @endisset
                </div>
            </header>
        @endif

        <main class="flex-grow w-full max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 focus:outline-none" tabindex="-1">
            @yield('content')
            @isset($slot)
                {{ $slot }}
            @endisset
        </main>

        <footer class="bg-white border-t border-slate-200 mt-auto flex-shrink-0">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-slate-500 font-medium">
                <p>&copy; {{ date('Y') }} Municipality of Buguey. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="hover:text-emerald-600 transition">Privacy Policy</a>
                    <a href="#" class="hover:text-emerald-600 transition">Terms of Service</a>
                </div>
            </div>
        </footer>

        <x-toast />

        @stack('scripts')
    </body>
</html>
