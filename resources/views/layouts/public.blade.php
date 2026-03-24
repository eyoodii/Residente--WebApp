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
        .dropdown {
            position: relative;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            min-width: 220px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
            margin-top: 0.5rem;
            z-index: 1000;
            overflow: hidden;
        }
        .dropdown:hover .dropdown-menu,
        .dropdown.active .dropdown-menu {
            display: block;
        }
        .dropdown-menu a {
            display: block;
            padding: 0.75rem 1rem;
            color: #034732;
            font-size: 0.875rem;
            transition: all 0.2s;
            border-bottom: 1px solid #f3f4f6;
        }
        .dropdown-menu a:last-child {
            border-bottom: none;
        }
        .dropdown-menu a:hover {
            background: #034732;
            color: #c6c013;
        }
        .dropdown:last-child .dropdown-menu {
            right: 0;
            left: auto;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased font-sans">
    @include('partials.loader')

    <!-- Navigation -->
    <nav class="bg-deep-forest text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-2 border-b border-white border-opacity-10">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('logo_buguey.png') }}" alt="Buguey Logo" class="w-10 h-10 object-contain rounded-full shadow-sm bg-white">
                    <div>
                        <span class="font-bold text-xl tracking-wide block leading-tight">RESIDENTE</span>
                        <span class="text-xs text-golden-glow font-medium tracking-wide uppercase">Municipality of Buguey</span>
                    </div>
                </div>
                <div class="flex flex-col items-end">
                    <div class="text-xs text-gray-300 font-medium">PST</div>
                    <div id="pst-clock" class="text-lg font-bold text-golden-glow leading-tight"></div>
                </div>
            </div>
            <div class="flex justify-center py-2">
                <div class="hidden md:flex space-x-4 items-center flex-wrap justify-center">
                    <a href="{{ url('/') }}" class="hover:text-golden-glow font-medium transition text-sm {{ request()->is('/') ? 'text-golden-glow' : '' }}">Home</a>
                    <a href="{{ route('news-events') }}" class="hover:text-golden-glow font-medium transition text-sm {{ request()->routeIs('news-events') ? 'text-golden-glow' : '' }}">News & Events</a>
                    <a href="{{ route('memos') }}" class="hover:text-golden-glow font-medium transition text-sm {{ request()->routeIs('memos') ? 'text-golden-glow' : '' }}">Memos</a>
                    
                    <div class="dropdown">
                        <a href="#about" class="hover:text-golden-glow font-medium transition text-sm cursor-pointer {{ request()->routeIs('about.*') ? 'text-golden-glow' : '' }}">About ▾</a>
                        <div class="dropdown-menu">
                            <a href="{{ route('about.history') }}">History</a>
                            <a href="{{ route('about.demographic') }}">Demographic Profile</a>
                            <a href="{{ route('about.barangay-list-map') }}">Barangay List Map</a>
                            <a href="{{ route('about.map') }}">Map of Buguey</a>
                            <a href="{{ route('about.barangay-list') }}">List of Barangay</a>
                            <a href="{{ route('about.subdivision-map') }}">Subdivision Map of Buguey</a>

                        </div>
                    </div>
                    
                    <a href="{{ route('public.services') }}" class="hover:text-golden-glow font-medium transition text-sm {{ request()->routeIs('public.services') ? 'text-golden-glow' : '' }}">Services</a>
                    <a href="{{ route('e-bugueyano') }}" class="hover:text-golden-glow font-medium transition text-sm {{ request()->routeIs('e-bugueyano') ? 'text-golden-glow' : '' }}">E-Bugueyano</a>
                    
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-sea-green hover:bg-opacity-90 px-4 py-1.5 rounded-md font-bold transition shadow text-sm">Dashboard</a>
                        @else
                            <div class="dropdown">
                                <a href="#" class="hover:text-golden-glow font-medium transition text-sm cursor-pointer flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                    Account ▾
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
        </div>
    </nav>
    
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
        function updatePSTClock() {
            const now = new Date();
            const pstTime = now.toLocaleString('en-US', { 
                timeZone: 'Asia/Manila',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });
            document.getElementById('pst-clock').textContent = pstTime;
        }
        
        updatePSTClock();
        setInterval(updatePSTClock, 1000);

        // Dropdown click handling
        document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = document.querySelectorAll('.dropdown');
            
            dropdowns.forEach(dropdown => {
                const trigger = dropdown.querySelector('a[href="#"], a[href="#about"]');
                
                if (trigger) {
                    trigger.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Close other dropdowns
                        dropdowns.forEach(d => {
                            if (d !== dropdown) {
                                d.classList.remove('active');
                            }
                        });
                        
                        // Toggle current dropdown
                        dropdown.classList.toggle('active');
                    });
                }
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown')) {
                    dropdowns.forEach(d => d.classList.remove('active'));
                }
            });
            
            // Prevent dropdown from closing when clicking inside menu
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
