@extends('layouts.app')

@section('title', 'Profile Setup - Step 1: Location')

@push('styles')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
<div class="bg-gray-50 antialiased font-sans min-h-screen flex flex-col items-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-2xl">
        
        {{-- Header with Progress --}}
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-extrabold text-deep-forest">Profile Setup</h2>
            <p class="text-gray-600 mt-2">Step {{ $currentStep }} of {{ $totalSteps }}: <span class="text-sea-green font-bold">Location</span></p>
            
            {{-- Progress Bar --}}
            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-6 relative overflow-hidden">
                <div class="bg-sea-green h-2.5 rounded-full transition-all duration-500" 
                     style="width: {{ ($currentStep / $totalSteps) * 100 }}%"></div>
            </div>
            <div class="flex justify-between text-xs text-gray-500 mt-2 font-bold uppercase tracking-wide">
                <span class="text-sea-green">Location</span>
                <span>Role</span>
                <span>Identity</span>
                <span>Details</span>
            </div>
        </div>

        {{-- Step Content --}}
        <div class="bg-white rounded-xl shadow-xl border-t-4 border-sea-green overflow-hidden"
             x-data="addressChecker()">
            
            <div class="p-8">
                <div class="flex items-center mb-6">
                    <div class="flex-shrink-0 bg-sea-green rounded-full p-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-bold text-deep-forest">Geographic Key (HN)</h3>
                        <p class="text-sm text-gray-600">Enter your home address to assign your Household Number</p>
                    </div>
                </div>

                <form action="{{ route('profile.setup.location') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Barangay --}}
                        <div class="md:col-span-2">
                            <label for="barangay" class="block text-sm font-medium text-gray-700">Barangay *</label>
                            <select name="barangay" id="barangay" required
                                    x-on:change="checkAddress()"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sea-green focus:ring-sea-green sm:text-sm">
                                <option value="">Select Barangay</option>
                                @foreach(config('barangays.list', []) as $name => $code)
                                    <option value="{{ $name }}" {{ old('barangay', $resident->barangay) === $name ? 'selected' : '' }}>
                                        {{ $name }} ({{ $code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('barangay')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Purok --}}
                        <div>
                            <label for="purok" class="block text-sm font-medium text-gray-700">Purok *</label>
                            <input type="text" name="purok" id="purok" required
                                   x-on:blur="checkAddress()"
                                   value="{{ old('purok', $resident->purok !== 'Pending Update' ? $resident->purok : '') }}"
                                   placeholder="e.g., Purok 1, Centro"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sea-green focus:ring-sea-green sm:text-sm">
                            @error('purok')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Street --}}
                        <div>
                            <label for="street" class="block text-sm font-medium text-gray-700">Street</label>
                            <input type="text" name="street" id="street"
                                   x-on:blur="checkAddress()"
                                   value="{{ old('street') }}"
                                   placeholder="e.g., Rizal St."
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sea-green focus:ring-sea-green sm:text-sm">
                        </div>

                        {{-- House Number --}}
                        <div>
                            <label for="house_number" class="block text-sm font-medium text-gray-700">House/Lot Number</label>
                            <input type="text" name="house_number" id="house_number"
                                   x-on:blur="checkAddress()"
                                   value="{{ old('house_number') }}"
                                   placeholder="e.g., 123"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sea-green focus:ring-sea-green sm:text-sm">
                        </div>

                        {{-- Municipality (pre-filled) --}}
                        <div>
                            <label for="municipality" class="block text-sm font-medium text-gray-700">Municipality</label>
                            <input type="text" name="municipality" id="municipality"
                                   value="{{ old('municipality', 'Buguey') }}"
                                   readonly
                                   class="mt-1 block w-full rounded-md border-gray-200 bg-gray-50 shadow-sm sm:text-sm">
                        </div>
                    </div>

                    {{-- Address Preview / HN Assignment --}}
                    <div class="mt-6 p-4 rounded-lg border-2 transition-all duration-300"
                         :class="addressInfo.exists ? 'bg-blue-50 border-blue-200' : 'bg-green-50 border-green-200'">
                        <div x-show="addressInfo.message" class="flex items-start">
                            <div class="flex-shrink-0">
                                <template x-if="addressInfo.exists">
                                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </template>
                                <template x-if="!addressInfo.exists">
                                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </template>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium" :class="addressInfo.exists ? 'text-blue-800' : 'text-green-800'" x-text="addressInfo.message"></p>
                                <p x-show="addressInfo.household_number" class="text-xs text-gray-600 mt-1">
                                    Household Number: <span class="font-mono font-bold" x-text="addressInfo.household_number"></span>
                                </p>
                            </div>
                        </div>
                        <div x-show="!addressInfo.message" class="text-sm text-gray-500 italic">
                            Enter your address to see your Household Number assignment.
                        </div>
                    </div>

                    {{-- Info Box --}}
                    <div class="mt-6 bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-amber-800">What is a Household Number (HN)?</h4>
                                <p class="text-sm text-amber-700 mt-1">
                                    The HN is your <strong>Geographic Key</strong> - it identifies your physical address. 
                                    All residents living at the same address share the same HN, even if they belong to different families.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-sea-green hover:bg-deep-forest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sea-green transition-colors">
                            Continue to Step 2
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

@push('scripts')
<script>
function addressChecker() {
    return {
        addressInfo: {
            exists: false,
            household_number: null,
            message: null
        },
        debounceTimer: null,
        
        checkAddress() {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => this.doCheck(), 500);
        },
        
        async doCheck() {
            const purok = document.getElementById('purok').value;
            const barangay = document.getElementById('barangay').value;
            const houseNumber = document.getElementById('house_number').value;
            const street = document.getElementById('street').value;
            
            if (!purok || !barangay) {
                this.addressInfo = { exists: false, household_number: null, message: null };
                return;
            }
            
            try {
                const response = await fetch('{{ route("profile.setup.check-address") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ purok, barangay, house_number: houseNumber, street })
                });
                
                const data = await response.json();
                this.addressInfo = {
                    exists: data.exists,
                    household_number: data.household_number,
                    message: data.message
                };
            } catch (error) {
                console.error('Address check failed:', error);
            }
        }
    }
}
</script>
@endpush
@endsection
