@extends('layouts.department')

@section('title', match($user->department_role) {
    'SBFIN'  => 'SB Finance, Budget & Comprehensive Affairs',
    'SBHLT'  => 'SB Health, Sanitation & Ecology',
    'SBWMN'  => 'SB Women, Family, Trade Commerce & Industry',
    'SBRLS'  => 'SB Rules, Privileges & Legislative Oversight',
    'SBPIC'  => 'SB Public Information & Communication',
    'SBTSP'  => 'SB Transportation',
    'SBPWK'  => 'SB Public Works, Infrastructure & Housing',
    'SBAGR'  => 'SB Agriculture & Farmers Association',
    'SBBGA'  => 'SB Barangay Affairs',
    'SKPRS'  => 'SK Federation President Portal',
    default  => 'Legislative Dashboard',
})

@section('subtitle', $config['department'] ?? 'Sangguniang Bayan')

@php
    $roleTheme = match($user->department_role) {
        'SBFIN'  => ['color' => 'blue',    'icon' => '💰', 'gradient' => 'from-blue-700 to-cyan-600'],
        'SBHLT'  => ['color' => 'teal',    'icon' => '🏥', 'gradient' => 'from-teal-700 to-emerald-600'],
        'SBWMN'  => ['color' => 'pink',    'icon' => '👩‍⚖️', 'gradient' => 'from-pink-600 to-purple-600'],
        'SBRLS'  => ['color' => 'slate',   'icon' => '⚖️',  'gradient' => 'from-slate-700 to-gray-600'],
        'SBPIC'  => ['color' => 'indigo',  'icon' => '📡',  'gradient' => 'from-indigo-700 to-blue-600'],
        'SBTSP'  => ['color' => 'yellow',  'icon' => '🛺',  'gradient' => 'from-yellow-500 to-orange-500'],
        'SBPWK'  => ['color' => 'orange',  'icon' => '🏗️', 'gradient' => 'from-orange-600 to-red-500'],
        'SBAGR'  => ['color' => 'emerald', 'icon' => '🌾',  'gradient' => 'from-emerald-700 to-green-600'],
        'SBBGA'  => ['color' => 'purple',  'icon' => '🗺️', 'gradient' => 'from-purple-700 to-violet-600'],
        'SKPRS'  => ['color' => 'orange',  'icon' => '🎓',  'gradient' => 'from-orange-500 to-amber-500'],
        default  => ['color' => 'blue',    'icon' => '🏛️', 'gradient' => 'from-blue-700 to-indigo-600'],
    };
    $themeColor = $roleTheme['color'];
    $themeIcon  = $roleTheme['icon'];
    $gradient   = $roleTheme['gradient'];
@endphp

