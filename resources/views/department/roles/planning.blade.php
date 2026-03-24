@extends('layouts.department')

@section('title', match($user->department_role) {
    'MPDC' => 'Planning & Development Dashboard',
    'ENGR' => 'Engineering & Infrastructure Dashboard',
    'ASSOR' => 'Property Assessment Dashboard',
    default => 'Planning Dashboard',
})
@section('subtitle', $config['department'] ?? 'Planning Office')

@section('content')
<div class="p-8 space-y-8">

    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-deep-forest to-sea-green text-white rounded-2xl p-7 shadow-xl flex items-center justify-between">
        <div>
            <p class="text-golden-glow text-xs font-bold uppercase tracking-widest mb-1">{{ $config['department'] ?? '' }}</p>
            <h2 class="text-3xl font-extrabold">{{ $user->department_label }}</h2>
            <p class="text-gray-200 mt-1 text-sm max-w-lg">{{ $config['description'] }}</p>
        </div>
        <div class="text-5xl opacity-20 hidden lg:block">🏗️</div>
    </div>

    {{-- Summary KPIs --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs text-gray-400">Total Residents</p>
            <p class="text-3xl font-black text-deep-forest mt-1">{{ number_format($totalResidents) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs text-gray-400">Total Households</p>
            <p class="text-3xl font-black text-sea-green mt-1">{{ number_format($totalHouseholds) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs text-gray-400">Flood-Prone</p>
            <p class="text-3xl font-black text-tiger-orange mt-1">{{ number_format($floodProneCount) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs text-gray-400">Flood-Prone Barangays</p>
            <p class="text-3xl font-black text-burnt-tangerine mt-1">{{ $floodProneByBarangay->count() }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Flood-Prone Households by Barangay --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">🌊 Flood-Prone Residents by Barangay</h3>
            @if($floodProneByBarangay->isEmpty())
                <p class="text-gray-400 text-sm text-center py-6">No flood-prone records found.</p>
            @else
            @php $maxFlood = $floodProneByBarangay->max('count') ?: 1; @endphp
            <div class="space-y-3">
                @foreach($floodProneByBarangay as $item)
                @php $pct = round(($item->count / $maxFlood) * 100, 1); @endphp
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-600 w-32 truncate flex-shrink-0">{{ $item->barangay ?? 'Unknown' }}</span>
                    <div class="flex-1 bg-gray-100 rounded-full h-5 overflow-hidden">
                        <div class="h-5 rounded-full bg-gradient-to-r from-tiger-orange to-burnt-tangerine flex items-center pl-2"
                             style="width: {{ max($pct, 6) }}%">
                            <span class="text-white text-xs font-semibold">{{ $item->count }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- House Materials Breakdown --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">🏠 House Materials Breakdown</h3>
            @if($houseMaterials->isEmpty())
                <p class="text-gray-400 text-sm text-center py-6">No house material data available.</p>
            @else
            <div class="space-y-3">
                @php
                    $totalMat = $houseMaterials->sum('count') ?: 1;
                    $colors = ['bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-red-500'];
                @endphp
                @foreach($houseMaterials as $i => $mat)
                @php $pct = round(($mat->count / $totalMat) * 100, 1); @endphp
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600 capitalize">{{ $mat->house_materials ?? 'Unknown' }}</span>
                        <span class="font-semibold text-gray-800">{{ number_format($mat->count) }} ({{ $pct }}%)</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                        <div class="{{ $colors[$i % count($colors)] }} h-3 rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- Water Source Distribution --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">💧 Water Source Distribution</h3>
        @if($waterSources->isEmpty())
            <p class="text-gray-400 text-sm text-center py-4">No water source data available.</p>
        @else
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php $totalWater = $waterSources->sum('count') ?: 1; @endphp
            @foreach($waterSources as $ws)
            @php $pct = round(($ws->count / $totalWater) * 100, 1); @endphp
            <div class="text-center p-4 bg-blue-50 rounded-xl border border-blue-100">
                <p class="text-2xl font-black text-blue-700">{{ $pct }}%</p>
                <p class="text-xs text-blue-600 mt-1 font-medium capitalize">{{ $ws->water_source ?? 'Unknown' }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ number_format($ws->count) }} residents</p>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Role-Specific Section --}}
    @if($user->department_role === 'MPDC')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2">📍 Locational Clearance Queue</h3>
        <div class="flex items-center gap-3 p-4 bg-golden-glow/10 rounded-xl border border-golden-glow/30">
            <span class="text-2xl">🚧</span>
            <div>
                <p class="text-sm font-semibold text-gray-700">Locational Clearance Module</p>
                <p class="text-xs text-gray-500">This module is being developed. Integration with CLUP records coming soon.</p>
            </div>
        </div>
    </div>
    @elseif($user->department_role === 'ASSOR')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2">📜 Property Assessment Cross-Reference</h3>
        <div class="flex items-center gap-3 p-4 bg-purple-50 rounded-xl border border-purple-100">
            <span class="text-2xl">🏡</span>
            <div>
                <p class="text-sm font-semibold text-gray-700">Property Registry Module</p>
                <p class="text-xs text-gray-500">Use the house materials data above to cross-reference with land title records. Full module integration coming soon.</p>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
