@extends('layouts.admin')

@section('title', 'Verification Dashboard')

@push('styles')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('header')
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Verification Dashboard</h1>
                <p class="text-sm text-gray-500 mt-1">Cross-verify household linkages and manage member assignments</p>
            </div>
            <a href="{{ route('admin.households.index') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                </svg>
                Back to Households
            </a>
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Stats Overview --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total HN</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_households']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total HHN</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_families']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-sea-green/20 rounded-full p-3">
                        <svg class="w-6 h-6 text-sea-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Linked</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['linked_residents']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-amber-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Unlinked</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['unlinked_residents']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 {{ $stats['ghost_members'] > 0 ? 'ring-2 ring-red-500' : '' }}">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Ghost Members</p>
                        <p class="text-2xl font-bold {{ $stats['ghost_members'] > 0 ? 'text-red-600' : 'text-gray-900' }}">
                            {{ number_format($stats['ghost_members']) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Ghost Members Alert --}}
            @if($ghostMembers->isNotEmpty())
            <div class="lg:col-span-2 bg-red-50 border border-red-200 rounded-xl p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-semibold text-red-800">Ghost Members Detected</h3>
                        <p class="text-sm text-red-700 mt-1">
                            These residents have verified accounts but incomplete household linkage. They need to be assigned to a household.
                        </p>
                        
                        <div class="mt-4 overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="text-left text-red-800">
                                        <th class="pb-2 font-semibold">Resident</th>
                                        <th class="pb-2 font-semibold">Issue</th>
                                        <th class="pb-2 font-semibold">Barangay</th>
                                        <th class="pb-2 font-semibold">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-red-200">
                                    @foreach($ghostMembers->take(5) as $ghost)
                                    <tr>
                                        <td class="py-2 font-medium text-red-900">{{ $ghost['resident']->full_name }}</td>
                                        <td class="py-2">
                                            @if($ghost['missing_hn'])
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Missing HN</span>
                                            @endif
                                            @if($ghost['missing_hhn'])
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Missing HHN</span>
                                            @endif
                                        </td>
                                        <td class="py-2 text-red-700">{{ $ghost['resident']->barangay }}</td>
                                        <td class="py-2">
                                            <a href="{{ route('admin.verification.fix-ghost', ['resident' => $ghost['resident']->id]) }}"
                                               class="text-red-600 hover:text-red-800 font-medium">
                                                Fix →
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($ghostMembers->count() > 5)
                        <div class="mt-4">
                            <a href="{{ route('admin.verification.search', ['filter' => 'ghost']) }}"
                               class="text-sm font-medium text-red-600 hover:text-red-800">
                                View all {{ $ghostMembers->count() }} ghost members →
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Multiple Families at Same Address --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Multi-Family Addresses
                        <span class="text-sm font-normal text-gray-500 ml-2">(Potential conflicts)</span>
                    </h3>
                </div>
                <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                    @forelse($multipleFamily as $household)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-mono text-sea-green">{{ $household->household_number }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $household->full_address }}</p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($household->householdHeads as $head)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                 {{ $loop->first ? 'bg-sea-green text-white' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $head->surname }} ({{ $head->household_head_number }})
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2 py-1 rounded bg-amber-100 text-amber-800 text-xs font-medium">
                                    {{ $household->household_heads_count }} families
                                </span>
                                <a href="{{ route('admin.verification.household', $household) }}"
                                   class="ml-4 text-sea-green hover:text-deep-forest">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center text-gray-500">
                        No multi-family addresses found.
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Linkages --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Household Linkages</h3>
                </div>
                <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                    @forelse($recentLinkages as $resident)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $resident->full_name }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Linked to: <span class="font-medium">{{ $resident->householdHeadRelation?->resident?->full_name ?? 'Unknown' }}</span>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-mono text-sea-green">{{ $resident->household?->household_number ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $resident->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center text-gray-500">
                        No recent linkages found.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.verification.search') }}"
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-sea-green hover:bg-sea-green/5 transition-colors">
                    <svg class="w-8 h-8 text-sea-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">Search Residents</p>
                        <p class="text-xs text-gray-500">Find and verify any resident</p>
                    </div>
                </a>

                <a href="{{ route('admin.households.search.address') }}"
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-sea-green hover:bg-sea-green/5 transition-colors">
                    <svg class="w-8 h-8 text-sea-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">Search by Address (HN)</p>
                        <p class="text-xs text-gray-500">Find households by location</p>
                    </div>
                </a>

                <a href="{{ route('admin.households.search.head') }}"
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-sea-green hover:bg-sea-green/5 transition-colors">
                    <svg class="w-8 h-8 text-sea-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">Search by Head (HHN)</p>
                        <p class="text-xs text-gray-500">Find families by surname</p>
                    </div>
                </a>

                <a href="{{ route('admin.households.search.individual') }}"
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-sea-green hover:bg-sea-green/5 transition-colors">
                    <svg class="w-8 h-8 text-sea-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                    </svg>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">Search Individual (HHM)</p>
                        <p class="text-xs text-gray-500">Find specific members</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
