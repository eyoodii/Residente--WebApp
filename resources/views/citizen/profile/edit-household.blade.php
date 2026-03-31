@extends('layouts.citizen')

@section('title', 'Edit Household Profile | RESIDENTE App')
@section('page-title', 'Household Profile')

@section('content')
    <div class="px-4 lg:px-6 py-6">
        <div class="max-w-4xl mx-auto">
            <nav class="mb-6">
                <a href="{{ route('citizen.profile.index') }}" class="text-sea-green hover:text-deep-forest font-semibold text-sm flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Profile
                </a>
            </nav>

            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-r from-deep-forest to-sea-green px-6 sm:px-8 py-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/15 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819"/></svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-extrabold text-white">{{ $householdProfile->exists ? 'Edit' : 'Add' }} Household Profile</h1>
                            <p class="text-white/75 text-sm mt-0.5 hidden sm:block">Housing, utilities, and household details</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('citizen.profile.household.update') }}" class="p-6 sm:p-8">
                    @csrf
                    @method('PUT')

                    @if($errors->any())
                        <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
                            <p class="font-bold text-red-800 mb-2">Please fix the following errors:</p>
                            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Housing Section --}}
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-5 pb-3 border-b-2 border-gray-100">
                            <svg class="w-5 h-5 text-deep-forest" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                            <h2 class="text-lg font-bold text-gray-900">Housing Information</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Housing Type <span class="text-red-500">*</span></label>
                                <select name="housing_type" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                                    <option value="">Select Housing Type</option>
                                    <option value="Owned" {{ old('housing_type', $householdProfile->housing_type) == 'Owned' ? 'selected' : '' }}>Owned</option>
                                    <option value="Rented" {{ old('housing_type', $householdProfile->housing_type) == 'Rented' ? 'selected' : '' }}>Rented</option>
                                    <option value="Rent-Free with Consent" {{ old('housing_type', $householdProfile->housing_type) == 'Rent-Free with Consent' ? 'selected' : '' }}>Rent-Free with Consent</option>
                                    <option value="Informal Settler" {{ old('housing_type', $householdProfile->housing_type) == 'Informal Settler' ? 'selected' : '' }}>Informal Settler</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Dwelling Type</label>
                                <select name="dwelling_type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                                    <option value="">Select Dwelling Type</option>
                                    <option value="Single Detached" {{ old('dwelling_type', $householdProfile->dwelling_type) == 'Single Detached' ? 'selected' : '' }}>Single Detached</option>
                                    <option value="Duplex" {{ old('dwelling_type', $householdProfile->dwelling_type) == 'Duplex' ? 'selected' : '' }}>Duplex</option>
                                    <option value="Apartment" {{ old('dwelling_type', $householdProfile->dwelling_type) == 'Apartment' ? 'selected' : '' }}>Apartment</option>
                                    <option value="Townhouse" {{ old('dwelling_type', $householdProfile->dwelling_type) == 'Townhouse' ? 'selected' : '' }}>Townhouse</option>
                                    <option value="Makeshift/Salvaged" {{ old('dwelling_type', $householdProfile->dwelling_type) == 'Makeshift/Salvaged' ? 'selected' : '' }}>Makeshift/Salvaged</option>
                                    <option value="Others" {{ old('dwelling_type', $householdProfile->dwelling_type) == 'Others' ? 'selected' : '' }}>Others</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Number of Rooms</label>
                                <input type="number" name="number_of_rooms" value="{{ old('number_of_rooms', $householdProfile->number_of_rooms) }}" min="0"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Water Source</label>
                                <input type="text" name="water_source" value="{{ old('water_source', $householdProfile->water_source) }}" placeholder="e.g., Deep Well, Water District"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Toilet Facility</label>
                                <input type="text" name="toilet_facility" value="{{ old('toilet_facility', $householdProfile->toilet_facility) }}" placeholder="e.g., Water Sealed, Pit Latrine"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Total Monthly Household Income (₱)</label>
                                <input type="number" name="total_household_income" value="{{ old('total_household_income', $householdProfile->total_household_income) }}" min="0" step="0.01"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                            </div>
                        </div>
                    </div>

                    {{-- Utilities Section --}}
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-5 pb-3 border-b-2 border-gray-100">
                            <svg class="w-5 h-5 text-deep-forest" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                            <h2 class="text-lg font-bold text-gray-900">Utilities & Assets</h2>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                            <label class="flex items-center gap-2.5 cursor-pointer py-2">
                                <input type="checkbox" name="has_electricity" value="1" {{ old('has_electricity', $householdProfile->has_electricity) ? 'checked' : '' }}
                                    class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                <span class="text-sm text-gray-700 font-medium">Electricity</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer py-2">
                                <input type="checkbox" name="has_water_supply" value="1" {{ old('has_water_supply', $householdProfile->has_water_supply) ? 'checked' : '' }}
                                    class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                <span class="text-sm text-gray-700 font-medium">Water Supply</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer py-2">
                                <input type="checkbox" name="has_internet_access" value="1" {{ old('has_internet_access', $householdProfile->has_internet_access) ? 'checked' : '' }}
                                    class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                <span class="text-sm text-gray-700 font-medium">Internet Access</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer py-2">
                                <input type="checkbox" name="has_television" value="1" {{ old('has_television', $householdProfile->has_television) ? 'checked' : '' }}
                                    class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                <span class="text-sm text-gray-700 font-medium">Television</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer py-2">
                                <input type="checkbox" name="has_radio" value="1" {{ old('has_radio', $householdProfile->has_radio) ? 'checked' : '' }}
                                    class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                <span class="text-sm text-gray-700 font-medium">Radio</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer py-2">
                                <input type="checkbox" name="owns_vehicle" value="1" {{ old('owns_vehicle', $householdProfile->owns_vehicle) ? 'checked' : '' }}
                                    class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                <span class="text-sm text-gray-700 font-medium">Owns Vehicle</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Vehicle Types (if applicable)</label>
                                <input type="text" name="vehicle_types" value="{{ old('vehicle_types', $householdProfile->vehicle_types) }}" placeholder="e.g., Motorcycle, Car"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Agricultural Land Area (hectares)</label>
                                <input type="number" name="agricultural_land_area" value="{{ old('agricultural_land_area', $householdProfile->agricultural_land_area) }}" min="0" step="0.01"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                            </div>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2.5 cursor-pointer">
                                    <input type="checkbox" name="owns_agricultural_land" value="1" {{ old('owns_agricultural_land', $householdProfile->owns_agricultural_land) ? 'checked' : '' }}
                                        class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                    <span class="text-sm font-semibold text-gray-700">Owns Agricultural Land</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Additional Information --}}
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-5 pb-3 border-b-2 border-gray-100">
                            <svg class="w-5 h-5 text-deep-forest" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                            <h2 class="text-lg font-bold text-gray-900">Additional Information</h2>
                        </div>
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Special Needs (if any)</label>
                                <textarea name="special_needs" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">{{ old('special_needs', $householdProfile->special_needs) }}</textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Government Assistance Programs Received</label>
                                <textarea name="assistance_received" rows="3" placeholder="e.g., 4Ps, AKAP, Ayuda" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">{{ old('assistance_received', $householdProfile->assistance_received) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 justify-end pt-6 border-t border-slate-100">
                        <a href="{{ route('citizen.profile.index') }}" class="px-6 py-3 border-2 border-gray-300 rounded-xl font-bold text-gray-700 hover:bg-gray-50 transition text-center shadow-sm">
                            Cancel
                        </a>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-deep-forest to-sea-green hover:from-sea-green hover:to-deep-forest text-white rounded-xl font-bold shadow-lg transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
