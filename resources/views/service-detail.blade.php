@extends('layouts.citizen')

@section('title', $service->name . ' | RESIDENTE App')
@section('page-title', $service->name)
@section('page-subtitle', $service->department)

@section('header-actions')
    @if($service->is_active)
        <form action="{{ route('services.request', $service->slug) }}" method="POST">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-1.5 bg-deep-forest hover:bg-sea-green text-white pl-4 pr-5 py-2 rounded-xl font-bold text-sm shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Request This Service
            </button>
        </form>
    @else
        <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 border border-red-200 px-3 py-2 rounded-xl font-bold text-xs">
            <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block animate-pulse"></span>
            Unavailable
        </span>
    @endif
@endsection

@section('content')
<div class="px-4 lg:px-6 py-6 space-y-5">

    {{-- Unavailability Notice --}}
    @if(!$service->is_active)
    <div class="bg-red-50 border border-red-200 rounded-2xl p-5 flex items-start gap-4">
        <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
        </div>
        <div>
            <h3 class="font-bold text-red-800 text-sm mb-0.5">Service Temporarily Unavailable</h3>
            <p class="text-sm text-red-700 leading-relaxed">
                This service is not currently accepting requests. Please check back later or contact the {{ $service->department }}.
            </p>
        </div>
    </div>
    @endif

    {{-- Service Overview Card --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 lg:px-8 py-5 border-b border-slate-100">
            <h2 class="text-base font-extrabold text-slate-900 flex items-center gap-2.5">
                <span class="w-7 h-7 bg-deep-forest/10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-deep-forest" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                </span>
                Service Overview
            </h2>
        </div>

        <div class="px-6 lg:px-8 py-6">
            {{-- Stats strip --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Department</p>
                    <p class="text-sm font-bold text-slate-900 leading-snug">{{ $service->department }}</p>
                </div>
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Classification</p>
                    <p class="text-sm font-bold text-slate-900">{{ $service->classification }}</p>
                </div>
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Processing Time</p>
                    <p class="text-sm font-bold text-slate-900">{{ $service->processing_time_formatted }}</p>
                </div>
                <div class="bg-sea-green/5 rounded-2xl p-4 border border-sea-green/15">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Fee</p>
                    <p class="text-sm font-bold text-sea-green">{{ $service->formatted_fee }}</p>
                </div>
            </div>

            {{-- Description + Who May Avail --}}
            <div class="space-y-4">
                @if($service->description)
                <div>
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Description</h4>
                    <p class="text-sm text-slate-700 leading-relaxed">{{ $service->description }}</p>
                </div>
                @endif
                @if($service->who_may_avail)
                <div>
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Who May Avail</h4>
                    <p class="text-sm text-slate-700 leading-relaxed">{{ $service->who_may_avail }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Requirements --}}
    @if($service->requirements->count() > 0)
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 lg:px-8 py-5 border-b border-slate-100">
            <h2 class="text-base font-extrabold text-slate-900 flex items-center gap-2.5">
                <span class="w-7 h-7 bg-amber-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </span>
                Requirements
                <span class="ml-1 bg-amber-100 text-amber-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ $service->requirements->count() }}</span>
            </h2>
        </div>
        <div class="divide-y divide-slate-50">
            @foreach($service->requirements as $i => $requirement)
            <div class="px-6 lg:px-8 py-4 flex items-start gap-4 hover:bg-slate-50 transition-colors">
                <span class="w-6 h-6 rounded-full bg-sea-green/10 text-sea-green text-xs font-extrabold flex items-center justify-center flex-shrink-0 mt-0.5">{{ $i + 1 }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-900 leading-snug">{{ $requirement->requirement }}</p>
                    @if($requirement->where_to_secure)
                    <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        Secure from: {{ $requirement->where_to_secure }}
                    </p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Process Timeline --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 lg:px-8 py-5 border-b border-slate-100">
            <h2 class="text-base font-extrabold text-slate-900 flex items-center gap-2.5">
                <span class="w-7 h-7 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                Service Process Timeline
            </h2>
        </div>
        <div class="px-6 lg:px-8 py-6">
            <div class="relative">
                @foreach($service->steps as $step)
                <div class="flex gap-4 {{ !$loop->last ? 'mb-4' : '' }} relative">
                    @if(!$loop->last)
                    <div class="absolute left-5 top-10 bottom-[-1rem] w-0.5 {{ $step->is_client_step ? 'bg-tiger-orange/30' : 'bg-sea-green/30' }}"></div>
                    @endif

                    {{-- Step circle --}}
                    <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center font-extrabold z-10 text-sm shadow-sm
                        {{ $step->is_client_step ? 'bg-tiger-orange text-white' : 'bg-sea-green text-white' }}">
                        {{ $step->step_number }}
                    </div>

                    {{-- Step card --}}
                    <div class="flex-1 rounded-2xl p-4 border {{ $step->is_client_step ? 'bg-orange-50 border-orange-100' : 'bg-slate-50 border-slate-100' }}">
                        <div class="flex flex-wrap items-start justify-between gap-2 mb-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                    {{ $step->is_client_step ? 'bg-tiger-orange text-white' : 'bg-sea-green text-white' }}">
                                    {{ $step->is_client_step ? 'Client Action' : 'Agency Step' }}
                                </span>
                            </div>
                            @if($step->processing_time_minutes)
                            <span class="text-xs text-slate-500 font-medium bg-white border border-slate-200 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $step->processing_time_minutes }} min
                            </span>
                            @endif
                        </div>

                        <p class="text-sm text-slate-700 leading-relaxed">{{ $step->description }}</p>

                        @if($step->responsible_person || $step->fee > 0)
                        <div class="flex flex-wrap gap-3 mt-2.5 text-xs text-slate-500 font-medium">
                            @if($step->responsible_person)
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                {{ $step->responsible_person }}
                            </span>
                            @endif
                            @if($step->fee > 0)
                            <span class="flex items-center gap-1 text-sea-green font-semibold">
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                                ₱{{ number_format($step->fee, 2) }}
                            </span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Summary totals --}}
            <div class="mt-6 bg-gradient-to-r from-deep-forest to-sea-green rounded-2xl p-5 text-white flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold text-white/70 uppercase tracking-wide mb-0.5">Total Processing Time</p>
                    <p class="text-xl font-extrabold">{{ $service->processing_time_formatted }}</p>
                </div>
                <div class="w-px h-10 bg-white/20"></div>
                <div class="text-right">
                    <p class="text-xs font-bold text-white/70 uppercase tracking-wide mb-0.5">Total Fee</p>
                    <p class="text-xl font-extrabold">{{ $service->formatted_fee }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- CTA Banner --}}
    @if($service->is_active)
    <div class="bg-gradient-to-r from-deep-forest via-[#045d3f] to-sea-green rounded-3xl shadow-xl p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 text-white">
        <div>
            <h3 class="text-xl font-extrabold mb-1 leading-tight">Ready to request this service?</h3>
            <p class="text-white/75 text-sm">Submit your request now and track its progress in real-time.</p>
        </div>
        <form action="{{ route('services.request', $service->slug) }}" method="POST" class="flex-shrink-0">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-white hover:bg-golden-glow text-deep-forest px-8 py-3.5 rounded-2xl font-extrabold shadow-lg transition transform hover:-translate-y-0.5 text-sm">
                Request Now
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </button>
        </form>
    </div>
    @endif

</div>
@endsection
