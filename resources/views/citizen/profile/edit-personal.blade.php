@extends('layouts.citizen')

@section('title', 'Edit Personal Information | RESIDENTE App')
@section('page-title', 'Edit Personal Information')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('citizen.profile.index') }}" class="text-sea-green hover:text-deep-forest font-medium transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Profile
                    </a>
                </li>
            </ol>
        </nav>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-deep-forest to-sea-green px-4 sm:px-8 py-4 sm:py-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 sm:w-12 h-10 sm:h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 sm:w-7 h-6 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-white">Edit Personal Information</h1>
                        <p class="text-white text-opacity-90 text-sm mt-1 hidden sm:block">Update your personal details and identity information</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('citizen.profile.personal.update') }}" class="p-4 sm:p-8">
                @csrf
                @method('PUT')

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-5 rounded-r-xl shadow-sm">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1">
                                <p class="font-bold text-red-800 mb-2">Please correct the following errors:</p>
                                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Personal Identity Section -->
                <div class="mb-10">
                    <div class="flex items-center gap-2 mb-6 pb-3 border-b-2 border-gray-100">
                        <svg class="w-5 h-5 text-deep-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                        </svg>
                        <h2 class="text-lg font-bold text-gray-900">Basic Identity Information</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        <!-- First Name -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="first_name" 
                                value="{{ old('first_name', $resident->first_name) }}" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm"
                                placeholder="Enter first name"
                                autocomplete="given-name"
                            >
                            <p class="text-xs text-gray-500">As shown in your birth certificate</p>
                        </div>

                        <!-- Middle Name -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                Middle Name <span class="text-gray-400 text-xs">(Optional)</span>
                            </label>
                            <input 
                                type="text" 
                                name="middle_name" 
                                value="{{ old('middle_name', $resident->middle_name) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm"
                                placeholder="Enter middle name"
                                autocomplete="additional-name"
                            >
                            <p class="text-xs text-gray-500">Mother's maiden surname if applicable</p>
                        </div>

                        <!-- Last Name -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="last_name" 
                                value="{{ old('last_name', $resident->last_name) }}" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm"
                                placeholder="Enter last name"
                                autocomplete="family-name"
                            >
                            <p class="text-xs text-gray-500">Family name or surname</p>
                        </div>

                        <!-- Extension Name -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                Suffix
                            </label>
                            <select 
                                name="extension_name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm"
                            >
                                <option value="">No suffix</option>
                                <option value="Jr." {{ old('extension_name', $resident->extension_name) == 'Jr.' ? 'selected' : '' }}>Jr. (Junior)</option>
                                <option value="Sr." {{ old('extension_name', $resident->extension_name) == 'Sr.' ? 'selected' : '' }}>Sr. (Senior)</option>
                                <option value="II" {{ old('extension_name', $resident->extension_name) == 'II' ? 'selected' : '' }}>II (Second)</option>
                                <option value="III" {{ old('extension_name', $resident->extension_name) == 'III' ? 'selected' : '' }}>III (Third)</option>
                                <option value="IV" {{ old('extension_name', $resident->extension_name) == 'IV' ? 'selected' : '' }}>IV (Fourth)</option>
                                <option value="V" {{ old('extension_name', $resident->extension_name) == 'V' ? 'selected' : '' }}>V (Fifth)</option>
                            </select>
                            <p class="text-xs text-gray-500">Name suffix (e.g., Jr., Sr., III)</p>
                        </div>

                        <!-- Date of Birth -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                Date of Birth <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                name="date_of_birth" 
                                value="{{ old('date_of_birth', $resident->date_of_birth?->format('Y-m-d')) }}" 
                                required
                                max="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm"
                                autocomplete="bday"
                            >
                            <p class="text-xs text-gray-500">As indicated in birth certificate</p>
                        </div>

                        <!-- Place of Birth -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                Place of Birth <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="place_of_birth" 
                                value="{{ old('place_of_birth', $resident->place_of_birth) }}" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm"
                                placeholder="e.g., Quezon City, Metro Manila"
                            >
                            <p class="text-xs text-gray-500">City/Municipality, Province</p>
                        </div>

                    </div>
                </div>

                <!-- Demographic Information Section -->
                <div class="mb-10">
                    <div class="flex items-center gap-2 mb-6 pb-3 border-b-2 border-gray-100">
                        <svg class="w-5 h-5 text-deep-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h2 class="text-lg font-bold text-gray-900">Demographic Details</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        <!-- Gender -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                Gender <span class="text-red-500">*</span>
                            </label>
                            <select 
                                name="gender" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm"
                                autocomplete="sex"
                            >
                                <option value="">Select gender</option>
                                <option value="Male" {{ old('gender', $resident->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $resident->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                            <p class="text-xs text-gray-500">Biological sex assigned at birth</p>
                        </div>

                        <!-- Civil Status -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                Civil Status <span class="text-red-500">*</span>
                            </label>
                            <select 
                                name="civil_status" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm"
                            >
                                <option value="">Select civil status</option>
                                <option value="Single" {{ old('civil_status', $resident->civil_status) == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ old('civil_status', $resident->civil_status) == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Widowed" {{ old('civil_status', $resident->civil_status) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                <option value="Legally Separated" {{ old('civil_status', $resident->civil_status) == 'Legally Separated' ? 'selected' : '' }}>Legally Separated</option>
                                <option value="Annulled" {{ old('civil_status', $resident->civil_status) == 'Annulled' ? 'selected' : '' }}>Annulled</option>
                            </select>
                            <p class="text-xs text-gray-500">Current marital status</p>
                        </div>

                        <!-- Blood Type -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                Blood Type <span class="text-gray-400 text-xs">(Optional)</span>
                            </label>
                            <select 
                                name="blood_type"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm"
                            >
                                <option value="">Unknown / Not specified</option>
                                <option value="O+" {{ old('blood_type', $resident->blood_type) == 'O+' ? 'selected' : '' }}>O+ (O Positive)</option>
                                <option value="O-" {{ old('blood_type', $resident->blood_type) == 'O-' ? 'selected' : '' }}>O- (O Negative)</option>
                                <option value="A+" {{ old('blood_type', $resident->blood_type) == 'A+' ? 'selected' : '' }}>A+ (A Positive)</option>
                                <option value="A-" {{ old('blood_type', $resident->blood_type) == 'A-' ? 'selected' : '' }}>A- (A Negative)</option>
                                <option value="B+" {{ old('blood_type', $resident->blood_type) == 'B+' ? 'selected' : '' }}>B+ (B Positive)</option>
                                <option value="B-" {{ old('blood_type', $resident->blood_type) == 'B-' ? 'selected' : '' }}>B- (B Negative)</option>
                                <option value="AB+" {{ old('blood_type', $resident->blood_type) == 'AB+' ? 'selected' : '' }}>AB+ (AB Positive)</option>
                                <option value="AB-" {{ old('blood_type', $resident->blood_type) == 'AB-' ? 'selected' : '' }}>AB- (AB Negative)</option>
                            </select>
                            <p class="text-xs text-gray-500">For medical and emergency purposes</p>
                        </div>

                    </div>
                </div>

                <!-- Contact & Classification Section -->
                <div class="mb-10">
                    <div class="flex items-center gap-2 mb-6 pb-3 border-b-2 border-gray-100">
                        <svg class="w-5 h-5 text-deep-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <h2 class="text-lg font-bold text-gray-900">Contact Information & Classification</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        <!-- Contact Number -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                Mobile Number <span class="text-gray-400 text-xs">(Optional)</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">+63</span>
                                </div>
                                <input 
                                    type="tel" 
                                    name="contact_number" 
                                    value="{{ old('contact_number', $resident->contact_number) }}" 
                                    pattern="09[0-9]{9}"
                                    maxlength="11"
                                    class="w-full pl-14 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm"
                                    placeholder="09xxxxxxxxx"
                                    autocomplete="tel"
                                >
                            </div>
                            <p class="text-xs text-gray-500">11-digit Philippine mobile number</p>
                        </div>

                        <!-- Occupation -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                Occupation <span class="text-gray-400 text-xs">(Optional)</span>
                            </label>
                            <input 
                                type="text" 
                                name="occupation" 
                                value="{{ old('occupation', $resident->occupation) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm"
                                placeholder="e.g., Teacher, Farmer, Engineer"
                                autocomplete="organization-title"
                            >
                            <p class="text-xs text-gray-500">Your current job or profession</p>
                        </div>

                        <!-- Vulnerable Sector -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                Vulnerable / Priority Sector <span class="text-red-500">*</span>
                            </label>
                            <select 
                                name="vulnerable_sector" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm"
                            >
                                <option value="None" {{ old('vulnerable_sector', $resident->vulnerable_sector) == 'None' ? 'selected' : '' }}>None / Not Applicable</option>
                                <option value="Senior Citizen" {{ old('vulnerable_sector', $resident->vulnerable_sector) == 'Senior Citizen' ? 'selected' : '' }}>Senior Citizen (60 years old and above)</option>
                                <option value="PWD" {{ old('vulnerable_sector', $resident->vulnerable_sector) == 'PWD' ? 'selected' : '' }}>Person with Disability (PWD)</option>
                                <option value="Solo Parent" {{ old('vulnerable_sector', $resident->vulnerable_sector) == 'Solo Parent' ? 'selected' : '' }}>Solo Parent</option>
                                <option value="Indigenous People" {{ old('vulnerable_sector', $resident->vulnerable_sector) == 'Indigenous People' ? 'selected' : '' }}>Indigenous People (IP)</option>
                                <option value="4Ps Beneficiary" {{ old('vulnerable_sector', $resident->vulnerable_sector) == '4Ps Beneficiary' ? 'selected' : '' }}>4Ps Beneficiary</option>
                            </select>
                            <p class="text-xs text-gray-500">For government programs and priority services</p>
                        </div>

                        <!-- Household Relationship -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                Household Relationship <span class="text-red-500">*</span>
                            </label>
                            <select 
                                name="household_relationship" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm"
                            >
                                <option value="Household Head" {{ old('household_relationship', $resident->household_relationship ?? 'Household Head') == 'Household Head' ? 'selected' : '' }}>Household Head</option>
                                <option value="Spouse" {{ old('household_relationship', $resident->household_relationship) == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                                <option value="Child" {{ old('household_relationship', $resident->household_relationship) == 'Child' ? 'selected' : '' }}>Child (Son/Daughter)</option>
                                <option value="Parent" {{ old('household_relationship', $resident->household_relationship) == 'Parent' ? 'selected' : '' }}>Parent (Father/Mother)</option>
                                <option value="Sibling" {{ old('household_relationship', $resident->household_relationship) == 'Sibling' ? 'selected' : '' }}>Sibling (Brother/Sister)</option>
                                <option value="Grandchild" {{ old('household_relationship', $resident->household_relationship) == 'Grandchild' ? 'selected' : '' }}>Grandchild</option>
                                <option value="Grandparent" {{ old('household_relationship', $resident->household_relationship) == 'Grandparent' ? 'selected' : '' }}>Grandparent</option>
                                <option value="Other Relative" {{ old('household_relationship', $resident->household_relationship) == 'Other Relative' ? 'selected' : '' }}>Other Relative (Cousin, Uncle, Aunt, etc.)</option>
                                <option value="Non-Relative" {{ old('household_relationship', $resident->household_relationship) == 'Non-Relative' ? 'selected' : '' }}>Non-Relative (Boarder, Helper, etc.)</option>
                            </select>
                            <p class="text-xs text-gray-500">Your relationship to the household head</p>
                        </div>

                        <!-- Household Number -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                Household Number <span class="text-gray-400 text-xs">(Optional)</span>
                            </label>
                            <input 
                                type="text" 
                                name="household_number" 
                                value="{{ old('household_number', $resident->household_number) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm"
                                placeholder="e.g., HH-2026-001"
                            >
                            <p class="text-xs text-gray-500">Unique household identifier for census</p>
                        </div>

                        <!-- Household Member Number -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                HHM (Household Member #) <span class="text-gray-400 text-xs">(Optional)</span>
                            </label>
                            <input 
                                type="number" 
                                name="household_member_number" 
                                value="{{ old('household_member_number', $resident->household_member_number) }}"
                                min="1"
                                max="99"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm"
                                placeholder="1, 2, 3, ..."
                            >
                            <p class="text-xs text-gray-500">Position/order within household (1 = Head)</p>
                        </div>

                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-between items-center pt-6 border-t-2 border-gray-100">
                    <p class="text-sm text-gray-600">
                        <span class="text-red-500 font-bold">*</span> Required fields must be completed
                    </p>
                    <div class="flex gap-3 w-full sm:w-auto">
                        <a href="{{ route('citizen.profile.index') }}" 
                           class="flex-1 sm:flex-initial px-6 py-3 border-2 border-gray-300 rounded-xl font-bold text-gray-700 hover:bg-gray-50 transition text-center shadow-sm">
                            Cancel
                        </a>
                        <button type="submit"
                                class="flex-1 sm:flex-initial px-8 py-3 bg-gradient-to-r from-deep-forest to-sea-green hover:from-sea-green hover:to-deep-forest text-white rounded-xl font-bold shadow-lg transition">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
