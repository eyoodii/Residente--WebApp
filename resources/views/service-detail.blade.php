<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $service->name }} | RESIDENTE App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased font-sans flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-deep-forest text-white flex flex-col shadow-xl flex-shrink-0">
        <div class="h-20 flex items-center px-6 border-b border-sea-green border-opacity-30">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-deep-forest font-bold mr-3 shadow-sm">LGU</div>
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
        <header class="bg-white shadow-sm px-8 py-4 flex-shrink-0">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    @php
                        $breadcrumbs = [
                            ['label' => '🏠 Home', 'url' => route('dashboard')],
                            ['label' => 'E-Services Directory', 'url' => route('services.index')],
                            ['label' => $service->department, 'url' => route('services.index') . '#' . \Illuminate\Support\Str::slug($service->department)],
                            ['label' => $service->name, 'url' => '#'],
                        ];
                    @endphp
                    <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
                    
                    <div class="flex items-center gap-3 mt-2">
                        <h1 class="text-2xl font-bold text-deep-forest">{{ $service->name }}</h1>
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">
                            {{ $service->department }}
                        </span>
                        @if(!$service->is_active)
                            <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full animate-pulse">
                                ⛔ UNAVAILABLE
                            </span>
                        @endif
                    </div>
                </div>
                @if($service->is_active)
                    <form action="{{ route('services.request', $service->slug) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-tiger-orange hover:bg-burnt-tangerine text-white px-6 py-3 rounded-lg font-bold shadow-sm transition whitespace-nowrap">
                            Request This Service
                        </button>
                    </form>
                @else
                    <button disabled class="bg-gray-300 text-gray-500 px-6 py-3 rounded-lg font-bold cursor-not-allowed whitespace-nowrap">
                        Currently Unavailable
                    </button>
                @endif
            </div>
        </header>

        <div class="p-8">
            <!-- Unavailability Notice -->
            @if(!$service->is_active)
                <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg mb-6 shadow-sm">
                    <div class="flex items-start gap-4">
                        <span class="text-4xl">🚫</span>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-red-800 mb-2">Service Temporarily Unavailable</h3>
                            <p class="text-red-700 mb-3">
                                This service is currently not accepting requests. This may be due to system maintenance, 
                                department office closure, or policy updates.
                            </p>
                            <p class="text-red-600 text-sm">
                                Please check back later or contact the {{ $service->department }} for more information.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Service Overview -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-600 uppercase font-semibold mb-1">Department</p>
                        <p class="text-sm font-bold text-deep-forest">{{ $service->department }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-600 uppercase font-semibold mb-1">Classification</p>
                        <p class="text-sm font-bold text-deep-forest">{{ $service->classification }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-600 uppercase font-semibold mb-1">Processing Time</p>
                        <p class="text-sm font-bold text-deep-forest">{{ $service->processing_time_formatted }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-600 uppercase font-semibold mb-1">Fee</p>
                        <p class="text-sm font-bold text-tiger-orange">{{ $service->formatted_fee }}</p>
                    </div>
                </div>

                @if($service->description)
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-deep-forest mb-2">Description</h3>
                    <p class="text-gray-700">{{ $service->description }}</p>
                </div>
                @endif

                @if($service->who_may_avail)
                <div>
                    <h3 class="text-lg font-bold text-deep-forest mb-2">Who May Avail</h3>
                    <p class="text-gray-700">{{ $service->who_may_avail }}</p>
                </div>
                @endif
            </div>

            <!-- Requirements -->
            @if($service->requirements->count() > 0)
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h3 class="text-xl font-bold text-deep-forest mb-4 flex items-center gap-2">
                    <span class="text-2xl">📋</span> Requirements
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Requirement</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Where to Secure</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($service->requirements as $requirement)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4 text-gray-800">
                                    <span class="flex items-center gap-2">
                                        <span class="text-sea-green">✓</span>
                                        {{ $requirement->requirement }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-gray-600">{{ $requirement->where_to_secure }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Process Timeline -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-xl font-bold text-deep-forest mb-6 flex items-center gap-2">
                    <span class="text-2xl">🔄</span> Service Process Timeline
                </h3>
                
                <div class="relative">
                    @foreach($service->steps as $index => $step)
                    <div class="flex gap-4 mb-4 relative">
                        <!-- Timeline line -->
                        @if(!$loop->last)
                        <div class="absolute left-5 top-10 bottom-[-1rem] w-0.5 {{ $step->is_client_step ? 'bg-tiger-orange' : 'bg-sea-green' }} opacity-30"></div>
                        @endif
                        
                        <!-- Step number circle -->
                        <div class="flex-shrink-0 w-10 h-10 {{ $step->is_client_step ? 'bg-tiger-orange' : 'bg-sea-green' }} text-white rounded-full flex items-center justify-center font-bold shadow-sm z-10 text-sm">
                            {{ $step->step_number }}
                        </div>
                        
                        <!-- Step content -->
                        <div class="flex-1 bg-gray-50 rounded-lg p-3 shadow-sm">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <span class="inline-block px-2 py-0.5 {{ $step->is_client_step ? 'bg-tiger-orange' : 'bg-sea-green' }} text-white text-[10px] font-bold rounded-full mb-1">
                                        {{ $step->is_client_step ? '👤 Client Step' : '🏛️ Agency Action' }}
                                    </span>
                                    <h4 class="font-bold text-deep-forest text-sm">{{ $step->is_client_step ? 'Client Action' : 'Agency Action' }}</h4>
                                </div>
                                @if($step->processing_time_minutes)
                                <span class="text-xs text-gray-600 bg-white px-2 py-1 rounded-full border border-gray-100">
                                    ⏱️ {{ $step->processing_time_minutes }} min
                                </span>
                                @endif
                            </div>
                            
                            <p class="text-gray-700 mb-2 text-sm">{{ $step->description }}</p>
                            
                            <div class="flex flex-wrap gap-3 text-xs text-gray-500">
                                @if($step->responsible_person)
                                <span class="flex items-center gap-1">
                                    <span class="font-semibold">👨‍💼 Responsible:</span> {{ $step->responsible_person }}
                                </span>
                                @endif
                                @if($step->fee > 0)
                                <span class="flex items-center gap-1">
                                    <span class="font-semibold">💰 Fee:</span> ₱{{ number_format($step->fee, 2) }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Total Summary -->
                <div class="mt-6 p-4 bg-gradient-to-r from-sea-green to-deep-forest text-white rounded-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm opacity-90">Total Processing Time</p>
                            <p class="text-2xl font-bold">{{ $service->processing_time_formatted }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm opacity-90">Total Fee</p>
                            <p class="text-2xl font-bold">{{ $service->formatted_fee }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Request CTA -->
            <div class="mt-6 bg-gradient-to-r from-tiger-orange to-burnt-tangerine text-white rounded-xl shadow-lg p-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold mb-2">Ready to request this service?</h3>
                        <p class="text-gray-100 text-sm">Submit your request now and track its progress in real-time.</p>
                    </div>
                    <form action="{{ route('services.request', $service->slug) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-white hover:bg-gray-100 text-tiger-orange px-8 py-4 rounded-lg font-bold shadow-lg transition">
                            Request Now →
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
