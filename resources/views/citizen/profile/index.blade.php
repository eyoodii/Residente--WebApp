@extends('layouts.citizen')

@section('title', 'My Profile | RESIDENTE App')
@section('page-title', 'My Profile')
@section('page-subtitle', 'Personal & Household Information')

@section('header-actions')
    @if($resident->is_verified)
        <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-full border border-emerald-200">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            Verified
        </span>
    @else
        <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold bg-amber-50 text-amber-700 px-3 py-1.5 rounded-full border border-amber-200">
            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
            Pending
        </span>
    @endif
@endsection

@section('content')
<div class="px-4 lg:px-6 py-6 space-y-5">

    {{-- Personal Information --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200">
        <div class="px-5 lg:px-6 py-4 flex justify-between items-center border-b border-gray-100">
            <div class="flex items-center gap-2.5">
                <span class="w-7 h-7 rounded-lg bg-deep-forest/5 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-deep-forest" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                </span>
                <h3 class="text-[14px] font-bold text-gray-800">Personal Information</h3>
            </div>
            <a href="{{ route('citizen.profile.personal.edit') }}" class="text-[12px] font-semibold text-sea-green hover:text-deep-forest transition">Edit</a>
        </div>
        <div class="px-5 lg:px-6 py-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-4">
            @php
                $personalFields = [
                    ['label' => 'Full Name', 'value' => $resident->full_name, 'bold' => true],
                    ['label' => 'Email', 'value' => $resident->email],
                    ['label' => 'Contact', 'value' => $resident->contact_number ?? 'Not set'],
                    ['label' => 'Date of Birth', 'value' => $resident->date_of_birth ? $resident->date_of_birth->format('M d, Y') . ' (' . $resident->age . ' yrs)' : 'Not set'],
                    ['label' => 'Place of Birth', 'value' => $resident->place_of_birth ?? 'Not set'],
                    ['label' => 'Gender', 'value' => $resident->gender ?? 'Not set'],
                    ['label' => 'Civil Status', 'value' => $resident->civil_status ?? 'Not set'],
                    ['label' => 'Blood Type', 'value' => $resident->blood_type ?? 'Not specified'],
                    ['label' => 'Occupation', 'value' => $resident->occupation ?? 'Not specified'],
                    ['label' => 'Vulnerable Sector', 'value' => $resident->vulnerable_sector ?? 'None'],
                ];
            @endphp
            @foreach($personalFields as $f)
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-1">{{ $f['label'] }}</p>
                    <p class="text-[13px] {{ ($f['value'] === 'Not set' || $f['value'] === 'Not specified' || $f['value'] === 'None') ? 'text-gray-300 italic' : 'text-gray-800' }} {{ ($f['bold'] ?? false) ? 'font-semibold' : '' }}">{{ $f['value'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Address --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200">
        <div class="px-5 lg:px-6 py-4 flex justify-between items-center border-b border-gray-100">
            <div class="flex items-center gap-2.5">
                <span class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                </span>
                <h3 class="text-[14px] font-bold text-gray-800">Address</h3>
            </div>
            <a href="{{ route('citizen.profile.address.edit') }}" class="text-[12px] font-semibold text-sea-green hover:text-deep-forest transition">Edit</a>
        </div>
        <div class="px-5 lg:px-6 py-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-4">
            <div>
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-1">Purok / Street</p>
                <p class="text-[13px] {{ ($resident->purok ?? null) ? 'text-gray-800' : 'text-gray-300 italic' }}">{{ $resident->purok ?? 'Not set' }}</p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-1">Barangay</p>
                <p class="text-[13px] text-gray-800">{{ $resident->barangay }}</p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-1">Municipality</p>
                <p class="text-[13px] text-gray-800">{{ $resident->municipality ?? 'Buguey' }}</p>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-1">Province</p>
                <p class="text-[13px] text-gray-800">{{ $resident->province ?? 'Cagayan' }}</p>
            </div>
        </div>
    </div>

    {{-- Household Profile --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200">
        <div class="px-5 lg:px-6 py-4 flex justify-between items-center border-b border-gray-100">
            <div class="flex items-center gap-2.5">
                <span class="w-7 h-7 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819"/></svg>
                </span>
                <h3 class="text-[14px] font-bold text-gray-800">Household Profile</h3>
            </div>
            <a href="{{ route('citizen.profile.household.edit') }}" class="text-[12px] font-semibold text-sea-green hover:text-deep-forest transition">
                {{ $householdProfile ? 'Edit' : 'Add' }}
            </a>
        </div>
        @if($householdProfile)
            <div class="px-5 lg:px-6 py-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-4">
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-1">Housing Type</p>
                    <p class="text-[13px] text-gray-800">{{ $householdProfile->housing_type }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-1">Dwelling Type</p>
                    <p class="text-[13px] {{ $householdProfile->dwelling_type ? 'text-gray-800' : 'text-gray-300 italic' }}">{{ $householdProfile->dwelling_type ?? 'Not specified' }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-1">Rooms</p>
                    <p class="text-[13px] {{ $householdProfile->number_of_rooms ? 'text-gray-800' : 'text-gray-300 italic' }}">{{ $householdProfile->number_of_rooms ?? 'Not specified' }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-1">Water Source</p>
                    <p class="text-[13px] {{ $householdProfile->water_source ? 'text-gray-800' : 'text-gray-300 italic' }}">{{ $householdProfile->water_source ?? 'Not specified' }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-1">Utilities</p>
                    <div class="flex gap-1.5 flex-wrap">
                        @if($householdProfile->has_electricity)<span class="text-[11px] bg-yellow-50 text-yellow-700 px-2 py-0.5 rounded-lg font-medium border border-yellow-100">Electricity</span>@endif
                        @if($householdProfile->has_water_supply)<span class="text-[11px] bg-blue-50 text-blue-700 px-2 py-0.5 rounded-lg font-medium border border-blue-100">Water</span>@endif
                        @if($householdProfile->has_internet_access)<span class="text-[11px] bg-purple-50 text-purple-700 px-2 py-0.5 rounded-lg font-medium border border-purple-100">Internet</span>@endif
                        @if(!$householdProfile->has_electricity && !$householdProfile->has_water_supply && !$householdProfile->has_internet_access)
                            <span class="text-[12px] text-gray-300 italic">None listed</span>
                        @endif
                    </div>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-1">Monthly Income</p>
                    <p class="text-[13px] {{ $householdProfile->total_household_income ? 'text-gray-800' : 'text-gray-300 italic' }}">{{ $householdProfile->total_household_income ? '₱' . number_format($householdProfile->total_household_income, 2) : 'Not disclosed' }}</p>
                </div>
            </div>
        @else
            <div class="px-5 lg:px-6 py-12 text-center">
                <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819"/></svg>
                <p class="text-[13px] text-gray-400 mb-1">No household profile yet.</p>
                <p class="text-[12px] text-gray-300">Click "Add" to provide household information.</p>
            </div>
        @endif
    </div>

    {{-- Household Members --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200">
        <div class="px-5 lg:px-6 py-4 flex justify-between items-center border-b border-gray-100">
            <div class="flex items-center gap-2.5">
                <span class="w-7 h-7 rounded-lg bg-violet-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                </span>
                <h3 class="text-[14px] font-bold text-gray-800">Household Members</h3>
                @if($householdMembers && $householdMembers->count() > 0)
                    <span class="text-[10px] font-bold bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">{{ $householdMembers->count() }}</span>
                @endif
            </div>
            <a href="{{ route('citizen.profile.members.add') }}" class="inline-flex items-center gap-1 text-[12px] font-semibold text-sea-green hover:text-deep-forest transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Member
            </a>
        </div>
        @if($householdMembers && $householdMembers->count() > 0)
            <div class="divide-y divide-gray-50">
                @foreach($householdMembers as $member)
                    <div class="px-5 lg:px-6 py-4 hover:bg-gray-50/50 transition-colors">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-gray-100 to-gray-50 text-gray-500 flex items-center justify-center font-bold text-[13px] flex-shrink-0 border border-gray-100">
                                    {{ strtoupper(substr($member->first_name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[13px] font-bold text-gray-800 truncate">{{ $member->full_name }}</p>
                                    <p class="text-[11px] text-gray-400 mt-0.5">{{ $member->relationship }} · {{ $member->age }} yrs · {{ $member->gender }}@if($member->occupation) · {{ $member->occupation }}@endif</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('citizen.profile.members.delete', $member) }}" onsubmit="return confirm('Remove this member?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[11px] font-semibold text-gray-300 hover:text-red-500 transition">Remove</button>
                            </form>
                        </div>

                        @if($member->is_active_ofw || $member->is_returned_ofw || $member->is_local_migrant || $member->is_pwd || $member->is_senior_citizen || $member->is_solo_parent || $member->is_indigenous_people || $member->is_4ps_beneficiary)
                            <div class="flex gap-1.5 flex-wrap mt-2.5 ml-12">
                                @if($member->is_active_ofw)<span class="text-[10px] bg-blue-50 text-blue-600 px-2.5 py-0.5 rounded-lg font-semibold border border-blue-100">Active OFW</span>@endif
                                @if($member->is_returned_ofw)<span class="text-[10px] bg-indigo-50 text-indigo-600 px-2.5 py-0.5 rounded-lg font-semibold border border-indigo-100">Returned OFW</span>@endif
                                @if($member->is_local_migrant)<span class="text-[10px] bg-purple-50 text-purple-600 px-2.5 py-0.5 rounded-lg font-semibold border border-purple-100">Local Migrant</span>@endif
                                @if($member->is_pwd)<span class="text-[10px] bg-yellow-50 text-yellow-700 px-2.5 py-0.5 rounded-lg font-semibold border border-yellow-100">PWD</span>@endif
                                @if($member->is_senior_citizen)<span class="text-[10px] bg-orange-50 text-orange-600 px-2.5 py-0.5 rounded-lg font-semibold border border-orange-100">Senior Citizen</span>@endif
                                @if($member->is_solo_parent)<span class="text-[10px] bg-pink-50 text-pink-600 px-2.5 py-0.5 rounded-lg font-semibold border border-pink-100">Solo Parent</span>@endif
                                @if($member->is_indigenous_people)<span class="text-[10px] bg-green-50 text-green-600 px-2.5 py-0.5 rounded-lg font-semibold border border-green-100">Indigenous People</span>@endif
                                @if($member->is_4ps_beneficiary)<span class="text-[10px] bg-red-50 text-red-600 px-2.5 py-0.5 rounded-lg font-semibold border border-red-100">4Ps Beneficiary</span>@endif
                            </div>
                        @endif

                        @if($member->is_active_ofw || $member->is_returned_ofw || $member->is_local_migrant)
                            <div class="mt-3 ml-12 p-3 bg-gray-50 rounded-xl border border-gray-100/50">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">OFW / Migrant Details</p>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-1.5 text-[12px]">
                                    @if($member->is_active_ofw)
                                        @if($member->ofw_country)<div><span class="text-gray-400">Country:</span> <span class="text-gray-700">{{ $member->ofw_country }}</span></div>@endif
                                        @if($member->ofw_nature_of_work)<div><span class="text-gray-400">Work:</span> <span class="text-gray-700">{{ $member->ofw_nature_of_work }}</span></div>@endif
                                        @if($member->ofw_year_deployed)<div><span class="text-gray-400">Deployed:</span> <span class="text-gray-700">{{ $member->ofw_year_deployed }}</span></div>@endif
                                    @endif
                                    @if($member->is_returned_ofw)
                                        @if($member->ofw_year_returned)<div><span class="text-gray-400">Returned:</span> <span class="text-gray-700">{{ $member->ofw_year_returned }}</span></div>@endif
                                        @if($member->ofw_nature_of_return)<div><span class="text-gray-400">Return Type:</span> <span class="text-gray-700">{{ $member->ofw_nature_of_return }}</span></div>@endif
                                    @endif
                                    @if($member->is_local_migrant && $member->local_migrant_location)
                                        <div><span class="text-gray-400">Working in:</span> <span class="text-gray-700">{{ $member->local_migrant_location }}</span></div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-5 lg:px-6 py-12 text-center">
                <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                <p class="text-[13px] text-gray-400 mb-1">No household members yet.</p>
                <p class="text-[12px] text-gray-300">Add your family members to complete your profile.</p>
            </div>
        @endif
    </div>

</div>
@endsection
