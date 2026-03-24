@extends('layouts.admin')

@section('title', 'Search by Household Head')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('admin.households.index') }}" class="text-blue-600 hover:text-blue-800">Households</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li class="text-gray-700">Search by Head</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Search by Household Head / HHN</h1>
            <p class="text-gray-600 mt-2">Level 2: Find family units by surname or Household Head Number</p>
        </div>

        <!-- Search Form -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <form method="GET" action="{{ route('admin.households.search.head') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Surname, Name, or HHN</label>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Enter surname, head name, or HHN..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Barangay Filter</label>
                    <select name="barangay" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">All Barangays</option>
                        @foreach($householdHeads->pluck('household.barangay')->unique()->filter() as $barangay)
                            <option value="{{ $barangay }}" {{ request('barangay') == $barangay ? 'selected' : '' }}>
                                {{ $barangay }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
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
                    <span class="text-gray-500 font-normal">({{ $householdHeads->total() }} families found)</span>
                </h2>
            </div>

            @if($householdHeads->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($householdHeads as $head)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="h-14 w-14 rounded-full bg-green-100 flex items-center justify-center">
                                            <span class="text-green-800 font-bold text-lg">{{ substr($head->surname, 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="flex items-center flex-wrap gap-2">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                {{ $head->household_head_number }}
                                            </span>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                {{ $head->household->household_number ?? 'N/A' }}
                                            </span>
                                            @if($head->is_4ps_beneficiary)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    4Ps Beneficiary
                                                </span>
                                            @endif
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mt-2">
                                            {{ $head->resident->full_name ?? 'Unknown' }}
                                        </h3>
                                        <p class="text-gray-600">
                                            <span class="font-medium">Surname:</span> {{ $head->surname }}
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            <span class="font-medium">Address:</span> {{ $head->household->full_address ?? 'N/A' }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            <span class="font-medium">Members:</span> {{ $head->members_count }} registered
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-4 md:mt-0">
                                    <a href="{{ route('admin.households.head.show', $head) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                        View Family →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $householdHeads->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="mt-4 text-gray-500">No families found matching your criteria.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
