@extends('layouts.admin')

@section('title', 'Register New Household')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">

    {{-- Breadcrumb --}}
    <nav class="mb-5">
        <ol class="flex items-center gap-2 text-sm text-gray-500">
            <li><a href="{{ route('admin.households.index') }}" class="text-sea-green hover:text-deep-forest font-semibold transition-colors">🏠 Households</a></li>
            <li><span class="text-gray-300">/</span></li>
            <li class="text-gray-700 font-medium">Register New Household</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-deep-forest flex items-center gap-2">
            <span class="w-8 h-8 rounded-lg bg-sea-green/10 text-sea-green flex items-center justify-center text-base">🏡</span>
            Register New Household
        </h1>
        <p class="text-gray-500 text-sm mt-1">Create a new physical address entry (HN)</p>
    </div>

    {{-- Validation Errors Summary --}}
    @if ($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 rounded-lg px-4 py-3 flex items-start gap-3">
            <span class="text-red-500 text-lg leading-none mt-0.5">⚠</span>
            <div>
                <p class="text-sm font-semibold text-red-700 mb-1">Please fix the following errors:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm text-red-600">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.households.store') }}">
        @csrf

        <div class="space-y-5">

            {{-- ── Physical Address Card ── --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <h2 class="text-base font-bold text-deep-forest mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-sea-green/10 text-sea-green flex items-center justify-center text-xs font-bold">📍</span>
                    Physical Address
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- House/Lot Number --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">House/Lot Number</label>
                        <input type="text" name="house_number" value="{{ old('house_number') }}"
                               placeholder="e.g., 123, Block 5 Lot 10"
                               class="w-full px-3.5 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent transition
                                      {{ $errors->has('house_number') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        @error('house_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Street --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Street</label>
                        <input type="text" name="street" value="{{ old('street') }}"
                               placeholder="e.g., Rizal Street"
                               class="w-full px-3.5 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent transition
                                      {{ $errors->has('street') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        @error('street')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Purok --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Purok <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="purok" value="{{ old('purok') }}"
                               placeholder="e.g., Purok 1, Sitio Maligaya"
                               required
                               class="w-full px-3.5 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent transition
                                      {{ $errors->has('purok') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        @error('purok')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Barangay --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Barangay <span class="text-red-500">*</span>
                        </label>
                        <select name="barangay" required
                                class="w-full px-3.5 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent transition
                                       {{ $errors->has('barangay') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            <option value="">Select Barangay</option>
                            @foreach(config('barangays.list', []) as $name => $code)
                                <option value="{{ $name }}" {{ old('barangay') === $name ? 'selected' : '' }}>
                                    {{ $name }} ({{ $code }})
                                </option>
                            @endforeach
                        </select>
                        @error('barangay')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Municipality (read-only display) --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Municipality</label>
                        <input type="text" name="municipality" value="{{ old('municipality', 'Buguey') }}"
                               readonly
                               class="w-full px-3.5 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 text-gray-500 cursor-not-allowed">
                    </div>

                    {{-- Province (read-only display) --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Province</label>
                        <input type="text" name="province" value="{{ old('province', 'Cagayan') }}"
                               readonly
                               class="w-full px-3.5 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 text-gray-500 cursor-not-allowed">
                    </div>
                </div>
            </div>

            {{-- ── Housing Information Card ── --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <h2 class="text-base font-bold text-deep-forest mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-sea-green/10 text-sea-green flex items-center justify-center text-xs font-bold">🏠</span>
                    Housing Information
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Housing Type --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Housing Type <span class="text-red-500">*</span>
                        </label>
                        <select name="housing_type" required
                                class="w-full px-3.5 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent transition
                                       {{ $errors->has('housing_type') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            <option value="">Select housing type...</option>
                            <option value="Owned"                  {{ old('housing_type') == 'Owned' ? 'selected' : '' }}>Owned</option>
                            <option value="Rented"                 {{ old('housing_type') == 'Rented' ? 'selected' : '' }}>Rented</option>
                            <option value="Rent-Free with Consent" {{ old('housing_type') == 'Rent-Free with Consent' ? 'selected' : '' }}>Rent-Free with Consent</option>
                            <option value="Informal Settler"       {{ old('housing_type') == 'Informal Settler' ? 'selected' : '' }}>Informal Settler</option>
                        </select>
                        @error('housing_type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Notes --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Notes <span class="text-gray-400 font-normal">(Optional)</span></label>
                        <textarea name="notes" rows="3"
                                  placeholder="Any additional notes about this household..."
                                  class="w-full px-3.5 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent transition resize-none">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ── Action Buttons ── --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.households.index') }}"
                   class="px-5 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-sea-green hover:bg-deep-forest text-white rounded-lg text-sm font-bold transition-colors shadow-sm flex items-center gap-2">
                    <span>🏡</span> Register Household
                </button>
            </div>

        </div>
    </form>

    {{-- Info box --}}
    <div class="mt-5 bg-sea-green/5 border border-sea-green/20 rounded-lg p-4 flex items-start gap-3">
        <span class="text-sea-green text-lg leading-none mt-0.5">ℹ</span>
        <div>
            <p class="text-sm font-semibold text-deep-forest mb-0.5">What happens next?</p>
            <p class="text-sm text-gray-600">
                A unique Household Number (HN) will be generated automatically once you register.
                You can then add family units (HHN) and individual members (HHM) to this address.
            </p>
        </div>
    </div>

</div>
@endsection
