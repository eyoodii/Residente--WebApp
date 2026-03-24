@extends('layouts.admin')

@section('title', 'Add Member — ' . $householdHead->household_head_number)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">

    {{-- Breadcrumb --}}
    <nav class="mb-5">
        <ol class="flex items-center gap-2 text-sm text-gray-500">
            <li><a href="{{ route('admin.households.index') }}" class="text-sea-green hover:text-deep-forest font-semibold transition-colors">🏠 Households</a></li>
            <li><span class="text-gray-300">/</span></li>
            <li><a href="{{ route('admin.households.show', $householdHead->household) }}" class="text-sea-green hover:text-deep-forest font-semibold transition-colors">{{ $householdHead->household->household_number }}</a></li>
            <li><span class="text-gray-300">/</span></li>
            <li><a href="{{ route('admin.households.head.show', $householdHead) }}" class="text-sea-green hover:text-deep-forest font-semibold transition-colors">{{ $householdHead->household_head_number }}</a></li>
            <li><span class="text-gray-300">/</span></li>
            <li class="text-gray-700 font-medium">Add Member</li>
        </ol>
    </nav>

    {{-- Page Header --}}
    @php $isCoHead = request()->boolean('co_head'); @endphp
    <div class="mb-5">
        <h1 class="text-2xl font-bold text-deep-forest flex items-center gap-2">
            <span class="w-8 h-8 rounded-lg bg-sea-green/10 text-sea-green flex items-center justify-center text-base">{{ $isCoHead ? '👤' : '👥' }}</span>
            {{ $isCoHead ? 'Add Secondary Household Head' : 'Add Household Member' }}
        </h1>
        <p class="text-gray-500 text-sm mt-1">
            @if($isCoHead)
                Register the co-head or acting head for the <span class="font-semibold text-deep-forest">{{ $householdHead->head_name }}</span> family.
            @else
                Register a new member (HHM) under the <span class="font-semibold text-deep-forest">{{ $householdHead->head_name }}</span> family.
            @endif
        </p>
    </div>

    {{-- Family context badge --}}
    <div class="mb-5 flex items-center gap-3 bg-sea-green/5 border border-sea-green/20 rounded-lg px-4 py-3 flex-wrap">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
            {{ $householdHead->household->household_number }}
        </span>
        <span class="text-gray-300 text-xs">›</span>
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

    <form method="POST" action="{{ route('admin.households.member.store', $householdHead) }}">
        @csrf

        <div class="space-y-5">

            {{-- ── Personal Information ── --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <h2 class="text-base font-bold text-deep-forest mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-sea-green/10 text-sea-green flex items-center justify-center text-xs">👤</span>
                    Personal Information
                </h2>

                {{-- Row 1: Last Name + First Name + Middle Name --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="last_name"
                               value="{{ old('last_name', $householdHead->surname) }}"
                               required maxlength="255" placeholder="e.g., Dela Cruz"
                               class="w-full px-3.5 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent transition
                                      {{ $errors->has('last_name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        @error('last_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="first_name"
                               value="{{ old('first_name') }}"
                               required maxlength="255" placeholder="e.g., Juan"
                               class="w-full px-3.5 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent transition
                                      {{ $errors->has('first_name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        @error('first_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Middle Name <span class="text-gray-400 font-normal">(Optional)</span>
                        </label>
                        <input type="text" name="middle_name"
                               value="{{ old('middle_name') }}"
                               maxlength="255" placeholder="e.g., Reyes"
                               class="w-full px-3.5 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent transition">
                    </div>
                </div>
            </div>

            {{-- ── Demographics ── --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <h2 class="text-base font-bold text-deep-forest mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-sea-green/10 text-sea-green flex items-center justify-center text-xs">📋</span>
                    Demographics
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Date of Birth <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date_of_birth"
                               value="{{ old('date_of_birth') }}"
                               required
                               class="w-full px-3.5 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent transition
                                      {{ $errors->has('date_of_birth') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        @error('date_of_birth')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Gender <span class="text-red-500">*</span>
                        </label>
                        <select name="gender" required
                                class="w-full px-3.5 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent transition
                                       {{ $errors->has('gender') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            <option value="">Select...</option>
                            @foreach(['Male', 'Female', 'Other'] as $g)
                                <option value="{{ $g }}" {{ old('gender') == $g ? 'selected' : '' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                        @error('gender')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Civil Status</label>
                        <select name="civil_status"
                                class="w-full px-3.5 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent transition">
                            @foreach(['Single', 'Married', 'Widowed', 'Legally Separated'] as $cs)
                                <option value="{{ $cs }}" {{ old('civil_status', 'Single') == $cs ? 'selected' : '' }}>{{ $cs }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- ── Family Relationship ── --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <h2 class="text-base font-bold text-deep-forest mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-sea-green/10 text-sea-green flex items-center justify-center text-xs">🔗</span>
                    Family Relationship
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Relationship to Head <span class="text-red-500">*</span>
                        </label>
                        <select name="relationship" required
                                class="w-full px-3.5 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent transition
                                       {{ $errors->has('relationship') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            <option value="">Select relationship...</option>
                            @foreach(['Co-Head', 'Spouse', 'Son', 'Daughter', 'Father', 'Mother', 'Brother', 'Sister', 'Grandchild', 'Other Relative'] as $rel)
                                <option value="{{ $rel }}" {{ old('relationship', $isCoHead ? 'Co-Head' : '') == $rel ? 'selected' : '' }}>{{ $rel }}</option>
                            @endforeach
                        </select>
                        @error('relationship')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Occupation <span class="text-gray-400 font-normal">(Optional)</span>
                        </label>
                        <input type="text" name="occupation"
                               value="{{ old('occupation') }}"
                               maxlength="255" placeholder="e.g., Student, Farmer, Teacher"
                               class="w-full px-3.5 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sea-green focus:border-transparent transition">
                    </div>
                </div>
            </div>

            {{-- ── Actions ── --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.households.head.show', $householdHead) }}"
                   class="px-5 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-sea-green hover:bg-deep-forest text-white rounded-lg text-sm font-bold transition-colors shadow-sm flex items-center gap-2">
                    <span>👥</span> Add Member
                </button>
            </div>

        </div>
    </form>

    {{-- Info box --}}
    <div class="mt-5 bg-sea-green/5 border border-sea-green/20 rounded-lg p-4 flex items-start gap-3">
        <span class="text-sea-green text-lg leading-none mt-0.5">ℹ</span>
        <div>
            <p class="text-sm font-semibold text-deep-forest mb-0.5">Member Numbering</p>
            <p class="text-sm text-gray-600">
                Each member will automatically receive a unique HHM number within this family.
                If this member later registers an account, they can be linked to this record.
            </p>
        </div>
    </div>

</div>
@endsection
