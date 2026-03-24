@extends('layouts.app')

@section('title', 'Profile Setup - Step 3: Identity')

@push('styles')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
<div class="bg-gray-50 antialiased font-sans min-h-screen flex flex-col items-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-2xl">
        
        {{-- Header with Progress --}}
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-extrabold text-deep-forest">Profile Setup</h2>
            <p class="text-gray-600 mt-2">Step {{ $currentStep }} of {{ $totalSteps }}: <span class="text-sea-green font-bold">Identity</span></p>
            
            {{-- Progress Bar --}}
            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-6 relative overflow-hidden">
                <div class="bg-sea-green h-2.5 rounded-full transition-all duration-500" 
                     style="width: {{ ($currentStep / $totalSteps) * 100 }}%"></div>
            </div>
            <div class="flex justify-between text-xs text-gray-500 mt-2 font-bold uppercase tracking-wide">
                <span class="text-sea-green">Location ✓</span>
                <span class="text-sea-green">Role ✓</span>
                <span class="text-sea-green">Identity</span>
                <span>Details</span>
            </div>
        </div>

        {{-- Step Content --}}
        <div class="bg-white rounded-xl shadow-xl border-t-4 border-deep-forest overflow-hidden">
            
            <div class="p-8">
                <div class="flex items-center mb-6">
                    <div class="flex-shrink-0 bg-deep-forest rounded-full p-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-bold text-deep-forest">Identity Key (HHM)</h3>
                        <p class="text-sm text-gray-600">Confirm your relationship within the household</p>
                    </div>
                </div>

                <form action="{{ route('profile.setup.identity') }}" method="POST">
                    @csrf

                    {{-- Identity Preview --}}
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wide">Full Name</span>
                                <p class="text-lg font-semibold text-gray-900">{{ $resident->full_name }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 uppercase tracking-wide">Surname</span>
                                <p class="text-lg font-semibold text-gray-900">{{ $resident->last_name }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Relationship to Head --}}
                    <div class="mb-6">
                        <label for="relationship" class="block text-sm font-medium text-gray-700">
                            Relationship to Household Head *
                        </label>
                        <select name="relationship" id="relationship" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sea-green focus:ring-sea-green sm:text-sm">
                            <option value="">Select Relationship</option>
                            @if($resident->is_household_head)
                                <option value="Self (Head)" selected>Self (Head of Household)</option>
                            @else
                                <option value="Spouse">Spouse</option>
                                <option value="Son">Son</option>
                                <option value="Daughter">Daughter</option>
                                <option value="Father">Father</option>
                                <option value="Mother">Mother</option>
                                <option value="Brother">Brother</option>
                                <option value="Sister">Sister</option>
                                <option value="Grandchild">Grandchild</option>
                                <option value="Grandparent">Grandparent</option>
                                <option value="In-law">In-law</option>
                                <option value="Nephew/Niece">Nephew/Niece</option>
                                <option value="Other Relative">Other Relative</option>
                                <option value="Non-Relative">Non-Relative</option>
                            @endif
                        </select>
                        @error('relationship')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Info Box --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-blue-800">About Your Identity Key (HHM)</h4>
                                <p class="text-sm text-blue-700 mt-1">
                                    Your HHM number uniquely identifies you within the LGU system while anchoring you to your family unit.
                                    This enables individual tracking of services and benefits while maintaining family-level census data.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Navigation Buttons --}}
                    <div class="mt-8 flex justify-between">
                        <a href="{{ route('profile.setup', ['step' => 2]) }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="mr-2 -ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                            </svg>
                            Back
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-sea-green hover:bg-deep-forest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sea-green transition-colors">
                            Continue to Final Step
                            <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
