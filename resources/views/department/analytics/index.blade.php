@extends('layouts.department')
@section('title', 'Analytics Dashboard')
@section('subtitle', config('department_permissions.' . auth()->user()->department_role . '.department', 'Analytics'))

@section('content')
<div class="space-y-6">

    @include('department.components._module-header', [
        'icon'     => '📊',
        'title'    => 'Analytics Dashboard',
        'subtitle' => 'Real-time demographic and service utilisation data for the Municipality of Buguey.',
    ])

    {{-- Sub-nav --}}
    @include('department.analytics._analytics-nav')

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        @php $kpis = [
            ['icon'=>'👤','label'=>'Total Citizens','value'=>number_format($totalResidents),'color'=>'blue'],
            ['icon'=>'✅','label'=>'Verified','value'=>number_format($verifiedCount),'color'=>'green'],
            ['icon'=>'🏠','label'=>'Households','value'=>number_format($totalHouseholds),'color'=>'purple'],
            ['icon'=>'📋','label'=>'Service Requests','value'=>number_format($serviceStats['total']),'color'=>'orange'],
        ]; @endphp
        @foreach($kpis as $kpi)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-{{ $kpi['color'] }}-50 flex items-center justify-center text-2xl flex-shrink-0">{{ $kpi['icon'] }}</div>
            <div>
                <p class="text-xs text-gray-400 font-medium">{{ $kpi['label'] }}</p>
                <p class="text-2xl font-extrabold text-gray-900">{{ $kpi['value'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Population by Barangay --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">🗺️ Population by Barangay</h3>
            <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
                @foreach($barangayStats as $bs)
                @php $pct = $totalResidents > 0 ? round(($bs->count / $totalResidents) * 100, 1) : 0; @endphp
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-700 font-medium">{{ $bs->barangay }}</span>
                        <span class="text-gray-500">{{ number_format($bs->count) }} ({{ $pct }}%)</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="h-2 rounded-full bg-sea-green" style="width:{{ $pct }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Vulnerable Sectors --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">❤️ Vulnerable Sectors</h3>
            <div class="space-y-2">
                @forelse($vulnerableSectors as $vs)
                <div class="flex justify-between items-center p-3 bg-red-50 rounded-xl">
                    <span class="text-sm font-medium text-red-800">{{ $vs->vulnerable_sector }}</span>
                    <span class="text-sm font-bold text-red-700">{{ number_format($vs->count) }}</span>
                </div>
                @empty
                <p class="text-sm text-gray-400">No vulnerable sector data recorded yet.</p>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
