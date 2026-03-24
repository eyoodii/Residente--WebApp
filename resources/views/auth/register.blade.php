<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | RESIDENTE App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ── Floating-label form groups ─────────────────────────────────── */
        .form-group { position: relative; }
        .form-group .form-input,
        .form-group .form-select {
            width: 100%;
            padding: 0.875rem 0.875rem 0.5rem;
            font-size: 0.875rem;
            border: 1.5px solid #d1d5db;
            border-radius: 0.625rem;
            background: #fff;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-group .form-input:focus,
        .form-group .form-select:focus {
            border-color: #008148;
            box-shadow: 0 0 0 3px rgba(0,129,72,0.10);
        }
        .form-group .form-label {
            position: absolute;
            left: 0.75rem;
            top: 0.75rem;
            font-size: 0.8125rem;
            color: #6b7280;
            pointer-events: none;
            transition: all 0.2s ease;
            background: transparent;
            padding: 0 0.25rem;
        }
        .form-group .form-input:focus ~ .form-label,
        .form-group .form-input:not(:placeholder-shown) ~ .form-label,
        .form-group .form-select:focus ~ .form-label,
        .form-group .form-select:not([data-empty="true"]) ~ .form-label,
        .form-group .form-label.active {
            top: -0.5rem;
            left: 0.625rem;
            font-size: 0.6875rem;
            font-weight: 600;
            color: #008148;
            background: #fff;
        }

        /* Validation states */
        .form-group.is-valid .form-input,
        .form-group.is-valid .form-select { border-color: #16a34a; }
        .form-group.is-valid .form-label { color: #16a34a; }
        .form-group.is-invalid .form-input,
        .form-group.is-invalid .form-select { border-color: #ef2917; }
        .form-group.is-invalid .form-label { color: #ef2917; }

        .validation-msg {
            font-size: 0.6875rem;
            margin-top: 0.25rem;
            min-height: 1rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            transition: all 0.2s;
        }
        .validation-msg.error { color: #ef2917; }
        .validation-msg.success { color: #16a34a; }

        /* Icon indicator inside input */
        .form-group .field-icon {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.875rem;
            opacity: 0;
            transition: opacity 0.2s;
        }
        .form-group.is-valid .field-icon.icon-valid,
        .form-group.is-invalid .field-icon.icon-invalid { opacity: 1; }

        /* ── Step indicators ────────────────────────────────────────────── */
        .step-dot {
            width: 2rem; height: 2rem;
            border-radius: 9999px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700;
            border: 2px solid #d1d5db;
            color: #9ca3af;
            background: #fff;
            transition: all 0.3s;
        }
        .step-dot.active {
            border-color: #008148;
            color: #fff;
            background: #008148;
        }
        .step-dot.completed {
            border-color: #16a34a;
            color: #fff;
            background: #16a34a;
        }
        .step-line {
            flex: 1; height: 2px;
            background: #e5e7eb;
            transition: background 0.3s;
        }
        .step-line.active { background: #16a34a; }

        /* ── Password strength meter ────────────────────────────────────── */
        .strength-meter { height: 4px; border-radius: 9999px; background: #e5e7eb; overflow: hidden; margin-top: 0.5rem; }
        .strength-bar { height: 100%; border-radius: 9999px; transition: width 0.3s, background 0.3s; width: 0%; }

        /* ── Smooth section transitions ──────────────────────────────────── */
        @keyframes slideUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        .animate-in { animation: slideUp 0.35s ease both; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-gray-50 to-emerald-50/30 antialiased font-sans min-h-screen">
    @include('partials.loader')

    <div class="min-h-screen flex flex-col lg:flex-row">

        {{-- ── Left Branding Panel (desktop) ────────────────────────────── --}}
        <div class="hidden lg:flex lg:w-[420px] xl:w-[480px] flex-col justify-between bg-deep-forest text-white relative overflow-hidden flex-shrink-0">
            {{-- Decorative pattern --}}
            <div class="absolute inset-0 opacity-[0.04]" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-golden-glow via-tiger-orange to-golden-glow"></div>

            <div class="relative z-10 px-10 pt-14">
                <div class="flex items-center gap-4 mb-10">
                    <div class="relative">
                        <div class="absolute inset-0 rounded-full bg-golden-glow/20 blur-md"></div>
                        <img src="{{ asset('logo_buguey.png') }}" alt="Seal of Buguey"
                             class="relative w-16 h-16 rounded-full bg-white object-contain shadow-xl ring-2 ring-white/20 p-1">
                    </div>
                    <div class="border-l border-white/15 pl-4">
                        <h1 class="text-2xl font-extrabold tracking-[0.2em] leading-none">RESIDENTE</h1>
                        <p class="text-white/50 text-[10px] font-semibold tracking-widest mt-1">MUNICIPALITY OF BUGUEY</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <h2 class="text-xl font-bold leading-snug">Create Your<br>Resident Profile</h2>
                        <p class="text-white/50 text-sm mt-2 leading-relaxed max-w-xs">
                            Join the official resident management system. Verify your identity and access municipal services.
                        </p>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex items-center gap-3 text-white/70">
                            <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-golden-glow">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </span>
                            <span>Verified & secure registration</span>
                        </div>
                        <div class="flex items-center gap-3 text-white/70">
                            <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-golden-glow">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </span>
                            <span>Access municipal services online</span>
                        </div>
                        <div class="flex items-center gap-3 text-white/70">
                            <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-golden-glow">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <span>Real-time request tracking</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative z-10 px-10 pb-8">
                <div class="border-t border-white/10 pt-5">
                    <p class="text-white/30 text-[10px] tracking-wider leading-relaxed">
                        Republic of the Philippines &bull; Province of Cagayan<br>
                        Resident Information System &copy; {{ date('Y') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- ── Right Content Panel ──────────────────────────────────────── --}}
        <div class="flex-1 flex flex-col">

            {{-- Mobile header --}}
            <div class="lg:hidden bg-deep-forest text-white px-5 py-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('logo_buguey.png') }}" alt="Buguey Logo" class="w-10 h-10 object-contain rounded-full bg-white shadow p-0.5">
                    <div>
                        <h1 class="text-lg font-extrabold tracking-widest">RESIDENTE</h1>
                        <p class="text-white/50 text-[9px] tracking-widest font-semibold">MUNICIPALITY OF BUGUEY</p>
                    </div>
                </div>
            </div>

            <div class="flex-1 flex items-start lg:items-center justify-center px-4 sm:px-6 py-8 lg:py-12 overflow-y-auto">
                <div class="w-full max-w-2xl">

                    {{-- Step indicators --}}
                    <div class="flex items-center justify-center gap-0 mb-8 px-8">
                        <div class="flex flex-col items-center gap-1">
                            <div id="step-1" class="step-dot active">1</div>
                            <span class="text-[10px] font-semibold text-sea-green">Personal</span>
                        </div>
                        <div id="line-1-2" class="step-line mx-2"></div>
                        <div class="flex flex-col items-center gap-1">
                            <div id="step-2" class="step-dot">2</div>
                            <span class="text-[10px] font-medium text-gray-400">Location</span>
                        </div>
                        <div id="line-2-3" class="step-line mx-2"></div>
                        <div class="flex flex-col items-center gap-1">
                            <div id="step-3" class="step-dot">3</div>
                            <span class="text-[10px] font-medium text-gray-400">Account</span>
                        </div>
                        <div id="line-3-4" class="step-line mx-2"></div>
                        <div class="flex flex-col items-center gap-1">
                            <div id="step-4" class="step-dot">4</div>
                            <span class="text-[10px] font-medium text-gray-400">Confirm</span>
                        </div>
                    </div>

                    {{-- Card --}}
                    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-200/80 overflow-hidden">

                        {{-- Card header --}}
                        <div class="bg-gradient-to-r from-deep-forest to-emerald-800 px-6 sm:px-8 py-5">
                            <h2 class="text-white font-bold text-lg tracking-tight">Resident Registration</h2>
                            <p class="text-emerald-200/60 text-xs mt-0.5">All fields marked with <span class="text-tiger-orange font-bold">*</span> are required</p>
                        </div>

                        <div class="flex flex-col gap-3 px-6 sm:px-8 py-5 sm:py-6">

                            {{-- Validation Error Summary --}}
                            @if ($errors->any())
                            <div id="error-summary" class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 animate-in">
                                <div class="flex gap-3">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-burnt-tangerine" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-bold text-red-800">Please correct the following errors:</h3>
                                        <ul class="mt-1.5 text-xs text-red-700 space-y-1 list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Email notice --}}
                            <div class="mb-6 flex items-start gap-3 bg-amber-50 border border-amber-200/60 rounded-xl px-4 py-3">
                                <svg class="w-5 h-5 text-tiger-orange flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-xs text-amber-900 leading-relaxed">
                                    <strong>Important:</strong> Use a valid email address — you must verify it to access the system.
                                </p>
                            </div>

                            <form id="registrationForm" action="{{ route('register') }}" method="POST" class="flex flex-col gap-3" novalidate>
                                @csrf

                                {{-- ── Section 1: Personal Information ────── --}}
                                <fieldset>
                                    <legend class="flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">
                                        <span class="w-5 h-5 rounded bg-sea-green/10 text-sea-green flex items-center justify-center text-[10px] font-bold">1</span>
                                        Personal Information
                                    </legend>

                                    <div class="space-y-5">

                                        {{-- Row 1: Names --}}
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                                            {{-- First Name --}}
                                            <div class="form-group" data-validate="name" data-required>
                                                <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" placeholder=" " autocomplete="given-name"
                                                       class="form-input peer">
                                                <label for="first_name" class="form-label">First Name <span class="text-burnt-tangerine">*</span></label>
                                                <span class="field-icon icon-valid text-green-500">&#10003;</span>
                                                <span class="field-icon icon-invalid text-burnt-tangerine">!</span>
                                                @error('first_name') <div class="validation-msg error">{{ $message }}</div> @enderror
                                                <div class="validation-msg" data-msg></div>
                                            </div>

                                            {{-- Middle Name --}}
                                            <div class="form-group" data-validate="name">
                                                <input id="middle_name" name="middle_name" type="text" value="{{ old('middle_name') }}" placeholder=" " autocomplete="additional-name"
                                                       class="form-input peer">
                                                <label for="middle_name" class="form-label">Middle Name</label>
                                                <span class="field-icon icon-valid text-green-500">&#10003;</span>
                                                <span class="field-icon icon-invalid text-burnt-tangerine">!</span>
                                                <div class="validation-msg" data-msg></div>
                                            </div>

                                            {{-- Last Name --}}
                                            <div class="form-group" data-validate="name" data-required>
                                                <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}" placeholder=" " autocomplete="family-name"
                                                       class="form-input peer">
                                                <label for="last_name" class="form-label">Last Name <span class="text-burnt-tangerine">*</span></label>
                                                <span class="field-icon icon-valid text-green-500">&#10003;</span>
                                                <span class="field-icon icon-invalid text-burnt-tangerine">!</span>
                                                @error('last_name') <div class="validation-msg error">{{ $message }}</div> @enderror
                                                <div class="validation-msg" data-msg></div>
                                            </div>

                                            {{-- Extension Name --}}
                                            <div class="form-group">
                                                <select id="extension_name" name="extension_name" class="form-select form-input peer">
                                                    <option value="">None</option>
                                                    <option value="Jr." {{ old('extension_name') == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                                    <option value="Sr." {{ old('extension_name') == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                                    <option value="II" {{ old('extension_name') == 'II' ? 'selected' : '' }}>II</option>
                                                    <option value="III" {{ old('extension_name') == 'III' ? 'selected' : '' }}>III</option>
                                                    <option value="IV" {{ old('extension_name') == 'IV' ? 'selected' : '' }}>IV</option>
                                                    <option value="V" {{ old('extension_name') == 'V' ? 'selected' : '' }}>V</option>
                                                </select>
                                                <label for="extension_name" class="form-label active">Ext. Name</label>
                                                @error('extension_name') <div class="validation-msg error">{{ $message }}</div> @enderror
                                            </div>
                                        </div>

                                        {{-- Row 2: DOB + Relationship --}}
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                                            {{-- Date of Birth --}}
                                            <div class="form-group" data-validate="dob" data-required>
                                                <input id="date_of_birth" name="date_of_birth" type="date" value="{{ old('date_of_birth') }}" placeholder=" "
                                                       class="form-input peer">
                                                <label for="date_of_birth" class="form-label active">Date of Birth <span class="text-burnt-tangerine">*</span></label>
                                                <span class="field-icon icon-valid text-green-500">&#10003;</span>
                                                <span class="field-icon icon-invalid text-burnt-tangerine">!</span>
                                                @error('date_of_birth') <div class="validation-msg error">{{ $message }}</div> @enderror
                                                <div class="validation-msg" data-msg></div>
                                            </div>

                                            {{-- Household Relationship --}}
                                            <div class="form-group" data-validate="select" data-required>
                                                <select id="household_relationship" name="household_relationship" class="form-select form-input peer">
                                                    <option value="" disabled {{ old('household_relationship') ? '' : 'selected' }}>Select relationship...</option>
                                                    <option value="Household Head" {{ old('household_relationship') == 'Household Head' ? 'selected' : '' }}>Household Head</option>
                                                    <option value="Spouse" {{ old('household_relationship') == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                                                    <option value="Child" {{ old('household_relationship') == 'Child' ? 'selected' : '' }}>Child (Son/Daughter)</option>
                                                    <option value="Parent" {{ old('household_relationship') == 'Parent' ? 'selected' : '' }}>Parent (Father/Mother)</option>
                                                    <option value="Sibling" {{ old('household_relationship') == 'Sibling' ? 'selected' : '' }}>Sibling (Brother/Sister)</option>
                                                    <option value="Grandchild" {{ old('household_relationship') == 'Grandchild' ? 'selected' : '' }}>Grandchild</option>
                                                    <option value="Grandparent" {{ old('household_relationship') == 'Grandparent' ? 'selected' : '' }}>Grandparent</option>
                                                    <option value="Other Relative" {{ old('household_relationship') == 'Other Relative' ? 'selected' : '' }}>Other Relative (Cousin, Uncle, Aunt, etc.)</option>
                                                    <option value="Non-Relative" {{ old('household_relationship') == 'Non-Relative' ? 'selected' : '' }}>Non-Relative (Boarder, Helper, etc.)</option>
                                                </select>
                                                <label for="household_relationship" class="form-label active">Household Relationship <span class="text-burnt-tangerine">*</span></label>
                                                <span class="field-icon icon-valid text-green-500">&#10003;</span>
                                                <span class="field-icon icon-invalid text-burnt-tangerine">!</span>
                                                @error('household_relationship') <div class="validation-msg error">{{ $message }}</div> @enderror
                                                <div class="validation-msg" data-msg></div>
                                            </div>
                                        </div>

                                    </div>{{-- /space-y-5 --}}
                                </fieldset>

                                <hr class="border-slate-100 -my-1">

                                {{-- ── Section 2: Location ────────────────── --}}
                                <fieldset>
                                    <legend class="flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">
                                        <span class="w-5 h-5 rounded bg-sea-green/10 text-sea-green flex items-center justify-center text-[10px] font-bold">2</span>
                                        Location Details
                                    </legend>

                                    {{-- Physical Address Row: Purok, House/Lot No., Street --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-5">
                                        {{-- Purok / Sitio --}}
                                        <div class="form-group">
                                            <input id="purok" name="purok" type="text" value="{{ old('purok') }}" maxlength="100" placeholder=" "
                                                   class="form-input peer">
                                            <label for="purok" class="form-label">Purok / Sitio</label>
                                            <span class="field-icon icon-valid text-green-500">&#10003;</span>
                                            @error('purok') <div class="validation-msg error">{{ $message }}</div> @enderror
                                            <div class="validation-msg" data-msg></div>
                                        </div>

                                        {{-- House / Lot Number --}}
                                        <div class="form-group">
                                            <input id="house_number" name="house_number" type="text" value="{{ old('house_number') }}" maxlength="100" placeholder=" "
                                                   class="form-input peer">
                                            <label for="house_number" class="form-label">House / Lot No.</label>
                                            <span class="field-icon icon-valid text-green-500">&#10003;</span>
                                            @error('house_number') <div class="validation-msg error">{{ $message }}</div> @enderror
                                            <div class="validation-msg" data-msg></div>
                                        </div>

                                        {{-- Street / Road --}}
                                        <div class="form-group">
                                            <input id="street" name="street" type="text" value="{{ old('street') }}" maxlength="150" placeholder=" "
                                                   class="form-input peer">
                                            <label for="street" class="form-label">Street / Road</label>
                                            <span class="field-icon icon-valid text-green-500">&#10003;</span>
                                            @error('street') <div class="validation-msg error">{{ $message }}</div> @enderror
                                            <div class="validation-msg" data-msg></div>
                                        </div>
                                    </div>

                                    {{-- Barangay & Postal Code Row --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                                        {{-- Barangay --}}
                                        <div class="form-group" data-validate="select" data-required>
                                            <select id="barangay" name="barangay" class="form-select form-input peer">
                                                <option value="" disabled {{ old('barangay') ? '' : 'selected' }}>Select your barangay...</option>
                                                @foreach(config('barangays.list', []) as $name => $code)
                                                    <option value="{{ $name }}" {{ old('barangay') == $name ? 'selected' : '' }}>
                                                        {{ $name }} ({{ $code }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="barangay" class="form-label active">Barangay <span class="text-burnt-tangerine">*</span></label>
                                            <span class="field-icon icon-valid text-green-500">&#10003;</span>
                                            <span class="field-icon icon-invalid text-burnt-tangerine">!</span>
                                            @error('barangay') <div class="validation-msg error">{{ $message }}</div> @enderror
                                            <div class="validation-msg" data-msg></div>
                                        </div>

                                        {{-- Postal Code --}}
                                        <div class="form-group" data-validate="postal" data-required>
                                            <input id="postal_code" name="postal_code" type="text" value="{{ old('postal_code', '3511') }}" maxlength="4" placeholder=" " inputmode="numeric"
                                                   class="form-input peer">
                                            <label for="postal_code" class="form-label">Postal Code <span class="text-burnt-tangerine">*</span></label>
                                            <span class="field-icon icon-valid text-green-500">&#10003;</span>
                                            <span class="field-icon icon-invalid text-burnt-tangerine">!</span>
                                            @error('postal_code') <div class="validation-msg error">{{ $message }}</div> @enderror
                                            <div class="validation-msg" data-msg></div>
                                        </div>
                                    </div>

                                    {{-- Family Registration Type --}}
                                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Household Registration</p>
                                        <p class="text-xs text-slate-400 mb-3">Let us know your household situation so barangay staff can assist you correctly.</p>
                                        <div class="flex flex-col sm:flex-row gap-3">
                                            {{-- New Family --}}
                                            <label class="flex items-start gap-3 cursor-pointer rounded-lg border-2 p-3 flex-1 transition-colors
                                                          {{ old('family_registration_type', 'new_family') == 'new_family' ? 'border-sea-green bg-sea-green/5' : 'border-slate-200 bg-white hover:border-sea-green/50' }}">
                                                <input type="radio" name="family_registration_type" value="new_family"
                                                       class="mt-0.5 accent-sea-green"
                                                       {{ old('family_registration_type', 'new_family') == 'new_family' ? 'checked' : '' }}
                                                       onchange="this.closest('.flex').querySelectorAll('label').forEach(l => l.classList.remove('border-sea-green','bg-sea-green/5')); this.closest('label').classList.add('border-sea-green','bg-sea-green/5')">
                                                <div>
                                                    <span class="text-sm font-semibold text-slate-700">New Family</span>
                                                    <p class="text-xs text-slate-500 mt-0.5">I am starting a new household record at this address.</p>
                                                </div>
                                            </label>

                                            {{-- Part of Existing Family --}}
                                            <label class="flex items-start gap-3 cursor-pointer rounded-lg border-2 p-3 flex-1 transition-colors
                                                          {{ old('family_registration_type') == 'existing_family' ? 'border-sea-green bg-sea-green/5' : 'border-slate-200 bg-white hover:border-sea-green/50' }}">
                                                <input type="radio" name="family_registration_type" value="existing_family"
                                                       class="mt-0.5 accent-sea-green"
                                                       {{ old('family_registration_type') == 'existing_family' ? 'checked' : '' }}
                                                       onchange="this.closest('.flex').querySelectorAll('label').forEach(l => l.classList.remove('border-sea-green','bg-sea-green/5')); this.closest('label').classList.add('border-sea-green','bg-sea-green/5')">
                                                <div>
                                                    <span class="text-sm font-semibold text-slate-700">Part of Existing Family</span>
                                                    <p class="text-xs text-slate-500 mt-0.5">Another family member is already registered at this same house.</p>
                                                </div>
                                            </label>
                                        </div>
                                        @error('family_registration_type') <div class="validation-msg error mt-2">{{ $message }}</div> @enderror
                                    </div>
                                </fieldset>

                                <hr class="border-slate-100 -my-1">

                                {{-- ── Section 3: Account ─────────────────── --}}
                                <fieldset>
                                    <legend class="flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">
                                        <span class="w-5 h-5 rounded bg-sea-green/10 text-sea-green flex items-center justify-center text-[10px] font-bold">3</span>
                                        Account Credentials
                                    </legend>

                                    {{-- Email --}}
                                    <div class="form-group" data-validate="email" data-required>
                                        <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder=" " autocomplete="email"
                                               class="form-input peer">
                                        <label for="email" class="form-label">Email Address <span class="text-burnt-tangerine">*</span></label>
                                        <span class="field-icon icon-valid text-green-500">&#10003;</span>
                                        <span class="field-icon icon-invalid text-burnt-tangerine">!</span>
                                        @error('email') <div class="validation-msg error">{{ $message }}</div> @enderror
                                        <div class="validation-msg" data-msg></div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                        {{-- Password --}}
                                        <div>
                                            <div class="form-group" data-validate="password" data-required>
                                                <input id="password" name="password" type="password" placeholder=" " autocomplete="new-password" minlength="8"
                                                       class="form-input peer pr-10">
                                                <label for="password" class="form-label">Password <span class="text-burnt-tangerine">*</span></label>
                                                <button type="button" onclick="togglePasswordVisibility('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition focus:outline-none z-10">
                                                    <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path id="password-eye-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path id="password-eye-open-2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        <path id="password-eye-closed" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                                    </svg>
                                                </button>
                                                @error('password') <div class="validation-msg error">{{ $message }}</div> @enderror
                                            </div>

                                            {{-- Strength meter --}}
                                            <div class="strength-meter">
                                                <div id="strength-bar" class="strength-bar"></div>
                                            </div>
                                            <div id="strength-label" class="text-[10px] font-semibold mt-1 text-gray-400 text-right">&nbsp;</div>

                                            {{-- Requirements --}}
                                            <div class="mt-2 bg-slate-50 rounded-lg p-3 border border-slate-100">
                                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Requirements</p>
                                                <div class="grid grid-cols-4 gap-2">
                                                    <div id="req-length" class="flex items-center gap-1.5 text-[11px] text-gray-400 transition-colors">
                                                        <span class="req-icon w-3.5 h-3.5 rounded-full border border-gray-300 flex items-center justify-center text-[8px] transition-all flex-shrink-0">&#9675;</span>
                                                        <span>8+ characters</span>
                                                    </div>
                                                    <div id="req-mixed" class="flex items-center gap-1.5 text-[11px] text-gray-400 transition-colors">
                                                        <span class="req-icon w-3.5 h-3.5 rounded-full border border-gray-300 flex items-center justify-center text-[8px] transition-all flex-shrink-0">&#9675;</span>
                                                        <span>Upper & lower</span>
                                                    </div>
                                                    <div id="req-number" class="flex items-center gap-1.5 text-[11px] text-gray-400 transition-colors">
                                                        <span class="req-icon w-3.5 h-3.5 rounded-full border border-gray-300 flex items-center justify-center text-[8px] transition-all flex-shrink-0">&#9675;</span>
                                                        <span>1 number</span>
                                                    </div>
                                                    <div id="req-special" class="flex items-center gap-1.5 text-[11px] text-gray-400 transition-colors">
                                                        <span class="req-icon w-3.5 h-3.5 rounded-full border border-gray-300 flex items-center justify-center text-[8px] transition-all flex-shrink-0">&#9675;</span>
                                                        <span>1 symbol (!@#$%^&*)</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Confirm Password --}}
                                        <div>
                                            <div class="form-group" data-validate="confirm" data-required>
                                                <input id="password_confirmation" name="password_confirmation" type="password" placeholder=" " autocomplete="new-password" minlength="8"
                                                       class="form-input peer pr-10">
                                                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-burnt-tangerine">*</span></label>
                                                <button type="button" onclick="togglePasswordVisibility('password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition focus:outline-none z-10">
                                                    <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path id="password_confirmation-eye-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path id="password_confirmation-eye-open-2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        <path id="password_confirmation-eye-closed" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                                    </svg>
                                                </button>
                                                @error('password_confirmation') <div class="validation-msg error">{{ $message }}</div> @enderror
                                                <div class="validation-msg" data-msg></div>
                                            </div>
                                            <div id="password-match-feedback" class="mt-2 text-xs">
                                                <p id="match-status" class="hidden flex items-center gap-1.5 text-[11px] font-semibold"></p>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <hr class="border-slate-100 -my-1">

                                {{-- ── Section 4: Terms & Submit ──────────── --}}
                                <fieldset>
                                    <legend class="flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">
                                        <span class="w-5 h-5 rounded bg-sea-green/10 text-sea-green flex items-center justify-center text-[10px] font-bold">4</span>
                                        Confirmation
                                    </legend>

                                    <div class="bg-slate-50 rounded-xl border border-slate-200/80 p-4">
                                        <div class="flex items-start gap-3">
                                            <div class="flex items-center h-5 mt-0.5">
                                                <input id="terms" name="terms" type="checkbox" required
                                                       class="h-4.5 w-4.5 text-sea-green focus:ring-sea-green border-gray-300 rounded cursor-pointer transition">
                                            </div>
                                            <div class="text-sm">
                                                <label for="terms" class="font-medium text-gray-800 cursor-pointer leading-relaxed">
                                                    I have read and accept the
                                                    <button type="button" onclick="openTermsModal()" class="text-tiger-orange hover:text-burnt-tangerine underline font-bold transition">Terms and Conditions</button>
                                                    and
                                                    <button type="button" onclick="openPrivacyModal()" class="text-tiger-orange hover:text-burnt-tangerine underline font-bold transition">Privacy Policy</button>.
                                                </label>
                                                @error('terms')
                                                    <span class="block text-xs text-burnt-tangerine mt-1 font-semibold">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mt-3 ml-7">
                                            <div id="req-terms" class="flex items-center gap-1.5 text-[11px] text-gray-400 transition-colors">
                                                <span class="req-icon w-3.5 h-3.5 rounded-full border border-gray-300 flex items-center justify-center text-[8px] transition-all">&#9675;</span>
                                                <span>Terms and Conditions accepted</span>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                {{-- Submit --}}
                                <div class="pt-2">
                                    <button id="submitButton" type="submit"
                                            class="w-full flex justify-center items-center gap-2.5 py-3.5 px-6 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-tiger-orange to-burnt-tangerine hover:from-burnt-tangerine hover:to-tiger-orange focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-tiger-orange shadow-lg shadow-tiger-orange/25 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none transform hover:scale-[1.01] active:scale-[0.99]">
                                        <span id="buttonText" class="tracking-wide">CREATE ACCOUNT</span>
                                        <svg id="buttonArrow" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        <svg id="loadingSpinner" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </form>

                            <div class="mt-6 text-center">
                                <p class="text-sm text-gray-500">
                                    Already registered?
                                    <a href="{{ route('login') }}" class="font-bold text-sea-green hover:text-deep-forest transition underline decoration-sea-green/30 hover:decoration-sea-green">Log in here</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="text-center mt-6 lg:hidden">
                        <p class="text-[10px] text-gray-400 tracking-wider">
                            Municipality of Buguey &bull; Province of Cagayan &bull; &copy; {{ date('Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div id="termsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="bg-deep-forest text-white px-6 py-4 rounded-t-xl flex items-center justify-between border-b-4 border-tiger-orange">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('logo_buguey.png') }}" alt="Buguey Logo" class="w-10 h-10 object-contain rounded-full shadow-sm bg-white">
                    <h2 class="text-2xl font-bold">Terms and Conditions</h2>
                </div>
                <button onclick="closeTermsModal()" class="text-white hover:text-tiger-orange transition text-3xl font-bold leading-none">&times;</button>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="p-6 overflow-y-auto flex-1">
                <div class="bg-golden-glow bg-opacity-20 border-l-4 border-tiger-orange p-4 rounded-r-md mb-6">
                    <p class="text-sm text-deep-forest font-medium">
                        <strong>Effective Date:</strong> February 27, 2026
                    </p>
                    <p class="text-sm text-deep-forest mt-2">
                        Welcome to ResidenteWebApp. Please read these Terms and Conditions carefully before using our service.
                    </p>
                </div>

                <div class="prose prose-sm max-w-none space-y-6">
                    <section>
                        <h3 class="text-lg font-bold text-deep-forest mb-2">1. Acceptance of Agreement</h3>
                        <p class="text-gray-700 leading-relaxed">
                            By creating an account or using the Service, you confirm that you have read, understood, and agreed to be bound by these Terms. If you are entering into these Terms on behalf of a homeowners association (HOA), building management, or legal entity, you represent that you have the authority to bind such entity to these Terms.
                        </p>
                    </section>

                    <section>
                        <h3 class="text-lg font-bold text-deep-forest mb-2">2. User Accounts and Eligibility</h3>
                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                            <li><strong>Registration:</strong> You must provide accurate, current, and complete information during the registration process.</li>
                            <li><strong>Security:</strong> You are solely responsible for safeguarding your password and for any activities or actions under your account. You must notify us immediately upon becoming aware of any breach of security or unauthorized use of your account.</li>
                            <li><strong>Eligibility:</strong> Use of the Service is limited to residents, property owners, and authorized management staff of participating residential communities.</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="text-lg font-bold text-deep-forest mb-2">3. Community Guidelines & User Conduct</h3>
                        <p class="text-gray-700 leading-relaxed mb-2">
                            To maintain a safe and professional environment, all users must adhere to the following Community Guidelines:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                            <li><strong>Respectful Interaction:</strong> Harassment, hate speech, bullying, or discriminatory language toward neighbors or staff is strictly prohibited.</li>
                            <li><strong>Privacy:</strong> Do not disclose the private information of other residents without explicit consent.</li>
                            <li><strong>Integrity:</strong> You must use your legal identity and correct unit number. Impersonation of other residents or management is a material breach of these Terms.</li>
                            <li><strong>Prohibited Content:</strong> You may not upload content that is illegal, offensive, or contains viruses/malicious code.</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="text-lg font-bold text-deep-forest mb-2">4. Property Management & Maintenance</h3>
                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                            <li><strong>Service Requests:</strong> Maintenance requests submitted through the app are processed according to the priority and scheduling of your local property management. ResidenteWebApp is a communication tool and is not responsible for the physical execution or quality of repairs.</li>
                            <li><strong>Emergency Services:</strong> Do not use the Service for life-threatening emergencies. In such cases, contact local emergency services (e.g., 911) directly.</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="text-lg font-bold text-deep-forest mb-2">5. Payments and Billing</h3>
                        <p class="text-gray-700 leading-relaxed mb-2">
                            If the Service allows for the payment of HOA fees, rent, or utilities:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                            <li><strong>Third-Party Processors:</strong> Payments are handled by secure third-party payment gateways. ResidenteWebApp does not store full credit card or bank account details.</li>
                            <li><strong>Accuracy:</strong> You are responsible for ensuring all payment information is correct. We are not liable for late fees or penalties resulting from failed transactions due to insufficient funds or incorrect data.</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="text-lg font-bold text-deep-forest mb-2">6. Intellectual Property</h3>
                        <p class="text-gray-700 leading-relaxed">
                            The Service and its original content, features, and functionality are the exclusive property of ResidenteWebApp and its licensors. You are granted a limited, non-exclusive, non-transferable license to use the app for its intended residential management purposes.
                        </p>
                    </section>

                    <section>
                        <h3 class="text-lg font-bold text-deep-forest mb-2">7. Data Privacy and Ownership</h3>
                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                            <li><strong>User Data:</strong> You retain ownership of the data you provide. However, you grant us a worldwide, royalty-free license to use, host, and display such data solely for the purpose of providing the Service.</li>
                            <li><strong>Privacy Policy:</strong> Our collection and use of personal information are governed by our Privacy Policy, which is incorporated into these Terms by reference.</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="text-lg font-bold text-deep-forest mb-2">8. Limitation of Liability</h3>
                        <p class="text-gray-700 leading-relaxed mb-2">
                            To the maximum extent permitted by law, ResidenteWebApp shall not be liable for any indirect, incidental, special, or consequential damages, including loss of profits, data, or use, arising out of or in connection with:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                            <li>Disputes between residents.</li>
                            <li>Actions or omissions of property management or third-party contractors.</li>
                            <li>Service interruptions or technical malfunctions.</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="text-lg font-bold text-deep-forest mb-2">9. Termination</h3>
                        <p class="text-gray-700 leading-relaxed">
                            We may terminate or suspend your access to the Service immediately, without prior notice, for conduct that we believe violates these Terms or is harmful to other users, us, or third parties, or for any other reason at our sole discretion.
                        </p>
                    </section>

                    <section>
                        <h3 class="text-lg font-bold text-deep-forest mb-2">10. Governing Law</h3>
                        <p class="text-gray-700 leading-relaxed">
                            These Terms shall be governed by and construed in accordance with the laws of the jurisdiction where the residential property is located, without regard to its conflict of law provisions.
                        </p>
                    </section>

                    <section>
                        <h3 class="text-lg font-bold text-deep-forest mb-2">11. Amendments</h3>
                        <p class="text-gray-700 leading-relaxed">
                            We reserve the right to modify these Terms at any time. We will notify users of significant changes via app notification or email. Continued use of the Service following such changes constitutes your acceptance of the revised Terms.
                        </p>
                    </section>

                    <section class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-bold text-deep-forest mb-2">Contact Us</h3>
                        <p class="text-gray-700 leading-relaxed">
                            If you have any questions about these Terms, please contact the administration at: 
                            <a href="mailto:support@residentewebapp.com" class="text-sea-green hover:text-deep-forest font-bold underline">support@residentewebapp.com</a>
                        </p>
                    </section>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 rounded-b-xl border-t border-gray-200 flex justify-end gap-3">
                <button onclick="closeTermsModal()" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 font-medium transition">
                    Close
                </button>
                <button onclick="acceptTerms()" class="px-6 py-2 bg-tiger-orange hover:bg-burnt-tangerine text-white rounded-md font-bold shadow transition">
                    I Accept
                </button>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div id="privacyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="bg-deep-forest text-white px-6 py-4 rounded-t-xl flex items-center justify-between border-b-4 border-sea-green">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('logo_buguey.png') }}" alt="Buguey Logo" class="w-10 h-10 object-contain rounded-full shadow-sm bg-white">
                    <h2 class="text-2xl font-bold">Privacy Policy</h2>
                </div>
                <button onclick="closePrivacyModal()" class="text-white hover:text-sea-green transition text-3xl font-bold leading-none">&times;</button>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="p-6 overflow-y-auto flex-1">
                <div class="bg-sea-green bg-opacity-20 border-l-4 border-sea-green p-4 rounded-r-md mb-6">
                    <p class="text-sm text-deep-forest font-medium">
                        <strong>Last Updated:</strong> February 27, 2026
                    </p>
                    <p class="text-sm text-deep-forest mt-2">
                        Your privacy is important to us. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use the ResidenteWebApp service.
                    </p>
                </div>

                <div class="prose prose-smmax-w-none space-y-6">
                    <!-- I. Information We Collect -->
                    <section>
                        <h3 class="text-lg font-bold text-deep-forest mb-2">I. Information We Collect</h3>
                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                            <li><strong>Personal Data:</strong> Name, unit number, contact number, and email address.</li>
                            <li><strong>Transaction Data:</strong> Records of HOA dues, billing history, and maintenance logs.</li>
                            <li><strong>Usage Data:</strong> Log data, IP addresses, and device information used to access the app.</li>
                        </ul>
                    </section>

                    <!-- II. How We Use Your Information -->
                    <section>
                        <h3 class="text-lg font-bold text-deep-forest mb-2">II. How We Use Your Information</h3>
                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                            <li>To verify residency and provide access to community features.</li>
                            <li>To facilitate communication between residents and management.</li>
                            <li>To process billing and generate digital receipts.</li>
                        </ul>
                    </section>

                    <!-- III. Data Retention and Security -->
                    <section>
                        <h3 class="text-lg font-bold text-deep-forest mb-2">III. Data Retention and Security</h3>
                        <p class="text-gray-700 leading-relaxed">
                            We implement industry-standard encryption to protect your data. Your information is stored only as long as you remain a resident or as required by law for financial auditing. <strong>We do not sell your personal data to third-party advertisers.</strong>
                        </p>
                    </section>

                    <!-- IV. Disclaimer for Resident Transactions -->
                    <section>
                        <h3 class="text-lg font-bold text-deep-forest mb-2">IV. Notice of Third-Party Transactions</h3>
                        <p class="text-gray-700 leading-relaxed mb-3">
                            ResidenteWebApp may provide a "Marketplace" or "Bulletin Board" feature for the convenience of residents. You acknowledge and agree that:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                            <li><strong>No Endorsement:</strong> ResidenteWebApp does not vet, screen, or guarantee the quality, safety, or legality of items or services advertised by residents.</li>
                            <li><strong>User Risk:</strong> All transactions (financial or otherwise) are strictly between the individual parties. ResidenteWebApp is not a party to these transactions and shall not be held liable for any fraud, theft, or dissatisfaction.</li>
                            <li><strong>No Payment Mediation:</strong> Unless explicitly stated, the app does not process payments for resident-to-resident sales. We recommend meeting in well-lit, public areas of the property for exchanges.</li>
                        </ul>
                    </section>

                    <!-- V. Maintenance Request Protocol -->
                    <section>
                        <h3 class="text-lg font-bold text-deep-forest mb-2">V. Protocol for Service Requests</h3>
                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                            <li><strong>Submission:</strong> All non-emergency maintenance requests must be submitted via the "Maintenance" module with a clear description and, if possible, a photo of the issue.</li>
                            <li><strong>Access Consent:</strong> By submitting a request, you grant authorized maintenance personnel permission to enter your unit during standard business hours unless a specific appointment is requested.</li>
                            <li><strong>Response Times:</strong> Maintenance is prioritized by severity (e.g., a burst pipe takes precedence over a flickering light). ResidenteWebApp is a tracking tool; actual repair timelines are determined by the Property Management Office.</li>
                            <li><strong>Non-Emergency Only:</strong> This protocol is for property-related repairs. For police, fire, or medical emergencies, call local emergency services immediately.</li>
                        </ul>
                    </section>

                    <!-- VI. Security Protocol -->
                    <section>
                        <h3 class="text-lg font-bold text-deep-forest mb-2">VI. Security & Access Control</h3>
                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                            <li><strong>Personal Codes:</strong> Resident entry codes are for personal use only. Sharing your primary access code with non-residents is a security violation.</li>
                            <li><strong>Visitor Management:</strong> Temporary "Guest Codes" generated by the app will expire after a set duration (e.g., 24 hours). Residents are responsible for the actions of any guest they grant access to via the app.</li>
                            <li><strong>Audit Logs:</strong> For community safety, the system maintains a log of which codes were used and at what time. This data is accessible only by authorized Security Personnel during investigations.</li>
                        </ul>
                    </section>

                    <!-- VII. Compliance -->
                    <section>
                        <h3 class="text-lg font-bold text-deep-forest mb-2">VII. Legal Compliance</h3>
                        <p class="text-gray-700 leading-relaxed">
                            This Privacy Policy complies with the <strong>Data Privacy Act of 2012 (Republic Act No. 10173)</strong> of the Philippines and other applicable data protection regulations. We are committed to protecting your personal information and ensuring transparency in how we collect, use, and store your data.
                        </p>
                    </section>

                    <!-- VIII. Your Rights -->
                    <section>
                        <h3 class="text-lg font-bold text-deep-forest mb-2">VIII. Your Rights</h3>
                        <p class="text-gray-700 leading-relaxed mb-2">
                            Under the Data Privacy Act, you have the right to:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                            <li>Access your personal data stored in our system.</li>
                            <li>Request correction of inaccurate or incomplete information.</li>
                            <li>Object to the processing of your data for direct marketing purposes.</li>
                            <li>Request deletion of your data upon termination of residency, subject to legal retention requirements.</li>
                        </ul>
                    </section>

                    <!-- IX. Changes to Privacy Policy -->
                    <section>
                        <h3 class="text-lg font-bold text-deep-forest mb-2">IX. Changes to This Privacy Policy</h3>
                        <p class="text-gray-700 leading-relaxed">
                            We reserve the right to update this Privacy Policy to reflect changes in our practices or legal requirements. Users will be notified of significant changes via email or in-app notification. Continued use of the Service after such modifications constitutes acceptance of the updated policy.
                        </p>
                    </section>

                    <!-- Contact Section -->
                    <section class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-bold text-deep-forest mb-2">Contact Us</h3>
                        <p class="text-gray-700 leading-relaxed">
                            If you have any questions about this Privacy Policy or wish to exercise your data privacy rights, please contact us at: 
                            <a href="mailto:privacy@residentewebapp.com" class="text-sea-green hover:text-deep-forest font-bold underline">privacy@residentewebapp.com</a>
                        </p>
                    </section>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 rounded-b-xl border-t border-gray-200 flex justify-end gap-3">
                <button onclick="closePrivacyModal()" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 font-medium transition">
                    Close
                </button>
                <button onclick="acceptPrivacy()" class="px-6 py-2 bg-sea-green hover:bg-deep-forest text-white rounded-md font-bold shadow transition">
                    I Understand
                </button>
            </div>
        </div>
    </div>

    <script>
        // ── Global functions (called via onclick attributes in HTML) ──────────

        function togglePasswordVisibility(fieldId) {
            const input    = document.getElementById(fieldId);
            const eyeOpen  = document.getElementById(fieldId + '-eye-open');
            const eyeOpen2 = document.getElementById(fieldId + '-eye-open-2');
            const eyeClosed = document.getElementById(fieldId + '-eye-closed');

            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeOpen2.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeOpen2.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }

        function openTermsModal() {
            document.getElementById('termsModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeTermsModal() {
            document.getElementById('termsModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        function acceptTerms() {
            document.getElementById('terms').checked = true;
            closeTermsModal();
            validateTerms();
        }
        function openPrivacyModal() {
            document.getElementById('privacyModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closePrivacyModal() {
            document.getElementById('privacyModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        function acceptPrivacy() {
            document.getElementById('terms').checked = true;
            closePrivacyModal();
            validateTerms();
        }

        function validateTerms() {
            var cb = document.getElementById('terms');
            if (!cb) return;
            setReq('req-terms', cb.checked);
            updateSteps();
        }

        // ── Shared helpers ───────────────────────────────────────────────────
        function setReq(id, passing) {
            var el = document.getElementById(id);
            if (!el) return;
            var icon = el.querySelector('.req-icon');
            if (passing) {
                el.classList.remove('text-gray-400');
                el.classList.add('text-green-600');
                if (icon) { icon.style.borderColor = '#16a34a'; icon.style.background = '#16a34a'; icon.style.color = '#fff'; icon.innerHTML = '&#10003;'; }
            } else {
                el.classList.remove('text-green-600');
                el.classList.add('text-gray-400');
                if (icon) { icon.style.borderColor = '#d1d5db'; icon.style.background = '#fff'; icon.style.color = '#9ca3af'; icon.innerHTML = '&#9675;'; }
            }
        }

        function setGroupState(group, state, msg) {
            if (!group) return;
            group.classList.remove('is-valid', 'is-invalid');
            if (state === 'valid') group.classList.add('is-valid');
            if (state === 'invalid') group.classList.add('is-invalid');
            var msgEl = group.querySelector('[data-msg]');
            if (msgEl) {
                msgEl.classList.remove('error', 'success');
                if (state === 'invalid') { msgEl.classList.add('error'); msgEl.textContent = msg || ''; }
                else if (state === 'valid') { msgEl.classList.add('success'); msgEl.textContent = msg || ''; }
                else { msgEl.textContent = ''; }
            }
        }

        // ── Step progress tracker ────────────────────────────────────────────
        function updateSteps() {
            var s1 = isSection1Valid();
            var s2 = isSection2Valid();
            var s3 = isSection3Valid();
            var s4 = document.getElementById('terms') && document.getElementById('terms').checked;

            setStep(1, s1 ? 'completed' : 'active');
            setStep(2, s1 && s2 ? 'completed' : (s1 ? 'active' : ''));
            setStep(3, s1 && s2 && s3 ? 'completed' : (s1 && s2 ? 'active' : ''));
            setStep(4, s1 && s2 && s3 && s4 ? 'completed' : (s1 && s2 && s3 ? 'active' : ''));

            setLine('line-1-2', s1);
            setLine('line-2-3', s1 && s2);
            setLine('line-3-4', s1 && s2 && s3);
        }
        function setStep(n, state) {
            var el = document.getElementById('step-' + n);
            if (!el) return;
            el.classList.remove('active', 'completed');
            if (state) el.classList.add(state);
        }
        function setLine(id, active) {
            var el = document.getElementById(id);
            if (!el) return;
            if (active) el.classList.add('active'); else el.classList.remove('active');
        }

        var namePattern = /^[a-zA-Z\sñÑ.'\-]+$/;

        function isSection1Valid() {
            var fn = document.getElementById('first_name');
            var ln = document.getElementById('last_name');
            var dob = document.getElementById('date_of_birth');
            var hr = document.getElementById('household_relationship');
            return fn && fn.value.trim() && namePattern.test(fn.value) &&
                   ln && ln.value.trim() && namePattern.test(ln.value) &&
                   dob && dob.value &&
                   hr && hr.value;
        }
        function isSection2Valid() {
            var b = document.getElementById('barangay');
            var p = document.getElementById('postal_code');
            return b && b.value && p && /^[0-9]{4}$/.test(p.value);
        }
        function isSection3Valid() {
            var e = document.getElementById('email');
            var pw = document.getElementById('password');
            var cf = document.getElementById('password_confirmation');
            if (!e || !pw || !cf) return false;
            var emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e.value);
            var pwVal = pw.value;
            var pwOk = pwVal.length >= 8 && /[a-z]/.test(pwVal) && /[A-Z]/.test(pwVal) && /\d/.test(pwVal) && /[!@#$%^&*]/.test(pwVal);
            return emailOk && pwOk && pwVal === cf.value && cf.value.length > 0;
        }

        // ── DOM-dependent setup ──────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function () {

            // Close modals
            document.getElementById('termsModal')?.addEventListener('click', function (e) { if (e.target === this) closeTermsModal(); });
            document.getElementById('privacyModal')?.addEventListener('click', function (e) { if (e.target === this) closePrivacyModal(); });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { closeTermsModal(); closePrivacyModal(); } });

            // ── Field validators ──────────────────────────────────────────
            var validators = {
                name: function (input, group) {
                    var val = input.value.trim();
                    var required = group.hasAttribute('data-required');
                    if (!val && required) { setGroupState(group, 'invalid', 'This field is required'); return false; }
                    if (!val) { setGroupState(group, ''); return true; }
                    if (!namePattern.test(val)) { setGroupState(group, 'invalid', "Only letters, spaces, periods, hyphens, and apostrophes"); return false; }
                    setGroupState(group, 'valid'); return true;
                },
                dob: function (input, group) {
                    var val = input.value;
                    if (!val) { setGroupState(group, 'invalid', 'Date of birth is required'); return false; }
                    var d = new Date(val);
                    var now = new Date();
                    if (d >= now) { setGroupState(group, 'invalid', 'Date must be in the past'); return false; }
                    var age = now.getFullYear() - d.getFullYear();
                    if (age > 120) { setGroupState(group, 'invalid', 'Please enter a valid date'); return false; }
                    setGroupState(group, 'valid'); return true;
                },
                select: function (input, group) {
                    if (!input.value) { setGroupState(group, 'invalid', 'Please select an option'); return false; }
                    setGroupState(group, 'valid'); return true;
                },
                postal: function (input, group) {
                    var val = input.value.trim();
                    if (!val) { setGroupState(group, 'invalid', 'Postal code is required'); return false; }
                    if (!/^[0-9]{4}$/.test(val)) { setGroupState(group, 'invalid', 'Must be a 4-digit number'); return false; }
                    setGroupState(group, 'valid'); return true;
                },
                email: function (input, group) {
                    var val = input.value.trim();
                    if (!val) { setGroupState(group, 'invalid', 'Email is required'); return false; }
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) { setGroupState(group, 'invalid', 'Enter a valid email address'); return false; }
                    setGroupState(group, 'valid'); return true;
                },
                password: function (input, group) {
                    var pw = input.value;
                    if (!pw) { setGroupState(group, ''); updateStrength(0); return false; }
                    var score = 0;
                    var len = pw.length >= 8; if (len) score++;
                    var mix = /[a-z]/.test(pw) && /[A-Z]/.test(pw); if (mix) score++;
                    var num = /\d/.test(pw); if (num) score++;
                    var spc = /[!@#$%^&*]/.test(pw); if (spc) score++;

                    setReq('req-length', len);
                    setReq('req-mixed', mix);
                    setReq('req-number', num);
                    setReq('req-special', spc);
                    updateStrength(score);

                    if (score < 4) { setGroupState(group, 'invalid'); return false; }
                    setGroupState(group, 'valid'); return true;
                },
                confirm: function (input, group) {
                    var pw = document.getElementById('password');
                    var matchStatus = document.getElementById('match-status');
                    if (!input.value) { setGroupState(group, ''); if (matchStatus) matchStatus.classList.add('hidden'); return false; }
                    if (matchStatus) matchStatus.classList.remove('hidden');
                    if (pw && pw.value === input.value) {
                        setGroupState(group, 'valid');
                        if (matchStatus) { matchStatus.innerHTML = '<span style="color:#16a34a">&#10003;</span> Passwords match'; matchStatus.style.color = '#16a34a'; }
                        return true;
                    } else {
                        setGroupState(group, 'invalid');
                        if (matchStatus) { matchStatus.innerHTML = '<span style="color:#ef2917">&#10007;</span> Passwords do not match'; matchStatus.style.color = '#ef2917'; }
                        return false;
                    }
                }
            };

            function updateStrength(score) {
                var bar = document.getElementById('strength-bar');
                var label = document.getElementById('strength-label');
                if (!bar || !label) return;
                var map = [
                    { w: '0%',   c: '#e5e7eb', t: '', tc: '#9ca3af' },
                    { w: '25%',  c: '#ef4444', t: 'Weak', tc: '#ef4444' },
                    { w: '50%',  c: '#f59e0b', t: 'Fair', tc: '#f59e0b' },
                    { w: '75%',  c: '#3b82f6', t: 'Good', tc: '#3b82f6' },
                    { w: '100%', c: '#16a34a', t: 'Strong', tc: '#16a34a' }
                ];
                var m = map[score];
                bar.style.width = m.w;
                bar.style.background = m.c;
                label.textContent = m.t;
                label.style.color = m.tc;
            }

            // Attach real-time validation to all form groups with data-validate
            document.querySelectorAll('.form-group[data-validate]').forEach(function (group) {
                var type = group.getAttribute('data-validate');
                var input = group.querySelector('input, select');
                var handler = validators[type];
                if (!input || !handler) return;

                var events = (input.tagName === 'SELECT') ? ['change'] : ['input', 'blur'];
                events.forEach(function (evt) {
                    input.addEventListener(evt, function () {
                        handler(input, group);
                        updateSteps();
                    });
                });

                // Initial state for pre-filled fields
                if (input.value) handler(input, group);
            });

            // Re-validate confirm when password changes
            var pwInput = document.getElementById('password');
            var cfInput = document.getElementById('password_confirmation');
            pwInput?.addEventListener('input', function () {
                if (cfInput && cfInput.value) {
                    var cfGroup = cfInput.closest('.form-group');
                    validators.confirm(cfInput, cfGroup);
                }
                updateSteps();
            });

            // Terms checkbox
            document.getElementById('terms')?.addEventListener('change', validateTerms);
            validateTerms();
            updateSteps();

            // ── Form submit ───────────────────────────────────────────────
            document.getElementById('registrationForm')?.addEventListener('submit', function (e) {
                var terms = document.getElementById('terms');
                var submitBtn = document.getElementById('submitButton');
                var btnText = document.getElementById('buttonText');
                var btnArrow = document.getElementById('buttonArrow');
                var spinner = document.getElementById('loadingSpinner');
                var pw = document.getElementById('password');
                var cf = document.getElementById('password_confirmation');

                // Validate all groups on submit
                var allValid = true;
                document.querySelectorAll('.form-group[data-validate]').forEach(function (group) {
                    var type = group.getAttribute('data-validate');
                    var input = group.querySelector('input, select');
                    var handler = validators[type];
                    if (input && handler && !handler(input, group)) allValid = false;
                });

                if (!terms.checked) {
                    e.preventDefault();
                    terms.focus();
                    terms.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    setReq('req-terms', false);
                    return;
                }

                if (!allValid) {
                    e.preventDefault();
                    var firstErr = document.querySelector('.form-group.is-invalid');
                    if (firstErr) {
                        var errInput = firstErr.querySelector('input, select');
                        if (errInput) errInput.focus();
                        firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    return;
                }

                submitBtn.disabled = true;
                btnText.textContent = 'CREATING ACCOUNT...';
                if (btnArrow) btnArrow.classList.add('hidden');
                spinner.classList.remove('hidden');
            });

            // Scroll to errors on page load
            @if ($errors->any())
            var errSummary = document.getElementById('error-summary');
            if (errSummary) errSummary.scrollIntoView({ behavior: 'smooth', block: 'start' });
            @endif

        }); // end DOMContentLoaded
    </script>
</body>
</html>
