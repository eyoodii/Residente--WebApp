@extends('layouts.citizen')

@section('title', 'Request #' . $serviceRequest->request_number . ' | RESIDENTE App')
@section('page-title', 'Request Tracker')
@section('page-subtitle', $serviceRequest->service->name)

@section('header-actions')
@php
$headerStatusMap = [
    'pending'          => ['bg' => 'bg-amber-50 border-amber-200',   'text' => 'text-amber-700',   'dot' => 'bg-amber-400',    'label' => 'Pending'],
    'in-progress'      => ['bg' => 'bg-orange-50 border-orange-200', 'text' => 'text-orange-700',  'dot' => 'bg-tiger-orange', 'label' => 'Processing'],
    'ready-for-pickup' => ['bg' => 'bg-sea-green/10 border-sea-green/30','text' => 'text-sea-green','dot' => 'bg-sea-green',    'label' => 'Ready for Pickup'],
    'completed'        => ['bg' => 'bg-slate-100 border-slate-200',  'text' => 'text-slate-600',   'dot' => 'bg-slate-400',    'label' => 'Completed'],
    'cancelled'        => ['bg' => 'bg-red-50 border-red-200',       'text' => 'text-red-600',     'dot' => 'bg-red-400',      'label' => 'Cancelled'],
];
$hs = $headerStatusMap[$serviceRequest->status] ?? $headerStatusMap['pending'];
@endphp
    <span class="inline-flex items-center gap-2 {{ $hs['bg'] }} {{ $hs['text'] }} border px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide flex-shrink-0">
        <span class="w-1.5 h-1.5 rounded-full {{ $hs['dot'] }} {{ $serviceRequest->status === 'in-progress' ? 'animate-pulse' : '' }} inline-block"></span>
        {{ $hs['label'] }}
    </span>
@endsection

@section('content')
<div class="px-4 lg:px-6 py-6 space-y-5">

    {{-- Request Overview Card --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 lg:px-8 py-5 border-b border-slate-100">
            <h2 class="text-base font-extrabold text-slate-900 flex items-center gap-2.5">
                <span class="w-7 h-7 bg-deep-forest/10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-deep-forest" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                </span>
                Request Details
            </h2>
        </div>
        <div class="px-6 lg:px-8 py-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Request Number</p>
                <p class="text-base font-extrabold text-deep-forest font-mono">{{ $serviceRequest->request_number }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Service</p>
                <p class="text-sm font-bold text-slate-900 leading-snug">{{ $serviceRequest->service->name }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Submitted On</p>
                <p class="text-sm font-bold text-slate-900">{{ $serviceRequest->requested_at->format('M d, Y') }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $serviceRequest->requested_at->format('h:i A') }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Current Step</p>
                @php $totalSteps = $serviceRequest->service->steps->count(); @endphp
                <div class="flex items-center gap-2">
                    <p class="text-sm font-bold text-tiger-orange">
                        Step {{ $serviceRequest->current_step }} / {{ $totalSteps }}
                    </p>
                </div>
                @if($totalSteps > 0)
                <div class="mt-1.5 w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="bg-sea-green h-1.5 rounded-full transition-all"
                         style="width: {{ round(($serviceRequest->current_step / $totalSteps) * 100) }}%"></div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Progress Timeline --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 lg:px-8 py-5 border-b border-slate-100">
            <h2 class="text-base font-extrabold text-slate-900 flex items-center gap-2.5">
                <span class="w-7 h-7 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                Progress Timeline
            </h2>
        </div>
        <div class="px-6 lg:px-8 py-6">
            <div class="relative">
                @foreach($serviceRequest->service->steps as $step)
                @php
                    $isComplete = $step->step_number < $serviceRequest->current_step;
                    $isCurrent  = $step->step_number == $serviceRequest->current_step;
                    $isPending  = $step->step_number > $serviceRequest->current_step;
                @endphp
                <div class="flex gap-4 relative {{ !$loop->last ? 'mb-4' : '' }}">
                    @if(!$loop->last)
                    <div class="absolute left-5 top-10 bottom-[-1rem] w-0.5
                        {{ $isComplete ? 'bg-sea-green' : 'bg-slate-200' }}"></div>
                    @endif

                    {{-- Circle --}}
                    <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center font-extrabold z-10 text-sm shadow-sm
                        {{ $isComplete ? 'bg-sea-green text-white' : '' }}
                        {{ $isCurrent  ? 'bg-tiger-orange text-white ring-4 ring-orange-100' : '' }}
                        {{ $isPending  ? 'bg-slate-100 text-slate-400' : '' }}">
                        @if($isComplete)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        @else
                            {{ $step->step_number }}
                        @endif
                    </div>

                    {{-- Step card --}}
                    <div class="flex-1 rounded-2xl p-4 border transition
                        {{ $isComplete ? 'bg-slate-50 border-slate-100 opacity-60' : '' }}
                        {{ $isCurrent  ? 'bg-orange-50 border-tiger-orange/30 shadow-sm' : '' }}
                        {{ $isPending  ? 'bg-white border-slate-100 opacity-50' : '' }}">
                        <div class="flex flex-wrap items-start justify-between gap-2 mb-2">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full
                                    {{ $step->is_client_step ? 'bg-tiger-orange/10 text-tiger-orange' : 'bg-sea-green/10 text-sea-green' }}">
                                    {{ $step->is_client_step ? 'Your Action' : 'Agency Step' }}
                                </span>
                                @if($isCurrent)
                                <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-tiger-orange text-white animate-pulse">
                                    Current
                                </span>
                                @elseif($isComplete)
                                <span class="text-xs font-semibold text-sea-green">Completed</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-3 flex-shrink-0">
                                @if($step->processing_time_minutes)
                                <span class="text-xs text-slate-500 font-medium flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $step->processing_time_minutes }} min
                                </span>
                                @endif
                            </div>
                        </div>

                        <p class="text-sm {{ $isPending ? 'text-slate-400' : 'text-slate-700' }} leading-relaxed">{{ $step->description }}</p>

                        @if($step->responsible_person || $step->fee > 0)
                        <div class="flex flex-wrap gap-3 mt-2.5 text-xs text-slate-500 font-medium">
                            @if($step->responsible_person)
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                {{ $step->responsible_person }}
                            </span>
                            @endif
                            @if($step->fee > 0)
                            <span class="flex items-center gap-1 text-sea-green font-semibold">₱{{ number_format($step->fee, 2) }}</span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Requirements Reminder --}}
    @if($serviceRequest->service->requirements->count() > 0 && $serviceRequest->status === 'pending')
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
        <div class="flex items-center gap-2.5 mb-3">
            <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            </div>
            <h4 class="font-bold text-amber-800 text-sm">Reminder: Prepare Required Documents</h4>
        </div>
        <ul class="grid grid-cols-1 md:grid-cols-2 gap-2">
            @foreach($serviceRequest->service->requirements as $requirement)
            <li class="text-sm text-amber-800 flex items-start gap-2">
                <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                {{ $requirement->requirement }}
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('services.my-requests') }}"
           class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 px-6 py-3 rounded-xl font-bold text-sm transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Back to My Requests
        </a>
        @if($serviceRequest->status === 'completed')
        <button class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-sea-green hover:bg-deep-forest text-white px-6 py-3 rounded-xl font-bold text-sm transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
            Download Certificate
        </button>
        @endif
    </div>

</div>
@endsection
