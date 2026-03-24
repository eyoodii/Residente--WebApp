@extends('layouts.admin')

@section('title', 'Resident Details - ' . $resident->full_name)

@section('content')
<div class="p-8">
    <!-- Header with Back Button -->
    <div class="mb-8">
        <a href="{{ route('admin.residents.index') }}" class="inline-flex items-center gap-2 text-sea-green hover:text-deep-forest transition mb-4">
            <span>←</span> Back to Residents
        </a>
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-deep-forest flex items-center gap-3">
                    <span>👤</span> {{ $resident->full_name }}
                </h1>
                <p class="text-gray-600 mt-2">Complete resident profile and activity history</p>
            </div>
            <div class="flex gap-3">
                @if(!$resident->is_verified)
                    <a href="{{ route('admin.residents.verify', $resident) }}" class="px-6 py-3 bg-sea-green text-white rounded-lg hover:bg-opacity-90 transition font-bold">
                        ✓ Verify Profile
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Status Badges -->
    <div class="flex gap-3 mb-8">
        @if($resident->is_verified)
            <span class="px-4 py-2 bg-sea-green bg-opacity-10 text-sea-green rounded-lg font-bold">✓ Verified</span>
        @else
            <span class="px-4 py-2 bg-burnt-tangerine bg-opacity-10 text-burnt-tangerine rounded-lg font-bold">⚠️ Unverified</span>
        @endif
        
        <span class="px-4 py-2 bg-deep-forest bg-opacity-10 text-deep-forest rounded-lg font-bold uppercase">{{ $resident->role }}</span>
        
        @if($resident->profile_matched)
            <span class="px-4 py-2 bg-golden-glow bg-opacity-10 text-golden-glow rounded-lg font-bold">✓ Profile Matched</span>
        @endif
        
        @if($resident->is_auto_linked)
            <span class="px-4 py-2 bg-tiger-orange bg-opacity-10 text-tiger-orange rounded-lg font-bold">🔗 Auto-Linked</span>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-deep-forest mb-6 flex items-center gap-2">
                    <span>📋</span> Personal Information
                </h2>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">National ID</p>
                        <p class="text-base font-bold text-deep-forest mt-1">{{ $resident->national_id ?: 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Email</p>
                        <p class="text-base text-deep-forest mt-1">{{ $resident->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Contact Number</p>
                        <p class="text-base text-deep-forest mt-1">{{ $resident->contact_number ?: 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Date of Birth</p>
                        <p class="text-base text-deep-forest mt-1">{{ $resident->date_of_birth ? $resident->date_of_birth->format('F d, Y') : 'N/A' }} 
                            @if($resident->date_of_birth)
                                <span class="text-gray-500 text-sm">({{ $resident->date_of_birth->age }} years old)</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Gender</p>
                        <p class="text-base text-deep-forest mt-1">{{ ucfirst($resident->gender ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Civil Status</p>
                        <p class="text-base text-deep-forest mt-1">{{ ucfirst($resident->civil_status ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Blood Type</p>
                        <p class="text-base text-deep-forest mt-1">{{ strtoupper($resident->blood_type ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Occupation</p>
                        <p class="text-base text-deep-forest mt-1">{{ $resident->occupation ?: 'N/A' }}</p>
                    </div>
                    @if($resident->vulnerable_sector)
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600 font-medium">Vulnerable Sector</p>
                        <p class="text-base text-deep-forest mt-1">{{ $resident->vulnerable_sector }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- PhilSys ID Card - RESTRICTED -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-deep-forest">
                <h2 class="text-xl font-bold text-deep-forest mb-1 flex items-center gap-2">
                    <span>🪪</span> PhilSys ID Card
                    <span class="ml-auto text-xs font-normal px-2 py-1 rounded-full bg-red-100 text-red-700 flex items-center gap-1">
                        🔒 Restricted
                    </span>
                </h2>
                <p class="text-gray-500 text-sm mb-5">Official government-issued identification. Access is logged.</p>

                @if(auth()->user()->role === 'SA')
                    @if($resident->philsys_id_front || $resident->philsys_id_back)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @if($resident->philsys_id_front)
                            <div x-data="{ revealed: false }">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Front Side</p>
                                <div class="relative rounded-xl overflow-hidden border-2 border-gray-200 shadow">
                                    <img
                                        src="{{ route('admin.residents.philsys-image', [$resident, 'front']) }}"
                                        alt="PhilSys ID Front"
                                        class="w-full object-cover transition-all duration-500"
                                        :class="revealed ? 'blur-none' : 'blur-xl'"
                                    >
                                    <div x-show="!revealed" class="absolute inset-0 flex flex-col items-center justify-center bg-black bg-opacity-30 cursor-pointer" @click="revealed = true">
                                        <span class="text-4xl">🔍</span>
                                        <p class="text-white font-bold mt-2 text-sm">Click to reveal</p>
                                    </div>
                                    <button x-show="revealed" @click="revealed = false" class="absolute top-2 right-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded hover:bg-opacity-75 transition">Hide</button>
                                </div>
                            </div>
                            @endif

                            @if($resident->philsys_id_back)
                            <div x-data="{ revealed: false }">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Back Side</p>
                                <div class="relative rounded-xl overflow-hidden border-2 border-gray-200 shadow">
                                    <img
                                        src="{{ route('admin.residents.philsys-image', [$resident, 'back']) }}"
                                        alt="PhilSys ID Back"
                                        class="w-full object-cover transition-all duration-500"
                                        :class="revealed ? 'blur-none' : 'blur-xl'"
                                    >
                                    <div x-show="!revealed" class="absolute inset-0 flex flex-col items-center justify-center bg-black bg-opacity-30 cursor-pointer" @click="revealed = true">
                                        <span class="text-4xl">🔍</span>
                                        <p class="text-white font-bold mt-2 text-sm">Click to reveal</p>
                                    </div>
                                    <button x-show="revealed" @click="revealed = false" class="absolute top-2 right-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded hover:bg-opacity-75 transition">Hide</button>
                                </div>
                            </div>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400 mt-5">⚠️ Every view of this ID is recorded in Activity Logs.</p>
                    @else
                        <div class="text-center py-8 text-gray-400">
                            <span class="text-4xl block mb-2">📭</span>
                            <p class="font-medium">No PhilSys ID uploaded yet.</p>
                            <p class="text-sm mt-1">The resident has not submitted a card image during verification.</p>
                        </div>
                    @endif
                @else
                    <div class="flex items-center gap-4 p-5 bg-red-50 border border-red-200 rounded-xl">
                        <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-3xl">🔐</span>
                        </div>
                        <div>
                            <p class="font-bold text-red-700">Access Restricted</p>
                            <p class="text-sm text-red-500 mt-1">PhilSys ID images are classified and can only be viewed by the <strong>Super Administrator</strong>. This access is strictly logged.</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Address Information -->

            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-deep-forest mb-6 flex items-center gap-2">
                    <span>📍</span> Address Information
                </h2>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Purok/Sitio</p>
                        <p class="text-base text-deep-forest mt-1">{{ $resident->purok ?: 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Barangay</p>
                        <p class="text-base text-deep-forest mt-1">{{ $resident->barangay ?: 'N/A' }}
                            @if($resident->barangay_code)
                                <span class="text-gray-500 text-sm">({{ $resident->barangay_code }})</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Municipality</p>
                        <p class="text-base text-deep-forest mt-1">{{ $resident->municipality ?: 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Province</p>
                        <p class="text-base text-deep-forest mt-1">{{ $resident->province ?: 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Postal Code</p>
                        <p class="text-base text-deep-forest mt-1">{{ $resident->postal_code ?: 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Household Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-deep-forest mb-6 flex items-center gap-2">
                    <span>🏘️</span> Household Information
                </h2>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Household Number (HN)</p>
                        <p class="text-base font-mono font-bold text-deep-forest mt-1">{{ $resident->household_number ?: 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Member Number (HHM)</p>
                        <p class="text-base font-mono font-bold text-deep-forest mt-1">{{ $resident->household_member_number ?: 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Relationship to Household Head</p>
                        <p class="text-base text-deep-forest mt-1">{{ $resident->household_relationship ?: 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Household Head Status</p>
                        <p class="text-base text-deep-forest mt-1">
                            @if($resident->is_household_head)
                                <span class="text-sea-green font-bold">✓ Household Head</span>
                            @else
                                Member
                            @endif
                        </p>
                    </div>
                </div>

                @if($resident->householdProfile)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="font-bold text-deep-forest mb-4">Household Profile Details</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">Housing Type:</p>
                            <p class="font-medium">{{ $resident->householdProfile->housing_type ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Housing Tenure:</p>
                            <p class="font-medium">{{ $resident->householdProfile->housing_tenure ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Service Requests -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-deep-forest mb-6 flex items-center gap-2">
                    <span>📄</span> Service Requests
                    <span class="text-sm font-normal text-gray-500 ml-auto">{{ $resident->serviceRequests->count() }} total</span>
                </h2>
                @forelse($resident->serviceRequests as $request)
                <div class="mb-4 p-4 border border-gray-200 rounded-lg">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-deep-forest">{{ $request->service->service_name }}</h3>
                        <span class="px-3 py-1 text-xs rounded-full font-bold
                            {{ $request->status === 'completed' ? 'bg-sea-green text-white' : '' }}
                            {{ $request->status === 'pending' ? 'bg-golden-glow text-deep-forest' : '' }}
                            {{ $request->status === 'processing' ? 'bg-tiger-orange text-white' : '' }}
                            {{ $request->status === 'ready-for-pickup' ? 'bg-deep-forest text-white' : '' }}">
                            {{ strtoupper($request->status) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600">Tracking: <span class="font-mono font-bold">{{ $request->tracking_number }}</span></p>
                    <p class="text-xs text-gray-500 mt-1">Submitted: {{ $request->created_at->format('M d, Y g:i A') }}</p>
                </div>
                @empty
                <p class="text-gray-500 text-center py-8">No service requests yet</p>
                @endforelse
            </div>

            <!-- Activity Logs -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-deep-forest mb-6 flex items-center gap-2">
                    <span>📊</span> Recent Activity
                </h2>
                @forelse($resident->activityLogs as $log)
                <div class="mb-4 pb-4 border-b border-gray-200 last:border-0">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">
                            @if($log->action === 'login')
                                🔑
                            @elseif($log->action === 'profile_update')
                                ✏️
                            @elseif($log->action === 'service_request')
                                📄
                            @else
                                📌
                            @endif
                        </span>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-deep-forest">{{ $log->description }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $log->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-8">No activity logs yet</p>
                @endforelse
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Account Status -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-bold text-deep-forest mb-4">Account Status</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Email Verified:</span>
                        <span class="font-bold {{ $resident->email_verified_at ? 'text-sea-green' : 'text-burnt-tangerine' }}">
                            {{ $resident->email_verified_at ? '✓ Yes' : '✗ No' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Profile Verified:</span>
                        <span class="font-bold {{ $resident->is_verified ? 'text-sea-green' : 'text-burnt-tangerine' }}">
                            {{ $resident->is_verified ? '✓ Yes' : '✗ No' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Profile Matched:</span>
                        <span class="font-bold {{ $resident->profile_matched ? 'text-sea-green' : 'text-gray-400' }}">
                            {{ $resident->profile_matched ? '✓ Yes' : '✗ No' }}
                        </span>
                    </div>
                    @if($resident->profile_matched_at)
                    <div class="pt-3 border-t border-gray-200">
                        <p class="text-xs text-gray-500">Matched on:</p>
                        <p class="text-sm font-medium">{{ $resident->profile_matched_at->format('M d, Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-bold text-deep-forest mb-4">Quick Stats</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Requests:</span>
                        <span class="font-bold text-deep-forest">{{ $resident->serviceRequests->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Completed:</span>
                        <span class="font-bold text-sea-green">{{ $resident->serviceRequests->where('status', 'completed')->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Pending:</span>
                        <span class="font-bold text-golden-glow">{{ $resident->serviceRequests->whereIn('status', ['pending', 'processing'])->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Account Dates -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-bold text-deep-forest mb-4">Account Info</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600">Registered:</p>
                        <p class="font-medium">{{ $resident->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $resident->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <p class="text-gray-600">Last Updated:</p>
                        <p class="font-medium">{{ $resident->updated_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $resident->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-bold text-deep-forest mb-4">Actions</h3>
                <div class="space-y-2">
                    @if(!$resident->is_verified)
                    <a href="{{ route('admin.residents.verify', $resident) }}" class="block w-full px-4 py-2 bg-sea-green text-white rounded-lg hover:bg-opacity-90 transition text-center font-bold">
                        ✓ Verify Profile
                    </a>
                    @endif
                    <a href="{{ route('admin.residents.index') }}" class="block w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-center font-medium">
                        ← Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
