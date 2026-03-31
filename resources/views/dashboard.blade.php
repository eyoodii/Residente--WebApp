@extends('layouts.citizen')

@section('title', 'Resident Dashboard | RESIDENTE App')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Citizen Portal')

@section('header-actions')
    @if($resident->canAccessServices())
        <a href="{{ route('services.index') }}" class="inline-flex items-center gap-1.5 bg-deep-forest hover:bg-sea-green text-white pl-4 pr-5 py-2 rounded-xl font-semibold text-sm shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            New Request
        </a>
    @else
        <button disabled class="inline-flex items-center gap-1.5 bg-gray-200 text-gray-400 pl-4 pr-5 py-2 rounded-xl font-semibold text-sm cursor-not-allowed" title="Requires residency verification">
            New Request
        </button>
    @endif
@endsection

@section('content')
@php
    $readyCount      = $resident->ready_for_pickup_count ?? 0;
    $profileOk       = $resident->is_onboarding_complete && $resident->profile_matched;
    $emailVerified   = (bool) $resident->email_verified_at;
    $philsysVerified = $resident->hasPhilSysVerification();
    $onboardingDone  = (bool) $resident->is_onboarding_complete;

    $urgencyMap = ['ready-for-pickup' => 3, 'in-progress' => 2, 'pending' => 1];
    $sortedRequests = isset($recentRequests)
        ? $recentRequests->sortByDesc(fn($r) => $urgencyMap[$r->status] ?? 0)->take(4)
        : collect();

    $quickActions = [
        ['label' => "Mayor's Clearance", 'emoji' => '📜', 'route' => route('services.show', 'mayors-clearance'),    'hoverBorder' => 'hover:border-sea-green',      'hoverBg' => 'hover:bg-sea-green/5'],
        ['label' => "Get Cedula",         'emoji' => '🆔', 'route' => route('services.index'),                        'hoverBorder' => 'hover:border-blue-400',       'hoverBg' => 'hover:bg-blue-50/50'],
        ['label' => "Sanitary Permit",    'emoji' => '🏢', 'route' => route('services.show', 'sanitary-permit'),      'hoverBorder' => 'hover:border-tiger-orange',   'hoverBg' => 'hover:bg-orange-50/50'],
        ['label' => "Health Services",    'emoji' => '⚕️', 'route' => route('services.show', 'laboratory-services'),  'hoverBorder' => 'hover:border-burnt-tangerine','hoverBg' => 'hover:bg-red-50/50'],
    ];
@endphp

