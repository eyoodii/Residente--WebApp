@extends('layouts.citizen')

@section('title', 'My Service Requests | RESIDENTE App')
@section('page-title', 'My Requests')
@section('page-subtitle', 'Track your service request history')

@section('header-actions')
    <a href="{{ route('services.index') }}"
       class="inline-flex items-center gap-1.5 bg-deep-forest hover:bg-sea-green text-white pl-4 pr-5 py-2 rounded-xl font-semibold text-sm shadow-sm transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        New Request
    </a>
@endsection

@section('content')
@php
    $statusConfig = [
        'pending'         => ['label' => 'Pending',    'bg' => 'bg-amber-50',   'text' => 'text-amber-700',  'border' => 'border-amber-200', 'bar' => 'bg-amber-400', 'accent' => 'border-l-amber-400'],
        'in-progress'     => ['label' => 'Processing', 'bg' => 'bg-orange-50',  'text' => 'text-orange-700', 'border' => 'border-orange-200','bar' => 'bg-tiger-orange','accent' => 'border-l-tiger-orange'],
        'ready-for-pickup'=> ['label' => 'Ready',      'bg' => 'bg-sea-green/10','text' => 'text-sea-green', 'border' => 'border-sea-green/30','bar' => 'bg-sea-green',  'accent' => 'border-l-sea-green'],
        'completed'       => ['label' => 'Completed',  'bg' => 'bg-slate-100',  'text' => 'text-slate-600',  'border' => 'border-slate-200', 'bar' => 'bg-slate-400', 'accent' => 'border-l-slate-300'],
        'cancelled'       => ['label' => 'Cancelled',  'bg' => 'bg-red-50',     'text' => 'text-red-600',    'border' => 'border-red-200',   'bar' => 'bg-red-400',   'accent' => 'border-l-red-400'],
    ];
@endphp

