@extends('layouts.admin')

@section('title', 'Verify Resident - ' . $resident->full_name)

@section('content')
<div class="p-8">
    <!-- Header with Back Button -->
    <div class="mb-8">
        <a href="{{ route('admin.residents.show', $resident) }}" class="inline-flex items-center gap-2 text-sea-green hover:text-deep-forest transition mb-4">
            <span>←</span> Back to Resident Details
        </a>
        <h1 class="text-3xl font-bold text-deep-forest flex items-center gap-3">
            <span>✓</span> Verify Resident Profile
        </h1>
        <p class="text-gray-600 mt-2">Verify and upgrade {{ $resident->full_name }} to Citizen role</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Verification Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-deep-forest mb-6">Verification Details</h2>
                
                <form method="POST" action="{{ route('admin.residents.verify', $resident) }}">
                    @csrf
                    
                    <!-- Verification Method -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Verification Method *</label>
                        <div class="space-y-3">
                            <label class="flex items-start gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-sea-green transition">
                                <input type="radio" name="verification_method" value="manual" class="mt-1" required>
                                <div>
                                    <p class="font-bold text-deep-forest">Manual Verification</p>
                                    <p class="text-sm text-gray-600">Admin manually verified documents and identity</p>
                                </div>
                            </label>
                            
                            <label class="flex items-start gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-sea-green transition">
                                <input type="radio" name="verification_method" value="auto" class="mt-1" required>
                                <div>
                                    <p class="font-bold text-deep-forest">Automatic Verification</p>
                                    <p class="text-sm text-gray-600">System matched resident with household records</p>
                                </div>
                            </label>
                            
                            <label class="flex items-start gap-3 p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-sea-green transition">
                                <input type="radio" name="verification_method" value="biometric" class="mt-1" required>
                                <div>
                                    <p class="font-bold text-deep-forest">Biometric Verification</p>
                                    <p class="text-sm text-gray-600">Verified using biometric ID scanner or facial recognition</p>
                                </div>
                            </label>
                        </div>
                        @error('verification_method')
                        <p class="text-burnt-tangerine text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Verification Notes (Optional)</label>
                        <textarea name="notes" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent" placeholder="Add any additional notes about this verification..."></textarea>
                        @error('notes')
                        <p class="text-burnt-tangerine text-sm mt-2">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-2">These notes will be logged for audit purposes</p>
                    </div>

                    <!-- Warning Box -->
                    <div class="bg-golden-glow bg-opacity-10 border border-golden-glow rounded-lg p-4 mb-6">
                        <h3 class="font-bold text-deep-forest mb-2">⚠️ Important</h3>
                        <ul class="text-sm text-gray-700 space-y-1 list-disc list-inside">
                            <li>This action will upgrade the resident's role from <strong>Visitor</strong> to <strong>Citizen</strong></li>
                            <li>Citizens will have full access to all barangay e-services</li>
                            <li>This action will be logged and cannot be easily undone</li>
                            <li>The resident will be notified of their verified status</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <button type="submit" class="px-6 py-3 bg-sea-green text-white rounded-lg hover:bg-opacity-90 transition font-bold">
                            ✓ Verify and Upgrade to Citizen
                        </button>
                        <a href="{{ route('admin.residents.show', $resident) }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar - Resident Summary -->
        <div class="space-y-6">
            <!-- Resident Info Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-bold text-deep-forest mb-4">Resident Summary</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600">Name:</p>
                        <p class="font-bold text-deep-forest">{{ $resident->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Email:</p>
                        <p class="font-medium">{{ $resident->email }}</p>
                    </div>
                    @if($resident->national_id)
                    <div>
                        <p class="text-gray-600">National ID:</p>
                        <p class="font-medium font-mono">{{ $resident->national_id }}</p>
                    </div>
                    @endif
                    @if($resident->date_of_birth)
                    <div>
                        <p class="text-gray-600">Date of Birth:</p>
                        <p class="font-medium">{{ $resident->date_of_birth->format('F d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $resident->date_of_birth->age }} years old</p>
                    </div>
                    @endif
                    @if($resident->barangay)
                    <div>
                        <p class="text-gray-600">Barangay:</p>
                        <p class="font-medium">{{ $resident->barangay }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Current Status -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-bold text-deep-forest mb-4">Current Status</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Role:</span>
                        <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded font-bold text-xs uppercase">{{ $resident->role }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Verified:</span>
                        <span class="font-bold {{ $resident->is_verified ? 'text-sea-green' : 'text-burnt-tangerine' }}">
                            {{ $resident->is_verified ? '✓ Yes' : '✗ No' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Profile Matched:</span>
                        <span class="font-bold {{ $resident->profile_matched ? 'text-sea-green' : 'text-gray-400' }}">
                            {{ $resident->profile_matched ? '✓ Yes' : '✗ No' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- After Verification -->
            <div class="bg-sea-green bg-opacity-10 border border-sea-green rounded-lg p-6">
                <h3 class="font-bold text-deep-forest mb-3">After Verification</h3>
                <ul class="text-sm space-y-2">
                    <li class="flex items-start gap-2">
                        <span class="text-sea-green">✓</span>
                        <span>Role upgraded to <strong>Citizen</strong></span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-sea-green">✓</span>
                        <span>Profile marked as <strong>Verified</strong></span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-sea-green">✓</span>
                        <span>Full access to all services</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-sea-green">✓</span>
                        <span>Notification sent to resident</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
