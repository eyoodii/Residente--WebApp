@extends('layouts.admin')

@section('title', 'Service Management')
@section('subtitle', 'Manage available services for residents')

@section('content')
<div class="px-7 py-6">

    <!-- Page Actions -->
    <div class="flex items-center justify-between mb-6">
        <div></div>
        <a href="{{ route('admin.services.create') }}" class="bg-sea-green hover:bg-deep-forest text-white px-5 py-2.5 rounded-lg font-semibold transition shadow-sm flex items-center gap-2 text-sm">
            ➕ Add New Service
        </a>
    </div>

    <!-- Toast Notifications -->
    @if(session('toast_success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm">
            <div class="flex items-center">
                <span class="text-2xl mr-3">✅</span>
                <p class="text-green-800 font-medium">{{ session('toast_success') }}</p>
            </div>
        </div>
    @endif
    @if(session('toast_error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
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
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="text-3xl">{{ $service->icon ?? '📄' }}</span>
                                    <div>
                                        <h3 class="text-lg font-bold text-deep-forest">{{ $service->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $service->slug }}</p>
                                    </div>
                                    @if($service->is_active)
                                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">✓ Available</span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">⛔ Unavailable</span>
                                    @endif
                                </div>
                                <p class="text-gray-700 mb-3">{{ Str::limit($service->description, 150) }}</p>
                                <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                                    <span>💰 {{ $service->formatted_fee }}</span>
                                    <span>⏱️ {{ $service->processing_time_formatted }}</span>
                                    <span>📋 {{ $service->requirements_count }} requirements</span>
                                    <span>📍 {{ $service->steps_count }} steps</span>
                                    <span>📊 {{ $service->requests_count }} requests</span>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2 ml-6">
                                <form action="{{ route('admin.services.toggle', $service) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-4 py-2 rounded-lg font-semibold transition {{ $service->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                        {{ $service->is_active ? '🚫 Deactivate' : '✅ Activate' }}
                                    </button>
                                </form>
                                <a href="{{ route('admin.services.show', $service) }}" class="px-4 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg font-semibold transition text-center">👁️ View</a>
                                <a href="{{ route('admin.services.edit', $service) }}" class="px-4 py-2 bg-yellow-100 text-yellow-700 hover:bg-yellow-200 rounded-lg font-semibold transition text-center">✏️ Edit</a>
                                <form action="{{ route('admin.services.destroy', $service) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service? This action cannot be undone.');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full px-4 py-2 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg font-semibold transition">🗑️ Delete</button>
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
            <a href="{{ route('admin.services.create') }}" class="inline-block bg-sea-green hover:bg-deep-forest text-white px-8 py-3 rounded-lg font-semibold transition">➕ Add First Service</a>
        </div>
    @endif

</div>
@endsection
