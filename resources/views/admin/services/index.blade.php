<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Management | Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased font-sans flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-deep-forest text-white flex flex-col shadow-xl flex-shrink-0">
        <div class="h-20 flex items-center px-6 border-b border-sea-green border-opacity-30">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-deep-forest font-bold mr-3 shadow-sm">
                <span class="text-xs">SP</span>
            </div>
            <span class="font-bold text-xl tracking-wide">ADMIN</span>
        </div>
        
        <div class="p-4 flex-1 overflow-y-auto">
            <p class="text-xs uppercase text-golden-glow font-bold tracking-wider mb-4 mt-2">Admin Menu</p>
            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-white hover:bg-opacity-10 rounded-lg transition">
                    <span class="text-lg">📊</span> Dashboard
                </a>
                <a href="{{ route('admin.services.index') }}" class="flex items-center gap-3 px-4 py-3 bg-sea-green rounded-lg font-medium shadow-sm hover:bg-opacity-90 transition">
                    <span class="text-lg">⚙️</span> Service Management
                </a>
                <a href="{{ route('admin.residents.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-white hover:bg-opacity-10 rounded-lg transition">
                    <span class="text-lg">👥</span> Resident Management
                </a>
                <a href="{{ route('admin.activity-logs.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-white hover:bg-opacity-10 rounded-lg transition">
                    <span class="text-lg">📝</span> Activity Logs
                </a>
            </nav>
            
            <div class="mt-6 pt-6 border-t border-white border-opacity-20">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-white hover:bg-opacity-10 rounded-lg transition text-sm">
                    <span>🏠</span> Back to Portal
                </a>
                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-2 hover:bg-red-600 hover:bg-opacity-20 rounded-lg transition text-sm w-full text-left">
                        <span>🚪</span> Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="bg-white shadow-sm h-20 flex items-center px-8 border-b border-gray-200">
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-deep-forest">⚙️ E-Services Management</h1>
                <p class="text-sm text-gray-600 mt-0.5">Manage available services for residents</p>
            </div>
            <a href="{{ route('admin.services.create') }}" class="bg-sea-green hover:bg-deep-forest text-white px-6 py-3 rounded-lg font-semibold transition shadow-sm flex items-center gap-2">
                <span class="text-xl">➕</span> Add New Service
            </a>
        </header>

        <!-- Content -->
        <main class="flex-1 overflow-y-auto p-8">
            
            <!-- Toast Notifications -->
            @if(session('toast_success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm animate-fade-in">
                    <div class="flex items-center">
                        <span class="text-2xl mr-3">✅</span>
                        <p class="text-green-800 font-medium">{{ session('toast_success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('toast_error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm animate-fade-in">
                    <div class="flex items-center">
                        <span class="text-2xl mr-3">❌</span>
                        <p class="text-red-800 font-medium">{{ session('toast_error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Total Services</p>
                            <p class="text-3xl font-bold text-deep-forest mt-1">{{ $services->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">📋</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Active Services</p>
                            <p class="text-3xl font-bold text-green-600 mt-1">{{ $services->where('is_active', true)->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">✅</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Unavailable</p>
                            <p class="text-3xl font-bold text-red-600 mt-1">{{ $services->where('is_active', false)->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">🚫</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Departments</p>
                            <p class="text-3xl font-bold text-purple-600 mt-1">{{ $servicesByDepartment->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">🏢</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services by Department -->
            @foreach($servicesByDepartment as $department => $departmentServices)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 overflow-hidden">
                    <div class="bg-gradient-to-r from-deep-forest to-sea-green text-white px-6 py-4">
                        <h2 class="text-xl font-bold flex items-center gap-2">
                            <span>🏢</span> {{ $department }}
                            <span class="ml-2 bg-white bg-opacity-20 text-sm px-3 py-1 rounded-full">{{ $departmentServices->count() }} services</span>
                        </h2>
                    </div>

                    <div class="divide-y divide-gray-200">
                        @foreach($departmentServices as $service)
                            <div class="p-6 hover:bg-gray-50 transition">
                                <div class="flex items-start justify-between">
                                    <!-- Service Info -->
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="text-3xl">{{ $service->icon ?? '📄' }}</span>
                                            <div>
                                                <h3 class="text-lg font-bold text-deep-forest">{{ $service->name }}</h3>
                                                <p class="text-sm text-gray-600">{{ $service->slug }}</p>
                                            </div>
                                            
                                            <!-- Status Badge -->
                                            @if($service->is_active)
                                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                                    ✓ Available
                                                </span>
                                            @else
                                                <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                                                    ⛔ Unavailable
                                                </span>
                                            @endif
                                        </div>

                                        <p class="text-gray-700 mb-3">{{ Str::limit($service->description, 150) }}</p>

                                        <!-- Service Meta -->
                                        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                                            <span>💰 {{ $service->formatted_fee }}</span>
                                            <span>⏱️ {{ $service->processing_time_formatted }}</span>
                                            <span>📋 {{ $service->requirements_count }} requirements</span>
                                            <span>📍 {{ $service->steps_count }} steps</span>
                                            <span>📊 {{ $service->requests_count }} requests</span>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex flex-col gap-2 ml-6">
                                        <!-- Master Toggle Switch -->
                                        <form action="{{ route('admin.services.toggle', $service) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="px-4 py-2 rounded-lg font-semibold transition {{ $service->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                                {{ $service->is_active ? '🚫 Deactivate' : '✅ Activate' }}
                                            </button>
                                        </form>

                                        <a href="{{ route('admin.services.show', $service) }}" 
                                           class="px-4 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg font-semibold transition text-center">
                                            👁️ View
                                        </a>

                                        <a href="{{ route('admin.services.edit', $service) }}" 
                                           class="px-4 py-2 bg-yellow-100 text-yellow-700 hover:bg-yellow-200 rounded-lg font-semibold transition text-center">
                                            ✏️ Edit
                                        </a>

                                        <form action="{{ route('admin.services.destroy', $service) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this service? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full px-4 py-2 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg font-semibold transition">
                                                🗑️ Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            @if($services->isEmpty())
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <span class="text-6xl mb-4 block">📋</span>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">No Services Yet</h3>
                    <p class="text-gray-600 mb-6">Start by creating your first service for residents</p>
                    <a href="{{ route('admin.services.create') }}" 
                       class="inline-block bg-sea-green hover:bg-deep-forest text-white px-8 py-3 rounded-lg font-semibold transition">
                        ➕ Add First Service
                    </a>
                </div>
            @endif

        </main>
    </div>

</body>
</html>
