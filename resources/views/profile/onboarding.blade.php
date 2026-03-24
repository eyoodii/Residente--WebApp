@extends('layouts.app')

@section('title', 'Profile Setup')

@push('styles')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
<div class="bg-gray-50 antialiased font-sans min-h-screen flex flex-col items-center py-12 px-4 sm:px-6 lg:px-8">

    <div class="w-full max-w-3xl" x-data="{ step: 1, maxSteps: 5 }">
        
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-extrabold text-deep-forest">Complete Your Resident Profile</h2>
            <p class="text-gray-600 mt-2">Account Status: <span class="text-tiger-orange font-bold">In Progress</span></p>
            
            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-6 relative overflow-hidden">
                <div class="bg-sea-green h-2.5 rounded-full transition-all duration-500" :style="'width: ' + ((step / maxSteps) * 100) + '%'"></div>
            </div>
            <div class="flex justify-between text-xs text-gray-500 mt-2 font-bold uppercase tracking-wide">
                <span :class="step >= 1 ? 'text-sea-green' : ''">Housing</span>
                <span :class="step >= 2 ? 'text-sea-green' : ''">Agriculture</span>
                <span :class="step >= 3 ? 'text-sea-green' : ''">Aquaculture</span>
                <span :class="step >= 4 ? 'text-sea-green' : ''">Livestock</span>
                <span :class="step >= 5 ? 'text-sea-green' : ''">Fisheries</span>
            </div>
        </div>

        <form action="{{ route('profile.onboarding.store') }}" method="POST" class="bg-white rounded-xl shadow-xl border-t-4 border-tiger-orange overflow-hidden">
            @csrf

            {{-- Step 1: Housing & Sanitation --}}
            <div x-show="step === 1" x-transition.opacity class="p-8 space-y-6">
                <h3 class="text-xl font-bold text-deep-forest border-b pb-2">Step 1: Housing & Sanitation</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Residential Status</label>
                        <select name="residential_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sea-green focus:ring-sea-green sm:text-sm">
                            <option>Owned</option>
                            <option>Rented</option>
                            <option>Shared</option>
                            <option>Informal Settler</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">House Materials</label>
                        <select name="house_materials" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sea-green focus:ring-sea-green sm:text-sm">
                            <option value="Type A">Type A (Galvanized Iron/Concrete)</option>
                            <option value="Type B">Type B (Combination Light/Concrete)</option>
                            <option value="Type C">Type C (Light Materials Only)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Water Source</label>
                        <select name="water_source" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-sea-green focus:ring-sea-green sm:text-sm">
                            <option>Poso / Jet Matic</option>
                            <option>Balon (Well)</option>
                            <option>Tap / Piped Water</option>
                            <option>Others</option>
                        </select>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="flood_prone" class="h-4 w-4 text-sea-green rounded border-gray-300">
                            <label class="ml-2 block text-sm text-gray-900">Located in a flood-prone area</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="sanitary_toilet" class="h-4 w-4 text-sea-green rounded border-gray-300">
                            <label class="ml-2 block text-sm text-gray-900">Has access to a sanitary toilet</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 2: Agriculture (Crops) --}}
            <div x-show="step === 2" x-transition.opacity class="p-8 space-y-6" style="display: none;">
                <h3 class="text-xl font-bold text-deep-forest border-b pb-2">Step 2: Agriculture (Crops)</h3>
                <p class="text-sm text-gray-600">Select the crops you actively farm or manage.</p>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach(['Vegetables', 'Ginger', 'Banana', 'Mango', 'Pineapple', 'Citrus', 'Mungbean', 'Peanut', 'Coconut', 'Coffee', 'Cacao'] as $crop)
                    <label class="inline-flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer transition">
                        <input type="checkbox" name="crops[]" value="{{ $crop }}" class="h-4 w-4 text-sea-green rounded border-gray-300">
                        <span class="ml-2 text-sm text-gray-700 font-medium">{{ $crop }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Step 3: Aquaculture --}}
            <div x-show="step === 3" x-transition.opacity class="p-8 space-y-6" style="display: none;">
                <h3 class="text-xl font-bold text-deep-forest border-b pb-2">Step 3: Aquaculture</h3>
                <p class="text-sm text-gray-600">Select your marine farming activities.</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach(['Fishpond', 'Fishcage', 'Oyster (Raft/Broadcast)', 'Seaweed'] as $aqua)
                    <label class="inline-flex items-center p-4 border rounded-lg hover:bg-gray-50 cursor-pointer transition">
                        <input type="checkbox" name="aquaculture[]" value="{{ $aqua }}" class="h-5 w-5 text-sea-green rounded border-gray-300">
                        <span class="ml-3 text-md text-gray-700 font-bold">{{ $aqua }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Step 4: Livestock & Poultry --}}
            <div x-show="step === 4" x-transition.opacity class="p-8 space-y-6" style="display: none;">
                <h3 class="text-xl font-bold text-deep-forest border-b pb-2">Step 4: Livestock & Poultry</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h4 class="font-bold text-tiger-orange mb-3">Large Ruminants</h4>
                        <div class="space-y-2 flex flex-col">
                            <label class="inline-flex items-center"><input type="checkbox" name="livestock[]" value="Carabao" class="text-sea-green rounded"><span class="ml-2 text-sm">Carabao</span></label>
                            <label class="inline-flex items-center"><input type="checkbox" name="livestock[]" value="Cattle" class="text-sea-green rounded"><span class="ml-2 text-sm">Cattle</span></label>
                            <label class="inline-flex items-center"><input type="checkbox" name="livestock[]" value="Horse" class="text-sea-green rounded"><span class="ml-2 text-sm">Horse</span></label>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold text-tiger-orange mb-3">Small Ruminants</h4>
                        <div class="space-y-2 flex flex-col">
                            <label class="inline-flex items-center"><input type="checkbox" name="livestock[]" value="Goat" class="text-sea-green rounded"><span class="ml-2 text-sm">Goat</span></label>
                            <label class="inline-flex items-center"><input type="checkbox" name="livestock[]" value="Sheep" class="text-sea-green rounded"><span class="ml-2 text-sm">Sheep</span></label>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold text-tiger-orange mb-3">Others</h4>
                        <div class="space-y-2 flex flex-col">
                            <label class="inline-flex items-center"><input type="checkbox" name="livestock[]" value="Swine" class="text-sea-green rounded"><span class="ml-2 text-sm">Swine</span></label>
                            <label class="inline-flex items-center"><input type="checkbox" name="livestock[]" value="Poultry" class="text-sea-green rounded"><span class="ml-2 text-sm">Poultry</span></label>
                            <label class="inline-flex items-center"><input type="checkbox" name="livestock[]" value="Companions" class="text-sea-green rounded"><span class="ml-2 text-sm">Companions (Pets)</span></label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 5: Capture Fisheries --}}
            <div x-show="step === 5" x-transition.opacity class="p-8 space-y-6" style="display: none;">
                <h3 class="text-xl font-bold text-deep-forest border-b pb-2">Step 5: Capture Fisheries</h3>
                <p class="text-sm text-gray-600">Indicate your fishing engagement.</p>
                
                <div class="space-y-4">
                    <label class="flex items-center p-4 border rounded-lg hover:bg-gray-50 cursor-pointer transition">
                        <input type="checkbox" name="fisheries[]" value="Municipal Inland Fishing" class="h-5 w-5 text-sea-green rounded border-gray-300">
                        <span class="ml-3 text-md text-gray-700 font-bold">Municipal Inland Fishing</span>
                    </label>
                    <label class="flex items-center p-4 border rounded-lg hover:bg-gray-50 cursor-pointer transition">
                        <input type="checkbox" name="fisheries[]" value="Municipal Marine Fishing" class="h-5 w-5 text-sea-green rounded border-gray-300">
                        <span class="ml-3 text-md text-gray-700 font-bold">Municipal Marine Fishing</span>
                    </label>
                    <label class="flex items-center p-4 border rounded-lg hover:bg-gray-50 cursor-pointer transition">
                        <input type="checkbox" name="fisheries[]" value="Various Fishing Gear" class="h-5 w-5 text-sea-green rounded border-gray-300">
                        <span class="ml-3 text-md text-gray-700 font-bold">Various Fishing Gear Types</span>
                    </label>
                </div>
            </div>

            {{-- Navigation Footer --}}
            <div class="bg-gray-50 px-8 py-4 border-t flex items-center justify-between">
                
                <button type="button" x-show="step > 1" @click="step--" class="text-gray-600 font-bold hover:text-deep-forest transition px-4 py-2">
                    &larr; Back
                </button>
                <div x-show="step === 1"></div>
                
                <div class="flex gap-4">
                    <button type="button" @click="step < maxSteps ? step++ : $el.closest('form').submit()" class="text-gray-500 hover:text-tiger-orange font-bold text-sm px-4 py-2 transition underline">
                        Skip this step
                    </button>
                    
                    <button type="button" x-show="step < maxSteps" @click="step++" class="bg-deep-forest hover:bg-opacity-90 text-white font-bold py-2 px-6 rounded-lg transition shadow-md">
                        Next Step
                    </button>
                    
                    <button type="submit" x-show="step === maxSteps" class="bg-tiger-orange hover:bg-burnt-tangerine text-white font-bold py-2 px-6 rounded-lg transition shadow-md" style="display: none;">
                        Complete Onboarding
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection
