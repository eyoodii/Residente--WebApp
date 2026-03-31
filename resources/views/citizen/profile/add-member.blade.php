@extends('layouts.citizen')

@section('title', 'Add Household Member | RESIDENTE App')
@section('page-title', 'Add Household Member')

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
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-extrabold text-white">Add Household Member</h1>
                            <p class="text-white/75 text-sm mt-0.5 hidden sm:block">Register a new family/household member</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('citizen.profile.members.store') }}" class="p-6 sm:p-8">
                    @csrf

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

                    {{-- Basic Info --}}
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-5 pb-3 border-b-2 border-gray-100">
                            <svg class="w-5 h-5 text-deep-forest" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            <h2 class="text-lg font-bold text-gray-900">Personal Details</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm"
                                    placeholder="Enter first name" autocomplete="given-name">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Middle Name</label>
                                <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm"
                                    placeholder="Enter middle name" autocomplete="additional-name">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm"
                                    placeholder="Enter last name" autocomplete="family-name">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Suffix</label>
                                <select name="extension_name" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                                    <option value="">None</option>
                                    <option value="Jr." {{ old('extension_name') == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                    <option value="Sr." {{ old('extension_name') == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                    <option value="II" {{ old('extension_name') == 'II' ? 'selected' : '' }}>II</option>
                                    <option value="III" {{ old('extension_name') == 'III' ? 'selected' : '' }}>III</option>
                                    <option value="IV" {{ old('extension_name') == 'IV' ? 'selected' : '' }}>IV</option>
                                    <option value="V" {{ old('extension_name') == 'V' ? 'selected' : '' }}>V</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Date of Birth <span class="text-red-500">*</span></label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required max="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm"
                                    autocomplete="bday">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Gender <span class="text-red-500">*</span></label>
                                <select name="gender" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Relationship <span class="text-red-500">*</span></label>
                                <select name="relationship" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                                    <option value="">Select Relationship</option>
                                    <option value="Spouse" {{ old('relationship') == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                                    <option value="Son" {{ old('relationship') == 'Son' ? 'selected' : '' }}>Son</option>
                                    <option value="Daughter" {{ old('relationship') == 'Daughter' ? 'selected' : '' }}>Daughter</option>
                                    <option value="Father" {{ old('relationship') == 'Father' ? 'selected' : '' }}>Father</option>
                                    <option value="Mother" {{ old('relationship') == 'Mother' ? 'selected' : '' }}>Mother</option>
                                    <option value="Brother" {{ old('relationship') == 'Brother' ? 'selected' : '' }}>Brother</option>
                                    <option value="Sister" {{ old('relationship') == 'Sister' ? 'selected' : '' }}>Sister</option>
                                    <option value="Grandchild" {{ old('relationship') == 'Grandchild' ? 'selected' : '' }}>Grandchild</option>
                                    <option value="Other Relative" {{ old('relationship') == 'Other Relative' ? 'selected' : '' }}>Other Relative</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Civil Status <span class="text-red-500">*</span></label>
                                <select name="civil_status" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                                    <option value="">Select Status</option>
                                    <option value="Single" {{ old('civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ old('civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Widowed" {{ old('civil_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                    <option value="Legally Separated" {{ old('civil_status') == 'Legally Separated' ? 'selected' : '' }}>Legally Separated</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Work & Education --}}
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-5 pb-3 border-b-2 border-gray-100">
                            <svg class="w-5 h-5 text-deep-forest" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z"/></svg>
                            <h2 class="text-lg font-bold text-gray-900">Work & Education</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Occupation</label>
                                <input type="text" name="occupation" value="{{ old('occupation') }}" placeholder="e.g., Teacher, Farmer"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Monthly Income (₱)</label>
                                <input type="number" name="monthly_income" value="{{ old('monthly_income') }}" min="0" step="0.01"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Educational Attainment</label>
                                <select name="educational_attainment" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                                    <option value="">Select Educational Attainment</option>
                                    <option value="No Formal Education" {{ old('educational_attainment') == 'No Formal Education' ? 'selected' : '' }}>No Formal Education</option>
                                    <option value="Elementary Undergraduate" {{ old('educational_attainment') == 'Elementary Undergraduate' ? 'selected' : '' }}>Elementary Undergraduate</option>
                                    <option value="Elementary Graduate" {{ old('educational_attainment') == 'Elementary Graduate' ? 'selected' : '' }}>Elementary Graduate</option>
                                    <option value="High School Undergraduate" {{ old('educational_attainment') == 'High School Undergraduate' ? 'selected' : '' }}>High School Undergraduate</option>
                                    <option value="High School Graduate" {{ old('educational_attainment') == 'High School Graduate' ? 'selected' : '' }}>High School Graduate</option>
                                    <option value="College Undergraduate" {{ old('educational_attainment') == 'College Undergraduate' ? 'selected' : '' }}>College Undergraduate</option>
                                    <option value="College Graduate" {{ old('educational_attainment') == 'College Graduate' ? 'selected' : '' }}>College Graduate</option>
                                    <option value="Vocational" {{ old('educational_attainment') == 'Vocational' ? 'selected' : '' }}>Vocational</option>
                                    <option value="Post Graduate" {{ old('educational_attainment') == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Special Sectors --}}
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-5 pb-3 border-b-2 border-gray-100">
                            <svg class="w-5 h-5 text-deep-forest" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"/></svg>
                            <h2 class="text-lg font-bold text-gray-900">Special Sectors</h2>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <label class="flex items-center gap-2.5 cursor-pointer py-2">
                                <input type="checkbox" name="is_pwd" value="1" {{ old('is_pwd') ? 'checked' : '' }}
                                    class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                <span class="text-sm text-gray-700 font-medium">PWD</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer py-2">
                                <input type="checkbox" name="is_senior_citizen" value="1" {{ old('is_senior_citizen') ? 'checked' : '' }}
                                    class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                <span class="text-sm text-gray-700 font-medium">Senior Citizen</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer py-2">
                                <input type="checkbox" name="is_solo_parent" value="1" {{ old('is_solo_parent') ? 'checked' : '' }}
                                    class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                <span class="text-sm text-gray-700 font-medium">Solo Parent</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer py-2">
                                <input type="checkbox" name="is_indigenous_people" value="1" {{ old('is_indigenous_people') ? 'checked' : '' }}
                                    class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                <span class="text-sm text-gray-700 font-medium">Indigenous People</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer py-2">
                                <input type="checkbox" name="is_4ps_beneficiary" value="1" {{ old('is_4ps_beneficiary') ? 'checked' : '' }}
                                    class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                <span class="text-sm text-gray-700 font-medium">4Ps Beneficiary</span>
                            </label>
                        </div>
                    </div>

                    {{-- OFW / Migrant Section --}}
                    <div class="mb-8 p-6 bg-slate-50 rounded-2xl border border-slate-200">
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                                OFW / Migrant Worker
                            </h3>
                            <span class="text-xs text-slate-500 italic font-medium">Optional</span>
                        </div>

                        {{-- Active OFW --}}
                        <div class="mb-5">
                            <label class="flex items-center gap-2.5 cursor-pointer mb-3">
                                <input type="checkbox" name="is_active_ofw" id="is_active_ofw" value="1" {{ old('is_active_ofw') ? 'checked' : '' }}
                                    onchange="toggleOFWSection('active')"
                                    class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                <span class="text-sm font-bold text-gray-900">Currently Working Abroad (Active OFW)</span>
                            </label>
                            <div id="active-ofw-details" class="ml-7 space-y-4 {{ old('is_active_ofw') ? '' : 'hidden' }}">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">Country of Work</label>
                                        <input type="text" name="ofw_country" value="{{ old('ofw_country') }}" placeholder="e.g., Saudi Arabia"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">Nature of Work</label>
                                        <input type="text" name="ofw_nature_of_work" value="{{ old('ofw_nature_of_work') }}" placeholder="e.g., Nurse"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">Year Deployed</label>
                                        <input type="number" name="ofw_year_deployed" value="{{ old('ofw_year_deployed') }}" min="1900" max="{{ date('Y') + 1 }}" placeholder="YYYY"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Returned OFW --}}
                        <div class="mb-5 pt-5 border-t border-slate-200">
                            <label class="flex items-center gap-2.5 cursor-pointer mb-3">
                                <input type="checkbox" name="is_returned_ofw" id="is_returned_ofw" value="1" {{ old('is_returned_ofw') ? 'checked' : '' }}
                                    onchange="toggleOFWSection('returned')"
                                    class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                <span class="text-sm font-bold text-gray-900">Returned OFW (Last 5 Years)</span>
                            </label>
                            <div id="returned-ofw-details" class="ml-7 space-y-4 {{ old('is_returned_ofw') ? '' : 'hidden' }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">Year Returned</label>
                                        <input type="number" name="ofw_year_returned" value="{{ old('ofw_year_returned') }}" min="1900" max="{{ date('Y') }}" placeholder="YYYY"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">Nature of Return</label>
                                        <select name="ofw_nature_of_return" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                                            <option value="">Select Nature</option>
                                            <option value="Permanent" {{ old('ofw_nature_of_return') == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                            <option value="Temporary" {{ old('ofw_nature_of_return') == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                                            <option value="Vacation" {{ old('ofw_nature_of_return') == 'Vacation' ? 'selected' : '' }}>Vacation</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Local Migrant --}}
                        <div class="pt-5 border-t border-slate-200">
                            <label class="flex items-center gap-2.5 cursor-pointer mb-3">
                                <input type="checkbox" name="is_local_migrant" id="is_local_migrant" value="1" {{ old('is_local_migrant') ? 'checked' : '' }}
                                    onchange="toggleOFWSection('local')"
                                    class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                <span class="text-sm font-bold text-gray-900">Local Migrant Worker</span>
                                <span class="text-xs text-slate-500 font-medium">(Working outside province, 0-6 months)</span>
                            </label>
                            <div id="local-migrant-details" class="ml-7 {{ old('is_local_migrant') ? '' : 'hidden' }}">
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Current Location</label>
                                    <input type="text" name="local_migrant_location" value="{{ old('local_migrant_location') }}" placeholder="e.g., Manila, Cebu"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sea-green focus:border-transparent transition shadow-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 justify-end pt-6 border-t border-slate-100">
                        <a href="{{ route('citizen.profile.index') }}" class="px-6 py-3 border-2 border-gray-300 rounded-xl font-bold text-gray-700 hover:bg-gray-50 transition text-center shadow-sm">
                            Cancel
                        </a>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-deep-forest to-sea-green hover:from-sea-green hover:to-deep-forest text-white rounded-xl font-bold shadow-lg transition">
                            Add Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function toggleOFWSection(type) {
        const checkbox = document.getElementById('is_' + (type === 'active' ? 'active_ofw' : type === 'returned' ? 'returned_ofw' : 'local_migrant'));
        const details = document.getElementById((type === 'active' ? 'active' : type === 'returned' ? 'returned' : 'local') + '-' + (type === 'local' ? 'migrant' : 'ofw') + '-details');
        if (checkbox.checked) {
            details.classList.remove('hidden');
        } else {
            details.classList.add('hidden');
        }
    }
</script>
@endpush
