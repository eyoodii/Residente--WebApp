<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Services Directory | RESIDENTE App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased font-sans flex h-screen overflow-hidden">
    @include('partials.loader')

    <!-- Sidebar -->
    <aside class="w-64 bg-deep-forest text-white flex flex-col shadow-xl flex-shrink-0">
        <div class="h-20 flex items-center px-6 border-b border-sea-green border-opacity-30">
            <img src="{{ asset('logo_buguey.png') }}" alt="Buguey Logo" class="w-10 h-10 object-contain rounded-full shadow-sm bg-white mr-3">
            <span class="font-bold text-xl tracking-wide">RESIDENTE</span>
        </div>
        
        <div class="p-4 flex-1 overflow-y-auto">
            <p class="text-xs uppercase text-golden-glow font-bold tracking-wider mb-4 mt-2">Navigation</p>
            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-white hover:bg-opacity-10 rounded-lg transition">
                    <span class="text-lg">🏠</span> Dashboard
                </a>
                <a href="{{ route('services.index') }}" class="flex items-center gap-3 px-4 py-3 bg-sea-green rounded-lg font-medium shadow-sm hover:bg-opacity-90 transition">
                    <span class="text-lg">📚</span> E-Services Directory
                </a>
                <a href="{{ route('services.my-requests') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-white hover:bg-opacity-10 rounded-lg transition">
                    <span class="text-lg">📋</span> My Requests
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-2 hover:bg-white hover:bg-opacity-10 rounded-lg transition">
                    <span class="text-lg">👤</span> My Profile
                </a>
            </nav>
        </div>
        
        <div class="p-4 border-t border-sea-green border-opacity-30">
            <div class="flex flex-col mb-4">
                <span class="text-sm font-bold truncate">{{ $resident->first_name }} {{ $resident->last_name }}</span>
                <span class="text-xs text-gray-300 truncate">{{ $resident->email }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-center py-2 border border-tiger-orange text-tiger-orange hover:bg-tiger-orange hover:text-white rounded-md transition font-medium text-sm">
                    Log Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="h-20 bg-white shadow-sm flex items-center justify-between px-8 flex-shrink-0">
            <div>
                @php
                    $breadcrumbs = [
                        ['label' => '🏠 Home', 'url' => route('dashboard')],
                        ['label' => 'E-Services Directory', 'url' => route('services.index')],
                    ];
                @endphp
                <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
                <h1 class="text-2xl font-bold text-deep-forest">E-Services Directory</h1>
                <p class="text-sm text-gray-600">Browse all available LGU services by department</p>
            </div>
            <a href="{{ route('services.my-requests') }}" class="bg-tiger-orange hover:bg-burnt-tangerine text-white px-5 py-2.5 rounded-lg font-bold shadow-sm transition">
                View My Requests
            </a>
        </header>

        <div class="p-8">
            <div class="space-y-6">
                <div class="bg-white px-6 py-4 border-b border-gray-100 flex justify-between items-center rounded-t-xl shadow-sm">
                    <h2 class="text-2xl font-extrabold text-deep-forest">LGU Buguey Service Directory</h2>
                    <span class="bg-sea-green text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">Select a department</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 pb-8">
                    
                    @foreach($servicesByDepartment as $department => $services)
                        @php
                            $departmentConfig = [
                                'Municipal Health Office' => ['icon' => '⚕️', 'color' => 'sea-green'],
                                'Municipal Civil Registrar' => ['icon' => '📜', 'color' => 'tiger-orange'],
                                "Mayor's Office" => ['icon' => '🏛️', 'color' => 'golden-glow'],
                                'Municipal Planning and Development Office' => ['icon' => '🗺️', 'color' => 'burnt-tangerine'],
                            ];
                            $config = $departmentConfig[$department] ?? ['icon' => '📋', 'color' => 'sea-green'];
                            $departmentId = \Illuminate\Support\Str::slug($department);
                        @endphp

                        <div id="{{ $departmentId }}" class="bg-white rounded-xl shadow-sm border-t-4 border-{{ $config['color'] }} overflow-hidden hover:shadow-md transition scroll-mt-6">
                            <div class="p-6 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
                                <div class="w-12 h-12 bg-{{ $config['color'] }} bg-opacity-20 text-{{ $config['color'] }} rounded-full flex items-center justify-center text-2xl">
                                    {{ $config['icon'] }}
                                </div>
                                <h3 class="font-bold text-deep-forest text-lg">{{ $department }}</h3>
                            </div>
                            <ul class="p-4 divide-y divide-gray-100 text-sm text-gray-700 h-64 overflow-y-auto">
                                @foreach($services as $service)
                                    <li class="py-2 {{ $service->is_active ? 'hover:text-tiger-orange cursor-pointer' : 'opacity-50' }} transition">
                                        @if($service->is_active)
                                            <a href="{{ route('services.show', $service->slug) }}" class="flex justify-between items-center group">
                                                <span>{{ $service->name }}</span>
                                                <div class="flex items-center gap-2 text-xs">
                                                    <span class="text-gray-500">{{ $service->formatted_fee }}</span>
                                                    <span class="group-hover:translate-x-1 transition-transform">→</span>
                                                </div>
                                            </a>
                                        @else
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-400">{{ $service->name }}</span>
                                                <div class="flex items-center gap-2 text-xs">
                                                    <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded-full font-semibold">Unavailable</span>
                                                </div>
                                            </div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach

                    <!-- Document Verification (static) -->
                    <div class="bg-deep-forest text-white rounded-xl shadow-sm overflow-hidden relative border border-gray-800">
                        <div class="absolute top-0 right-0 p-4 opacity-10 text-6xl">✅</div>
                        <div class="p-5 relative z-10 border-b border-sea-green border-opacity-30">
                            <h3 class="font-bold text-golden-glow text-lg">Document Verification</h3>
                            <p class="text-xs text-gray-300 mt-1">Scan or input ID to verify authenticity</p>
                        </div>
                        <ul class="p-4 space-y-3 text-sm relative z-10">
                            <li>
                                <button class="w-full bg-white bg-opacity-10 hover:bg-opacity-20 text-left px-4 py-3 rounded transition flex justify-between items-center">
                                    Barangay Verify Certificate <span class="text-tiger-orange">→</span>
                                </button>
                            </li>
                            <li>
                                <button class="w-full bg-white bg-opacity-10 hover:bg-opacity-20 text-left px-4 py-3 rounded transition flex justify-between items-center">
                                    ATOP Verify Certificate <span class="text-tiger-orange">→</span>
                                </button>
                            </li>
                        </ul>
                    </div>

                </div>

                <!-- Additional Information Section -->
                <div class="bg-gradient-to-r from-sea-green to-deep-forest text-white rounded-xl shadow-lg p-8 mt-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold mb-2">Need Help Choosing a Service?</h3>
                            <p class="text-gray-100 text-sm">Contact our support team for guidance on which service fits your needs.</p>
                        </div>
                        <button class="bg-golden-glow hover:bg-white text-deep-forest px-6 py-3 rounded-lg font-bold shadow-lg transition">
                            Contact Support
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
