<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Service Requests | RESIDENTE App</title>
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
                <a href="{{ route('services.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-white hover:bg-opacity-10 rounded-lg transition">
                    <span class="text-lg">📚</span> E-Services Directory
                </a>
                <a href="{{ route('services.my-requests') }}" class="flex items-center gap-3 px-4 py-3 bg-sea-green rounded-lg font-medium shadow-sm hover:bg-opacity-90 transition">
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
                            ['label' => 'My Service Requests', 'url' => route('services.my-requests')],
                        ];
                    @endphp
                    <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
                    
                    <div class="mt-2">
                        <h1 class="text-2xl font-bold text-deep-forest">My Service Requests</h1>
                        <p class="text-sm text-gray-600">Track all your service requests and their progress</p>
                    </div>
                </div>
                <a href="{{ route('services.index') }}" class="bg-tiger-orange hover:bg-burnt-tangerine text-white px-5 py-2.5 rounded-lg font-bold shadow-sm transition whitespace-nowrap">
                    + New Request
                </a>
            </div>
        </header>

        <div class="p-8">
            @if($requests->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <div class="text-6xl mb-4">📋</div>
                <h2 class="text-2xl font-bold text-deep-forest mb-2">No Service Requests Yet</h2>
                <p class="text-gray-600 mb-6">Start by requesting a service from our directory</p>
                <a href="{{ route('services.index') }}" class="inline-block bg-tiger-orange hover:bg-burnt-tangerine text-white px-6 py-3 rounded-lg font-bold transition">
                    Browse Services
                </a>
            </div>
            @else
            <!-- Requests List -->
            <div class="space-y-4">
                @foreach($requests as $request)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-bold text-deep-forest">{{ $request->service->name }}</h3>
                                    <span class="px-3 py-1 {{ $request->status_badge_color }} rounded-full font-bold text-xs uppercase">
                                        {{ $request->status }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-1">
                                    <span class="font-semibold">Request #:</span> {{ $request->request_number }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-semibold">Department:</span> {{ $request->service->department }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 mb-1">Requested on</p>
                                <p class="text-sm font-semibold text-gray-700">{{ $request->requested_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $request->requested_at->format('h:i A') }}</p>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-semibold text-gray-600">Progress</span>
                                <span class="text-xs font-semibold text-tiger-orange">
                                    Step {{ $request->current_step }} of {{ $request->service->steps->count() }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-tiger-orange h-2 rounded-full transition-all" 
                                     style="width: {{ ($request->current_step / $request->service->steps->count()) * 100 }}%">
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-3">
                            <a href="{{ route('service-request.show', $request->request_number) }}" 
                               class="flex-1 bg-sea-green hover:bg-opacity-90 text-white px-4 py-2 rounded-lg font-semibold text-sm text-center transition">
                                View Details & Timeline
                            </a>
                            @if($request->status == 'completed')
                            <button class="bg-golden-glow hover:bg-opacity-90 text-deep-forest px-4 py-2 rounded-lg font-semibold text-sm transition">
                                Download
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $requests->links() }}
            </div>
            @endif
        </div>
    </main>
</body>
</html>
