@extends('layouts.citizen')

@section('title', 'E-Services Directory | RESIDENTE App')
@section('page-title', 'E-Services')
@section('page-subtitle', 'Browse all available LGU services by department')

@section('header-actions')
    <a href="{{ route('services.my-requests') }}"
       class="inline-flex items-center gap-1.5 bg-white border border-slate-200 hover:border-sea-green text-slate-700 hover:text-sea-green pl-4 pr-5 py-2 rounded-xl font-semibold text-sm shadow-sm transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
        My Requests
    </a>
@endsection

@section('content')
@php
$deptConfig = [
    'Municipal Health Office' => [
        'icon_bg'   => 'bg-sea-green/10',
        'icon_text' => 'text-sea-green',
        'bar'       => 'bg-sea-green',
        'heading'   => 'text-sea-green',
        'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>',
    ],
    'Municipal Civil Registrar' => [
        'icon_bg'   => 'bg-tiger-orange/10',
        'icon_text' => 'text-tiger-orange',
        'bar'       => 'bg-tiger-orange',
        'heading'   => 'text-tiger-orange',
        'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>',
    ],
    "Mayor's Office" => [
        'icon_bg'   => 'bg-golden-glow/15',
        'icon_text' => 'text-yellow-700',
        'bar'       => 'bg-golden-glow',
        'heading'   => 'text-yellow-700',
        'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>',
    ],
    'Municipal Planning and Development Office' => [
        'icon_bg'   => 'bg-burnt-tangerine/10',
        'icon_text' => 'text-burnt-tangerine',
        'bar'       => 'bg-burnt-tangerine',
        'heading'   => 'text-burnt-tangerine',
        'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"/>',
    ],
];
@endphp

<div class="px-4 lg:px-6 py-6 space-y-6"
     x-data="{ search: '' }">

    {{-- Search bar --}}
    <div class="relative">
        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
        <input x-model="search"
               type="text"
               placeholder="Search services..."
               class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sea-green/30 focus:border-sea-green shadow-sm transition">
        <button x-show="search" @click="search = ''"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Department Cards --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        @foreach($servicesByDepartment as $department => $services)
        @php
            $cfg = $deptConfig[$department] ?? [
                'icon_bg' => 'bg-slate-100', 'icon_text' => 'text-slate-600',
                'bar' => 'bg-slate-500', 'heading' => 'text-slate-700',
                'svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>',
            ];
            $deptId = \Illuminate\Support\Str::slug($department);
            $activeCount = $services->where('is_active', true)->count();
        @endphp

        <div id="{{ $deptId }}"
             class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden scroll-mt-6"
             x-show="search === '' || {{ collect($services->pluck('name'))->map(fn($n) => "'".addslashes($n)."'")->implode(',') }}.some(n => n.toLowerCase().includes(search.toLowerCase())) || '{{ addslashes($department) }}'.toLowerCase().includes(search.toLowerCase())">

            {{-- Card header --}}
            <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl {{ $cfg['icon_bg'] }} flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 {{ $cfg['icon_text'] }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        {!! $cfg['svg'] !!}
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-extrabold text-slate-900 text-sm leading-snug">{{ $department }}</h3>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $activeCount }} {{ Str::plural('service', $activeCount) }} available</p>
                </div>
                <div class="w-1.5 self-stretch rounded-full {{ $cfg['bar'] }} flex-shrink-0"></div>
            </div>

            {{-- Services list --}}
            <div class="divide-y divide-slate-50">
                @foreach($services as $service)
                <div x-show="search === '' || '{{ addslashes($service->name) }}'.toLowerCase().includes(search.toLowerCase())"
                     class="{{ $service->is_active ? '' : 'opacity-45' }}">
                    @if($service->is_active)
                        <a href="{{ route('services.show', $service->slug) }}"
                           class="group flex items-center justify-between px-6 py-3.5 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-1.5 h-1.5 rounded-full {{ $cfg['bar'] }} flex-shrink-0 opacity-50 group-hover:opacity-100"></span>
                                <span class="text-sm font-medium text-slate-700 group-hover:text-slate-900 truncate transition">{{ $service->name }}</span>
                            </div>
                            <div class="flex items-center gap-2.5 flex-shrink-0 ml-3">
                                @if($service->formatted_fee && $service->formatted_fee !== 'Free')
                                    <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-lg">{{ $service->formatted_fee }}</span>
                                @else
                                    <span class="text-xs font-semibold text-sea-green bg-sea-green/10 px-2 py-0.5 rounded-lg">Free</span>
                                @endif
                                <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-sea-green transition group-hover:translate-x-0.5 transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </a>
                    @else
                        <div class="flex items-center justify-between px-6 py-3.5 cursor-default">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-200 flex-shrink-0"></span>
                                <span class="text-sm font-medium text-slate-400 truncate">{{ $service->name }}</span>
                            </div>
                            <span class="text-[11px] font-semibold text-red-500 bg-red-50 border border-red-100 px-2 py-0.5 rounded-lg flex-shrink-0 ml-3">Unavailable</span>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        {{-- Document Verification Card --}}
        <div class="bg-gradient-to-br from-deep-forest to-[#023a29] rounded-3xl shadow-sm overflow-hidden"
             x-show="search === '' || 'document verification'.includes(search.toLowerCase()) || 'barangay cert'.includes(search.toLowerCase()) || 'atop'.includes(search.toLowerCase())">
            <div class="px-6 py-5 border-b border-white/10 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-golden-glow" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-extrabold text-white text-sm">Document Verification</h3>
                    <p class="text-xs text-white/60 font-medium mt-0.5">Verify authenticity of issued documents</p>
                </div>
                <div class="w-1.5 self-stretch rounded-full bg-golden-glow/50 flex-shrink-0"></div>
            </div>
            <div class="divide-y divide-white/5">
                <button class="w-full flex items-center justify-between px-6 py-3.5 hover:bg-white/10 transition-colors text-left group">
                    <span class="text-sm font-medium text-white/80 group-hover:text-white transition">Barangay Certificate Verify</span>
                    <svg class="w-3.5 h-3.5 text-white/40 group-hover:text-golden-glow transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
                <button class="w-full flex items-center justify-between px-6 py-3.5 hover:bg-white/10 transition-colors text-left group">
                    <span class="text-sm font-medium text-white/80 group-hover:text-white transition">ATOP Certificate Verify</span>
                    <svg class="w-3.5 h-3.5 text-white/40 group-hover:text-golden-glow transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- No results state --}}
    <div x-show="search !== '' && document.querySelectorAll('[x-show]:not([style*=\'display: none\'])').length <= 1"
         style="display: none;"
         class="text-center py-16 bg-white rounded-3xl border border-slate-100 shadow-sm">
        <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
        <p class="text-sm font-bold text-slate-500">No services match "<span x-text="search"></span>"</p>
        <p class="text-xs text-slate-400 mt-1">Try a shorter keyword or browse by department above</p>
    </div>

    {{-- Help Banner --}}
    <div class="bg-gradient-to-r from-deep-forest to-sea-green rounded-3xl shadow-lg p-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-extrabold text-white mb-1 leading-tight">Need help choosing a service?</h3>
                <p class="text-white/75 text-sm">Our support team can guide you to the right service for your needs.</p>
            </div>
            <button class="flex-shrink-0 bg-white hover:bg-golden-glow text-deep-forest px-6 py-3 rounded-xl font-bold text-sm shadow-lg transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/></svg>
                Contact Support
            </button>
        </div>
    </div>

</div>
@endsection
