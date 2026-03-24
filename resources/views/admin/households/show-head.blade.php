@extends('layouts.admin')

@section('title', 'Family Details — ' . $householdHead->household_head_number)

@section('content')
@php
    $memberCount = $householdHead->members->count();
@endphp
<div x-data="householdHeadPage()" class="max-w-5xl mx-auto px-4 py-8">
    {{-- Breadcrumb --}}
    <nav class="mb-5">
        <ol class="flex items-center gap-2 text-sm text-gray-500">
            <li><a href="{{ route('admin.households.index') }}" class="text-sea-green hover:text-deep-forest font-semibold transition-colors">🏠 Households</a></li>
            <li><span class="text-gray-300">/</span></li>
            <li><a href="{{ route('admin.households.show', $householdHead->household) }}" class="text-sea-green hover:text-deep-forest font-semibold transition-colors">{{ $householdHead->household->household_number }}</a></li>
            <li><span class="text-gray-300">/</span></li>
            <li class="text-gray-700 font-medium">{{ $householdHead->household_head_number }}</li>
        </ol>
    </nav>

    {{-- ── Header Card ── --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 mb-5">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 flex-wrap mb-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-sea-green/10 text-sea-green border border-sea-green/20">
                        {{ $householdHead->household_head_number }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                        {{ $householdHead->household->household_number }}
                    </span>
                    @if($householdHead->is_primary_family)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-golden-glow/20 text-yellow-700 border border-golden-glow/40">Primary</span>
                    @endif
                    @if($householdHead->is_4ps_beneficiary)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200">4Ps</span>
                    @endif
                </div>
                <h1 class="text-xl font-bold text-deep-forest">{{ $householdHead->head_name }} Family</h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    Surname: <span class="font-semibold text-gray-700">{{ $householdHead->surname }}</span>
                    &nbsp;·&nbsp;
                    {{ $memberCount }} {{ Str::plural('member', $memberCount) }}
                    &nbsp;·&nbsp;
                    {{ $householdHead->household->full_address }}
                </p>
            </div>
            <div class="flex flex-col sm:items-end gap-2 flex-shrink-0">
                <a href="{{ route('admin.households.member.create', $householdHead) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-sea-green hover:bg-deep-forest text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                    <span>👥</span> + Member
                </a>
                <button type="button"
                        @click="openAddCoHeadModal()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-sea-green/10 text-sea-green text-sm font-semibold rounded-lg hover:bg-sea-green/20 transition-colors border border-sea-green/20">
                    <span>👤</span> + Co-Head
                </button>
                <a href="{{ route('admin.households.head.create', $householdHead->household) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-600 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors border border-gray-200">
                    <span>🏡</span> + Secondary Head (HHN)
                </a>
                <a href="{{ route('admin.households.head.edit', $householdHead) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 text-gray-600 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                    <span>✏️</span> Edit Family
                </a>
                <button type="button"
                        onclick="document.getElementById('archive-modal').classList.remove('hidden')"
                        class="inline-flex items-center gap-2 px-4 py-2 border border-red-200 text-red-600 text-sm font-semibold rounded-lg hover:bg-red-50 transition-colors">
                    <span>🗄️</span> Archive
                </button>
            </div>
        </div>
    </div>

    {{-- ── Flash --}}
    @if(session('success'))
        <div class="mb-5 bg-green-50 border border-green-200 rounded-lg px-4 py-3 flex items-center gap-3">
            <span class="text-green-600">✓</span>
            <p class="text-sm font-semibold text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    {{-- ── Household Head Info ── --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 mb-5">
        <h2 class="text-sm font-bold text-deep-forest uppercase tracking-widest mb-4 flex items-center gap-2">
            <span>👤</span> Household Head
        </h2>
        @if($householdHead->resident)
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-sea-green/10 flex items-center justify-center text-sea-green font-bold text-base flex-shrink-0">
                    {{ strtoupper(substr($householdHead->resident->first_name, 0, 1)) }}{{ strtoupper(substr($householdHead->resident->last_name, 0, 1)) }}
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-2 flex-1">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Full Name</p>
                        <p class="text-sm font-semibold text-deep-forest">{{ $householdHead->resident->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Email</p>
                        <p class="text-sm text-gray-700">{{ $householdHead->resident->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Contact</p>
                        <p class="text-sm text-gray-700">{{ $householdHead->resident->contact_number ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Civil Status</p>
                        <p class="text-sm text-gray-700">{{ $householdHead->resident->civil_status }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Occupation</p>
                        <p class="text-sm text-gray-700">{{ $householdHead->resident->occupation ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">National ID</p>
                        <p class="text-sm text-gray-700">{{ $householdHead->resident->national_id ?? '—' }}</p>
                    </div>
                </div>
            </div>
        @else
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 font-bold text-base flex-shrink-0">
                    {{ strtoupper(substr($householdHead->surname, 0, 2)) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-deep-forest">{{ $householdHead->head_name }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Manually registered — not linked to a resident account.</p>
                </div>
            </div>
        @endif
    </div>

    {{-- ── Registered Family Members (linked residents) ── --}}
    @if($linkedResidents->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-5">
            <div class="px-5 py-3.5 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-bold text-deep-forest uppercase tracking-widest flex items-center gap-2">
                    <span>🪪</span> Registered Family Members
                    <span class="text-gray-400 font-normal normal-case tracking-normal">({{ $linkedResidents->count() }})</span>
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Relationship</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">National ID</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($linkedResidents as $resident)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-sea-green/10 flex items-center justify-center text-sea-green font-bold text-xs flex-shrink-0">
                                            {{ strtoupper(substr($resident->first_name, 0, 1)) }}{{ strtoupper(substr($resident->last_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-deep-forest">{{ $resident->full_name }}</p>
                                            <p class="text-xs text-gray-400">{{ $resident->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-gray-600">{{ $resident->household_relationship ?? 'Member' }}</td>
                                <td class="px-5 py-3.5 text-gray-600">{{ $resident->contact_number ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-gray-600">{{ $resident->national_id ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- ── Household Members (HHM) ── --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-5">
        <div class="px-5 py-3.5 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-deep-forest uppercase tracking-widest flex items-center gap-2">
                <span>👥</span> Household Members (HHM)
                <span class="text-gray-400 font-normal normal-case tracking-normal">({{ $memberCount }})</span>
            </h2>
            @if($memberCount > 0)
                <a href="{{ route('admin.households.member.create', $householdHead) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-sea-green/10 text-sea-green text-xs font-semibold rounded-lg hover:bg-sea-green/20 transition-colors border border-sea-green/20">
                    + Add Member
                </a>
            @endif
        </div>

        @if($memberCount > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">HHM</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Relationship</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Age / DOB</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tags</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Linked</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($householdHead->members as $member)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-sea-green/10 text-sea-green border border-sea-green/20">
                                        {{ $member->member_number ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-xs flex-shrink-0">
                                            {{ strtoupper(substr($member->first_name, 0, 1)) }}{{ strtoupper(substr($member->last_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-deep-forest">{{ $member->full_name }}</p>
                                            <p class="text-xs text-gray-400">{{ $member->gender }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($member->relationship === 'Co-Head')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-sea-green/10 text-sea-green border border-sea-green/20">Co-Head</span>
                                    @else
                                        <span class="text-gray-600 text-sm">{{ $member->relationship }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="text-gray-700">{{ $member->age }} yrs</p>
                                    <p class="text-xs text-gray-400">{{ $member->date_of_birth->format('M d, Y') }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex flex-wrap gap-1">
                                        @if($member->is_pwd)
                                            <span class="px-1.5 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-700 border border-red-200">PWD</span>
                                        @endif
                                        @if($member->is_senior_citizen)
                                            <span class="px-1.5 py-0.5 rounded text-xs font-semibold bg-orange-100 text-orange-700 border border-orange-200">Senior</span>
                                        @endif
                                        @if($member->is_4ps_beneficiary)
                                            <span class="px-1.5 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200">4Ps</span>
                                        @endif
                                        @if($member->is_active_ofw)
                                            <span class="px-1.5 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">OFW</span>
                                        @endif
                                        @if(!$member->is_pwd && !$member->is_senior_citizen && !$member->is_4ps_beneficiary && !$member->is_active_ofw)
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($member->linkedResident)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">Linked</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-200">Unlinked</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.households.member.edit', [$householdHead, $member]) }}"
                                           class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-gray-700 hover:bg-gray-50 text-xs font-semibold rounded transition-colors">
                                            ✏️ Edit
                                        </a>
                                        <button
                                            type="button"
                                            @click="openDeleteMemberModal({{ $member->id }}, '{{ addslashes($member->full_name) }}')"
                                            class="inline-flex items-center px-3 py-1.5 border border-red-300 text-red-700 hover:bg-red-50 text-xs font-semibold rounded transition-colors">
                                            🗑️ Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            {{-- Empty state --}}
            <div class="flex flex-col items-center text-center py-12 px-6">
                <div class="w-14 h-14 rounded-full bg-sea-green/10 flex items-center justify-center text-2xl mb-4">👥</div>
                <h3 class="text-base font-bold text-deep-forest mb-1">No members added yet</h3>
                <p class="text-sm text-gray-500 max-w-xs mb-5">
                    Register the first family member (HHM) under the <span class="font-semibold">{{ $householdHead->head_name }}</span> family.
                </p>
                <a href="{{ route('admin.households.member.create', $householdHead) }}"
                   class="inline-flex items-center gap-2 px-5 py-2 bg-sea-green hover:bg-deep-forest text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                    <span>👥</span> Add First Member
                </a>
            </div>
        @endif
    </div>

    {{-- ── Potential Members (Auto-Link Suggestions) ── --}}
    @if($potentialMembers->count() > 0)
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-5">
            <h3 class="text-sm font-bold text-amber-800 mb-1 flex items-center gap-2">
                <span>💡</span> Surname Match — Potential Family Members
            </h3>
            <p class="text-xs text-amber-700 mb-4">
                These residents share the <strong>{{ $householdHead->surname }}</strong> surname at this address but are not yet linked to this family.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($potentialMembers as $potential)
                    <div class="bg-white rounded-lg border border-amber-100 px-4 py-3 flex items-center justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-deep-forest">{{ $potential->full_name }}</p>
                            <p class="text-xs text-gray-400">{{ $potential->email }}</p>
                        </div>
                        <form action="{{ route('admin.households.auto-link', $householdHead) }}" method="POST">
                            @csrf
                            <input type="hidden" name="resident_id" value="{{ $potential->id }}">
                            <button type="submit"
                                    class="px-3 py-1.5 bg-sea-green hover:bg-deep-forest text-white text-xs font-semibold rounded-lg transition-colors">
                                Link
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

{{-- ── Quick Add Co-Head Modal ── --}}
<div id="add-cohead-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
    <div class="bg-white rounded-xl shadow-xl border border-gray-200 max-w-md w-full p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-lg bg-sea-green/10 text-sea-green flex items-center justify-center">👤</div>
            <h3 class="text-lg font-bold text-deep-forest">Add Co-Head Member</h3>
        </div>

        <div class="mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
            <p class="text-xs font-semibold text-gray-600 mb-1">FAMILY</p>
            <p class="text-sm font-semibold text-deep-forest">{{ $householdHead->head_name }} Family</p>
            <p class="text-xs text-gray-500 mt-1">{{ $householdHead->household->full_address }}</p>
        </div>

        {{-- Submits to storeHead so co-head gets its own HHN code --}}
        <form id="add-cohead-form" method="POST" action="{{ route('admin.households.head.store', $householdHead->household) }}">
            @csrf
            {{-- Pass the originating head id so we can redirect back here --}}
            <input type="hidden" name="from_head" value="{{ $householdHead->id }}">
            <input type="hidden" name="entry_mode" :value="coHead.mode">

            {{-- ── Entry Mode Toggle ── --}}
            <div class="mb-4 pb-4 border-b border-gray-200">
                <p class="text-xs font-bold text-deep-forest mb-2.5">How would you like to add the co-head?</p>
                
                <div class="space-y-2">
                    {{-- Link a registered resident --}}
                    <label :class="[
                               coHead.mode === 'resident' ? 'border-sea-green bg-sea-green/5' : 'border-gray-200 bg-white hover:border-sea-green/40',
                               !coHead.hasResidents ? 'opacity-50 cursor-not-allowed' : ''
                           ]"
                           class="flex items-start gap-3 cursor-pointer rounded-lg border-2 p-2.5 transition-colors">
                        <input type="radio" x-model="coHead.mode" value="resident"
                               :disabled="!coHead.hasResidents"
                               class="mt-0.5 accent-sea-green disabled:opacity-40">
                        <div class="flex-1">
                            <span class="text-xs font-semibold text-slate-700"
                                  :class="!coHead.hasResidents ? 'opacity-60' : ''">Link a Registered Resident</span>
                            <p class="text-xs text-slate-500 mt-0.5">
                                <span x-show="coHead.hasResidents" x-text="`${coHead.residentCount} available`"></span>
                                <span x-show="!coHead.hasResidents" class="text-amber-600 font-medium">No residents available</span>
                            </p>
                        </div>
                    </label>

                    {{-- Manual entry --}}
                    <label :class="coHead.mode === 'manual' ? 'border-sea-green bg-sea-green/5' : 'border-gray-200 bg-white hover:border-sea-green/40'"
                           class="flex items-start gap-3 cursor-pointer rounded-lg border-2 p-2.5 transition-colors">
                        <input type="radio" x-model="coHead.mode" value="manual" class="mt-0.5 accent-sea-green">
                        <div class="flex-1">
                            <span class="text-xs font-semibold text-slate-700">Enter Name Manually</span>
                            <p class="text-xs text-slate-500 mt-0.5">A new HHN code will be auto-generated for this co-head.</p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- ── Resident Select (resident mode) ── --}}
            <div x-show="coHead.mode === 'resident'" x-transition class="mb-4">
                <label class="block text-xs font-semibold text-gray-700 mb-1">
                    Select Resident <span class="text-red-500">*</span>
                </label>
                <select name="resident_id"
                        :required="coHead.mode === 'resident'"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent">
                    <option value="">— Choose a resident —</option>
                    <template x-for="r in coHead.residents" :key="r.id">
                        <option :value="r.id" x-text="`${r.full_name} (${r.email})`"></option>
                    </template>
                </select>
            </div>

            {{-- ── Manual Name Entry (manual mode) ── --}}
            <div x-show="coHead.mode === 'manual'" x-transition class="space-y-3 mb-4">
                <div class="p-2.5 bg-sea-green/5 border border-sea-green/20 rounded-lg">
                    <p class="text-xs text-sea-green font-semibold">A new HHN (Household Head Number) will be auto-generated for this co-head.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="head_last_name"
                               :required="coHead.mode === 'manual'"
                               maxlength="100" placeholder="e.g., Santos"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="head_first_name"
                               :required="coHead.mode === 'manual'"
                               maxlength="100" placeholder="e.g., Juan"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent">
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-200">
                <button type="button"
                        onclick="document.getElementById('add-cohead-modal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-5 py-2 bg-sea-green hover:bg-deep-forest text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                    Add Co-Head
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── Delete Member Confirmation Modal ── --}}
<div id="delete-member-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
    <div class="bg-white rounded-xl shadow-xl border border-gray-200 max-w-md w-full p-6">
        <div class="flex items-start gap-4 mb-5">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 text-red-600 text-lg">🗑️</div>
            <div>
                <h3 class="text-base font-bold text-deep-forest">Remove this member?</h3>
                <p class="text-sm text-gray-500 mt-1">
                    <strong x-text="deleteMember.name"></strong> will be removed from the family.
                    This action uses soft delete and can be restored by a developer if needed.
                </p>
            </div>
        </div>
        <div class="flex items-center justify-end gap-3">
            <button type="button"
                    onclick="document.getElementById('delete-member-modal').classList.add('hidden')"
                    class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <form :action="deleteMember.action" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                    Yes, Remove
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ── Archive Confirmation Modal ── --}}
<div id="archive-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
    <div class="bg-white rounded-xl shadow-xl border border-gray-200 max-w-md w-full p-6">
        <div class="flex items-start gap-4 mb-5">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 text-red-600 text-lg">🗄️</div>
            <div>
                <h3 class="text-base font-bold text-deep-forest">Archive this family unit?</h3>
                <p class="text-sm text-gray-500 mt-1">
                    <strong>{{ $householdHead->household_head_number }}</strong> — {{ $householdHead->head_name }} family will be archived.
                    Members and linked residents will be unaffected, but this family unit will no longer appear in active listings.
                </p>
                <p class="text-xs text-gray-400 mt-2">This action uses soft delete and can be restored by a developer if needed.</p>
            </div>
        </div>
        <div class="flex items-center justify-end gap-3">
            <button type="button"
                    onclick="document.getElementById('archive-modal').classList.add('hidden')"
                    class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <form method="POST" action="{{ route('admin.households.head.destroy', $householdHead) }}">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                    Yes, Archive
                </button>
            </form>
        </div>
    </div>
</div>

</div>{{-- end x-data wrapper --}}

<script>
function householdHeadPage() {
    return {
        deleteMember: {
            id: null,
            name: '',
            action: ''
        },
        coHead: {
            mode: 'resident',
            residents: [],
            residentCount: 0,
            hasResidents: false
        },
        openDeleteMemberModal(memberId, memberName) {
            this.deleteMember.id = memberId;
            this.deleteMember.name = memberName;
            this.deleteMember.action = `/admin/households/head/{{ $householdHead->id }}/member/${memberId}`;
            document.getElementById('delete-member-modal').classList.remove('hidden');
        },
        openAddCoHeadModal() {
            // Initialize residents data
            this.coHead.residents = @json($availableResidents->map(fn($r) => ['id' => $r->id, 'full_name' => $r->full_name, 'email' => $r->email])->values());
            this.coHead.residentCount = this.coHead.residents.length;
            this.coHead.hasResidents = this.coHead.residents.length > 0;
            // Default to resident mode if available, else manual
            this.coHead.mode = this.coHead.residents.length > 0 ? 'resident' : 'manual';
            document.getElementById('add-cohead-modal').classList.remove('hidden');
        }
    };
}
</script>
@endsection
