@extends('layouts.admin')

@section('title', 'Master Collections')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-deep-forest flex items-center gap-3">
            <span>📊</span> Master Collections
        </h1>
        <p class="text-gray-600 mt-2">Complete household and family data collections (HN → HHN → HHM)</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-sea-green">
            <p class="text-gray-600 text-sm font-medium">Total Households (HN)</p>
            <p class="text-3xl font-bold text-deep-forest mt-2">{{ number_format($stats['total_households']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-tiger-orange">
            <p class="text-gray-600 text-sm font-medium">Total Families (HHN)</p>
            <p class="text-3xl font-bold text-deep-forest mt-2">{{ number_format($stats['total_families']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-golden-glow">
            <p class="text-gray-600 text-sm font-medium">Total Members (HHM)</p>
            <p class="text-3xl font-bold text-deep-forest mt-2">{{ number_format($stats['total_members']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-burnt-tangerine">
            <p class="text-gray-600 text-sm font-medium">Complete Households</p>
            <p class="text-3xl font-bold text-deep-forest mt-2">{{ number_format($stats['households_with_families']) }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form method="GET" action="{{ route('admin.master-collections') }}" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by HN, HHN, Address, Surname..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
            </div>
            <div class="w-64">
                <select name="barangay" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                    <option value="">All Barangays</option>
                    @foreach($barangays as $name => $code)
                        <option value="{{ $name }}" {{ $barangay === $name ? 'selected' : '' }}>{{ $name }} ({{ $code }})</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-sea-green text-white rounded-lg hover:bg-opacity-90 transition font-bold">
                🔍 Filter
            </button>
            @if($search || $barangay)
                <a href="{{ route('admin.master-collections') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-bold">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Household List -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-deep-forest">Household Collections</h3>
            <p class="text-sm text-gray-600 mt-1">{{ $households->total() }} households found</p>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($households as $household)
            <div class="p-6 hover:bg-gray-50 transition">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="text-lg font-bold text-deep-forest flex items-center gap-2">
                            <span class="px-3 py-1 bg-deep-forest text-white text-sm rounded font-mono">{{ $household->household_number }}</span>
                            {{ $household->full_address }}
                        </h4>
                        <p class="text-sm text-gray-600 mt-1">{{ $household->barangay }}, {{ $household->municipality }}</p>
                    </div>
                    <span class="px-3 py-1 bg-sea-green bg-opacity-10 text-sea-green text-sm rounded font-bold">
                        {{ $household->families->count() }} Families
                    </span>
                </div>

                <!-- Families -->
                @forelse($household->families as $family)
                <div class="ml-6 mb-3 pl-4 border-l-2 border-tiger-orange">
                    <div class="flex justify-between items-center mb-2">
                        <h5 class="font-bold text-tiger-orange flex items-center gap-2">
                            <span class="px-2 py-1 bg-tiger-orange text-white text-xs rounded font-mono">{{ $family->hhn_number }}</span>
                            {{ $family->head_surname }} Family
                        </h5>
                        <span class="text-xs text-gray-600">{{ $family->members->count() }} members</span>
                    </div>
                    
                    <!-- Members -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                        @foreach($family->members as $member)
                        <div class="text-sm flex items-center gap-2">
                            <span class="w-6 h-6 bg-golden-glow bg-opacity-20 text-golden-glow rounded-full flex items-center justify-center text-xs font-bold">{{ $loop->iteration }}</span>
                            <span class="font-medium">{{ $member->full_name }}</span>
                            <span class="text-xs text-gray-500">({{ $member->household_relationship }})</span>
                            @if($member->is_auto_linked)
                                <span class="text-xs bg-burnt-tangerine text-white px-2 py-0.5 rounded">Auto-linked</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @empty
                <p class="ml-6 text-sm text-gray-500 italic">No families in this household yet.</p>
                @endforelse
            </div>
            @empty
            <div class="p-12 text-center">
                <p class="text-gray-500">No households found. Try adjusting your filters.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="p-6 border-t border-gray-200">
            {{ $households->links() }}
        </div>
    </div>
</div>
@endsection
