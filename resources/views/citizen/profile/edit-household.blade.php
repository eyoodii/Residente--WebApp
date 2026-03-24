<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Household Profile | RESIDENTE App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased font-sans min-h-screen">

    <div class="p-8">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                <a href="{{ route('citizen.profile.index') }}" class="text-sea-green hover:text-deep-forest font-bold text-sm flex items-center gap-1">
                    ← Back to Profile
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-deep-forest px-6 py-4">
                    <h1 class="text-2xl font-bold text-white">{{ $householdProfile->exists ? 'Edit' : 'Add' }} Household Profile</h1>
                </div>

                <form method="POST" action="{{ route('citizen.profile.household.update') }}" class="p-6">
                    @csrf
                    @method('PUT')

                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                            <p class="font-bold text-red-800 mb-2">Please fix the following errors:</p>
                            <ul class="list-disc list-inside text-sm text-red-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-6">
                        
                        <!-- Housing Type -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Housing Type <span class="text-red-500">*</span></label>
                            <select name="housing_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                                <option value="">Select Housing Type</option>
                                <option value="Owned" {{ old('housing_type', $householdProfile->housing_type) == 'Owned' ? 'selected' : '' }}>Owned</option>
                                <option value="Rented" {{ old('housing_type', $householdProfile->housing_type) == 'Rented' ? 'selected' : '' }}>Rented</option>
                                <option value="Rent-Free with Consent" {{ old('housing_type', $householdProfile->housing_type) == 'Rent-Free with Consent' ? 'selected' : '' }}>Rent-Free with Consent</option>
                                <option value="Informal Settler" {{ old('housing_type', $householdProfile->housing_type) == 'Informal Settler' ? 'selected' : '' }}>Informal Settler</option>
                            </select>
                        </div>

                        <!-- Dwelling Type -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Dwelling Type</label>
                            <select name="dwelling_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                                <option value="">Select Dwelling Type</option>
                                <option value="Single Detached" {{ old('dwelling_type', $householdProfile->dwelling_type) == 'Single Detached' ? 'selected' : '' }}>Single Detached</option>
                                <option value="Duplex" {{ old('dwelling_type', $householdProfile->dwelling_type) == 'Duplex' ? 'selected' : '' }}>Duplex</option>
                                <option value="Apartment" {{ old('dwelling_type', $householdProfile->dwelling_type) == 'Apartment' ? 'selected' : '' }}>Apartment</option>
                                <option value="Townhouse" {{ old('dwelling_type', $householdProfile->dwelling_type) == 'Townhouse' ? 'selected' : '' }}>Townhouse</option>
                                <option value="Makeshift/Salvaged" {{ old('dwelling_type', $householdProfile->dwelling_type) == 'Makeshift/Salvaged' ? 'selected' : '' }}>Makeshift/Salvaged</option>
                                <option value="Others" {{ old('dwelling_type', $householdProfile->dwelling_type) == 'Others' ? 'selected' : '' }}>Others</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Number of Rooms -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Number of Rooms</label>
                                <input type="number" name="number_of_rooms" value="{{ old('number_of_rooms', $householdProfile->number_of_rooms) }}" min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                            </div>

                            <!-- Water Source -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Water Source</label>
                                <input type="text" name="water_source" value="{{ old('water_source', $householdProfile->water_source) }}" placeholder="e.g., Deep Well, Water District"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                            </div>

                            <!-- Toilet Facility -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Toilet Facility</label>
                                <input type="text" name="toilet_facility" value="{{ old('toilet_facility', $householdProfile->toilet_facility) }}" placeholder="e.g., Water Sealed, Pit Latrine"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                            </div>

                            <!-- Total Household Income -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Total Monthly Household Income (₱)</label>
                                <input type="number" name="total_household_income" value="{{ old('total_household_income', $householdProfile->total_household_income) }}" min="0" step="0.01"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                            </div>

                        </div>

                        <!-- Utilities (Checkboxes) -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-3">Available Utilities</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="has_electricity" value="1" {{ old('has_electricity', $householdProfile->has_electricity) ? 'checked' : '' }}
                                        class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                    <span class="text-sm text-gray-700">Electricity</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="has_water_supply" value="1" {{ old('has_water_supply', $householdProfile->has_water_supply) ? 'checked' : '' }}
                                        class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                    <span class="text-sm text-gray-700">Water Supply</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="has_internet_access" value="1" {{ old('has_internet_access', $householdProfile->has_internet_access) ? 'checked' : '' }}
                                        class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                    <span class="text-sm text-gray-700">Internet Access</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="has_television" value="1" {{ old('has_television', $householdProfile->has_television) ? 'checked' : '' }}
                                        class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                    <span class="text-sm text-gray-700">Television</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="has_radio" value="1" {{ old('has_radio', $householdProfile->has_radio) ? 'checked' : '' }}
                                        class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                    <span class="text-sm text-gray-700">Radio</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="owns_vehicle" value="1" {{ old('owns_vehicle', $householdProfile->owns_vehicle) ? 'checked' : '' }}
                                        class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                    <span class="text-sm text-gray-700">Owns Vehicle</span>
                                </label>
                            </div>
                        </div>

                        <!-- Vehicle Types -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Vehicle Types (if applicable)</label>
                            <input type="text" name="vehicle_types" value="{{ old('vehicle_types', $householdProfile->vehicle_types) }}" placeholder="e.g., Motorcycle, Car"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                        </div>

                        <!-- Agricultural Land -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="flex items-center gap-2 cursor-pointer mb-2">
                                    <input type="checkbox" name="owns_agricultural_land" value="1" {{ old('owns_agricultural_land', $householdProfile->owns_agricultural_land) ? 'checked' : '' }}
                                        class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                    <span class="text-sm font-bold text-gray-700">Owns Agricultural Land</span>
                                </label>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Agricultural Land Area (hectares)</label>
                                <input type="number" name="agricultural_land_area" value="{{ old('agricultural_land_area', $householdProfile->agricultural_land_area) }}" min="0" step="0.01"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                            </div>
                        </div>

                        <!-- Special Needs -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Special Needs (if any)</label>
                            <textarea name="special_needs" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">{{ old('special_needs', $householdProfile->special_needs) }}</textarea>
                        </div>

                        <!-- Assistance Received -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Government Assistance Programs Received</label>
                            <textarea name="assistance_received" rows="3" placeholder="e.g., 4Ps, AKAP, Ayuda" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">{{ old('assistance_received', $householdProfile->assistance_received) }}</textarea>
                        </div>

                    </div>

                    <div class="mt-8 flex gap-4 justify-end">
                        <a href="{{ route('citizen.profile.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg font-bold text-gray-700 hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-tiger-orange hover:bg-burnt-tangerine text-white rounded-lg font-bold shadow transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
