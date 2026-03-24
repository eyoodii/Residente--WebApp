<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $service->name }} | Service Details</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased font-sans">

    <div class="min-h-screen p-8">
        <!-- Header -->
        <div class="max-w-6xl mx-auto mb-8">
            <a href="{{ route('admin.services.index') }}" 
               class="inline-flex items-center gap-2 text-sea-green hover:text-deep-forest font-semibold mb-4">
                ← Back to Services
            </a>
            
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <span class="text-6xl">{{ $service->icon ?? '📄' }}</span>
                    <div>
                        <h1 class="text-3xl font-bold text-deep-forest">{{ $service->name }}</h1>
                        <p class="text-gray-600 mt-1">{{ $service->department }}</p>
                        
                        <!-- Status Badge -->
                        @if($service->is_active)
                            <span class="inline-block mt-2 px-4 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">
                                ✓ Available to Residents
                            </span>
                        @else
                            <span class="inline-block mt-2 px-4 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded-full">
                                ⛔ Currently Unavailable
                            </span>
                        @endif
                    </div>
                </div>

                <div class="flex gap-3">
                    <form action="{{ route('admin.services.toggle', $service) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="px-6 py-3 rounded-lg font-bold transition shadow-sm {{ $service->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                            {{ $service->is_active ? '🚫 Deactivate Service' : '✅ Activate Service' }}
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.services.edit', $service) }}" 
                       class="px-6 py-3 bg-sea-green hover:bg-deep-forest text-white rounded-lg font-bold transition shadow-sm">
                        ✏️ Edit Service
                    </a>
                </div>
            </div>
        </div>

        <!-- Toast Notifications -->
        @if(session('toast_success'))
            <div class="max-w-6xl mx-auto mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm">
                <div class="flex items-center">
                    <span class="text-2xl mr-3">✅</span>
                    <p class="text-green-800 font-medium">{{ session('toast_success') }}</p>
                </div>
            </div>
        @endif

        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Service Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-deep-forest mb-4">📋 Service Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Description</label>
                            <p class="text-gray-800 mt-1">{{ $service->description ?? 'No description provided.' }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-semibold text-gray-600">Classification</label>
                                <p class="text-gray-800 mt-1">{{ $service->classification }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-600">Type</label>
                                <p class="text-gray-800 mt-1">{{ $service->type }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-600">Who May Avail</label>
                            <p class="text-gray-800 mt-1">{{ $service->who_may_avail ?? 'All residents' }}</p>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="text-sm font-semibold text-gray-600">Service Fee</label>
                                <p class="text-gray-800 mt-1 text-lg font-bold">{{ $service->formatted_fee }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-600">Processing Time</label>
                                <p class="text-gray-800 mt-1 text-lg font-bold">{{ $service->processing_time_formatted }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-600">Slug</label>
                                <p class="text-gray-600 mt-1 text-sm font-mono">{{ $service->slug }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Requirements -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-deep-forest mb-4 flex items-center justify-between">
                        <span>📑 Required Documents</span>
                        <span class="text-sm font-normal text-gray-600">{{ $service->requirements->count() }} requirements</span>
                    </h2>
                    
                    @if($service->requirements->count() > 0)
                        <div class="space-y-3">
                            @foreach($service->requirements as $requirement)
                                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                                    <span class="text-xl">{{ $requirement->is_required ? '✅' : '📄' }}</span>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-800">{{ $requirement->requirement }}</p>
                                        @if($requirement->where_to_secure)
                                            <p class="text-sm text-gray-600 mt-1">Where to secure: {{ $requirement->where_to_secure }}</p>
                                        @endif
                                    </div>
                                    @if($requirement->is_required)
                                        <span class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded-full font-semibold">Required</span>
                                    @else
                                        <span class="text-xs px-2 py-1 bg-gray-200 text-gray-700 rounded-full font-semibold">Optional</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No requirements defined for this service.</p>
                    @endif
                </div>

                <!-- Processing Steps -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-deep-forest mb-4 flex items-center justify-between">
                        <span>📍 Processing Steps</span>
                        <span class="text-sm font-normal text-gray-600">{{ $service->steps->count() }} steps</span>
                    </h2>
                    
                    @if($service->steps->count() > 0)
                        <div class="">
                            @foreach($service->steps as $step)
                                <div class="flex gap-3">
                                    <div class="flex-shrink-0 flex flex-col items-center">
                                        <div class="w-8 h-8 bg-sea-green text-white rounded-full flex items-center justify-center font-bold text-sm">
                                            {{ $step->step_number }}
                                        </div>
                                        @if(!$loop->last)
                                            <div class="w-0.5 flex-1 bg-gray-300 my-1 min-h-[1.5rem]"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1 pb-3">
                                        <h3 class="font-bold text-gray-800 text-sm">{{ $step->title }}</h3>
                                        @if($step->description)
                                            <p class="text-gray-600 text-xs mt-0.5">{{ $step->description }}</p>
                                        @endif
                                        <div class="flex gap-3 mt-1.5 text-xs text-gray-500">
                                            @if($step->assigned_office)
                                                <span>🏢 {{ $step->assigned_office }}</span>
                                            @endif
                                            @if($step->duration_minutes)
                                                <span>⏱️ {{ $step->duration_minutes }} min</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No processing steps defined for this service.</p>
                    @endif
                </div>

            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Request Statistics -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-deep-forest mb-4">📊 Request Statistics</h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Total Requests</span>
                            <span class="font-bold text-xl text-deep-forest">{{ $requestStats['total'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Pending</span>
                            <span class="font-bold text-yellow-600">{{ $requestStats['pending'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Processing</span>
                            <span class="font-bold text-blue-600">{{ $requestStats['processing'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Completed</span>
                            <span class="font-bold text-green-600">{{ $requestStats['completed'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Cancelled</span>
                            <span class="font-bold text-red-600">{{ $requestStats['cancelled'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-deep-forest mb-4">⚡ Quick Actions</h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('admin.services.edit', $service) }}" 
                           class="block w-full px-4 py-2 bg-yellow-100 text-yellow-700 hover:bg-yellow-200 rounded-lg font-semibold transition text-center">
                            ✏️ Edit Service
                        </a>

                        <form action="{{ route('admin.services.toggle', $service) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="w-full px-4 py-2 rounded-lg font-semibold transition {{ $service->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                {{ $service->is_active ? '🚫 Deactivate' : '✅ Activate' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.services.destroy', $service) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this service? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg font-semibold transition">
                                🗑️ Delete Service
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Service Metadata -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-deep-forest mb-4">ℹ️ Metadata</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-gray-600">Created</span>
                            <p class="font-semibold text-gray-800">{{ $service->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Last Updated</span>
                            <p class="font-semibold text-gray-800">{{ $service->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Service ID</span>
                            <p class="font-mono text-gray-600">#{{ $service->id }}</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</body>
</html>