@section('content')
<div class="p-6 space-y-8">

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- WELCOME BANNER                                              --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="bg-gradient-to-br {{ $gradient }} text-white rounded-3xl p-8 shadow-2xl flex items-center justify-between relative overflow-hidden">
        {{-- Decorative orbs --}}
        <div class="absolute -right-12 -top-12 w-56 h-56 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-6 -bottom-10 w-40 h-40 bg-black/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-3">
                <span class="w-2 h-2 rounded-full bg-white/60 animate-pulse"></span>
                <p class="text-white/70 text-xs font-bold uppercase tracking-widest">{{ $config['department'] ?? 'Sangguniang Bayan' }}</p>
            </div>
            <h2 class="text-3xl font-extrabold tracking-tight leading-tight">
                Welcome, {{ $user->first_name }}!
            </h2>
            <p class="text-white/75 mt-2 text-sm font-medium max-w-xl leading-relaxed">
                {{ $config['description'] ?? 'Access legislative analytics and demographic data to support your committee resolutions.' }}
            </p>
            <div class="mt-4 flex items-center gap-2">
                <span class="px-3 py-1 bg-white/15 border border-white/20 text-white text-xs font-bold rounded-full">
                    📊 Analytical Mode
                </span>
                <span class="px-3 py-1 bg-white/15 border border-white/20 text-white text-xs font-bold rounded-full">
                    👁️ Read-Only Access
                </span>
            </div>
        </div>

        <div class="hidden lg:flex flex-col items-center gap-2 relative z-10 flex-shrink-0">
            <div class="w-20 h-20 bg-white/15 rounded-2xl flex items-center justify-center text-4xl border border-white/25 shadow-inner backdrop-blur-sm">
                {{ $themeIcon }}
            </div>
            <span class="text-white/60 text-xs font-bold uppercase tracking-wider">{{ $user->department_role }}</span>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- KPI CARDS                                                   --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    @if(!empty($kpi))
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($kpi as $card)
        @php
            $colorMap = [
                'blue'    => ['bar' => 'bg-blue-500',    'bg' => 'bg-blue-50',    'text' => 'text-blue-700'],
                'teal'    => ['bar' => 'bg-teal-500',    'bg' => 'bg-teal-50',    'text' => 'text-teal-700'],
                'emerald' => ['bar' => 'bg-emerald-500', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700'],
                'green'   => ['bar' => 'bg-green-500',   'bg' => 'bg-green-50',   'text' => 'text-green-700'],
                'amber'   => ['bar' => 'bg-amber-500',   'bg' => 'bg-amber-50',   'text' => 'text-amber-700'],
                'yellow'  => ['bar' => 'bg-yellow-500',  'bg' => 'bg-yellow-50',  'text' => 'text-yellow-700'],
                'orange'  => ['bar' => 'bg-orange-500',  'bg' => 'bg-orange-50',  'text' => 'text-orange-700'],
                'red'     => ['bar' => 'bg-red-500',     'bg' => 'bg-red-50',     'text' => 'text-red-700'],
                'pink'    => ['bar' => 'bg-pink-500',    'bg' => 'bg-pink-50',    'text' => 'text-pink-700'],
                'purple'  => ['bar' => 'bg-purple-500',  'bg' => 'bg-purple-50',  'text' => 'text-purple-700'],
                'indigo'  => ['bar' => 'bg-indigo-500',  'bg' => 'bg-indigo-50',  'text' => 'text-indigo-700'],
                'slate'   => ['bar' => 'bg-slate-500',   'bg' => 'bg-slate-100',  'text' => 'text-slate-700'],
            ];
            $c = $colorMap[$card['color']] ?? $colorMap['blue'];
        @endphp
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-7 flex items-center gap-5 relative overflow-hidden group hover:shadow-2xl hover:-translate-y-0.5 transition duration-300">
            <div class="absolute left-0 top-0 w-1.5 h-full {{ $c['bar'] }} group-hover:w-2 transition-all rounded-l-3xl"></div>
            <div class="w-14 h-14 {{ $c['bg'] }} rounded-2xl flex items-center justify-center text-2xl shadow-inner flex-shrink-0">
                {{ $card['icon'] }}
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ $card['label'] }}</p>
                <p class="text-3xl font-extrabold {{ $c['text'] }} mt-0.5 tabular-nums">{{ $card['value'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- LEGISLATIVE INSIGHTS & ANALYTICS PANEL                     --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    @if(!empty($insights))
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">

        {{-- Panel Header --}}
        <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 backdrop-blur-sm">
            <div>
                <h2 class="text-lg font-extrabold text-slate-900">Legislative Insights & Analytics</h2>
                <p class="text-sm text-slate-500 font-medium mt-0.5">Extract precise demographic data to support your resolutions and ordinances.</p>
            </div>
            <a href="{{ route('department.analytics.index') }}"
               class="flex items-center gap-2 text-sm font-bold px-5 py-2.5 rounded-xl border transition
                      @if($themeColor === 'yellow') text-yellow-700 bg-yellow-50 border-yellow-200 hover:bg-yellow-100
                      @elseif($themeColor === 'pink') text-pink-700 bg-pink-50 border-pink-200 hover:bg-pink-100
                      @elseif($themeColor === 'teal') text-teal-700 bg-teal-50 border-teal-200 hover:bg-teal-100
                      @elseif($themeColor === 'emerald') text-emerald-700 bg-emerald-50 border-emerald-200 hover:bg-emerald-100
                      @elseif($themeColor === 'indigo') text-indigo-700 bg-indigo-50 border-indigo-200 hover:bg-indigo-100
                      @elseif($themeColor === 'orange') text-orange-700 bg-orange-50 border-orange-200 hover:bg-orange-100
                      @elseif($themeColor === 'purple') text-purple-700 bg-purple-50 border-purple-200 hover:bg-purple-100
                      @elseif($themeColor === 'slate') text-slate-700 bg-slate-100 border-slate-200 hover:bg-slate-200
                      @else text-blue-700 bg-blue-50 border-blue-200 hover:bg-blue-100
                      @endif">
                <span>📊</span> Open Analytics
            </a>
        </div>

        {{-- Insight Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8">
            @foreach($insights as $insight)
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 hover:shadow-xl hover:-translate-y-0.5 transition duration-300 group relative overflow-hidden cursor-pointer">

                {{-- Decorative glow --}}
                <div class="absolute top-0 right-0 w-36 h-36 rounded-full blur-3xl opacity-[0.07] -mr-10 -mt-10 pointer-events-none
                    @if($themeColor === 'yellow') bg-yellow-500
                    @elseif($themeColor === 'pink') bg-pink-500
                    @elseif($themeColor === 'teal') bg-teal-500
                    @elseif($themeColor === 'emerald') bg-emerald-500
                    @elseif($themeColor === 'indigo') bg-indigo-500
                    @elseif($themeColor === 'orange') bg-orange-500
                    @elseif($themeColor === 'purple') bg-purple-500
                    @elseif($themeColor === 'slate') bg-slate-500
                    @else bg-blue-500
                    @endif"></div>

                <div class="flex items-start gap-4 relative z-10">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0 shadow-sm
                        @if($themeColor === 'yellow') bg-yellow-100
                        @elseif($themeColor === 'pink') bg-pink-100
                        @elseif($themeColor === 'teal') bg-teal-100
                        @elseif($themeColor === 'emerald') bg-emerald-100
                        @elseif($themeColor === 'indigo') bg-indigo-100
                        @elseif($themeColor === 'orange') bg-orange-100
                        @elseif($themeColor === 'purple') bg-purple-100
                        @elseif($themeColor === 'slate') bg-slate-200
                        @else bg-blue-100
                        @endif">
                        {{ $insight['icon'] }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-extrabold text-slate-900 text-base leading-tight">{{ $insight['title'] }}</h3>
                        <p class="text-sm text-slate-600 mt-2 font-medium leading-relaxed">{{ $insight['desc'] }}</p>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-slate-200 relative z-10">
                    <button class="flex items-center gap-1.5 text-sm font-bold transition group-hover:gap-2.5
                        @if($themeColor === 'yellow') text-yellow-700 group-hover:text-yellow-800
                        @elseif($themeColor === 'pink') text-pink-700 group-hover:text-pink-800
                        @elseif($themeColor === 'teal') text-teal-700 group-hover:text-teal-800
                        @elseif($themeColor === 'emerald') text-emerald-700 group-hover:text-emerald-800
                        @elseif($themeColor === 'indigo') text-indigo-700 group-hover:text-indigo-800
                        @elseif($themeColor === 'orange') text-orange-700 group-hover:text-orange-800
                        @elseif($themeColor === 'purple') text-purple-700 group-hover:text-purple-800
                        @elseif($themeColor === 'slate') text-slate-700 group-hover:text-slate-900
                        @else text-blue-700 group-hover:text-blue-800
                        @endif">
                        {{ $insight['action'] }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- AUTHORIZED MODULES QUICK ACCESS                            --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    @if(!empty($config['modules']))
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="px-8 py-5 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
            <div class="p-2 bg-slate-100 rounded-xl text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
            </div>
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Authorized Modules</h3>
                <p class="text-xs text-slate-500 font-medium">Quick access to all permitted functions for your committee.</p>
            </div>
        </div>

        <div class="p-6">
            @php
                $moduleLabels = [
                    'analytics'          => ['icon' => '📈', 'label' => 'Analytics & Reports',    'route' => 'department.analytics.index'],
                    'master_collections' => ['icon' => '🗂️', 'label' => 'Master Collections',    'route' => 'department.master-collections.index'],
                    'household_management'=> ['icon' => '🏠', 'label' => 'Household Management', 'route' => 'department.households.index'],
                    'activity_logs'      => ['icon' => '📝', 'label' => 'Activity Logs',          'route' => 'department.activity-logs.index'],
                    'transparency_board' => ['icon' => '📋', 'label' => 'Transparency Board',     'route' => 'department.transparency-board.index'],
                    'announcements'      => ['icon' => '📢', 'label' => 'Transparency Board',     'route' => 'department.transparency-board.index'],
                    'livelihood_programs'=> ['icon' => '🌾', 'label' => 'Livelihood Programs',    'route' => 'department.livelihood.index'],
                ];
            @endphp
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($config['modules'] as $module)
                    @php $m = $moduleLabels[$module] ?? ['icon' => '🔲', 'label' => ucwords(str_replace('_', ' ', $module)), 'route' => null]; @endphp
                    @if($m['route'])
                        <a href="{{ route($m['route']) }}"
                           class="flex items-center gap-3 px-4 py-4 bg-slate-50 hover:bg-gradient-to-br hover:from-slate-700 hover:to-slate-900 hover:text-white rounded-2xl border border-slate-200 hover:border-transparent transition-all duration-200 group shadow-sm hover:shadow-lg hover:-translate-y-0.5">
                            <span class="text-2xl flex-shrink-0">{{ $m['icon'] }}</span>
                            <span class="text-sm font-bold text-slate-700 group-hover:text-white leading-tight">{{ $m['label'] }}</span>
                        </a>
                    @else
                        <div class="flex items-center gap-3 px-4 py-4 bg-slate-50 rounded-2xl border border-slate-200 opacity-50 cursor-default">
                            <span class="text-2xl flex-shrink-0">{{ $m['icon'] }}</span>
                            <div>
                                <span class="text-sm font-bold text-slate-600">{{ $m['label'] }}</span>
                                <p class="text-xs text-slate-400 font-medium">Coming soon</p>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- READ-ONLY NOTICE FOOTER                                     --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="flex items-center gap-3 px-6 py-4 bg-amber-50 border border-amber-200 rounded-2xl">
        <span class="text-xl">🔒</span>
        <p class="text-sm font-medium text-amber-800">
            <span class="font-bold">Read-Only Mode:</span>
            Your role has view-only access to RESIDENTE data. No citizen records can be modified from this portal.
            Data is refreshed in real-time and may be used to support committee hearings and legislative drafting.
        </p>
    </div>

</div>
@endsection