<div class="px-4 lg:px-6 py-6 space-y-6" x-data="{ activeTab: 'memos' }">

    {{-- ====== COMPONENT 1: HERO BANNER ====== --}}
    <div class="bg-gradient-to-r from-deep-forest via-[#045d3f] to-sea-green rounded-3xl p-7 sm:p-9 shadow-xl text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl -mr-24 -mt-24 pointer-events-none"></div>
        <div class="absolute bottom-0 left-1/3 w-48 h-48 bg-sea-green/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">

            {{-- Avatar + Greeting --}}
            <div class="flex items-start gap-4 w-full md:w-auto">
                <div class="w-14 h-14 rounded-2xl bg-white/15 border-2 border-white/30 flex items-center justify-center flex-shrink-0 shadow-lg">
                    <span class="text-xl font-black text-white select-none">
                        {{ strtoupper(substr($resident->first_name, 0, 1)) }}{{ strtoupper(substr($resident->last_name, 0, 1)) }}
                    </span>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        @if($resident->canAccessServices())
                            <span class="inline-flex items-center gap-1.5 bg-white/20 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide border border-white/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 inline-block"></span>
                                Verified Citizen
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-amber-400/20 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide border border-amber-300/40 text-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-300 inline-block animate-pulse"></span>
                                Pending Verification
                            </span>
                        @endif
                        <span class="text-white/60 text-xs font-medium font-mono">
                            #{{ str_pad($resident->id, 6, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight leading-tight">
                        Welcome back, {{ $resident->first_name }}!
                    </h1>
                    <p class="text-white/80 mt-1.5 text-sm leading-relaxed hidden sm:block">
                        Your Buguey digital e-services portal — track requests, update your profile, and stay informed.
                    </p>
                </div>
            </div>

            {{-- Stats + CTA --}}
            <div class="w-full md:w-auto flex flex-col gap-3 flex-shrink-0">
                {{-- 3-stat strip --}}
                <div class="flex items-stretch gap-px bg-white/10 rounded-2xl p-1 border border-white/15">
                    <div class="flex-1 text-center px-4 py-2.5 rounded-xl">
                        <p class="text-2xl font-extrabold leading-none">{{ $serviceStats['completed'] ?? 0 }}</p>
                        <p class="text-xs text-white/70 font-bold uppercase tracking-wide mt-1">Done</p>
                    </div>
                    <div class="w-px bg-white/15 my-2"></div>
                    <div class="flex-1 text-center px-4 py-2.5 rounded-xl">
                        <p class="text-2xl font-extrabold leading-none">{{ $serviceStats['pending'] ?? 0 }}</p>
                        <p class="text-xs text-white/70 font-bold uppercase tracking-wide mt-1">Active</p>
                    </div>
                    <div class="w-px bg-white/15 my-2"></div>
                    <div class="flex-1 text-center px-4 py-2.5 rounded-xl {{ $readyCount > 0 ? 'bg-golden-glow/25 ring-1 ring-golden-glow/40' : '' }} transition">
                        <p class="text-2xl font-extrabold leading-none {{ $readyCount > 0 ? 'text-golden-glow' : '' }}">{{ $readyCount }}</p>
                        <p class="text-xs font-bold uppercase tracking-wide mt-1 {{ $readyCount > 0 ? 'text-golden-glow/80' : 'text-white/70' }}">Pickup</p>
                    </div>
                </div>

                {{-- CTA --}}
                @if($resident->canAccessServices())
                    <div class="flex gap-2">
                        <a href="{{ route('services.index') }}"
                           class="flex-1 text-center bg-white text-deep-forest px-5 py-3 rounded-xl font-extrabold shadow-lg hover:bg-golden-glow transition transform hover:-translate-y-0.5 text-sm">
                            + New Request
                        </a>
                        <a href="{{ route('services.my-requests') }}"
                           class="flex-1 text-center bg-white/15 border border-white/30 text-white px-5 py-3 rounded-xl font-bold hover:bg-white/25 transition text-sm">
                            My Requests
                        </a>
                    </div>
                @else
                    <a href="{{ route('verification.philsys') }}"
                       class="block text-center bg-white text-deep-forest px-8 py-3 rounded-xl font-extrabold shadow-lg hover:bg-golden-glow transition transform hover:-translate-y-0.5 text-sm">
                        Complete Verification →
                    </a>
                @endif
            </div>
        </div>

        {{-- Ready-for-Pickup Alert --}}
        @if($readyCount > 0)
        <div class="relative z-10 mt-5 bg-golden-glow/20 border border-golden-glow/35 rounded-xl px-4 py-3 flex items-center gap-3">
            <span class="w-2.5 h-2.5 rounded-full bg-golden-glow animate-pulse flex-shrink-0"></span>
            <p class="text-sm font-bold text-white">
                {{ $readyCount }} {{ Str::plural('document', $readyCount) }}
                {{ $readyCount === 1 ? 'is' : 'are' }} ready for pickup at the Municipal Hall.
            </p>
            <a href="{{ route('services.my-requests') }}"
               class="ml-auto text-xs font-bold text-golden-glow hover:text-white transition flex-shrink-0 flex items-center gap-1">
                View
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
        @endif
    </div>


    {{-- ====== COMPONENT 2: PHILSYS VERIFICATION BANNER ====== --}}
    @if($needsPhilSysVerification)
    <div x-data="{ dismissed: false }"
         x-show="!dismissed"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1">
        <div class="rounded-2xl border border-blue-200 bg-gradient-to-br from-blue-50 to-white overflow-hidden shadow-sm">

            {{-- Header --}}
            <div class="flex items-center justify-between px-5 lg:px-6 pt-5 pb-4 border-b border-blue-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-gray-900">Complete Your Identity Verification</h3>
                        <p class="text-xs text-gray-500 font-medium mt-0.5">Required to unlock all e-services</p>
                    </div>
                </div>
                <button @click="dismissed = true"
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-blue-100 text-gray-400 hover:text-gray-600 transition flex-shrink-0"
                        title="Dismiss for now">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- 4-Step Stepper --}}
            <div class="px-5 lg:px-6 pt-5 pb-2">
                <div class="flex items-start">

                    {{-- Step 1: Registered (always done) --}}
                    <div class="flex flex-col items-center">
                        <div class="w-9 h-9 rounded-full bg-sea-green flex items-center justify-center shadow-sm flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        </div>
                        <p class="text-[11px] font-bold mt-2 text-sea-green text-center leading-tight">Registered</p>
                    </div>

                    <div class="flex-1 h-0.5 mt-4 mx-2 {{ $emailVerified ? 'bg-sea-green' : 'bg-gray-200' }}"></div>

                    {{-- Step 2: Email --}}
                    <div class="flex flex-col items-center">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 shadow-sm
                            {{ $emailVerified ? 'bg-sea-green' : 'bg-blue-600 ring-4 ring-blue-100' }}">
                            @if($emailVerified)
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            @else
                                <span class="text-xs font-bold text-white">2</span>
                            @endif
                        </div>
                        <p class="text-[11px] font-bold mt-2 text-center leading-tight {{ $emailVerified ? 'text-sea-green' : 'text-blue-600' }}">Email<br>Verified</p>
                    </div>

                    <div class="flex-1 h-0.5 mt-4 mx-2 {{ $philsysVerified ? 'bg-sea-green' : 'bg-gray-200' }}"></div>

                    {{-- Step 3: PhilSys --}}
                    <div class="flex flex-col items-center">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 shadow-sm
                            {{ $philsysVerified ? 'bg-sea-green' : ($emailVerified ? 'bg-blue-600 ring-4 ring-blue-100' : 'bg-gray-100') }}">
                            @if($philsysVerified)
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            @elseif($emailVerified)
                                <span class="text-xs font-bold text-white">3</span>
                            @else
                                <span class="text-xs font-bold text-gray-400">3</span>
                            @endif
                        </div>
                        <p class="text-[11px] font-bold mt-2 text-center leading-tight
                            {{ $philsysVerified ? 'text-sea-green' : ($emailVerified ? 'text-blue-600' : 'text-gray-400') }}">
                            PhilSys<br>ID</p>
                    </div>

                    <div class="flex-1 h-0.5 mt-4 mx-2 {{ $onboardingDone ? 'bg-sea-green' : 'bg-gray-200' }}"></div>

                    {{-- Step 4: Profile --}}
                    <div class="flex flex-col items-center">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 shadow-sm
                            {{ $onboardingDone ? 'bg-sea-green' : ($philsysVerified ? 'bg-blue-600 ring-4 ring-blue-100' : 'bg-gray-100') }}">
                            @if($onboardingDone)
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            @elseif($philsysVerified)
                                <span class="text-xs font-bold text-white">4</span>
                            @else
                                <span class="text-xs font-bold text-gray-400">4</span>
                            @endif
                        </div>
                        <p class="text-[11px] font-bold mt-2 text-center leading-tight
                            {{ $onboardingDone ? 'text-sea-green' : ($philsysVerified ? 'text-blue-600' : 'text-gray-400') }}">
                            Profile<br>Setup</p>
                    </div>

                </div>
            </div>

            {{-- Contextual action --}}
            <div class="px-5 lg:px-6 py-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                @if(!$emailVerified)
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Next: Verify your email address</p>
                        <p class="text-xs text-gray-500 mt-0.5">Check your inbox for the verification link</p>
                    </div>
                    <form method="POST" action="{{ route('verification.send') }}" class="flex-shrink-0">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition shadow-sm">
                            Resend Email
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        </button>
                    </form>
                @elseif(!$philsysVerified)
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Next: Link your PhilSys National ID</p>
                        <p class="text-xs text-gray-500 mt-0.5">Have your PhilSys ID or ePhilID QR code ready</p>
                    </div>
                    <a href="{{ route('verification.philsys') }}"
                       class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition shadow-sm">
                        Start PhilSys Verification
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                @elseif(!$onboardingDone)
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Next: Complete your socio-economic profile</p>
                        <p class="text-xs text-gray-500 mt-0.5">Fill in your household & family information</p>
                    </div>
                    <a href="{{ route('citizen.profile.index') }}"
                       class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2.5 bg-sea-green hover:bg-deep-forest text-white font-semibold text-sm rounded-xl transition shadow-sm">
                        Complete Profile →
                    </a>
                @endif
            </div>

        </div>
    </div>
    @endif


    {{-- ====== MAIN CONTENT GRID ====== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- === LEFT COLUMN (2/3) === --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- ====== COMPONENT 3: MY SERVICE REQUESTS ====== --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 lg:p-8">
                <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
                    <h2 class="text-base font-extrabold text-slate-900 flex items-center gap-2.5">
                        <span class="w-7 h-7 bg-sea-green/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-sea-green" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        </span>
                        My Service Requests
                    </h2>
                    @if($resident->canAccessServices())
                        <a href="{{ route('services.my-requests') }}"
                           class="text-xs font-bold text-sea-green hover:text-deep-forest transition flex items-center gap-1">
                            View All
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @endif
                </div>

                @if($sortedRequests->count() > 0)
                    <div class="space-y-2.5">
                        @foreach($sortedRequests as $req)
                        @php
                            $isPickup = $req->status === 'ready-for-pickup';
                            $isActive = in_array($req->status, ['pending', 'in-progress']);
                        @endphp

                        <a href="{{ route('service-request.show', $req->id) }}"
                           class="group flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 p-4 rounded-2xl border transition-all duration-150
                               {{ $isPickup ? 'bg-sea-green/5 border-sea-green/20 hover:border-sea-green/40 hover:shadow-md'
                                            : 'bg-slate-50 border-slate-200 hover:border-slate-300 hover:shadow-sm' }}">

                            <div class="flex items-center gap-3.5">
                                <div class="w-11 h-11 rounded-xl shadow-sm border flex items-center justify-center flex-shrink-0
                                    {{ $isPickup ? 'bg-sea-green/10 border-sea-green/20' : 'bg-white border-slate-100' }}">
                                    @if($isPickup)
                                        <svg class="w-5 h-5 text-sea-green" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @elseif($req->status === 'in-progress')
                                        <svg class="w-5 h-5 text-tiger-orange" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @elseif($req->status === 'pending')
                                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                                    @elseif($req->status === 'completed')
                                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @else
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                    @endif
                                </div>

                                <div>
                                    <h3 class="font-bold text-slate-900 text-sm leading-tight group-hover:text-sea-green transition">
                                        {{ $req->service->name ?? 'Service Request' }}
                                    </h3>
                                    <p class="text-xs text-slate-500 font-medium mt-0.5 flex items-center gap-1.5">
                                        <span class="font-mono text-slate-600">{{ $req->request_number }}</span>
                                        <span class="text-slate-300">•</span>
                                        <span>{{ $req->created_at->diffForHumans() }}</span>
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2.5 flex-shrink-0">
                                @switch($req->status)
                                    @case('pending')
                                        <span class="bg-amber-50 text-amber-700 border border-amber-200 px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wide">Pending</span>
                                        @break
                                    @case('in-progress')
                                        <span class="bg-orange-50 text-orange-700 border border-orange-200 px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wide">Processing</span>
                                        @break
                                    @case('ready-for-pickup')
                                        <span class="bg-sea-green text-white px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wide shadow-sm">Ready ✓</span>
                                        @break
                                    @case('completed')
                                        <span class="bg-slate-100 text-slate-600 border border-slate-200 px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wide">Done</span>
                                        @break
                                    @case('cancelled')
                                        <span class="bg-red-50 text-red-600 border border-red-200 px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wide">Cancelled</span>
                                        @break
                                    @default
                                        <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wide">{{ ucwords(str_replace('-', ' ', $req->status)) }}</span>
                                @endswitch

                                <div class="w-7 h-7 flex items-center justify-center rounded-full bg-white border border-slate-200 text-slate-500 group-hover:text-sea-green group-hover:border-sea-green/30 transition shadow-sm flex-shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10">
                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-slate-100">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-500">No service requests yet</p>
                        <p class="text-xs text-slate-400 mt-1">Submit your first e-service request</p>
                        @if($resident->canAccessServices())
                            <a href="{{ route('services.index') }}"
                               class="mt-4 inline-flex items-center gap-1.5 bg-sea-green/10 hover:bg-sea-green text-sea-green hover:text-white px-5 py-2.5 rounded-xl font-bold text-sm transition">
                                Browse E-Services
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </a>
                        @endif
                    </div>
                @endif
            </div>


            {{-- ====== COMPONENT 4: OFFICIAL ANNOUNCEMENTS ====== --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                {{-- Tab header --}}
                <div class="px-6 lg:px-8 pt-6 pb-0 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-base font-extrabold text-slate-900 mb-4 flex items-center gap-2.5">
                        <span class="w-7 h-7 bg-burnt-tangerine/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-burnt-tangerine" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46"/></svg>
                        </span>
                        Official Announcements
                    </h2>

                    <div class="flex gap-1 -mb-px">
                        <button @click="activeTab = 'memos'"
                                :class="activeTab === 'memos' ? 'border-sea-green text-sea-green bg-white' : 'border-transparent text-slate-600 hover:text-slate-800'"
                                class="px-4 py-2.5 border-b-2 font-bold text-sm transition rounded-t-lg flex items-center gap-1.5">
                            Memorandums
                            @if($memos->count() > 0)
                                <span :class="activeTab === 'memos' ? 'bg-sea-green text-white' : 'bg-slate-200 text-slate-600'"
                                      class="text-[11px] px-1.5 py-0.5 rounded-full font-bold transition min-w-[20px] text-center">{{ $memos->count() }}</span>
                            @endif
                        </button>
                        <button @click="activeTab = 'ordinances'"
                                :class="activeTab === 'ordinances' ? 'border-sea-green text-sea-green bg-white' : 'border-transparent text-slate-600 hover:text-slate-800'"
                                class="px-4 py-2.5 border-b-2 font-bold text-sm transition rounded-t-lg flex items-center gap-1.5">
                            Ordinances
                            @if($ordinances->count() > 0)
                                <span :class="activeTab === 'ordinances' ? 'bg-sea-green text-white' : 'bg-slate-200 text-slate-600'"
                                      class="text-[11px] px-1.5 py-0.5 rounded-full font-bold transition min-w-[20px] text-center">{{ $ordinances->count() }}</span>
                            @endif
                        </button>
                        <button @click="activeTab = 'news'"
                                :class="activeTab === 'news' ? 'border-sea-green text-sea-green bg-white' : 'border-transparent text-slate-600 hover:text-slate-800'"
                                class="px-4 py-2.5 border-b-2 font-bold text-sm transition rounded-t-lg flex items-center gap-1.5">
                            News
                            @if($news->count() > 0)
                                <span :class="activeTab === 'news' ? 'bg-sea-green text-white' : 'bg-slate-200 text-slate-600'"
                                      class="text-[11px] px-1.5 py-0.5 rounded-full font-bold transition min-w-[20px] text-center">{{ $news->count() }}</span>
                            @endif
                        </button>
                    </div>
                </div>

                <div class="p-6 lg:p-8 max-h-[480px] overflow-y-auto" id="announcement-container">

                    {{-- ── Memorandums ── --}}
                    <div x-show="activeTab === 'memos'"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="space-y-2">
                        @forelse($memos as $memo)
                        @php $isPriority = str_contains(strtolower($memo->category ?? ''), 'urgent') || str_contains(strtolower($memo->category ?? ''), 'high'); @endphp
                        <div x-data="{ open: false }"
                             class="rounded-xl border transition-all duration-150
                                 {{ $isPriority ? 'border-burnt-tangerine/25 bg-red-50/40 hover:border-burnt-tangerine/40' : 'border-slate-200 bg-white hover:border-slate-300' }}">
                            <button @click="open = !open" class="w-full text-left px-4 py-4 flex items-start justify-between gap-3 group">
                                <div class="flex items-start gap-3 min-w-0 flex-1">
                                    <div class="w-1 self-stretch rounded-full flex-shrink-0 {{ $isPriority ? 'bg-burnt-tangerine' : 'bg-sea-green' }}"></div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-bold text-slate-900 text-sm leading-snug group-hover:text-sea-green transition">{{ $memo->title }}</p>
                                        <p x-show="!open" class="text-xs text-slate-500 mt-1 line-clamp-1">{{ Str::limit($memo->content, 90) }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0 mt-0.5">
                                    @if($isPriority)
                                        <span class="text-[11px] font-bold text-burnt-tangerine bg-red-50 border border-red-200 px-2 py-0.5 rounded-md">{{ $memo->category }}</span>
                                    @else
                                        <span class="text-[11px] font-medium text-slate-500 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-md">{{ $memo->category }}</span>
                                    @endif
                                    <svg :class="open ? 'rotate-180' : ''"
                                         class="w-4 h-4 text-slate-400 transition-transform duration-200 flex-shrink-0"
                                         fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                    </svg>
                                </div>
                            </button>
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 style="display: none;"
                                 class="px-4 pb-4">
                                <div class="pl-4 ml-0.5 border-l-2 {{ $isPriority ? 'border-burnt-tangerine/30' : 'border-slate-200' }}">
                                    <p class="text-sm text-slate-700 leading-relaxed">{{ $memo->content }}</p>
                                    <p class="text-xs text-slate-500 font-medium mt-3">Posted {{ $memo->formatted_posted_at }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center mx-auto mb-3 border border-slate-100">
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            </div>
                            <p class="text-sm font-semibold text-slate-400">No memorandums at this time.</p>
                        </div>
                        @endforelse
                        @if($memos->count() >= 10)
                            <div class="text-center pt-3">
                                <button onclick="loadMore('memos')" id="load-more-memos"
                                        class="px-5 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition font-semibold text-sm">
                                    Load More
                                </button>
                            </div>
                        @endif
                    </div>

                    {{-- ── Ordinances ── --}}
                    <div x-show="activeTab === 'ordinances'"
                         style="display: none;"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="space-y-2">
                        @forelse($ordinances as $ordinance)
                        <div x-data="{ open: false }"
                             class="rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition-all duration-150">
                            <button @click="open = !open" class="w-full text-left px-4 py-4 flex items-start justify-between gap-3 group">
                                <div class="flex items-start gap-3 min-w-0 flex-1">
                                    <div class="w-1 self-stretch rounded-full flex-shrink-0 bg-blue-400"></div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-bold text-slate-900 text-sm leading-snug group-hover:text-sea-green transition">{{ $ordinance->title }}</p>
                                        <p x-show="!open" class="text-xs text-slate-500 mt-1 line-clamp-1">{{ Str::limit($ordinance->content, 90) }}</p>
                                    </div>
                                </div>
                                <svg :class="open ? 'rotate-180' : ''"
                                     class="w-4 h-4 text-slate-400 transition-transform duration-200 flex-shrink-0 mt-0.5"
                                     fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                </svg>
                            </button>
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 style="display: none;"
                                 class="px-4 pb-4">
                                <div class="pl-4 ml-0.5 border-l-2 border-slate-200">
                                    <p class="text-sm text-slate-700 leading-relaxed">{{ $ordinance->content }}</p>
                                    <div class="flex items-center justify-between mt-3">
                                        <p class="text-xs text-slate-500 font-medium">Posted {{ $ordinance->formatted_posted_at }}</p>
                                        <button class="text-xs font-bold text-sea-green hover:text-deep-forest transition flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                            Download PDF
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center mx-auto mb-3 border border-slate-100">
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                            </div>
                            <p class="text-sm font-semibold text-slate-400">No ordinances at this time.</p>
                        </div>
                        @endforelse
                        @if($ordinances->count() >= 10)
                            <div class="text-center pt-3">
                                <button onclick="loadMore('ordinances')" id="load-more-ordinances"
                                        class="px-5 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition font-semibold text-sm">
                                    Load More
                                </button>
                            </div>
                        @endif
                    </div>

                    {{-- ── News ── --}}
                    <div x-show="activeTab === 'news'"
                         style="display: none;"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="space-y-2">
                        @forelse($news as $item)
                        <div x-data="{ open: false }"
                             class="rounded-xl border border-slate-200 bg-white hover:border-slate-300 transition-all duration-150">
                            <button @click="open = !open" class="w-full text-left px-4 py-4 flex items-start justify-between gap-3 group">
                                <div class="flex items-start gap-3 min-w-0 flex-1">
                                    <div class="w-1 self-stretch rounded-full flex-shrink-0 bg-tiger-orange"></div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-bold text-slate-900 text-sm leading-snug group-hover:text-sea-green transition">{{ $item->title }}</p>
                                        <p x-show="!open" class="text-xs text-slate-500 mt-1 line-clamp-1">{{ Str::limit($item->content, 90) }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0 mt-0.5">
                                    <span class="text-[11px] font-medium {{ $item->category_badge_color }} px-2 py-0.5 rounded-md">{{ $item->category }}</span>
                                    <svg :class="open ? 'rotate-180' : ''"
                                         class="w-4 h-4 text-slate-400 transition-transform duration-200 flex-shrink-0"
                                         fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                    </svg>
                                </div>
                            </button>
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 style="display: none;"
                                 class="px-4 pb-4">
                                <div class="pl-4 ml-0.5 border-l-2 border-slate-200">
                                    <p class="text-sm text-slate-700 leading-relaxed">{{ $item->content }}</p>
                                    <p class="text-xs text-slate-500 font-medium mt-3">Posted {{ $item->formatted_posted_at }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center mx-auto mb-3 border border-slate-100">
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z"/></svg>
                            </div>
                            <p class="text-sm font-semibold text-slate-400">No news at this time.</p>
                        </div>
                        @endforelse
                        @if($news->count() >= 10)
                            <div class="text-center pt-3">
                                <button onclick="loadMore('news')" id="load-more-news"
                                        class="px-5 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition font-semibold text-sm">
                                    Load More
                                </button>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>


        {{-- === RIGHT SIDEBAR (1/3) === --}}
        <div class="space-y-6">

            {{-- ====== COMPONENT 5: QUICK ACTIONS ====== --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 lg:p-7">
                <div class="flex justify-between items-center mb-4 pb-3 border-b border-slate-100">
                    <h3 class="font-extrabold text-slate-900 text-base">Quick Actions</h3>
                    @if($resident->canAccessServices())
                        <a href="{{ route('services.index') }}"
                           class="text-xs font-bold text-sea-green hover:text-deep-forest transition flex items-center gap-0.5">
                            All Services
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-2.5">
                    @if($resident->canAccessServices())
                        @foreach($quickActions as $action)
                        <a href="{{ $action['route'] }}"
                           class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex flex-col items-center justify-center gap-2 transition-all duration-150 group hover:shadow-sm
                               {{ $action['hoverBorder'] }} {{ $action['hoverBg'] }}">
                            <span class="text-2xl group-hover:scale-110 transition-transform duration-150">{{ $action['emoji'] }}</span>
                            <span class="text-xs font-bold text-slate-800 text-center leading-snug">{{ $action['label'] }}</span>
                        </a>
                        @endforeach
                    @else
                        @foreach($quickActions as $action)
                        <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-4 flex flex-col items-center justify-center gap-2 cursor-not-allowed opacity-40">
                            <span class="text-2xl grayscale">{{ $action['emoji'] }}</span>
                            <span class="text-xs font-bold text-slate-500 text-center leading-snug">{{ $action['label'] }}</span>
                        </div>
                        @endforeach
                    @endif
                </div>

                @if(!$resident->canAccessServices())
                    <div class="mt-3 flex items-center gap-2.5 bg-amber-50 border border-amber-200 rounded-xl px-3.5 py-2.5">
                        <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        <p class="text-xs font-semibold text-amber-800">
                            Verify your identity to unlock e-services
                        </p>
                    </div>
                @endif
            </div>


            {{-- ====== COMPONENT 6: PROFILE HEALTH CARD ====== --}}
            @if(!$profileOk)
            {{-- State A: Needs Attention --}}
            <div class="bg-deep-forest rounded-3xl shadow-xl p-7 relative overflow-hidden text-white">
                <div class="absolute -bottom-8 -right-8 w-32 h-32 bg-white/5 rounded-full pointer-events-none"></div>
                <div class="absolute -top-8 -left-8 w-24 h-24 bg-white/5 rounded-full pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-2.5 h-2.5 rounded-full bg-golden-glow animate-pulse"></span>
                        <span class="text-xs font-bold text-golden-glow uppercase tracking-widest">Action Required</span>
                    </div>

                    <h3 class="text-lg font-extrabold mb-2 leading-tight">
                        {{ !$onboardingDone ? 'Finish Your Setup' : 'Update Household Data' }}
                    </h3>
                    <p class="text-sm text-white/75 mb-5 leading-relaxed">
                        {{ !$onboardingDone
                            ? 'Complete setup to unlock full LGU benefits and qualify for subsidy programs.'
                            : 'Keep your socio-economic data current to qualify for targeted municipal programs.' }}
                    </p>

                    {{-- Checklist --}}
                    <div class="space-y-2.5 mb-5">
                        @php
                            $checks = [
                                ['done' => $emailVerified,             'label' => 'Email verified'],
                                ['done' => $philsysVerified,           'label' => 'PhilSys linked'],
                                ['done' => $resident->profile_matched, 'label' => 'Profile matched'],
                                ['done' => $onboardingDone,            'label' => 'Setup complete'],
                            ];
                        @endphp
                        @foreach($checks as $check)
                        <div class="flex items-center gap-2.5">
                            <span class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 {{ $check['done'] ? 'bg-sea-green' : 'bg-white/20' }}">
                                @if($check['done'])
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                @endif
                            </span>
                            <span class="text-sm {{ $check['done'] ? 'text-white/85' : 'text-white/45' }}">
                                {{ $check['label'] }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                    <a href="{{ route('citizen.profile.index') }}"
                       class="block w-full text-center bg-gradient-to-r from-sea-green to-[#006b3c] hover:from-[#006b3c] hover:to-sea-green text-white font-bold py-3.5 px-4 rounded-xl transition shadow-lg text-sm">
                        {{ !$onboardingDone ? 'Finish Setup →' : 'Update Profile →' }}
                    </a>
                </div>
            </div>

            @else
            {{-- State B: Profile Complete --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-7">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-11 h-11 rounded-xl bg-sea-green/10 border border-sea-green/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-sea-green" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-extrabold text-slate-900">Profile Complete</p>
                        <p class="text-xs text-sea-green font-semibold mt-0.5">All steps verified ✓</p>
                    </div>
                </div>
                <p class="text-sm text-slate-600 leading-relaxed mb-4">
                    Your resident profile is fully verified. You're qualified for all available LGU programs and e-services.
                </p>
                <a href="{{ route('citizen.profile.index') }}"
                   class="block w-full text-center bg-slate-50 border border-slate-200 hover:border-sea-green hover:bg-sea-green/5 text-slate-700 hover:text-sea-green font-bold py-2.5 px-4 rounded-xl transition text-sm">
                    View My Profile
                </a>
            </div>
            @endif

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    let offsets = {
        memos: {{ $memos->count() }},
        ordinances: {{ $ordinances->count() }},
        news: {{ $news->count() }}
    };

    async function loadMore(category) {
        const button = document.getElementById('load-more-' + category);
        button.disabled = true;
        button.textContent = 'Loading...';
        try {
            const response = await fetch(`/dashboard/load-more-announcements?category=${category}&offset=${offsets[category]}`);
            const data = await response.json();
            if (data.announcements && data.announcements.length > 0) {
                const borderColorMap = { memos: 'bg-sea-green', ordinances: 'bg-blue-400', news: 'bg-tiger-orange' };
                data.announcements.forEach(a => {
                    const isPriority = category === 'memos' && ['urgent','high'].some(k => (a.category || '').toLowerCase().includes(k));
                    const div = document.createElement('div');
                    div.setAttribute('x-data', '{ open: false }');
                    div.className = `rounded-xl border transition-all duration-150 ${isPriority ? 'border-burnt-tangerine/25 bg-red-50/40' : 'border-slate-200 bg-white hover:border-slate-300'}`;

                    let badgeHtml = '';
                    if (isPriority) badgeHtml = `<span class="text-[11px] font-bold text-burnt-tangerine bg-red-50 border border-red-200 px-2 py-0.5 rounded-md">${a.category}</span>`;
                    else if (category === 'news') badgeHtml = `<span class="text-[11px] font-medium ${a.category_badge_color} px-2 py-0.5 rounded-md">${a.category}</span>`;
                    else if (category === 'memos') badgeHtml = `<span class="text-[11px] font-medium text-slate-500 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-md">${a.category}</span>`;

                    const downloadBtn = category === 'ordinances'
                        ? `<button class="text-xs font-bold text-sea-green hover:text-deep-forest transition flex items-center gap-1">Download PDF</button>`
                        : '';

                    div.innerHTML = `
                        <div class="px-4 py-4 flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3 min-w-0 flex-1">
                                <div class="w-1 self-stretch rounded-full flex-shrink-0 ${borderColorMap[category] || 'bg-slate-400'}"></div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-bold text-slate-900 text-sm leading-snug">${a.title}</p>
                                    <p class="text-xs text-slate-500 mt-1 line-clamp-1">${a.content_preview}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">${badgeHtml}</div>
                        </div>
                        <div class="px-4 pb-4">
                            <div class="pl-4 ml-0.5 border-l-2 border-slate-200">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-slate-500 font-medium">${a.formatted_posted_at}</span>
                                    ${downloadBtn}
                                </div>
                            </div>
                        </div>`;

                    button.parentElement.parentElement.insertBefore(div, button.parentElement);
                });
                offsets[category] += data.announcements.length;
                if (!data.hasMore) button.parentElement.remove();
                else { button.disabled = false; button.textContent = 'Load More'; }
            } else {
                button.parentElement.remove();
            }
        } catch (e) {
            console.error('Error loading more:', e);
            button.disabled = false;
            button.textContent = 'Load More';
        }
    }
</script>
@endpush
