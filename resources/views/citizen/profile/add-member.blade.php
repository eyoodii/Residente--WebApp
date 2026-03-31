@extends('layouts.citizen')

@section('title', 'Add Household Member | RESIDENTE App')
@section('page-title', 'Add Household Member')

@section('content')
    <div class="p-4 sm:p-8">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                <a href="{{ route('citizen.profile.index') }}" class="text-sea-green hover:text-deep-forest font-bold text-sm flex items-center gap-1">
                    ← Back to Profile
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-deep-forest px-6 py-4">
                    <h1 class="text-2xl font-bold text-white">Add Household Member</h1>
                </div>

                <form method="POST" action="{{ route('citizen.profile.members.store') }}" class="p-4 sm:p-6">
                    @csrf

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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- First Name -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                        </div>

                        <!-- Middle Name -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                        </div>

                        <!-- Extension Name -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Ext. Name</label>
                            <select name="extension_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                                <option value="">None</option>
                                <option value="Jr." {{ old('extension_name') == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                <option value="Sr." {{ old('extension_name') == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                <option value="II" {{ old('extension_name') == 'II' ? 'selected' : '' }}>II</option>
                                <option value="III" {{ old('extension_name') == 'III' ? 'selected' : '' }}>III</option>
                                <option value="IV" {{ old('extension_name') == 'IV' ? 'selected' : '' }}>IV</option>
                                <option value="V" {{ old('extension_name') == 'V' ? 'selected' : '' }}>V</option>
                            </select>
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Date of Birth <span class="text-red-500">*</span></label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                        </div>

                        <!-- Gender -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Gender <span class="text-red-500">*</span></label>
                            <select name="gender" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <!-- Relationship -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Relationship <span class="text-red-500">*</span></label>
                            <select name="relationship" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
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

                        <!-- Civil Status -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Civil Status <span class="text-red-500">*</span></label>
                            <select name="civil_status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                                <option value="">Select Status</option>
                                <option value="Single" {{ old('civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ old('civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Widowed" {{ old('civil_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                <option value="Legally Separated" {{ old('civil_status') == 'Legally Separated' ? 'selected' : '' }}>Legally Separated</option>
                            </select>
                        </div>

                        <!-- Occupation -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Occupation</label>
                            <input type="text" name="occupation" value="{{ old('occupation') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                        </div>

                        <!-- Monthly Income -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Monthly Income (₱)</label>
                            <input type="number" name="monthly_income" value="{{ old('monthly_income') }}" min="0" step="0.01"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                        </div>

                        <!-- Educational Attainment -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Educational Attainment</label>
                            <select name="educational_attainment" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
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

                    <!-- Special Sectors (Checkboxes) -->
                    <div class="mt-6">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Special Sectors (if applicable)</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_pwd" value="1" {{ old('is_pwd') ? 'checked' : '' }}
                                    class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                <span class="text-sm text-gray-700">Person with Disability (PWD)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_senior_citizen" value="1" {{ old('is_senior_citizen') ? 'checked' : '' }}
                                    class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                <span class="text-sm text-gray-700">Senior Citizen</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_solo_parent" value="1" {{ old('is_solo_parent') ? 'checked' : '' }}
                                    class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                <span class="text-sm text-gray-700">Solo Parent</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_indigenous_people" value="1" {{ old('is_indigenous_people') ? 'checked' : '' }}
                                    class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                <span class="text-sm text-gray-700">Indigenous People</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_4ps_beneficiary" value="1" {{ old('is_4ps_beneficiary') ? 'checked' : '' }}
                                    class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                <span class="text-sm text-gray-700">4Ps Beneficiary</span>
                            </label>
                        </div>
                    </div>

                    <!-- OFW Information Section -->
                    <div class="mt-8 p-6 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">🌏 OFW / Migrant Worker Information</h3>
                            <span class="text-xs text-gray-500 italic">Optional - Skip if not applicable</span>
                        </div>

                        <!-- Active OFW -->
                        <div class="mb-6">
                            <label class="flex items-center gap-2 cursor-pointer mb-3">
                                <input type="checkbox" name="is_active_ofw" id="is_active_ofw" value="1" {{ old('is_active_ofw') ? 'checked' : '' }}
                                    onchange="toggleOFWSection('active')"
                                    class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                <span class="text-sm font-bold text-gray-900">Currently Working Abroad (Active OFW)</span>
                            </label>

                            <div id="active-ofw-details" class="ml-6 space-y-4 {{ old('is_active_ofw') ? '' : 'hidden' }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Country of Work</label>
                                        <input type="text" name="ofw_country" value="{{ old('ofw_country') }}" placeholder="e.g., Saudi Arabia, Singapore"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Nature of Work</label>
                                        <input type="text" name="ofw_nature_of_work" value="{{ old('ofw_nature_of_work') }}" placeholder="e.g., Domestic Helper, Nurse, Engineer"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Year Deployed</label>
                                        <input type="number" name="ofw_year_deployed" value="{{ old('ofw_year_deployed') }}" min="1900" max="{{ date('Y') + 1 }}" placeholder="YYYY"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Returned OFW -->
                        <div class="mb-6 pt-6 border-t border-gray-300">
                            <label class="flex items-center gap-2 cursor-pointer mb-3">
                                <input type="checkbox" name="is_returned_ofw" id="is_returned_ofw" value="1" {{ old('is_returned_ofw') ? 'checked' : '' }}
                                    onchange="toggleOFWSection('returned')"
                                    class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                <span class="text-sm font-bold text-gray-900">Returned OFW (Last 5 Years)</span>
                            </label>

                            <div id="returned-ofw-details" class="ml-6 space-y-4 {{ old('is_returned_ofw') ? '' : 'hidden' }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Year Returned</label>
                                        <input type="number" name="ofw_year_returned" value="{{ old('ofw_year_returned') }}" min="1900" max="{{ date('Y') }}" placeholder="YYYY"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Nature of Return</label>
                                        <select name="ofw_nature_of_return" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                                            <option value="">Select Nature</option>
                                            <option value="Permanent" {{ old('ofw_nature_of_return') == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                            <option value="Temporary" {{ old('ofw_nature_of_return') == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                                            <option value="Vacation" {{ old('ofw_nature_of_return') == 'Vacation' ? 'selected' : '' }}>Vacation</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Local Migrant -->
                        <div class="pt-6 border-t border-gray-300">
                            <label class="flex items-center gap-2 cursor-pointer mb-3">
                                <input type="checkbox" name="is_local_migrant" id="is_local_migrant" value="1" {{ old('is_local_migrant') ? 'checked' : '' }}
                                    onchange="toggleOFWSection('local')"
                                    class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                                <span class="text-sm font-bold text-gray-900">Local Migrant Worker</span>
                                <span class="text-xs text-gray-500">(Working outside province for 0-6 months)</span>
                            </label>

                            <div id="local-migrant-details" class="ml-6 {{ old('is_local_migrant') ? '' : 'hidden' }}">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Current Location</label>
                                    <input type="text" name="local_migrant_location" value="{{ old('local_migrant_location') }}" placeholder="e.g., Manila, Cebu"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex gap-4 justify-end">
                        <a href="{{ route('citizen.profile.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg font-bold text-gray-700 hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-tiger-orange hover:bg-burnt-tangerine text-white rounded-lg font-bold shadow transition">
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
