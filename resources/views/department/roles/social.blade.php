@extends('layouts.department')

@section('title', match($user->department_role) {
    'MSWDO' => 'Social Welfare & Aid Targeting',
    'MHO'   => 'Health Services Dashboard',
    'DRRMO' => 'Emergency Management & Alerts',
    default => 'Social Services Dashboard',
})
@section('subtitle', $config['department'] ?? 'Social Services Office')

@section('content')
<div class="p-8 space-y-8">

    {{-- Welcome Banner --}}
    @php
        $bannerEmoji = match($user->department_role) {
            'MSWDO' => '🛡️', 'MHO' => '🏥', 'DRRMO' => '🚨', default => '🤝'
        };
    @endphp
    <div class="bg-gradient-to-r from-deep-forest to-sea-green text-white rounded-2xl p-7 shadow-xl flex items-center justify-between">
        <div>
            <p class="text-golden-glow text-xs font-bold uppercase tracking-widest mb-1">{{ $config['department'] ?? '' }}</p>
            <h2 class="text-3xl font-extrabold">{{ $user->department_label }}</h2>
            <p class="text-gray-200 mt-1 text-sm max-w-xl">{{ $config['description'] }}</p>
        </div>
        <div class="text-5xl opacity-20 hidden lg:block">{{ $bannerEmoji }}</div>
    </div>

    {{-- ============================================================ --}}
    {{-- MSWDO: VULNERABLE SECTOR TARGETING --}}
    {{-- ============================================================ --}}
    @if($user->department_role === 'MSWDO')

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        @php
            $sectorColors = [
                'Senior Citizen'     => ['bg-purple-50','text-purple-700','👴'],
                'PWD'                => ['bg-blue-50','text-blue-700','♿'],
                'Solo Parent'        => ['bg-pink-50','text-pink-700','👩‍👧'],
                'Indigenous People'  => ['bg-orange-50','text-orange-700','🏕️'],
            ];
        @endphp
        @foreach($vulnerableSummary as $vs)
        @php $col = $sectorColors[$vs->vulnerable_sector] ?? ['bg-gray-50','text-gray-700','👤']; @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl {{ $col[0] }} flex items-center justify-center text-xl flex-shrink-0">{{ $col[2] }}</div>
            <div>
                <p class="text-xs text-gray-400">{{ $vs->vulnerable_sector }}</p>
                <p class="text-2xl font-black {{ $col[1] }}">{{ number_format($vs->count) }}</p>
            </div>
        </div>
        @endforeach
        @if($vulnerableSummary->isEmpty())
        <div class="col-span-4 text-center py-6 text-gray-400 bg-white rounded-2xl border border-gray-100">No vulnerable sector data yet.</div>
        @endif
    </div>

    {{-- Vulnerable Sector Drill-Down Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">📍 Vulnerable Sectors by Barangay</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-500 border-b border-gray-100">
                        <th class="pb-3 font-semibold">Barangay</th>
                        <th class="pb-3 font-semibold">Sector</th>
                        <th class="pb-3 font-semibold text-right">Count</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($vulnerableSectors as $vs)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2.5 text-gray-700">{{ $vs->barangay ?? 'Unknown' }}</td>
                        <td class="py-2.5">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $vs->vulnerable_sector === 'Senior Citizen' ? 'bg-purple-100 text-purple-700' :
                                   ($vs->vulnerable_sector === 'PWD' ? 'bg-blue-100 text-blue-700' :
                                   ($vs->vulnerable_sector === 'Solo Parent' ? 'bg-pink-100 text-pink-700' : 'bg-orange-100 text-orange-700')) }}">
                                {{ $vs->vulnerable_sector }}
                            </span>
                        </td>
                        <td class="py-2.5 font-bold text-gray-800 text-right">{{ number_format($vs->count) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="py-6 text-center text-gray-400">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MHO: HEALTH SERVICES --}}
    {{-- ============================================================ --}}
    @elseif($user->department_role === 'MHO')

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-xl">🚽</div>
            <div>
                <p class="text-xs text-gray-400">With Sanitary Toilet</p>
                <p class="text-2xl font-black text-green-700">{{ number_format($sanitaryData['with_toilet']) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center text-xl">⚠️</div>
            <div>
                <p class="text-xs text-gray-400">Without Sanitary Toilet</p>
                <p class="text-2xl font-black text-red-700">{{ number_format($sanitaryData['without_toilet']) }}</p>
            </div>
        </div>
        @foreach($waterSources->take(2) as $ws)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-xl">💧</div>
            <div>
                <p class="text-xs text-gray-400 capitalize">{{ $ws->water_source }}</p>
                <p class="text-2xl font-black text-blue-700">{{ number_format($ws->count) }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Sanitation Risk --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">🔬 Sanitation Coverage</h3>
            @php
                $totalSanitary = $sanitaryData['with_toilet'] + $sanitaryData['without_toilet'];
                $sanitaryRate = $totalSanitary > 0 ? round(($sanitaryData['with_toilet'] / $totalSanitary) * 100, 1) : 0;
            @endphp
            <div class="text-center mb-4">
                <p class="text-5xl font-black {{ $sanitaryRate >= 80 ? 'text-green-600' : ($sanitaryRate >= 50 ? 'text-yellow-600' : 'text-red-600') }}">{{ $sanitaryRate }}%</p>
                <p class="text-sm text-gray-400 mt-1">Households with sanitary toilets</p>
            </div>
            <div class="w-full bg-red-100 rounded-full h-5 overflow-hidden">
                <div class="h-5 rounded-full bg-green-500 transition-all" style="width: {{ $sanitaryRate }}%"></div>
            </div>
            @if($sanitaryRate < 80)
            <div class="mt-3 flex items-center gap-2 p-3 bg-red-50 rounded-lg border border-red-100">
                <span class="text-red-500 text-lg">⚠️</span>
                <p class="text-xs text-red-700">Sanitation coverage below recommended 80%. Priority follow-up required.</p>
            </div>
            @endif
        </div>

        {{-- Water Source Breakdown --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">💧 Water Source Breakdown</h3>
            @php $totalWater = $waterSources->sum('count') ?: 1; @endphp
            <div class="space-y-3">
                @forelse($waterSources as $ws)
                @php $pct = round(($ws->count / $totalWater) * 100, 1); @endphp
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600 capitalize">{{ $ws->water_source ?? 'Unknown' }}</span>
                        <span class="font-semibold text-gray-800">{{ $pct }}%</span>
                    </div>
                    <div class="w-full bg-blue-100 rounded-full h-3">
                        <div class="h-3 rounded-full bg-blue-500" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @empty
                    <p class="text-gray-400 text-sm text-center py-4">No water source data.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- DRRMO: EMERGENCY ALERTS --}}
    {{-- ============================================================ --}}
    @elseif($user->department_role === 'DRRMO')

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Emergency Alert Broadcast Form --}}
        <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">📡 Broadcast Emergency Alert</h3>
            <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-xl text-xs text-yellow-700 mb-4 flex items-center gap-2">
                <span>⚠️</span> Emergency alert broadcasting routes are being set up. Contact Super Admin to enable this feature.
            </div>
            <form method="POST" action="#" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Alert Title *</label>
                    <input type="text" name="title" placeholder="e.g. Flood Warning: Buguey River Overflow"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-400 focus:border-red-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Message *</label>
                    <textarea name="content" rows="4" placeholder="Provide evacuation instructions, affected areas, and contact numbers..."
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-400 focus:border-red-400 resize-none"></textarea>
                </div>
                <input type="hidden" name="type" value="alert">
                <input type="hidden" name="is_published" value="1">
                <input type="hidden" name="posted_at" value="{{ now() }}">
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-burnt-tangerine text-white font-bold rounded-xl hover:bg-red-700 transition-colors">
                    🚨 Broadcast Alert Now
                </button>
            </form>
        </div>

        {{-- Flood-Prone Households --}}
        <div class="bg-white rounded-2xl shadow-sm border border-orange-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">🌊 At-Risk Households</h3>
            <div class="max-h-72 overflow-y-auto space-y-2">
                @forelse($floodProneHouseholds as $h)
                <div class="flex items-center gap-3 p-2.5 bg-orange-50 rounded-lg border border-orange-100">
                    <span class="text-base">🏠</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800">{{ $h->first_name }} {{ $h->last_name }}</p>
                        <p class="text-xs text-gray-500">Purok {{ $h->purok }}, {{ $h->barangay }}</p>
                    </div>
                    @if($h->household_number)
                    <span class="text-xs bg-orange-200 text-orange-800 px-2 py-0.5 rounded-full font-mono">HN-{{ $h->household_number }}</span>
                    @endif
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <p class="text-2xl mb-1">✅</p>
                    <p class="text-sm">No flood-prone household records.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Announcements --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4">📋 Recently Broadcast Alerts</h3>
        @forelse($announcements as $ann)
        <div class="flex items-start gap-3 p-3 mb-2 bg-red-50 rounded-xl border border-red-100">
            <span class="text-lg mt-0.5">🚨</span>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-800">{{ $ann->title }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $ann->posted_at?->diffForHumans() ?? 'Unknown date' }}</p>
            </div>
        </div>
        @empty
        <p class="text-gray-400 text-sm text-center py-4">No alerts broadcasted yet.</p>
        @endforelse
    </div>

    @endif

</div>
@endsection
