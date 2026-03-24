@extends('layouts.admin')

@section('title', 'Validation Flags')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-deep-forest flex items-center gap-3">
            <span>⚠️</span> Validation Flags
        </h1>
        <p class="text-gray-600 mt-2">Auto-linked residents requiring manual validation and review</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-burnt-tangerine">
            <p class="text-gray-600 text-sm font-medium">Total Auto-Linked</p>
            <p class="text-3xl font-bold text-deep-forest mt-2">{{ number_format($stats['total_auto_linked']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-tiger-orange">
            <p class="text-gray-600 text-sm font-medium">Needs Review</p>
            <p class="text-3xl font-bold text-deep-forest mt-2">{{ number_format($stats['needs_review']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-golden-glow">
            <p class="text-gray-600 text-sm font-medium">By Barangay</p>
            <div class="mt-2 space-y-1 max-h-24 overflow-y-auto">
                @forelse($stats['by_barangay'] as $barangay => $count)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-700">{{ $barangay }}</span>
                    <span class="font-bold text-deep-forest">{{ number_format($count) }}</span>
                </div>
                @empty
                <p class="text-sm text-gray-500 italic">No data</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="flex border-b border-gray-200">
            <a href="{{ route('admin.validation-flags', ['status' => 'pending']) }}" 
               class="px-6 py-4 font-bold {{ $status === 'pending' ? 'text-sea-green border-b-2 border-sea-green' : 'text-gray-600 hover:text-sea-green' }} transition">
                🔍 Pending Review ({{ $stats['needs_review'] }})
            </a>
            <a href="{{ route('admin.validation-flags', ['status' => 'all']) }}" 
               class="px-6 py-4 font-bold {{ $status === 'all' ? 'text-sea-green border-b-2 border-sea-green' : 'text-gray-600 hover:text-sea-green' }} transition">
                📋 All Auto-Linked ({{ $stats['total_auto_linked'] }})
            </a>
        </div>
    </div>

    <!-- Validation Flags List -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-deep-forest">Auto-Linked Residents</h3>
            <p class="text-sm text-gray-600 mt-1">{{ $residents->total() }} residents found requiring validation</p>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($residents as $resident)
            <div class="p-6 hover:bg-gray-50 transition">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <!-- Resident Info -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-burnt-tangerine bg-opacity-20 rounded-full flex items-center justify-center">
                                    <span class="text-2xl">👤</span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h4 class="text-lg font-bold text-deep-forest">{{ $resident->full_name }}</h4>
                                    <span class="px-3 py-1 bg-burnt-tangerine text-white text-xs rounded font-bold">
                                        ⚠️ AUTO-LINKED
                                    </span>
                                    @if($resident->is_verified)
                                    <span class="px-3 py-1 bg-sea-green bg-opacity-20 text-sea-green text-xs rounded font-bold">
                                        ✓ Verified
                                    </span>
                                    @endif
                                </div>
                                
                                <!-- Personal Details -->
                                <div class="grid grid-cols-2 gap-4 text-sm mb-3">
                                    <div>
                                        <span class="text-gray-600">Email:</span>
                                        <span class="font-medium text-deep-forest ml-2">{{ $resident->email }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Role:</span>
                                        <span class="font-medium text-deep-forest ml-2">{{ ucfirst($resident->role) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Barangay:</span>
                                        <span class="font-medium text-deep-forest ml-2">{{ $resident->barangay ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Registered:</span>
                                        <span class="font-medium text-deep-forest ml-2">{{ $resident->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>

                                <!-- Household & Family Info -->
                                @if($resident->household || $resident->family)
                                <div class="bg-gray-50 rounded-lg p-4 mt-3">
                                    <h5 class="text-sm font-bold text-gray-700 mb-3">Linked Household Data</h5>
                                    <div class="space-y-2">
                                        @if($resident->household)
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-1 bg-deep-forest text-white text-xs rounded font-mono">
                                                {{ $resident->household->household_number }}
                                            </span>
                                            <span class="text-sm text-gray-700">{{ $resident->household->full_address }}</span>
                                        </div>
                                        @endif
                                        
                                        @if($resident->family)
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-1 bg-tiger-orange text-white text-xs rounded font-mono">
                                                {{ $resident->family->hhn_number }}
                                            </span>
                                            <span class="text-sm text-gray-700">{{ $resident->family->head_surname }} Family</span>
                                            <span class="text-xs text-gray-500">({{ $resident->household_relationship ?? 'Member' }})</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @else
                                <div class="bg-yellow-50 rounded-lg p-4 mt-3 border border-yellow-200">
                                    <p class="text-sm text-yellow-800">
                                        <span class="font-bold">⚠️ Warning:</span> No household or family data linked yet.
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col gap-2 ml-4">
                        <a href="{{ route('admin.residents.show', $resident) }}" 
                           class="px-4 py-2 bg-sea-green text-white text-sm rounded-lg hover:bg-opacity-90 transition font-bold text-center">
                            👁️ View Details
                        </a>
                        <a href="{{ route('admin.verification.fix-ghost', $resident) }}" 
                           class="px-4 py-2 bg-tiger-orange text-white text-sm rounded-lg hover:bg-opacity-90 transition font-bold text-center">
                            🔧 Fix Linking
                        </a>
                        @if(!$resident->is_verified)
                        <form method="POST" action="{{ route('admin.residents.verify.store', $resident) }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-golden-glow text-white text-sm rounded-lg hover:bg-opacity-90 transition font-bold"
                                    onclick="return confirm('Are you sure you want to verify this resident?')">
                                ✓ Verify
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <div class="text-6xl mb-4">✅</div>
                <p class="text-xl font-bold text-deep-forest mb-2">All Clear!</p>
                <p class="text-gray-500">No auto-linked residents requiring validation at this time.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($residents->hasPages())
        <div class="p-6 border-t border-gray-200">
            {{ $residents->links() }}
        </div>
        @endif
    </div>

    <!-- Info Panel -->
    <div class="mt-8 bg-blue-50 rounded-lg p-6 border border-blue-200">
        <h3 class="text-lg font-bold text-blue-900 mb-3">ℹ️ What are Validation Flags?</h3>
        <div class="text-sm text-blue-800 space-y-2">
            <p>
                <strong>Auto-Linked Residents</strong> are users who were automatically matched to existing household/family records 
                based on their profile information during the onboarding process.
            </p>
            <p>
                <strong>Why validation is needed:</strong> While the auto-linking system is accurate, manual verification ensures 
                that the correct person is linked to the correct household and family unit, preventing data inconsistencies.
            </p>
            <p>
                <strong>Actions you can take:</strong>
            </p>
            <ul class="list-disc list-inside ml-4 space-y-1">
                <li><strong>View Details:</strong> See full resident profile and linked household/family information</li>
                <li><strong>Fix Linking:</strong> Reassign if the automatic link was incorrect</li>
                <li><strong>Verify:</strong> Confirm the link is correct and promote visitor to citizen</li>
            </ul>
        </div>
    </div>
</div>
@endsection
