<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | RESIDENTE App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .line-clamp-2 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }
        .line-clamp-3 {
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
        }

        /* ── Gov Dropdown ────────────────────────── */
        .dropdown { position: relative; }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            background: #fff;
            min-width: 240px;
            box-shadow: 0 12px 32px rgba(0,0,0,0.14);
            border-top: 3px solid #c6c013;
            border-radius: 0 0 0.5rem 0.5rem;
            z-index: 1000;
            overflow: hidden;
        }
        .dropdown:hover .dropdown-menu,
        .dropdown.active .dropdown-menu { display: block; }

        .dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1.1rem;
            color: #034732;
            font-size: 0.82rem;
            font-weight: 500;
            transition: background 0.18s, color 0.18s, padding-left 0.18s;
            border-bottom: 1px solid #f0f0f0;
        }
        .dropdown-menu a::before {
            content: '›';
            color: #c6c013;
            font-size: 1.05rem;
            font-weight: 700;
        }
        .dropdown-menu a:last-child { border-bottom: none; }
        .dropdown-menu a:hover {
            background: #034732;
            color: #c6c013;
            padding-left: 1.4rem;
        }
        .dropdown-menu a:hover::before { color: #c6c013; }

        /* right-align last dropdown */
        .dropdown:last-child .dropdown-menu { right: 0; left: auto; }

        /* ── Mobile menu transition ──────────────── */
        #mobile-menu { animation: slideDown 0.2s ease; }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Active nav underline ────────────────── */
        .nav-gov-link {
            position: relative;
            padding: 1rem 0.9rem;
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            transition: color 0.18s;
            white-space: nowrap;
        }
        .nav-gov-link::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 3px;
            background: #c6c013;
            border-radius: 2px 2px 0 0;
            transform: scaleX(0);
            transition: transform 0.2s;
        }
        .nav-gov-link:hover { color: #c6c013; }
        .nav-gov-link:hover::after,
        .nav-gov-link.active::after { transform: scaleX(1); }
        .nav-gov-link.active { color: #c6c013; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased font-sans">
    @include('partials.loader')

    <!-- ══ Government Header (Sticky) ══════════════════════════════════ -->
    <header class="sticky top-0 z-50 shadow-xl">

        {{-- Top Banner: Republic of the Philippines --}}
        <div class="bg-[#0c2340] text-white text-[11px] py-1.5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center gap-2">
                <div class="flex items-center gap-2 font-semibold tracking-wide">
                    {{-- PH Sun icon --}}
                    <svg class="w-4 h-4 flex-shrink-0 opacity-90" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="12" cy="12" r="4"/>
                        <path d="M12 2v3M12 19v3M4.22 4.22l2.12 2.12M17.66 17.66l2.12 2.12M2 12h3M19 12h3M4.22 19.78l2.12-2.12M17.66 6.34l2.12-2.12"/>
                    </svg>
                    <span class="hidden xs:inline">REPUBLIC OF THE PHILIPPINES</span>
                    <span class="xs:hidden">Rep. of the Philippines</span>
                    <span class="text-white/30 hidden sm:inline">|</span>
                    <span class="hidden sm:inline text-blue-200 font-normal">Official Government Website</span>
                </div>
                <div class="flex items-center gap-2 text-blue-200">
                    <span class="hidden sm:inline" id="pst-date-top"></span>
                    <span class="text-white/30 hidden sm:inline">|</span>
                    <span class="text-white/70 hidden sm:inline">PST</span>
                    <span id="pst-clock" class="font-bold text-white tabular-nums"></span>
                </div>
            </div>
        </div>

        {{-- Main Nav --}}
        <nav class="bg-deep-forest text-white" aria-label="Site navigation">

            {{-- Branding Strip --}}
            <div class="border-b border-white/10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">

                    {{-- Logo + Name --}}
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('logo_buguey.png') }}" alt="Municipality of Buguey Official Seal"
                             class="w-14 h-14 object-contain rounded-full bg-white p-0.5 shadow-md ring-2 ring-golden-glow/30 flex-shrink-0">
                        <div class="flex flex-col">
                            <span class="text-[10px] text-golden-glow font-bold tracking-[0.18em] uppercase leading-none mb-0.5 hidden sm:block">Republic of the Philippines</span>
                            <span class="font-extrabold text-lg sm:text-xl tracking-wide leading-tight uppercase">Municipality of Buguey</span>
                            <span class="text-[11px] text-white/60 tracking-widest uppercase hidden sm:block">Province of Cagayan</span>
                        </div>
                    </div>

                    {{-- Right: Official badge + mobile toggle --}}
                    <div class="flex items-center gap-3">
                        <div class="hidden lg:flex flex-col items-end text-right border-l border-white/10 pl-4">
                            <span class="text-golden-glow text-[11px] font-bold tracking-widest uppercase">Official eGov Portal</span>
                            <span class="text-white/50 text-[10px] tracking-wide">RESIDENTE Digital Platform</span>
                        </div>

                        {{-- Mobile hamburger --}}
                        <button id="mobile-menu-btn"
                                class="md:hidden p-2 rounded hover:bg-white/10 transition focus:outline-none focus:ring-2 focus:ring-golden-glow/50"
                                aria-label="Toggle navigation menu"
                                aria-expanded="false"
                                aria-controls="mobile-menu">
                            <svg id="icon-hamburger" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <svg id="icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Desktop Nav Bar --}}
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="hidden md:flex items-center justify-between">

                    {{-- Links --}}
                    <div class="flex items-center">
                        <a href="{{ url('/') }}"
                           class="nav-gov-link {{ request()->is('/') ? 'active' : 'text-white/85' }}">Home</a>

                        <a href="{{ route('news-events') }}"
                           class="nav-gov-link {{ request()->routeIs('news-events') ? 'active' : 'text-white/85' }}">News &amp; Events</a>

                        <a href="{{ route('memos') }}"
                           class="nav-gov-link {{ request()->routeIs('memos') ? 'active' : 'text-white/85' }}">Memos</a>

                        <div class="dropdown">
                            <a href="#" class="nav-gov-link flex items-center gap-1 {{ request()->routeIs('about.*') ? 'active' : 'text-white/85' }}">
                                About
                                <svg class="w-3 h-3 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </a>
                            <div class="dropdown-menu">
                                <a href="{{ route('about.history') }}">History</a>
                                <a href="{{ route('about.demographic') }}">Demographic Profile</a>
                                <a href="{{ route('about.barangay-list-map') }}">Barangay List Map</a>
                                <a href="{{ route('about.map') }}">Map of Buguey</a>
                                <a href="{{ route('about.barangay-list') }}">List of Barangay</a>
                                <a href="{{ route('about.subdivision-map') }}">Subdivision Map of Buguey</a>
                            </div>
                        </div>

                        <a href="{{ route('public.services') }}"
                           class="nav-gov-link {{ request()->routeIs('public.services') ? 'active' : 'text-white/85' }}">Services</a>

                        <a href="{{ route('e-bugueyano') }}"
                           class="nav-gov-link {{ request()->routeIs('e-bugueyano') ? 'active' : 'text-white/85' }}">E-Bugueyano</a>
                    </div>

                    {{-- Auth CTA --}}
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                               class="flex items-center gap-2 bg-golden-glow text-deep-forest hover:brightness-110 px-4 py-2 rounded font-bold transition shadow-md text-sm uppercase tracking-wide">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Dashboard
                            </a>
                        @else
                            <div class="dropdown">
                                <a href="#"
                                   class="flex items-center gap-2 border border-golden-glow/50 text-golden-glow hover:bg-white/10 px-4 py-2 rounded font-semibold transition text-sm cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    Account
                                    <svg class="w-3 h-3 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </a>
                                <div class="dropdown-menu">
                                    <a href="{{ route('login') }}">Log in</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}">Register</a>
                                    @endif
                                </div>
                            </div>
                        @endauth
                    @endif
                </div>
            </div>

            {{-- Mobile Navigation Menu --}}
            <div id="mobile-menu" class="md:hidden hidden border-t border-white/10">
                <div class="px-4 py-3 space-y-0.5">
                    <a href="{{ url('/') }}"
                       class="flex items-center gap-2 px-3 py-2.5 text-sm font-semibold rounded hover:bg-white/10 hover:text-golden-glow transition {{ request()->is('/') ? 'text-golden-glow bg-white/10' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('news-events') }}"
                       class="flex items-center gap-2 px-3 py-2.5 text-sm font-semibold rounded hover:bg-white/10 hover:text-golden-glow transition {{ request()->routeIs('news-events') ? 'text-golden-glow bg-white/10' : '' }}">
                        News &amp; Events
                    </a>
                    <a href="{{ route('memos') }}"
                       class="flex items-center gap-2 px-3 py-2.5 text-sm font-semibold rounded hover:bg-white/10 hover:text-golden-glow transition {{ request()->routeIs('memos') ? 'text-golden-glow bg-white/10' : '' }}">
                        Memos
                    </a>

                    {{-- About accordion --}}
                    <div class="mobile-accordion">
                        <button class="w-full flex justify-between items-center px-3 py-2.5 text-sm font-semibold rounded hover:bg-white/10 hover:text-golden-glow transition text-left {{ request()->routeIs('about.*') ? 'text-golden-glow bg-white/10' : '' }}">
                            About
                            <svg class="w-4 h-4 accordion-chevron transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="accordion-body hidden pl-3 mt-0.5 mb-1 ml-2 border-l-2 border-golden-glow/30 space-y-0.5">
                            <a href="{{ route('about.history') }}"            class="block px-3 py-2 text-sm text-white/75 hover:text-golden-glow rounded hover:bg-white/5 transition">History</a>
                            <a href="{{ route('about.demographic') }}"        class="block px-3 py-2 text-sm text-white/75 hover:text-golden-glow rounded hover:bg-white/5 transition">Demographic Profile</a>
                            <a href="{{ route('about.barangay-list-map') }}"  class="block px-3 py-2 text-sm text-white/75 hover:text-golden-glow rounded hover:bg-white/5 transition">Barangay List Map</a>
                            <a href="{{ route('about.map') }}"                class="block px-3 py-2 text-sm text-white/75 hover:text-golden-glow rounded hover:bg-white/5 transition">Map of Buguey</a>
                            <a href="{{ route('about.barangay-list') }}"      class="block px-3 py-2 text-sm text-white/75 hover:text-golden-glow rounded hover:bg-white/5 transition">List of Barangay</a>
                            <a href="{{ route('about.subdivision-map') }}"    class="block px-3 py-2 text-sm text-white/75 hover:text-golden-glow rounded hover:bg-white/5 transition">Subdivision Map</a>
                        </div>
                    </div>

                    <a href="{{ route('public.services') }}"
                       class="flex items-center gap-2 px-3 py-2.5 text-sm font-semibold rounded hover:bg-white/10 hover:text-golden-glow transition {{ request()->routeIs('public.services') ? 'text-golden-glow bg-white/10' : '' }}">
                        Services
                    </a>
                    <a href="{{ route('e-bugueyano') }}"
                       class="flex items-center gap-2 px-3 py-2.5 text-sm font-semibold rounded hover:bg-white/10 hover:text-golden-glow transition {{ request()->routeIs('e-bugueyano') ? 'text-golden-glow bg-white/10' : '' }}">
                        E-Bugueyano
                    </a>

                    @if (Route::has('login'))
                        <div class="pt-3 mt-2 border-t border-white/10 space-y-1">
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                   class="flex items-center justify-center gap-2 w-full px-3 py-2.5 text-sm font-bold rounded bg-golden-glow text-deep-forest">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="block px-3 py-2.5 text-sm font-semibold rounded hover:bg-white/10 hover:text-golden-glow transition">
                                    Log in
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                       class="block px-3 py-2.5 text-sm font-bold rounded bg-golden-glow text-deep-forest text-center">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>

        </nav>
    </header>
    
    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-8 md:py-10 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ asset('logo_buguey.png') }}" alt="Buguey Logo" class="w-10 h-10 object-contain rounded-full shadow-sm bg-white">
                    <span class="font-bold text-xl text-white">RESIDENTE</span>
                </div>
                <p class="text-sm">Municipality of Buguey, Cagayan</p>
                <p class="text-sm mt-2">Advancing Public Governance thru ICT Solutions and Applications.</p>
            </div>
            
            <div>
                <h3 class="text-white font-bold mb-4 text-lg">Emergency Hotlines</h3>
                <ul class="space-y-2 text-sm">
                    <li><span class="text-tiger-orange font-bold">PNP:</span> 000-000-0000</li>
                    <li><span class="text-tiger-orange font-bold">BFP:</span> 000-000-0000</li>
                    <li><span class="text-tiger-orange font-bold">Municipal Health:</span> 000-000-0000</li>
                </ul>
            </div>
            
            <div>
                <h3 class="text-white font-bold mb-4 text-lg">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('terms') }}" class="hover:text-golden-glow transition">Terms & Conditions</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-golden-glow transition">Privacy Policy</a></li>
                    <li><a href="{{ route('public.services') }}" class="hover:text-golden-glow transition">Services Directory</a></li>
                </ul>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 border-t border-gray-700 mt-8 pt-8 text-center text-sm">
            &copy; {{ date('Y') }} Municipality of Buguey. All rights reserved.
        </div>
    </footer>

    <script>
        // ── PST Clock & Date ───────────────────────────────────────────
        function updatePSTClock() {
            const now = new Date();
            const opts = { timeZone: 'Asia/Manila' };

            const timeStr = now.toLocaleString('en-US', {
                ...opts, hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true
            });
            const dateStr = now.toLocaleString('en-US', {
                ...opts, weekday: 'short', year: 'numeric', month: 'short', day: 'numeric'
            });

            const clockEl = document.getElementById('pst-clock');
            const dateEl  = document.getElementById('pst-date-top');
            if (clockEl) clockEl.textContent = timeStr;
            if (dateEl)  dateEl.textContent  = dateStr;
        }
        updatePSTClock();
        setInterval(updatePSTClock, 1000);

        document.addEventListener('DOMContentLoaded', function () {

            // ── Mobile Menu Toggle ─────────────────────────────────────
            const mobileBtn  = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            const iconHamburger = document.getElementById('icon-hamburger');
            const iconClose     = document.getElementById('icon-close');

            if (mobileBtn) {
                mobileBtn.addEventListener('click', function () {
                    const isOpen = !mobileMenu.classList.contains('hidden');
                    mobileMenu.classList.toggle('hidden');
                    iconHamburger.classList.toggle('hidden', !isOpen);
                    iconClose.classList.toggle('hidden', isOpen);
                    mobileBtn.setAttribute('aria-expanded', String(!isOpen));
                });
            }

            // ── Mobile Accordion ───────────────────────────────────────
            document.querySelectorAll('.mobile-accordion').forEach(acc => {
                const btn  = acc.querySelector('button');
                const body = acc.querySelector('.accordion-body');
                const chev = acc.querySelector('.accordion-chevron');
                if (btn) {
                    btn.addEventListener('click', function () {
                        const open = !body.classList.contains('hidden');
                        body.classList.toggle('hidden', open);
                        chev.style.transform = open ? '' : 'rotate(180deg)';
                    });
                }
            });

            // ── Desktop Dropdown (click on mobile-breakpoint edge cases) ─
            const dropdowns = document.querySelectorAll('.dropdown');
            dropdowns.forEach(dropdown => {
                const trigger = dropdown.querySelector('a[href="#"], a[href="#about"]');
                if (trigger) {
                    trigger.addEventListener('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        dropdowns.forEach(d => { if (d !== dropdown) d.classList.remove('active'); });
                        dropdown.classList.toggle('active');
                    });
                }
            });

            document.addEventListener('click', function (e) {
                if (!e.target.closest('.dropdown')) {
                    dropdowns.forEach(d => d.classList.remove('active'));
                }
            });

            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.addEventListener('click', e => e.stopPropagation());
            });
        });
    </script>
    
    @stack('scripts')

    @include('components.chatbot-widget')
</body>
</html>
