@extends('layouts.admin')

@section('title', 'Search Residents - Verification')

@push('styles')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('header')
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Search Residents</h1>
                <p class="text-sm text-gray-500 mt-1">Find residents and verify their household linkage</p>
            </div>
            <a href="{{ route('admin.verification.dashboard') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Search Form --}}
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <form action="{{ route('admin.verification.search') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-3">
                        <label for="query" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" name="query" id="query" 
                               value="{{ $query ?? '' }}"
                               placeholder="Enter name, email, or National ID..."
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sea-green focus:ring-sea-green sm:text-sm">
                    </div>
                    <div>
                        <label for="filter" class="block text-sm font-medium text-gray-700">Filter</label>
                        <select name="filter" id="filter"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sea-green focus:ring-sea-green sm:text-sm">
                            <option value="all" {{ ($filter ?? 'all') === 'all' ? 'selected' : '' }}>All Residents</option>
                            <option value="linked" {{ ($filter ?? '') === 'linked' ? 'selected' : '' }}>Linked to Household</option>
                            <option value="unlinked" {{ ($filter ?? '') === 'unlinked' ? 'selected' : '' }}>Not Linked</option>
                            <option value="ghost" {{ ($filter ?? '') === 'ghost' ? 'selected' : '' }}>Ghost Members</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-sea-green hover:bg-deep-forest">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Search
                    </button>
                </div>
            </form>
        </div>

        {{-- Results --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    Results
                    @if(isset($residents))
                        <span class="text-sm font-normal text-gray-500">({{ $residents->count() }} found)</span>
                    @endif
                </h3>
            </div>
            
            @if(isset($residents) && $residents->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resident</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">HN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">HHN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Head</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($residents as $resident)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-sea-green/10 rounded-full flex items-center justify-center">
                                        <span class="text-sea-green font-medium">{{ strtoupper(substr($resident->first_name, 0, 1)) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $resident->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $resident->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($resident->household)
                                    <span class="text-sm font-mono text-sea-green">{{ $resident->household->household_number }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Missing</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($resident->householdHeadRelation)
                                    <span class="text-sm font-mono text-deep-forest">{{ $resident->householdHeadRelation->household_head_number }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Missing</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($resident->is_household_head)
                                    <span class="text-sea-green font-medium">Self (Head)</span>
                                @elseif($resident->householdHeadRelation)
                                    {{ $resident->householdHeadRelation->resident?->full_name ?? 'Unknown' }}
                                @else
                                    <span class="text-gray-400">Not assigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($resident->household_id && $resident->household_head_id)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Linked
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        Ghost
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.residents.show', $resident) }}"
                                   class="text-sea-green hover:text-deep-forest">View</a>
                                @if(!$resident->household_id || !$resident->household_head_id)
                                    <a href="{{ route('admin.verification.fix-ghost', ['resident' => $resident->id]) }}"
                                       class="ml-4 text-red-600 hover:text-red-800">Fix</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No residents found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(isset($query) && $query)
                        Try adjusting your search criteria.
                    @else
                        Enter a search term to find residents.
                    @endif
                </p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
