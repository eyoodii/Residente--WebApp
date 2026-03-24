@extends('layouts.app')

@section('title', 'Profile Setup - Step 4: Details')

@push('styles')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
<div class="bg-gray-50 antialiased font-sans min-h-screen flex flex-col items-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-3xl">
        
        {{-- Header with Progress --}}
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-extrabold text-deep-forest">Profile Setup</h2>
            <p class="text-gray-600 mt-2">Step {{ $currentStep }} of {{ $totalSteps }}: <span class="text-sea-green font-bold">Personal Details</span></p>
            
            {{-- Progress Bar --}}
            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-6 relative overflow-hidden">
                <div class="bg-sea-green h-2.5 rounded-full transition-all duration-500" 
                     style="width: {{ ($currentStep / $totalSteps) * 100 }}%"></div>
            </div>
            <div class="flex justify-between text-xs text-gray-500 mt-2 font-bold uppercase tracking-wide">
                <span class="text-sea-green">Location ✓</span>
                <span class="text-sea-green">Role ✓</span>
                <span class="text-sea-green">Identity ✓</span>
                <span class="text-sea-green">Details</span>
            </div>
        </div>

        {{-- Triple-Key Summary --}}
        <div class="mb-6 bg-gradient-to-r from-sea-green to-deep-forest rounded-lg p-6 text-white">
            <h4 class="text-sm uppercase tracking-wide opacity-80">Your Triple-Key Identifier</h4>
            <div class="mt-4 grid grid-cols-3 gap-4">
                <div class="text-center">
                    <span class="text-xs opacity-70">Geographic (HN)</span>
                    <p class="font-mono font-bold text-lg">{{ $household?->household_number ?? 'N/A' }}</p>
                </div>
                <div class="text-center border-l border-r border-white/20">
                    <span class="text-xs opacity-70">Administrative (HHN)</span>
                    <p class="font-mono font-bold text-lg">{{ $householdHead?->household_head_number ?? 'N/A' }}</p>
                </div>
                <div class="text-center">
                    <span class="text-xs opacity-70">Identity (HHM)</span>
                    <p class="font-mono font-bold text-lg">HHM-{{ str_pad($resident->id, 3, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>

        {{-- Step Content --}}
        <div class="bg-white rounded-xl shadow-xl border-t-4 border-gold overflow-hidden"
             x-data="{ step: 1, maxSteps: 2 }">
            
            <div class="p-8">
                <div class="flex items-center mb-6">
                    <div class="flex-shrink-0 bg-gold rounded-full p-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-bold text-deep-forest">Complete Your Profile</h3>
                        <p class="text-sm text-gray-600">Personal data stored at your individual (HHM) level</p>
                    </div>
                </div>

                <form action="{{ route('profile.setup.details') }}" method="POST">
                    @csrf

                    {{-- Personal Information Section --}}
                    <div x-show="step === 1" x-transition.opacity>
                        <h4 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-6">Personal Information</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Place of Birth --}}
                            <div class="md:col-span-2">
                                <label for="place_of_birth" class="block text-sm font-medium text-gray-700">Place of Birth *</label>
                                <input type="text" name="place_of_birth" id="place_of_birth" required
                                       value="{{ old('place_of_birth', $resident->place_of_birth !== 'Pending Update' ? $resident->place_of_birth : '') }}"
                                       placeholder="e.g., Buguey, Cagayan"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sea-green focus:ring-sea-green sm:text-sm">
                                @error('place_of_birth')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Gender --}}
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700">Gender *</label>
                                <select name="gender" id="gender" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sea-green focus:ring-sea-green sm:text-sm">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender', $resident->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $resident->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender', $resident->gender) === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Civil Status --}}
                            <div>
                                <label for="civil_status" class="block text-sm font-medium text-gray-700">Civil Status *</label>
                                <select name="civil_status" id="civil_status" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sea-green focus:ring-sea-green sm:text-sm">
                                    <option value="">Select Status</option>
                                    <option value="Single" {{ old('civil_status', $resident->civil_status) === 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ old('civil_status', $resident->civil_status) === 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Widowed" {{ old('civil_status', $resident->civil_status) === 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                    <option value="Legally Separated" {{ old('civil_status', $resident->civil_status) === 'Legally Separated' ? 'selected' : '' }}>Legally Separated</option>
                                </select>
                                @error('civil_status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Contact Number --}}
                            <div>
                                <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                                <input type="tel" name="contact_number" id="contact_number"
                                       value="{{ old('contact_number', $resident->contact_number) }}"
                                       placeholder="e.g., 09171234567"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sea-green focus:ring-sea-green sm:text-sm">
                            </div>

                            {{-- Occupation --}}
                            <div>
                                <label for="occupation" class="block text-sm font-medium text-gray-700">Occupation</label>
                                <input type="text" name="occupation" id="occupation"
                                       value="{{ old('occupation', $resident->occupation) }}"
                                       placeholder="e.g., Farmer, Fisher, Teacher"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sea-green focus:ring-sea-green sm:text-sm">
                            </div>

                            {{-- Vulnerable Sector --}}
                            <div class="md:col-span-2">
                                <label for="vulnerable_sector" class="block text-sm font-medium text-gray-700">Vulnerable Sector (if applicable)</label>
                                <select name="vulnerable_sector" id="vulnerable_sector"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sea-green focus:ring-sea-green sm:text-sm">
                                    <option value="">None</option>
                                    <option value="Senior Citizen" {{ old('vulnerable_sector', $resident->vulnerable_sector) === 'Senior Citizen' ? 'selected' : '' }}>Senior Citizen</option>
                                    <option value="PWD" {{ old('vulnerable_sector', $resident->vulnerable_sector) === 'PWD' ? 'selected' : '' }}>Person with Disability (PWD)</option>
                                    <option value="Solo Parent" {{ old('vulnerable_sector', $resident->vulnerable_sector) === 'Solo Parent' ? 'selected' : '' }}>Solo Parent</option>
                                    <option value="Indigenous People" {{ old('vulnerable_sector', $resident->vulnerable_sector) === 'Indigenous People' ? 'selected' : '' }}>Indigenous People</option>
                                    <option value="4Ps Beneficiary" {{ old('vulnerable_sector', $resident->vulnerable_sector) === '4Ps Beneficiary' ? 'selected' : '' }}>4Ps Beneficiary</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="button" @click="step = 2"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-sea-green hover:bg-deep-forest">
                                Next: Housing Info
                                <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Housing & Environment Section --}}
                    <div x-show="step === 2" x-transition.opacity style="display: none;">
                        <h4 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-6">Housing & Environment</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Residential Type --}}
                            <div>
                                <label for="residential_type" class="block text-sm font-medium text-gray-700">Residential Status</label>
                                <select name="residential_type" id="residential_type"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sea-green focus:ring-sea-green sm:text-sm">
                                    <option value="Owned" {{ old('residential_type', $resident->residential_type) === 'Owned' ? 'selected' : '' }}>Owned</option>
                                    <option value="Rented" {{ old('residential_type', $resident->residential_type) === 'Rented' ? 'selected' : '' }}>Rented</option>
                                    <option value="Shared" {{ old('residential_type', $resident->residential_type) === 'Shared' ? 'selected' : '' }}>Shared</option>
                                    <option value="Informal Settler" {{ old('residential_type', $resident->residential_type) === 'Informal Settler' ? 'selected' : '' }}>Informal Settler</option>
                                </select>
                            </div>

                            {{-- House Materials --}}
                            <div>
                                <label for="house_materials" class="block text-sm font-medium text-gray-700">House Materials</label>
                                <select name="house_materials" id="house_materials"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sea-green focus:ring-sea-green sm:text-sm">
                                    <option value="Type A" {{ old('house_materials', $resident->house_materials) === 'Type A' ? 'selected' : '' }}>Type A (Galvanized Iron/Concrete)</option>
                                    <option value="Type B" {{ old('house_materials', $resident->house_materials) === 'Type B' ? 'selected' : '' }}>Type B (Combination)</option>
                                    <option value="Type C" {{ old('house_materials', $resident->house_materials) === 'Type C' ? 'selected' : '' }}>Type C (Light Materials)</option>
                                </select>
                            </div>

                            {{-- Water Source --}}
                            <div>
                                <label for="water_source" class="block text-sm font-medium text-gray-700">Water Source</label>
                                <select name="water_source" id="water_source"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sea-green focus:ring-sea-green sm:text-sm">
                                    <option value="Poso / Jet Matic" {{ old('water_source', $resident->water_source) === 'Poso / Jet Matic' ? 'selected' : '' }}>Poso / Jet Matic</option>
                                    <option value="Balon (Well)" {{ old('water_source', $resident->water_source) === 'Balon (Well)' ? 'selected' : '' }}>Balon (Well)</option>
                                    <option value="Tap / Piped Water" {{ old('water_source', $resident->water_source) === 'Tap / Piped Water' ? 'selected' : '' }}>Tap / Piped Water</option>
                                    <option value="Others" {{ old('water_source', $resident->water_source) === 'Others' ? 'selected' : '' }}>Others</option>
                                </select>
                            </div>

                            {{-- Checkboxes --}}
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="flood_prone" id="flood_prone"
                                           {{ old('flood_prone', $resident->flood_prone) ? 'checked' : '' }}
                                           class="h-4 w-4 text-sea-green rounded border-gray-300 focus:ring-sea-green">
                                    <label for="flood_prone" class="ml-2 block text-sm text-gray-900">
                                        Located in a flood-prone area
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="sanitary_toilet" id="sanitary_toilet"
                                           {{ old('sanitary_toilet', $resident->sanitary_toilet) ? 'checked' : '' }}
                                           class="h-4 w-4 text-sea-green rounded border-gray-300 focus:ring-sea-green">
                                    <label for="sanitary_toilet" class="ml-2 block text-sm text-gray-900">
                                        Has access to a sanitary toilet
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Completion Message --}}
                        <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-green-800">Almost Done!</h4>
                                    <p class="text-sm text-green-700 mt-1">
                                        Click "Complete Profile" to finalize your registration. Your Triple-Key identifier will be activated
                                        and you'll have full access to LGU e-services.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Navigation Buttons --}}
                        <div class="mt-8 flex justify-between">
                            <button type="button" @click="step = 1"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="mr-2 -ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                                </svg>
                                Back to Personal Info
                            </button>
                            <button type="submit"
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-gold hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gold transition-colors">
                                <svg class="mr-2 -ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Complete Profile
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
