<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESIDENTE App | Municipality of Buguey</title>
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

        /* ── Page load entrance animations ── */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(32px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.88); }
            to   { opacity: 1; transform: scale(1); }
        }
        .anim-nav        { animation: fadeInDown 0.6s ease both; }
        .anim-hero-title { animation: fadeInUp  0.7s ease 0.2s both; }
        .anim-hero-desc  { animation: fadeInUp  0.7s ease 0.4s both; }
        .anim-hero-btns  { animation: fadeInUp  0.7s ease 0.6s both; }
        .anim-fade-in    { animation: fadeIn    0.8s ease 0.1s both; }
        .anim-scale-in   { animation: scaleIn   0.7s ease 0.3s both; }

        /* ── Auto-scroll reveal animations ── */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-left {
            opacity: 0;
            transform: translateX(-40px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal-left.visible {
            opacity: 1;
            transform: translateX(0);
        }
        .reveal-right {
            opacity: 0;
            transform: translateX(40px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal-right.visible {
            opacity: 1;
            transform: translateX(0);
        }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .reveal-delay-4 { transition-delay: 0.4s; }
        .reveal-delay-5 { transition-delay: 0.5s; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased font-sans">
    @include('partials.loader')

    <nav class="bg-deep-forest text-white shadow-lg sticky top-0 z-50 anim-nav">
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
                    <a href="{{ url('/') }}" class="hover:text-golden-glow font-medium transition text-sm">Home</a>
                    <a href="{{ route('news-events') }}" class="hover:text-golden-glow font-medium transition text-sm">News & Events</a>
                    <a href="{{ route('memos') }}" class="hover:text-golden-glow font-medium transition text-sm">Memos</a>
                    
                    <div class="dropdown">
                        <a href="#about" class="hover:text-golden-glow font-medium transition text-sm cursor-pointer">About ▾</a>
                        <div class="dropdown-menu">
                            <a href="{{ route('about.history') }}">History</a>
                            <a href="{{ route('about.demographic') }}">Demographic Profile</a>
                            <a href="{{ route('about.barangay-list-map') }}">Barangay List Map</a>
                            <a href="{{ route('about.map') }}">Map of Buguey</a>
                            <a href="{{ route('about.barangay-list') }}">List of Barangay</a>
                            <a href="{{ route('about.subdivision-map') }}">Subdivision Map of Buguey</a>
                        </div>
                    </div>
                    
                    <a href="{{ route('public.services') }}" class="hover:text-golden-glow font-medium transition text-sm">Services</a>
                    <a href="{{ route('e-bugueyano') }}" class="hover:text-golden-glow font-medium transition text-sm">E-Bugueyano</a>
                    
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

    <!-- Hero Section -->
    <div class="relative overflow-hidden min-h-screen flex items-center">
        <!-- Background Video -->
        <video
            autoplay
            loop
            muted
            playsinline
            class="absolute inset-0 w-full h-full object-cover"
        >
            <source src="{{ asset('bugueyvideo.mp4') }}" type="video/mp4">
        </video>

        <!-- Dark overlay so text stays readable -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/50 to-black/70"></div>

        <!-- Decorative blur blob – kept behind overlay -->
        <div class="absolute inset-y-0 left-0 w-1/2 bg-emerald-900 rounded-r-full opacity-20 blur-3xl pointer-events-none"></div>

        <!-- Content -->
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="mt-3 text-5xl sm:text-6xl lg:text-7xl font-extrabold text-white tracking-tight leading-tight drop-shadow-lg anim-hero-title">
                    The Municipality of <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">Buguey</span>
                </h1>
                <p class="mt-6 text-xl text-slate-200 font-medium leading-relaxed drop-shadow anim-hero-desc">
                    A progressive, third-class agricultural municipality bridging our rich coastal heritage with modern digital governance. Access your local services, request clearances, and stay informed through the RESIDENTE platform.
                </p>

                <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4 anim-hero-btns">
                    <a href="{{ route('register') }}" class="px-8 py-3.5 text-base font-bold text-white bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-2xl hover:from-emerald-600 hover:to-emerald-700 transition transform hover:-translate-y-0.5 shadow-lg shadow-emerald-500/40">
                        Register as Resident
                    </a>
                    <a href="#discover" class="px-8 py-3.5 text-base font-bold text-white bg-white/10 border border-white/30 backdrop-blur-sm rounded-2xl hover:bg-white/20 transition shadow-sm">
                        Discover Buguey
                    </a>
                </div>
            </div>
        </div>

        <!-- Scroll-down cue -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 flex flex-col items-center gap-1 text-white/60 text-xs font-medium animate-bounce">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
            Scroll down
        </div>
    </div>

    <!-- ── Featured Video Section ── -->
    <section class="bg-white py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">

            <!-- Header -->
            <div class="text-center mb-10 reveal">
                <span class="inline-block px-4 py-1.5 bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-widest rounded-full mb-4">📽 Official Video</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">
                    Experience <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-blue-500">Buguey</span>
                </h2>
                <p class="mt-3 text-slate-500 text-base max-w-xl mx-auto">
                    A visual journey through the breathtaking landscapes, vibrant culture, and coastal charm of our municipality.
                </p>
            </div>

            <!-- Video Player -->
            <div class="reveal relative rounded-3xl overflow-hidden shadow-xl shadow-slate-200 border border-slate-200 group">
                <!-- Play overlay (shown when paused) -->
                <div id="video-overlay" class="absolute inset-0 z-10 flex items-center justify-center bg-black/40 transition-opacity duration-300 cursor-pointer" onclick="toggleFeaturedVideo()">
                    <div class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm border-2 border-white/60 flex items-center justify-center transition transform group-hover:scale-110">
                        <svg id="video-play-icon" xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                        <svg id="video-pause-icon" xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-white hidden" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                        </svg>
                    </div>
                </div>

                <video id="featured-video"
                       loop
                       muted
                       playsinline
                       class="w-full aspect-video object-cover"
                       poster="">
                    <source src="{{ asset('bugueyvideo.mp4') }}" type="video/mp4">
                </video>

                <!-- Bottom caption bar -->
                <div class="absolute bottom-0 left-0 right-0 px-6 py-4 bg-gradient-to-t from-black/80 to-transparent z-10 pointer-events-none">
                    <p class="text-white font-bold text-sm tracking-wide">🌊 Municipality of Buguey, Cagayan — Philippines</p>
                    <p class="text-slate-400 text-xs mt-0.5">Coastal beauty &bull; Agricultural heritage &bull; Modern governance</p>
                </div>
            </div>

            <!-- Stats row below video -->
            <div class="mt-8 grid grid-cols-3 gap-4 text-center reveal">
                <div class="bg-slate-50 border border-slate-200 rounded-2xl py-5">
                    <p class="text-3xl font-extrabold text-emerald-600">1623</p>
                    <p class="text-xs text-slate-500 mt-1 font-medium uppercase tracking-wide">Year Founded</p>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-2xl py-5">
                    <p class="text-3xl font-extrabold text-blue-600">30</p>
                    <p class="text-xs text-slate-500 mt-1 font-medium uppercase tracking-wide">Barangays</p>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-2xl py-5">
                    <p class="text-3xl font-extrabold text-amber-500">30k+</p>
                    <p class="text-xs text-slate-500 mt-1 font-medium uppercase tracking-wide">Population</p>
                </div>
            </div>
        </div>
    </section>

    <script>
        (function () {
            const video   = document.getElementById('featured-video');
            const overlay = document.getElementById('video-overlay');
            const playIcon  = document.getElementById('video-play-icon');
            const pauseIcon = document.getElementById('video-pause-icon');

            window.toggleFeaturedVideo = function () {
                if (video.paused) {
                    video.play();
                    overlay.style.opacity = '0';
                    overlay.style.pointerEvents = 'none';
                    playIcon.classList.add('hidden');
                    pauseIcon.classList.remove('hidden');
                } else {
                    video.pause();
                    overlay.style.opacity = '1';
                    overlay.style.pointerEvents = 'auto';
                    playIcon.classList.remove('hidden');
                    pauseIcon.classList.add('hidden');
                }
            };

            // Show overlay again when video ends (loop still fires but just in case)
            video.addEventListener('pause', function () {
                overlay.style.opacity = '1';
                overlay.style.pointerEvents = 'auto';
                playIcon.classList.remove('hidden');
                pauseIcon.classList.add('hidden');
            });
            video.addEventListener('play', function () {
                overlay.style.opacity = '0';
                overlay.style.pointerEvents = 'none';
                playIcon.classList.add('hidden');
                pauseIcon.classList.remove('hidden');
            });
        })();
    </script>

    <!-- ── Buguey Hymn Section ── -->
    <section class="bg-deep-forest py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">

            <!-- Header -->
            <div class="text-center mb-10 reveal">
                <span class="inline-block px-4 py-1.5 bg-golden-glow/20 text-golden-glow text-xs font-bold uppercase tracking-widest rounded-full mb-4">🎵 Official Anthem</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">
                    Buguey Municipal Hymn
                </h2>
                <p class="mt-3 text-gray-300 text-base max-w-xl mx-auto">
                    The official anthem of the Municipality of Buguey — a song of pride, heritage, and community.
                </p>
            </div>

            <!-- Hymn Video Player -->
            <div class="reveal relative rounded-3xl overflow-hidden shadow-2xl border border-white/10 group">
                <!-- Play overlay -->
                <div id="hymn-overlay" class="absolute inset-0 z-10 flex items-center justify-center bg-black/50 transition-opacity duration-300 cursor-pointer" onclick="toggleHymnVideo()">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-20 h-20 rounded-full bg-golden-glow/20 backdrop-blur-sm border-2 border-golden-glow/60 flex items-center justify-center transition transform group-hover:scale-110">
                            <svg id="hymn-play-icon" xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-golden-glow ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                            <svg id="hymn-pause-icon" xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-golden-glow hidden" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                            </svg>
                        </div>
                        <span class="text-white text-sm font-semibold tracking-wide">Play Hymn</span>
                    </div>
                </div>

                <video id="hymn-video"
                       playsinline
                       class="w-full aspect-video object-cover bg-black">
                    <source src="{{ asset('BUGUEY HYMN - ENGLISH VERSION WITH LYRICS - mestroarellano (1080p, h264).mp4') }}" type="video/mp4">
                </video>

                <!-- Bottom caption bar -->
                <div class="absolute bottom-0 left-0 right-0 px-6 py-4 bg-gradient-to-t from-black/80 to-transparent z-10 pointer-events-none">
                    <p class="text-white font-bold text-sm tracking-wide">🎶 Official Hymn — Municipality of Buguey, Cagayan</p>
                    <p class="text-gray-400 text-xs mt-0.5">English Version with Lyrics</p>
                </div>
            </div>

        </div>
    </section>

    <script>
        (function () {
            const hymnVideo   = document.getElementById('hymn-video');
            const hymnOverlay = document.getElementById('hymn-overlay');
            const hymnPlay    = document.getElementById('hymn-play-icon');
            const hymnPause   = document.getElementById('hymn-pause-icon');

            window.toggleHymnVideo = function () {
                if (hymnVideo.paused) {
                    hymnVideo.play();
                    hymnOverlay.style.opacity = '0';
                    hymnOverlay.style.pointerEvents = 'none';
                    hymnPlay.classList.add('hidden');
                    hymnPause.classList.remove('hidden');
                } else {
                    hymnVideo.pause();
                    hymnOverlay.style.opacity = '1';
                    hymnOverlay.style.pointerEvents = 'auto';
                    hymnPlay.classList.remove('hidden');
                    hymnPause.classList.add('hidden');
                }
            };

            hymnVideo.addEventListener('pause', function () {
                hymnOverlay.style.opacity = '1';
                hymnOverlay.style.pointerEvents = 'auto';
                hymnPlay.classList.remove('hidden');
                hymnPause.classList.add('hidden');
            });
            hymnVideo.addEventListener('play', function () {
                hymnOverlay.style.opacity = '0';
                hymnOverlay.style.pointerEvents = 'none';
                hymnPlay.classList.add('hidden');
                hymnPause.classList.remove('hidden');
            });
            hymnVideo.addEventListener('ended', function () {
                hymnOverlay.style.opacity = '1';
                hymnOverlay.style.pointerEvents = 'auto';
                hymnPlay.classList.remove('hidden');
                hymnPause.classList.add('hidden');
            });
        })();
    </script>

    <!-- Crossfade Carousel Section -->
    <section class="relative h-96 overflow-hidden bg-gray-900">
        <div class="carousel-fade relative w-full h-full">
            <!-- Slide 1 -->
            <div class="carousel-item active absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out opacity-100">
                <img src="{{ asset('slideshow/295851604_359433516361380_629033184688140763_n.jpg') }}" 
                     alt="Buguey Slideshow 1" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60"></div>
            </div>
            
            <!-- Slide 2 -->
            <div class="carousel-item absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out opacity-0">
                <img src="{{ asset('slideshow/380113.jpg') }}" 
                     alt="Buguey Slideshow 2" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60"></div>
            </div>
            
            <!-- Slide 3 -->
            <div class="carousel-item absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out opacity-0">
                <img src="{{ asset('slideshow/88822b_6f63948ebf154531aabe25a4e7d6289f~mv2.png') }}" 
                     alt="Buguey Slideshow 3" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60"></div>
            </div>
            
            <!-- Slide 4 -->
            <div class="carousel-item absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out opacity-0">
                <img src="{{ asset('slideshow/88822b_e7d9efe2fa5b4bbda278750364e683b3~mv2.jpg') }}" 
                     alt="Buguey Slideshow 4" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60"></div>
            </div>
            
            <!-- Slide 5 -->
            <div class="carousel-item absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out opacity-0">
                <img src="{{ asset('slideshow/News-1_Thumbnail-Crab.jpg') }}" 
                     alt="Buguey Slideshow 5" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60"></div>
            </div>
            
            <!-- Carousel Caption Overlay -->
            <div class="absolute bottom-0 left-0 right-0 p-8 text-white z-10">
                <div class="max-w-7xl mx-auto">
                    <h3 class="text-3xl font-bold mb-2">Discover Buguey</h3>
                    <p class="text-lg opacity-90">A glimpse of our municipality's beauty and culture</p>
                </div>
            </div>
            
            <!-- Navigation Dots -->
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2 z-20">
                <button class="carousel-dot w-3 h-3 rounded-full bg-white opacity-100 transition-opacity" data-slide="0"></button>
                <button class="carousel-dot w-3 h-3 rounded-full bg-white opacity-50 transition-opacity" data-slide="1"></button>
                <button class="carousel-dot w-3 h-3 rounded-full bg-white opacity-50 transition-opacity" data-slide="2"></button>
                <button class="carousel-dot w-3 h-3 rounded-full bg-white opacity-50 transition-opacity" data-slide="3"></button>
                <button class="carousel-dot w-3 h-3 rounded-full bg-white opacity-50 transition-opacity" data-slide="4"></button>
            </div>
        </div>
    </section>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const items = document.querySelectorAll('.carousel-item');
            const dots = document.querySelectorAll('.carousel-dot');
            let currentSlide = 0;
            const totalSlides = items.length;
            
            function showSlide(index) {
                // Hide all slides
                items.forEach(item => {
                    item.classList.remove('opacity-100');
                    item.classList.add('opacity-0');
                    item.classList.remove('active');
                });
                
                // Update dots
                dots.forEach(dot => {
                    dot.classList.remove('opacity-100');
                    dot.classList.add('opacity-50');
                });
                
                // Show current slide
                items[index].classList.remove('opacity-0');
                items[index].classList.add('opacity-100');
                items[index].classList.add('active');
                
                // Update current dot
                dots[index].classList.remove('opacity-50');
                dots[index].classList.add('opacity-100');
            }
            
            function nextSlide() {
                currentSlide = (currentSlide + 1) % totalSlides;
                showSlide(currentSlide);
            }
            
            // Auto advance every 5 seconds
            setInterval(nextSlide, 5000);
            
            // Manual navigation with dots
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    currentSlide = index;
                    showSlide(currentSlide);
                });
            });
        });
    </script>

    <!-- Discover Buguey Section -->
    <div id="discover" class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- History + Stats Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20">
                <div class="space-y-6 reveal reveal-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-blue-700 text-xs font-bold uppercase tracking-wider">
                        <span>&#9875;</span> Our Rich History
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">
                        From &ldquo;Navugay&rdquo; to a Thriving Coastal Town
                    </h2>
                    <p class="text-slate-600 font-medium leading-relaxed text-lg">
                        Steeped in centuries of history, our town was officially founded on May 20, 1623, by a Royal Decree from the King of Spain. Local lore tells the story of early sea pirates whose vessel was capsized by strong winds in the Babuyan Channel. The joyful locals shouted &ldquo;Navugay Ira!&rdquo; meaning capsized, a phrase that eventually evolved into the beloved name we carry today.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4 reveal reveal-right">
                    <div class="bg-white p-6 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 transform translate-y-8">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl mb-4 shadow-inner">&#128506;</div>
                        <h3 class="font-extrabold text-slate-900 text-2xl">30</h3>
                        <p class="text-sm font-bold text-slate-500 mt-1">Distinct Barangays</p>
                    </div>
                    <div class="bg-white p-6 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl mb-4 shadow-inner">&#128101;</div>
                        <h3 class="font-extrabold text-slate-900 text-2xl">30,175+</h3>
                        <p class="text-sm font-bold text-slate-500 mt-1">Strong Population</p>
                    </div>
                </div>
            </div>

            <!-- Vision Cards -->
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 sm:p-12 relative overflow-hidden reveal">
                <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-blue-50 to-emerald-50 rounded-full blur-3xl opacity-70 -mr-20 -mt-20 pointer-events-none"></div>

                <div class="relative z-10 text-center max-w-2xl mx-auto mb-12">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">A Clear Vision for Tomorrow</h2>
                    <p class="mt-4 text-slate-600 font-medium">
                        Guided by the Comprehensive Land Use Plan, the local government is actively directing physical and economic development toward a safer, healthier, and more progressive environment.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 hover:shadow-md transition reveal reveal-delay-1">
                        <h3 class="font-bold text-slate-900 text-lg">Agri-Fishery Excellence</h3>
                        <p class="text-sm text-slate-600 mt-2 font-medium">Establishing Buguey as a premier ecotourism and agricultural center within the province, capitalizing on our coastal and non-coastal divide.</p>
                    </div>
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 hover:shadow-md transition reveal reveal-delay-2">
                        <h3 class="font-bold text-slate-900 text-lg">Resilient Communities</h3>
                        <p class="text-sm text-slate-600 mt-2 font-medium">Sustainably managing our ecosystem to foster a climate-change and disaster-resilient population across all urban and rural sectors.</p>
                    </div>
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 hover:shadow-md transition reveal reveal-delay-3">
                        <h3 class="font-bold text-slate-900 text-lg">Progressive Infrastructure</h3>
                        <p class="text-sm text-slate-600 mt-2 font-medium">Developing dual-core urban control and linear expansion patterns to provide adequate infrastructure support for a globally competitive community.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <section id="services" class="py-8 md:py-12 lg:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 reveal">
                <h2 class="text-4xl font-extrabold text-deep-forest mb-4">LGU Buguey Services</h2>
                <p class="text-lg text-gray-600">Complete directory of available government services by department</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                
                <!-- Municipal Health Office -->
                <div class="bg-white rounded-xl shadow-sm border-t-4 border-sea-green overflow-hidden hover:shadow-md transition">
                    <div class="p-6 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-12 h-12 bg-sea-green bg-opacity-20 text-sea-green rounded-full flex items-center justify-center text-2xl">⚕️</div>
                        <h3 class="font-bold text-deep-forest text-lg">Municipal Health Office</h3>
                    </div>
                    <ul class="p-4 divide-y divide-gray-100 text-sm text-gray-700 max-h-72 overflow-y-auto">
                        <li class="py-2 hover:text-tiger-orange transition flex justify-between items-center">Availing of Anti-Rabies <span>→</span></li>
                        <li class="py-2 hover:text-tiger-orange transition flex justify-between items-center">Availing of Immunization Services <span>→</span></li>
                        <li class="py-2 hover:text-tiger-orange transition flex justify-between items-center">Availing of Laboratory Services <span>→</span></li>
                        <li class="py-2 hover:text-tiger-orange transition flex justify-between items-center">Maternal and Child Health Care <span>→</span></li>
                        <li class="py-2 hover:text-tiger-orange transition flex justify-between items-center">Anti-Tuberculosis Drugs/Medicines <span>→</span></li>
                        <li class="py-2 hover:text-tiger-orange transition flex justify-between items-center">Availing of Dental Services <span>→</span></li>
                        <li class="py-2 hover:text-tiger-orange transition flex justify-between items-center">Availing STD/STI Services <span>→</span></li>
                        <li class="py-2 hover:text-tiger-orange transition flex justify-between items-center">Securing Medical/Death Certificate <span>→</span></li>
                        <li class="py-2 hover:text-tiger-orange transition flex justify-between items-center">Out-Patient Department <span>→</span></li>
                        <li class="py-2 hover:text-tiger-orange transition flex justify-between items-center">Issuance of Sanitary Permit <span>→</span></li>
                    </ul>
                </div>

                <!-- Municipal Civil Registrar -->
                <div class="bg-white rounded-xl shadow-sm border-t-4 border-tiger-orange overflow-hidden hover:shadow-md transition">
                    <div class="p-6 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-12 h-12 bg-tiger-orange bg-opacity-20 text-tiger-orange rounded-full flex items-center justify-center text-2xl">📜</div>
                        <h3 class="font-bold text-deep-forest text-lg">Municipal Civil Registrar</h3>
                    </div>
                    <ul class="p-4 divide-y divide-gray-100 text-sm text-gray-700 max-h-72 overflow-y-auto">
                        <li class="py-2 hover:text-tiger-orange transition">Registration of Birth (Timely/Delayed, Legitimate/Illegitimate, Out-of-Town)</li>
                        <li class="py-2 hover:text-tiger-orange transition">Registration of Marriage (Timely/Delayed) & License Application</li>
                        <li class="py-2 hover:text-tiger-orange transition">Registration of Death (Timely/Delayed)</li>
                        <li class="py-2 hover:text-tiger-orange transition">Issuance of Forms (1A, 1B, 1C, 2A, 2B, 2C, 3A, 3B, CTC)</li>
                        <li class="py-2 hover:text-tiger-orange transition">Petition for Clerical Error/Change of Name (RA9048 & 10172)</li>
                        <li class="py-2 hover:text-tiger-orange transition">Registration of Court Orders & Legal Instruments</li>
                        <li class="py-2 hover:text-tiger-orange transition">Supplemental Report</li>
                    </ul>
                </div>

                <!-- Mayor's Office -->
                <div class="bg-white rounded-xl shadow-sm border-t-4 border-golden-glow overflow-hidden hover:shadow-md transition">
                    <div class="p-6 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-12 h-12 bg-golden-glow bg-opacity-30 text-deep-forest rounded-full flex items-center justify-center text-2xl">🏛️</div>
                        <h3 class="font-bold text-deep-forest text-lg">Mayor's Office</h3>
                    </div>
                    <ul class="p-4 divide-y divide-gray-100 text-sm text-gray-700">
                        <li class="py-3 hover:text-tiger-orange transition flex justify-between items-center">Issuance of Mayor's Clearance <span>→</span></li>
                        <li class="py-3 hover:text-tiger-orange transition flex justify-between items-center">Issuance of Business Permit <span>→</span></li>
                        <li class="py-3 hover:text-tiger-orange transition flex justify-between items-center">Issuance of Working Permit <span>→</span></li>
                        <li class="py-3 hover:text-tiger-orange transition flex justify-between items-center">Motorized Tricycle Operator's Permit <span>→</span></li>
                    </ul>
                </div>

                <!-- Planning and Development -->
                <div class="bg-white rounded-xl shadow-sm border-t-4 border-burnt-tangerine overflow-hidden hover:shadow-md transition">
                    <div class="p-6 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
                        <div class="w-12 h-12 bg-burnt-tangerine bg-opacity-20 text-burnt-tangerine rounded-full flex items-center justify-center text-2xl">🗺️</div>
                        <h3 class="font-bold text-deep-forest text-lg">Planning and Development</h3>
                    </div>
                    <ul class="p-4 divide-y divide-gray-100 text-sm text-gray-700">
                        <li class="py-3 hover:text-tiger-orange transition flex justify-between items-center">Zoning Certification/Land Issuance <span>→</span></li>
                        <li class="py-3 hover:text-tiger-orange transition flex justify-between items-center">Locational Clearance for Business Permit <span>→</span></li>
                    </ul>
                </div>

            </div>

            <!-- Call to Action -->
            <div class="mt-12 text-center">
                <div class="bg-gradient-to-r from-sea-green to-deep-forest text-white rounded-xl shadow-lg p-8 inline-block">
                    <h3 class="text-2xl font-bold mb-3">Ready to Access Services?</h3>
                    <p class="text-gray-100 mb-6">Create your RESIDENTE account to request services online</p>
                    @auth
                        <a href="{{ route('services.index') }}" class="bg-golden-glow hover:bg-white text-deep-forest px-8 py-3 rounded-lg font-bold shadow-lg transition inline-block">
                            View Full Directory
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="bg-golden-glow hover:bg-white text-deep-forest px-8 py-3 rounded-lg font-bold shadow-lg transition inline-block">
                            Register Now
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <section id="announcements" class="py-8 md:py-12 lg:py-16 bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-extrabold text-deep-forest mb-4">
                    <span class="text-golden-glow">🔔</span> Happenings Right Now
                </h2>
                <p class="text-lg text-gray-600">Stay updated with the latest news and announcements from the Municipality of Buguey</p>
                <div class="w-24 h-1 bg-tiger-orange mx-auto mt-4 rounded"></div>
            </div>
            
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    <!-- Left Side: Announcements List -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="bg-deep-forest text-white px-6 py-4">
                            <h3 class="text-xl font-bold">Recent Announcements</h3>
                        </div>
                        <div class="overflow-y-auto" style="max-height: 600px;">
                            @forelse($announcements as $index => $announcement)
                            <div class="announcement-item cursor-pointer border-b border-gray-100 hover:bg-gray-50 transition p-6" 
                                 data-index="{{ $index }}"
                                 data-title="{{ $announcement->title }}"
                                 data-content="{{ $announcement->content }}"
                                 data-category="{{ $announcement->category }}"
                                 data-time="{{ $announcement->formatted_posted_at }}"
                                 data-category-badge="{{ $announcement->category_badge_color }}"
                                 data-dot-color="{{ $announcement->timeline_dot_color }}">
                                <div class="flex items-start gap-3">
                                    <span class="w-3 h-3 rounded-full {{ $announcement->timeline_dot_color }} flex-shrink-0 mt-1.5"></span>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-deep-forest text-base mb-1 truncate">{{ $announcement->title }}</h4>
                                        <p class="text-xs text-gray-500 mb-2">{{ $announcement->formatted_posted_at }}</p>
                                        <span class="inline-block px-2 py-1 {{ $announcement->category_badge_color }} text-xs font-bold rounded-full">{{ $announcement->category }}</span>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                            @empty
                            <div class="p-8 text-center text-gray-500">
                                <p class="font-bold text-deep-forest text-lg mb-2">No Public Announcements Yet</p>
                                <p class="text-sm">Stay tuned for upcoming news and updates from your LGU.</p>
                            </div>
                            @endforelse
                        </div>
                        @if($announcements->isNotEmpty())
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                            <a href="{{ route('register') }}" class="text-tiger-orange hover:text-burnt-tangerine font-bold text-sm transition flex items-center justify-center gap-2">
                                <span>Register to see announcements specific to your barangay</span>
                                <span>→</span>
                            </a>
                        </div>
                        @endif
                    </div>

                    <!-- Right Side: Preview Panel -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="bg-sea-green text-white px-6 py-4">
                            <h3 class="text-xl font-bold">Preview</h3>
                        </div>
                        <div id="preview-panel" class="p-8" style="min-height: 500px;">
                            @if($announcements->isNotEmpty())
                            <div id="preview-content" class="w-full">
                                <!-- Embedded Link Preview Card -->
                                <div class="border border-gray-300 rounded-lg overflow-hidden hover:shadow-xl transition-shadow bg-white">
                                    <!-- Preview Image/Banner -->
                                    <div class="relative h-48 bg-gradient-to-br from-deep-forest via-sea-green to-tiger-orange overflow-hidden">
                                        <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                                            <div class="text-center text-white">
                                                <svg class="w-16 h-16 mx-auto mb-2 opacity-90" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                                                </svg>
                                                <p class="text-sm font-semibold">LGU BUGUEY</p>
                                                <p class="text-xs opacity-75">Official Announcement</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Preview Content -->
                                    <div class="p-5">
                                        <!-- URL Bar Style Header -->
                                        <div class="flex items-center gap-2 mb-3 pb-3 border-b border-gray-200">
                                            <div class="w-6 h-6 bg-deep-forest rounded-full flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm3 1h6v4H7V5zm6 6H7v2h6v-2z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-semibold text-deep-forest truncate">Municipality of Buguey</p>
                                                <p class="text-xs text-gray-500">buguey.gov.ph/announcements</p>
                                            </div>
                                            <span id="preview-dot" class="w-2 h-2 rounded-full {{ $announcements->first()->timeline_dot_color }} flex-shrink-0"></span>
                                        </div>
                                        
                                        <!-- Title and Meta -->
                                        <h4 id="preview-title" class="text-xl font-bold text-gray-900 mb-2 leading-snug line-clamp-2">{{ $announcements->first()->title }}</h4>
                                        
                                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span id="preview-time">{{ $announcements->first()->formatted_posted_at }}</span>
                                            <span class="text-gray-300">•</span>
                                            <span id="preview-category" class="inline-flex items-center px-2 py-0.5 {{ $announcements->first()->category_badge_color }} text-xs font-bold rounded">{{ $announcements->first()->category }}</span>
                                        </div>
                                        
                                        <!-- Description -->
                                        <p id="preview-text" class="text-sm text-gray-600 leading-relaxed line-clamp-3 mb-4">{{ $announcements->first()->content }}</p>
                                        
                                        <!-- Embed Footer -->
                                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                            <div class="flex items-center gap-2">
                                                <div class="flex -space-x-2">
                                                    <div class="w-6 h-6 bg-sea-green rounded-full border-2 border-white flex items-center justify-center">
                                                        <span class="text-xs text-white font-bold">LG</span>
                                                    </div>
                                                    <div class="w-6 h-6 bg-tiger-orange rounded-full border-2 border-white flex items-center justify-center">
                                                        <span class="text-xs text-white font-bold">U</span>
                                                    </div>
                                                </div>
                                                <span class="text-xs text-gray-500 font-medium">Posted by LGU Buguey</span>
                                            </div>
                                            <a href="#" class="text-xs text-sea-green hover:text-deep-forest font-semibold flex items-center gap-1 transition">
                                                Read more
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="h-full flex items-center justify-center text-center text-gray-400">
                                <div>
                                    <svg class="w-20 h-20 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">Select an announcement to preview</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const announcementItems = document.querySelectorAll('.announcement-item');
                const previewTitle = document.getElementById('preview-title');
                const previewText = document.getElementById('preview-text');
                const previewCategory = document.getElementById('preview-category');
                const previewTime = document.getElementById('preview-time');
                const previewDot = document.getElementById('preview-dot');
                
                announcementItems.forEach(item => {
                    item.addEventListener('click', function() {
                        // Remove active state from all items
                        announcementItems.forEach(i => i.classList.remove('bg-gray-100', 'border-l-4', 'border-tiger-orange'));
                        
                        // Add active state to clicked item
                        this.classList.add('bg-gray-100', 'border-l-4', 'border-tiger-orange');
                        
                        // Update preview panel
                        const title = this.dataset.title;
                        const content = this.dataset.content;
                        const category = this.dataset.category;
                        const time = this.dataset.time;
                        const categoryBadge = this.dataset.categoryBadge;
                        const dotColor = this.dataset.dotColor;
                        
                        if (previewTitle) previewTitle.textContent = title;
                        if (previewText) previewText.textContent = content;
                        if (previewCategory) {
                            previewCategory.textContent = category;
                            previewCategory.className = 'inline-flex items-center px-2 py-0.5 text-xs font-bold rounded ' + categoryBadge;
                        }
                        if (previewTime) previewTime.textContent = time;
                        if (previewDot) {
                            previewDot.className = 'w-2 h-2 rounded-full flex-shrink-0 ' + dotColor;
                        }
                    });
                });
                
                // Activate first item by default
                if (announcementItems.length > 0) {
                    announcementItems[0].classList.add('bg-gray-100', 'border-l-4', 'border-tiger-orange');
                }
            });
        </script>
    </section>

    <section id="governance" class="bg-deep-forest text-white py-8 md:py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-8">Aligned with National & Global Standards</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 max-w-4xl mx-auto">
                <div class="bg-white bg-opacity-10 p-6 rounded-xl border border-white border-opacity-20">
                    <h3 class="text-xl font-bold text-golden-glow mb-3">Sustainable Development Goals</h3>
                    <p class="text-sm text-gray-200">Supporting Sustainable Cities, Industry & Innovation, Reduced Inequalities, and Partnerships for the Goals.</p>
                </div>
                <div class="bg-white bg-opacity-10 p-6 rounded-xl border border-white border-opacity-20">
                    <h3 class="text-xl font-bold text-golden-glow mb-3">Seal of Good Local Governance</h3>
                    <p class="text-sm text-gray-200">Advancing metrics in disaster preparedness, social protection, and business-friendliness & competitiveness.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="population-map" class="py-8 md:py-12 lg:py-16 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-extrabold text-deep-forest mb-4">Population Density Map</h2>
                <p class="text-lg text-gray-600">Comprehensive land use data of Municipality of Buguey</p>
                <div class="w-24 h-1 bg-sea-green mx-auto mt-4 rounded"></div>
                <p class="text-sm text-gray-500 mt-4">Hover over the map to zoom in for better detail</p>
            </div>
            
            <div class="flex justify-center">
                <div class="map-container relative inline-block overflow-hidden rounded-xl shadow-2xl border-4 border-deep-forest bg-white">
                    <img 
                        src="{{ asset('Map.png') }}" 
                        alt="Population Density Map - Municipality of Buguey, Province of Cagayan" 
                        class="map-image transition-transform duration-500 ease-in-out cursor-zoom-in max-w-full h-auto"
                        style="max-height: 600px;"
                    />
                </div>
            </div>
        </div>
        
        <style>
            .map-container {
                perspective: 1000px;
            }
            
            .map-image {
                display: block;
            }
            
            .map-image:hover {
                transform: scale(1.5);
                cursor: zoom-in;
            }
            
            .map-container:hover {
                overflow: visible;
                z-index: 50;
            }
        </style>
    </section>

    <section id="location" class="py-8 md:py-12 lg:py-16 bg-gray-50 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-deep-forest">Locate the Municipal Hall</h2>
                <div class="w-24 h-1 bg-sea-green mx-auto mt-4 rounded"></div>
                <p class="mt-4 text-gray-600 max-w-2xl mx-auto">Visit us for physical inquiries, offline processing of clearances, and other local government assistance.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                
                <div class="p-8 lg:col-span-1 bg-deep-forest text-white flex flex-col justify-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-sea-green rounded-full opacity-50 blur-2xl"></div>
                    
                    <h3 class="text-2xl font-bold mb-6 relative z-10 text-golden-glow">Get in Touch</h3>
                    
                    <ul class="space-y-6 relative z-10">
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-white bg-opacity-10 rounded-full flex items-center justify-center flex-shrink-0 text-tiger-orange font-bold text-xl">📍</div>
                            <div>
                                <p class="font-bold text-lg">Address</p>
                                <p class="text-gray-300 text-sm mt-1">Municipal Hall Building, Centro, Buguey, Cagayan, Philippines 3511</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-white bg-opacity-10 rounded-full flex items-center justify-center flex-shrink-0 text-tiger-orange font-bold text-xl">🕒</div>
                            <div>
                                <p class="font-bold text-lg">Office Hours</p>
                                <p class="text-gray-300 text-sm mt-1">Monday - Friday<br>8:00 AM - 5:00 PM</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-white bg-opacity-10 rounded-full flex items-center justify-center flex-shrink-0 text-tiger-orange font-bold text-xl">✉️</div>
                            <div>
                                <p class="font-bold text-lg">Email</p>
                                <p class="text-gray-300 text-sm mt-1">lgu.buguey@gmail.com</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="lg:col-span-2 min-h-[400px] w-full relative">
                    <iframe 
                        class="absolute inset-0 w-full h-full border-0" 
                        src="https://maps.google.com/maps?q=Buguey,+Cagayan,+Philippines&t=&z=13&ie=UTF8&iwloc=&output=embed" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                
            </div>
        </div>
    </section>

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
                    <li><span class="text-tiger-orange font-bold">MDRRMO:</span> 000-000-0000</li>
                </ul>
            </div>
            
            <div>
                <h3 class="text-white font-bold mb-4 text-lg">Development Partner</h3>
                <p class="text-sm font-bold text-sea-green">Cagayan State University - Aparri</p>
                <p class="text-sm">College of Information and Computing Sciences (CICS)</p>
                <p class="text-sm mt-2">Technology-enabled processes and advancement of education and sciences.</p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-gray-800 text-sm text-center">
            &copy; {{ date('Y') }} Municipality of Buguey. All rights reserved.
        </div>
    </footer>

    <script>
        // Intersection Observer for scroll reveal animations
        document.addEventListener('DOMContentLoaded', function () {
            const revealEls = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
            const observer = new IntersectionObserver(function (entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15 });
            revealEls.forEach(el => observer.observe(el));
        });
    </script>

</body>
</html>
