@extends('layouts.admin')

@section('title', 'Individual Profile')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('admin.households.index') }}" class="text-blue-600 hover:text-blue-800">Households</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li><a href="{{ route('admin.households.search.individual') }}" class="text-blue-600 hover:text-blue-800">Individual Search</a></li>
                <li><span class="text-gray-500">/</span></li>
                <li class="text-gray-700">Profile</li>
            </ol>
        </nav>

        @if($type === 'resident')
            <!-- Resident Profile -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-8">
                    <div class="flex items-center">
                        <div class="h-20 w-20 rounded-full bg-white flex items-center justify-center">
                            <span class="text-blue-600 font-bold text-2xl">
                                {{ substr($individual->first_name, 0, 1) }}{{ substr($individual->last_name, 0, 1) }}
                            </span>
                        </div>
                        <div class="ml-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 mb-2">
                                Registered Resident
                            </span>
                            <h1 class="text-2xl font-bold text-white">{{ $individual->full_name }}</h1>
                            <p class="text-blue-100">{{ $individual->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Ancestry Chain -->
                <div class="px-6 py-4 bg-gray-50 border-b">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">HOUSEHOLD ANCESTRY</h3>
                    <div class="flex items-center space-x-4 overflow-x-auto">
                        @if($individual->household)
                            <a href="{{ route('admin.households.show', $individual->household) }}" class="flex items-center px-4 py-2 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors">
                                <span class="text-sm font-medium text-blue-800">HN: {{ $individual->household->household_number }}</span>
                            </a>
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        @endif
                        @if($individual->householdHeadRelation)
                            <a href="{{ route('admin.households.head.show', $individual->householdHeadRelation) }}" class="flex items-center px-4 py-2 bg-green-100 rounded-lg hover:bg-green-200 transition-colors">
                                <span class="text-sm font-medium text-green-800">HHN: {{ $individual->householdHeadRelation->household_head_number }}</span>
                            </a>
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        @endif
                        <span class="px-4 py-2 bg-purple-100 rounded-lg">
                            <span class="text-sm font-medium text-purple-800">{{ $individual->full_name }}</span>
                        </span>
                    </div>
                </div>

                <!-- Details -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Personal Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm text-gray-500">National ID</dt>
                                    <dd class="text-gray-900">{{ $individual->national_id ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Date of Birth</dt>
                                    <dd class="text-gray-900">{{ $individual->date_of_birth?->format('F d, Y') }} ({{ $individual->age }} years old)</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Gender</dt>
                                    <dd class="text-gray-900">{{ $individual->gender }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Civil Status</dt>
                                    <dd class="text-gray-900">{{ $individual->civil_status }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Contact Number</dt>
                                    <dd class="text-gray-900">{{ $individual->contact_number ?? 'Not provided' }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Address & Household</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm text-gray-500">Full Address</dt>
                                    <dd class="text-gray-900">
                                        {{ $individual->household?->full_address ?? "{$individual->purok}, {$individual->barangay}, {$individual->municipality}" }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Household Number (HN)</dt>
                                    <dd class="text-gray-900">{{ $individual->household?->household_number ?? 'Not assigned' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Family Unit (HHN)</dt>
                                    <dd class="text-gray-900">{{ $individual->householdHeadRelation?->household_head_number ?? 'Not assigned' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Role in Household</dt>
                                    <dd class="text-gray-900">
                                        @if($individual->is_household_head)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Household Head
                                            </span>
                                        @else
                                            {{ $individual->household_relationship ?? 'Member' }}
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Occupation</dt>
                                    <dd class="text-gray-900">{{ $individual->occupation ?? 'Not provided' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Household Member Profile -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-purple-600 to-purple-800 px-6 py-8">
                    <div class="flex items-center">
                        <div class="h-20 w-20 rounded-full bg-white flex items-center justify-center">
                            <span class="text-purple-600 font-bold text-2xl">
                                {{ substr($individual->first_name, 0, 1) }}{{ substr($individual->last_name, 0, 1) }}
                            </span>
                        </div>
                        <div class="ml-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 mb-2">
                                Household Member
                            </span>
                            <h1 class="text-2xl font-bold text-white">{{ $individual->full_name }}</h1>
                            <p class="text-purple-100">{{ $individual->member_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Ancestry Chain -->
                <div class="px-6 py-4 bg-gray-50 border-b">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">HOUSEHOLD ANCESTRY</h3>
                    <div class="flex items-center space-x-4 overflow-x-auto">
                        @if($individual->householdHead?->household)
                            <a href="{{ route('admin.households.show', $individual->householdHead->household) }}" class="flex items-center px-4 py-2 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors">
                                <span class="text-sm font-medium text-blue-800">HN: {{ $individual->householdHead->household->household_number }}</span>
                            </a>
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        @endif
                        @if($individual->householdHead)
                            <a href="{{ route('admin.households.head.show', $individual->householdHead) }}" class="flex items-center px-4 py-2 bg-green-100 rounded-lg hover:bg-green-200 transition-colors">
                                <span class="text-sm font-medium text-green-800">HHN: {{ $individual->householdHead->household_head_number }}</span>
                            </a>
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        @endif
                        <span class="px-4 py-2 bg-purple-100 rounded-lg">
                            <span class="text-sm font-medium text-purple-800">{{ $individual->member_number ?? 'N/A' }}</span>
                        </span>
                    </div>
                </div>

                <!-- Details -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Personal Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm text-gray-500">Full Name</dt>
                                    <dd class="text-gray-900">{{ $individual->full_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Date of Birth</dt>
                                    <dd class="text-gray-900">{{ $individual->date_of_birth?->format('F d, Y') }} ({{ $individual->age }} years old)</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Gender</dt>
                                    <dd class="text-gray-900">{{ $individual->gender }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Civil Status</dt>
                                    <dd class="text-gray-900">{{ $individual->civil_status }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Relationship to Head</dt>
                                    <dd class="text-gray-900">{{ $individual->relationship }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Household Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm text-gray-500">Household Head</dt>
                                    <dd class="text-gray-900">{{ $individual->householdHead?->resident?->full_name ?? 'Unknown' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Full Address</dt>
                                    <dd class="text-gray-900">{{ $individual->householdHead?->household?->full_address ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Occupation</dt>
                                    <dd class="text-gray-900">{{ $individual->occupation ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Registration Status</dt>
                                    <dd>
                                        @if($individual->linkedResident)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Linked to Account
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Not Registered
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                            
                            <!-- Special Categories -->
                            <h3 class="text-lg font-semibold mt-6 mb-4">Special Categories</h3>
                            <div class="flex flex-wrap gap-2">
                                @if($individual->is_pwd)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">PWD</span>
                                @endif
                                @if($individual->is_senior_citizen)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">Senior Citizen</span>
                                @endif
                                @if($individual->is_solo_parent)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-pink-100 text-pink-800">Solo Parent</span>
                                @endif
                                @if($individual->is_4ps_beneficiary)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">4Ps Beneficiary</span>
                                @endif
                                @if($individual->is_active_ofw)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">Active OFW</span>
                                @endif
                                @if(!$individual->is_pwd && !$individual->is_senior_citizen && !$individual->is_solo_parent && !$individual->is_4ps_beneficiary && !$individual->is_active_ofw)
                                    <span class="text-gray-500">None</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('admin.households.search.individual') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Search
            </a>
        </div>
    </div>
</div>
@endsection
