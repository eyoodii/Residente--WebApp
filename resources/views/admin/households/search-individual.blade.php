@extends('layouts.admin')

@section('title', 'Search Individual')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('admin.households.index') }}" class="text-blue-600 hover:text-blue-800">Households</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li class="text-gray-700">Search Individual</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Search Individual</h1>
            <p class="text-gray-600 mt-2">Level 3: Find any resident or member and view their full ancestry (HN, HHN, Address)</p>
        </div>

        <!-- Search Form -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <form method="GET" action="{{ route('admin.households.search.individual') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Name or National ID</label>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Enter first name, last name, or national ID..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Barangay Filter</label>
                    <select name="barangay" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">All Barangays</option>
                        {{-- Barangay options would be populated here --}}
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <svg class="h-5 w-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Results -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold">
                    Results 
                    <span class="text-gray-500 font-normal">({{ $results->count() }} individuals found)</span>
                </h2>
            </div>

            @if($results->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($results as $result)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        @if($result['type'] === 'resident')
                                            <div class="h-14 w-14 rounded-full bg-blue-100 flex items-center justify-center">
                                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                        @else
                                            <div class="h-14 w-14 rounded-full bg-purple-100 flex items-center justify-center">
                                                <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="flex items-center flex-wrap gap-2">
                                            @if($result['type'] === 'resident')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Registered Resident
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    Household Member
                                                </span>
                                            @endif
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mt-2">
                                            {{ $result['name'] }}
                                        </h3>
                                        
                                        <!-- Full Ancestry Display -->
                                        <div class="mt-2 text-sm">
                                            <div class="flex items-center text-gray-600 space-x-4">
                                                <span class="flex items-center">
                                                    <span class="font-medium text-blue-600">HN:</span>
                                                    <span class="ml-1 px-2 py-0.5 rounded bg-blue-50">{{ $result['hn'] }}</span>
                                                </span>
                                                <span class="text-gray-400">→</span>
                                                <span class="flex items-center">
                                                    <span class="font-medium text-green-600">HHN:</span>
                                                    <span class="ml-1 px-2 py-0.5 rounded bg-green-50">{{ $result['hhn'] }}</span>
                                                </span>
                                            </div>
                                            <p class="text-gray-500 mt-1">
                                                <span class="font-medium">Address:</span> {{ $result['address'] }}
                                            </p>
                                            @if(isset($result['head_name']) && $result['head_name'] !== 'N/A')
                                                <p class="text-gray-500">
                                                    <span class="font-medium">Household Head:</span> {{ $result['head_name'] }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 md:mt-0">
                                    <a href="{{ route('admin.households.individual.show', ['type' => $result['type'], 'id' => $result['data']->id]) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                        View Full Profile →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    @if(request('search'))
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <p class="mt-4 text-gray-500">No individuals found matching "{{ request('search') }}"</p>
                    @else
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <p class="mt-4 text-gray-500">Enter a name or National ID to search.</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Search Guide -->
        <div class="mt-8 bg-purple-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-purple-800 mb-4">Individual Search Guide</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-purple-700">
                <div>
                    <h4 class="font-medium mb-2">What You Can Search:</h4>
                    <ul class="list-disc list-inside space-y-1">
                        <li>First name or last name</li>
                        <li>National ID (PhilSys)</li>
                        <li>Filter by barangay</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium mb-2">Results Show:</h4>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Full ancestry chain (HN → HHN → Individual)</li>
                        <li>Complete address information</li>
                        <li>Household Head information</li>
                        <li>Registration status</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
