@extends('layouts.admin')

@section('title', 'Edit Household Head')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">

    {{-- Breadcrumb --}}
    <nav class="mb-5">
        <ol class="flex items-center gap-2 text-sm text-gray-500">
            <li><a href="{{ route('admin.households.index') }}" class="text-sea-green hover:text-deep-forest font-semibold transition-colors">🏠 Households</a></li>
            <li><span class="text-gray-300">/</span></li>
            <li><a href="{{ route('admin.households.show', $householdHead->household) }}" class="text-sea-green hover:text-deep-forest font-semibold transition-colors">{{ $householdHead->household->household_number }}</a></li>
            <li><span class="text-gray-300">/</span></li>
            <li class="text-gray-700 font-medium">Edit Family</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    <div class="mb-5">
        <h1 class="text-2xl font-bold text-deep-forest flex items-center gap-2">
            <span class="w-8 h-8 rounded-lg bg-sea-green/10 text-sea-green flex items-center justify-center text-base">✏️</span>
            Edit Family Unit
        </h1>
        <p class="text-gray-500 text-sm mt-1">Update details for <span class="font-semibold text-deep-forest">{{ $householdHead->household_head_number }}</span>.</p>
    </div>

    {{-- Household address badge --}}
    <div class="mb-5 flex items-center gap-3 bg-sea-green/5 border border-sea-green/20 rounded-lg px-4 py-3">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-sea-green/10 text-sea-green border border-sea-green/20">
            {{ $householdHead->household_head_number }}
        </span>
        <span class="text-sm text-deep-forest font-medium">{{ $householdHead->household->full_address }}</span>
    </div>

    {{-- Validation Errors --}}
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

    @php
        $isLinked = !is_null($householdHead->resident_id);
        $defaultMode = old('entry_mode', $isLinked ? 'resident' : 'manual');
    @endphp

    <div x-data="{
        mode: '{{ $defaultMode }}',
        hasResidents: {{ $availableResidents->isNotEmpty() ? 'true' : 'false' }}
    }">
        <form method="POST" action="{{ route('admin.households.head.update', $householdHead) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="entry_mode" :value="mode">

            <div class="space-y-5">

                {{-- ── Entry Mode Toggle ── --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <p class="text-sm font-bold text-deep-forest mb-3">Household head registration type</p>

                    <div class="flex flex-col sm:flex-row gap-3">
                        {{-- Link a registered resident --}}
                        <label :class="mode === 'resident' ? 'border-sea-green bg-sea-green/5' : 'border-gray-200 bg-white hover:border-sea-green/40'"
                               class="flex items-start gap-3 cursor-pointer rounded-lg border-2 p-3.5 flex-1 transition-colors"
                               :title="!hasResidents ? 'No available residents from this barangay.' : ''">
                            <input type="radio" x-model="mode" value="resident"
                                   :disabled="!hasResidents"
                                   class="mt-0.5 accent-sea-green disabled:opacity-40">
                            <div>
                                <span class="text-sm font-semibold text-slate-700"
                                      :class="!hasResidents ? 'opacity-40' : ''">Link a Registered Resident</span>
                                <p class="text-xs text-slate-500 mt-0.5"
                                   :class="!hasResidents ? 'opacity-40' : ''">
                                    @if($availableResidents->isNotEmpty())
                                        {{ $availableResidents->count() }} resident(s) from {{ $householdHead->household->barangay }} available.
                                    @else
                                        No residents from {{ $householdHead->household->barangay }} are available to assign.
                                    @endif
                                </p>
                            </div>
                        </label>

                        {{-- Manual entry --}}
                        <label :class="mode === 'manual' ? 'border-sea-green bg-sea-green/5' : 'border-gray-200 bg-white hover:border-sea-green/40'"
                               class="flex items-start gap-3 cursor-pointer rounded-lg border-2 p-3.5 flex-1 transition-colors">
                            <input type="radio" x-model="mode" value="manual" class="mt-0.5 accent-sea-green">
                            <div>
                                <span class="text-sm font-semibold text-slate-700">Enter Name Manually</span>
                                <p class="text-xs text-slate-500 mt-0.5">Head is not yet in the system — enter their name directly.</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- ── Resident Selector (resident mode) ── --}}
                <div x-show="mode === 'resident'" x-transition
                     class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <h2 class="text-base font-bold text-deep-forest mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 rounded bg-sea-green/10 text-sea-green flex items-center justify-center text-xs">👤</span>
                        Select Resident as Household Head
                    </h2>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Resident <span class="text-red-500">*</span>
                        </label>
                        <select name="resident_id"
                                :required="mode === 'resident'"
                                class="w-full px-3.5 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent transition
                                       {{ $errors->has('resident_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            <option value="">Select a registered resident...</option>
                            @foreach($availableResidents as $resident)
                                <option value="{{ $resident->id }}"
                                    {{ old('resident_id', $householdHead->resident_id) == $resident->id ? 'selected' : '' }}>
                                    {{ $resident->full_name }} — {{ $resident->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('resident_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1.5">
                            Only showing residents from <strong>{{ $householdHead->household->barangay }}</strong> who are not already household heads.
                        </p>
                    </div>
                </div>

                {{-- ── Manual Name Entry (manual mode) ── --}}
                <div x-show="mode === 'manual'" x-transition
                     class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <h2 class="text-base font-bold text-deep-forest mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 rounded bg-sea-green/10 text-sea-green flex items-center justify-center text-xs">✏️</span>
                        Head's Name
                    </h2>

                    {{-- Row 1: Last Name + Extension Name --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="head_last_name"
                                   value="{{ old('head_last_name', $householdHead->head_last_name) }}"
                                   :required="mode === 'manual'"
                                   maxlength="100" placeholder="e.g., Dela Cruz"
                                   class="w-full px-3.5 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent transition
                                          {{ $errors->has('head_last_name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            @error('head_last_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-400 mt-1">Used as the family surname for member linking.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Extension</label>
                            <select name="head_extension_name"
                                    class="w-full px-3.5 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent transition">
                                <option value="">None</option>
                                @foreach(['Jr.', 'Sr.', 'II', 'III', 'IV', 'V'] as $ext)
                                    <option value="{{ $ext }}"
                                        {{ old('head_extension_name', $householdHead->head_extension_name) === $ext ? 'selected' : '' }}>{{ $ext }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Row 2: First Name --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="head_first_name"
                               value="{{ old('head_first_name', $householdHead->head_first_name) }}"
                               :required="mode === 'manual'"
                               maxlength="100" placeholder="e.g., Juan"
                               class="w-full px-3.5 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent transition
                                      {{ $errors->has('head_first_name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        @error('head_first_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-start gap-2.5 mt-4 bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-3">
                        <span class="text-slate-400 text-sm leading-none mt-0.5 flex-shrink-0">ℹ</span>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            This family unit will not be linked to a resident account.
                            You can link a registered resident later by switching the mode above.
                        </p>
                    </div>
                </div>

                {{-- ── Family Details Card ── --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <h2 class="text-base font-bold text-deep-forest mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 rounded bg-sea-green/10 text-sea-green flex items-center justify-center text-xs">🏷</span>
                        Family Details
                    </h2>

                    <div class="space-y-4">
                        {{-- 4Ps Beneficiary --}}
                        <label class="flex items-start gap-3 cursor-pointer rounded-lg border border-gray-200 p-3.5 hover:border-sea-green/40 transition-colors">
                            <input type="checkbox" name="is_4ps_beneficiary" value="1"
                                   {{ old('is_4ps_beneficiary', $householdHead->is_4ps_beneficiary) ? 'checked' : '' }}
                                   class="mt-0.5 w-4 h-4 rounded accent-sea-green">
                            <div>
                                <span class="text-sm font-semibold text-gray-700">4Ps Beneficiary</span>
                                <p class="text-xs text-gray-500 mt-0.5">This family is a Pantawid Pamilyang Pilipino Program beneficiary.</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- ── Actions ── --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.households.show', $householdHead->household) }}"
                       class="px-5 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-sea-green hover:bg-deep-forest text-white rounded-lg text-sm font-bold transition-colors shadow-sm flex items-center gap-2">
                        <span>💾</span> Save Changes
                    </button>
                </div>

            </div>
        </form>
    </div>

</div>
@endsection