<div class="px-4 lg:px-6 py-6 space-y-6" x-data="{ filter: 'all' }">

    {{-- ====== STAT STRIP ====== --}}
    @if(!$requests->isEmpty())
    @php
        $all       = $requests->total();
        $active    = $requests->getCollection()->whereIn('status', ['pending','in-progress'])->count();
        $readyPick = $requests->getCollection()->where('status', 'ready-for-pickup')->count();
        $done      = $requests->getCollection()->where('status', 'completed')->count();
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <button @click="filter = 'all'"
                :class="filter === 'all' ? 'ring-2 ring-deep-forest bg-deep-forest text-white' : 'bg-white text-slate-700 hover:border-slate-300'"
                class="flex flex-col items-center justify-center gap-1 p-4 rounded-2xl border border-slate-200 shadow-sm transition cursor-pointer">
            <p class="text-2xl font-extrabold leading-none">{{ $all }}</p>
            <p class="text-xs font-bold uppercase tracking-wide opacity-80">All</p>
        </button>
        <button @click="filter = 'active'"
                :class="filter === 'active' ? 'ring-2 ring-tiger-orange bg-tiger-orange text-white' : 'bg-white text-slate-700 hover:border-amber-300'"
                class="flex flex-col items-center justify-center gap-1 p-4 rounded-2xl border border-slate-200 shadow-sm transition cursor-pointer">
            <p class="text-2xl font-extrabold leading-none">{{ $active }}</p>
            <p class="text-xs font-bold uppercase tracking-wide opacity-80">Active</p>
        </button>
        <button @click="filter = 'ready'"
                :class="filter === 'ready' ? 'ring-2 ring-sea-green bg-sea-green text-white' : 'bg-white text-slate-700 hover:border-sea-green/40'"
                class="flex flex-col items-center justify-center gap-1 p-4 rounded-2xl border border-slate-200 shadow-sm transition cursor-pointer">
            <p class="text-2xl font-extrabold leading-none">{{ $readyPick }}</p>
            <p class="text-xs font-bold uppercase tracking-wide opacity-80">Ready</p>
        </button>
        <button @click="filter = 'done'"
                :class="filter === 'done' ? 'ring-2 ring-slate-600 bg-slate-600 text-white' : 'bg-white text-slate-700 hover:border-slate-400'"
                class="flex flex-col items-center justify-center gap-1 p-4 rounded-2xl border border-slate-200 shadow-sm transition cursor-pointer">
            <p class="text-2xl font-extrabold leading-none">{{ $done }}</p>
            <p class="text-xs font-bold uppercase tracking-wide opacity-80">Done</p>
        </button>
    </div>
    @endif

    {{-- ====== REQUESTS LIST ====== --}}
    @if($requests->isEmpty())
        {{-- Empty State --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-12 text-center">
            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
            </div>
            <h2 class="text-lg font-extrabold text-slate-900 mb-1">No Service Requests Yet</h2>
            <p class="text-sm text-slate-500 mb-6">Submit your first request to get started with LGU e-services.</p>
            <a href="{{ route('services.index') }}"
               class="inline-flex items-center gap-2 bg-deep-forest hover:bg-sea-green text-white px-6 py-3 rounded-xl font-bold text-sm shadow-sm transition">
                Browse E-Services
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>

    @else
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 lg:px-8 py-5 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-base font-extrabold text-slate-900 flex items-center gap-2.5">
                    <span class="w-7 h-7 bg-deep-forest/10 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-deep-forest" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    </span>
                    Service Requests
                </h2>
                <span class="text-xs font-semibold text-slate-500">{{ $requests->total() }} total</span>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach($requests as $request)
                @php
                    $sc     = $statusConfig[$request->status] ?? $statusConfig['pending'];
                    $ready  = $request->status === 'ready-for-pickup';
                    $active = in_array($request->status, ['pending', 'in-progress']);
                    $totalSteps = $request->service->steps->count();
                    $pct    = $totalSteps > 0 ? round(($request->current_step / $totalSteps) * 100) : 0;
                @endphp

                {{-- Filter visibility (Alpine) --}}
                <div
                    x-show="
                        filter === 'all' ||
                        (filter === 'active' && {{ in_array($request->status, ['pending','in-progress']) ? 'true' : 'false' }}) ||
                        (filter === 'ready'  && {{ $request->status === 'ready-for-pickup' ? 'true' : 'false' }}) ||
                        (filter === 'done'   && {{ $request->status === 'completed' ? 'true' : 'false' }})
                    "
                    class="group border-l-4 {{ $sc['accent'] }} hover:bg-slate-50 transition-colors">

                    <a href="{{ route('service-request.show', $request->request_number) }}"
                       class="block px-6 lg:px-8 py-5">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                            {{-- Left: service info --}}
                            <div class="flex items-start gap-4 flex-1 min-w-0">
                                {{-- Status icon --}}
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 {{ $sc['bg'] }} border {{ $sc['border'] }}">
                                    @if($ready)
                                        <svg class="w-5 h-5 text-sea-green" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @elseif($request->status === 'in-progress')
                                        <svg class="w-5 h-5 text-tiger-orange" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @elseif($request->status === 'pending')
                                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                                    @elseif($request->status === 'completed')
                                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @else
                                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <h3 class="font-bold text-slate-900 text-sm leading-tight group-hover:text-sea-green transition truncate">
                                            {{ $request->service->name }}
                                        </h3>
                                        <span class="{{ $sc['bg'] }} {{ $sc['text'] }} border {{ $sc['border'] }} px-2.5 py-0.5 rounded-lg text-xs font-bold uppercase tracking-wide flex-shrink-0">
                                            {{ $sc['label'] }}
                                        </span>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-0.5 text-xs text-slate-500 font-medium">
                                        <span class="font-mono text-slate-600">{{ $request->request_number }}</span>
                                        <span class="text-slate-300">·</span>
                                        <span>{{ $request->service->department }}</span>
                                        <span class="text-slate-300">·</span>
                                        <span>{{ $request->requested_at->format('M d, Y') }}</span>
                                    </div>

                                    {{-- Progress bar (only for active) --}}
                                    @if($active && $totalSteps > 0)
                                    <div class="mt-2.5 flex items-center gap-2.5">
                                        <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="{{ $sc['bar'] }} h-1.5 rounded-full transition-all"
                                                 style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="text-[11px] font-bold {{ $sc['text'] }} flex-shrink-0">
                                            Step {{ $request->current_step }}/{{ $totalSteps }}
                                        </span>
                                    </div>
                                    @endif

                                    @if($ready)
                                    <p class="mt-2 text-xs font-bold text-sea-green flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sea-green inline-block animate-pulse"></span>
                                        Ready for pickup at the Municipal Hall
                                    </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Right: timestamp + arrow --}}
                            <div class="flex items-center gap-3 flex-shrink-0 sm:pl-4">
                                <div class="text-right hidden sm:block">
                                    <p class="text-xs text-slate-400 font-medium">Submitted</p>
                                    <p class="text-sm font-semibold text-slate-700">{{ $request->requested_at->diffForHumans() }}</p>
                                </div>
                                <div class="w-7 h-7 flex items-center justify-center rounded-full bg-white border border-slate-200 text-slate-400 group-hover:text-sea-green group-hover:border-sea-green/30 transition shadow-sm flex-shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Pagination --}}
        @if($requests->hasPages())
        <div class="flex justify-center">
            {{ $requests->links() }}
        </div>
        @endif
    @endif

</div>
@endsection
