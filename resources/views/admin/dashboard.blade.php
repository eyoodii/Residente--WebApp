@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('subtitle', 'System Overview & Statistics')

@section('content')
<div class="p-7">

    <!-- Statistics Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Residents -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-sea-green">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Residents</p>
                    <p class="text-3xl font-bold text-deep-forest mt-2">{{ number_format($totalResidents) }}</p>
                </div>
                <div class="bg-sea-green bg-opacity-10 p-3 rounded-lg">
                    <span class="text-2xl">👥</span>
                </div>
            </div>
            <div class="mt-4 flex gap-4 text-xs">
                <div>
                    <span class="text-gray-500">Citizens:</span>
                    <span class="font-bold text-sea-green">{{ $citizenCount }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Visitors:</span>
                    <span class="font-bold text-tiger-orange">{{ $visitorCount }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Admins:</span>
                    <span class="font-bold text-deep-forest">{{ $adminCount }}</span>
                </div>
            </div>
        </div>

        <!-- Pending Verification -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-tiger-orange">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Pending Verification</p>
                    <p class="text-3xl font-bold text-deep-forest mt-2">{{ number_format($pendingVerification) }}</p>
                </div>
                <div class="bg-tiger-orange bg-opacity-10 p-3 rounded-lg">
                    <span class="text-2xl">⏳</span>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">Visitors awaiting physical verification</p>
        </div>

        <!-- Service Requests -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-golden-glow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Service Requests</p>
                    <p class="text-3xl font-bold text-deep-forest mt-2">{{ number_format($totalRequests) }}</p>
                </div>
                <div class="bg-golden-glow bg-opacity-10 p-3 rounded-lg">
                    <span class="text-2xl">📋</span>
                </div>
            </div>
            <div class="mt-4 flex gap-3 text-xs">
                <div>
                    <span class="text-gray-500">Pending:</span>
                    <span class="font-bold">{{ $pendingRequests }}</span>
                </div>
                <div>
                    <span class="text-gray-500">In Progress:</span>
                    <span class="font-bold">{{ $inProgressRequests }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Ready:</span>
                    <span class="font-bold text-sea-green">{{ $readyForPickup }}</span>
                </div>
            </div>
        </div>

        <!-- Today's Activities -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-burnt-tangerine">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Today's Activities</p>
                    <p class="text-3xl font-bold text-deep-forest mt-2">{{ number_format($todayActivities) }}</p>
                </div>
                <div class="bg-burnt-tangerine bg-opacity-10 p-3 rounded-lg">
                    <span class="text-2xl">📊</span>
                </div>
            </div>
            <div class="mt-4 flex gap-3 text-xs">
                <div>
                    <span class="text-gray-500">Suspicious:</span>
                    <span class="font-bold text-burnt-tangerine">{{ $suspiciousActivities }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Critical:</span>
                    <span class="font-bold text-red-600">{{ $criticalActivities }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Registration Trends -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-deep-forest mb-4 flex items-center gap-2">
                <span>📈</span> Registration Trends
            </h3>
            <div class="space-y-3">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Last 7 Days</span>
                        <span class="font-bold text-sea-green">{{ $recentRegistrations }} new</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-sea-green h-2 rounded-full" style="width: {{ min(($recentRegistrations / max($totalResidents, 1)) * 100, 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Household Overview -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-deep-forest mb-4 flex items-center gap-2">
                <span>🏘️</span> Household Overview
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Profiled Households</span>
                    <span class="text-2xl font-bold text-deep-forest">{{ number_format($householdsWithProfiles) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Low Income Households</span>
                    <span class="text-2xl font-bold text-tiger-orange">{{ number_format($lowIncomeHouseholds) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Activities -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-deep-forest mb-4 flex items-center justify-between">
                <span class="flex items-center gap-2"><span>📝</span> Recent Activities</span>
                <a href="{{ route('admin.activity-logs.index') }}" class="text-xs text-sea-green hover:underline">View All →</a>
            </h3>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($recentActivities as $activity)
                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex-shrink-0 w-2 h-2 rounded-full mt-2 {{ $activity->severity === 'critical' ? 'bg-red-500' : ($activity->severity === 'warning' ? 'bg-tiger-orange' : 'bg-sea-green') }}"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $activity->description }}</p>
                        <div class="flex gap-3 mt-1">
                            <span class="text-xs text-gray-500">{{ $activity->user_email }}</span>
                            <span class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-8">No activities recorded yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Pending Verification -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-deep-forest mb-4 flex items-center justify-between">
                <span class="flex items-center gap-2"><span>⏳</span> Pending Verification</span>
                <a href="{{ route('admin.residents.index') }}?role=visitor" class="text-xs text-sea-green hover:underline">View All →</a>
            </h3>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($residentsNeedingVerification as $resident)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $resident->full_name }}</p>
                        <p class="text-xs text-gray-500">{{ $resident->email }} • {{ $resident->barangay }}</p>
                    </div>
                    <a href="{{ route('admin.residents.show', $resident) }}" class="px-3 py-1 bg-sea-green text-white text-xs rounded hover:bg-opacity-90 transition">
                        Verify
                    </a>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-8">No pending verifications.</p>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
