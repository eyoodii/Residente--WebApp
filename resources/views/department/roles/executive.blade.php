@extends('layouts.department')

@section('title', $user->department_role === 'MAYOR' ? 'Executive Dashboard' : 'Legislative Analytics')
@section('subtitle', $config['department'] ?? 'Office of the Mayor')

@section('content')
<div class="p-8 space-y-8">

    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-deep-forest to-sea-green text-white rounded-2xl p-7 flex items-center justify-between shadow-xl">
        <div>
            <p class="text-golden-glow text-xs font-bold uppercase tracking-widest mb-1">{{ $config['department'] ?? '' }}</p>
            <h2 class="text-3xl font-extrabold tracking-tight">{{ $user->department_role === 'MAYOR' ? 'Executive Overview' : 'Legislative Analytics' }}</h2>
            <p class="text-gray-200 mt-1 text-sm max-w-lg">{{ $config['description'] }}</p>
        </div>
        <div class="text-right hidden lg:block">
            <p class="text-white/60 text-xs">Municipality of</p>
            <p class="text-2xl font-bold text-golden-glow">Buguey</p>
            <p class="text-white/60 text-xs">Cagayan Valley</p>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        @php
            $kpis = [
                ['icon'=>'👤','label'=>'Total Citizens','value'=>number_format($totalResidents),'color'=>'blue'],
                ['icon'=>'✅','label'=>'Verified Residents','value'=>number_format($verifiedCount),'color'=>'green'],
                ['icon'=>'🏠','label'=>'Total Households','value'=>number_format($totalHouseholds),'color'=>'purple'],
                ['icon'=>'📋','label'=>'Service Requests','value'=>number_format($serviceStats['total']),'color'=>'orange'],
            ];
        @endphp

        @foreach($kpis as $kpi)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 rounded-2xl bg-{{ $kpi['color'] }}-50 flex items-center justify-center text-2xl flex-shrink-0">
                {{ $kpi['icon'] }}
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium">{{ $kpi['label'] }}</p>
                <p class="text-2xl font-extrabold text-gray-900 mt-0.5">{{ $kpi['value'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Municipality Health + Service Stats --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Verification Rate Bar --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">📊 Resident Verification Rate</h3>
            @php $rate = $totalResidents > 0 ? round(($verifiedCount / $totalResidents) * 100, 1) : 0; @endphp
            <div class="text-center mb-4">
                <p class="text-5xl font-black text-deep-forest">{{ $rate }}%</p>
                <p class="text-sm text-gray-400 mt-1">{{ number_format($verifiedCount) }} of {{ number_format($totalResidents) }} residents verified</p>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-4 overflow-hidden">
                <div class="h-4 rounded-full bg-gradient-to-r from-sea-green to-golden-glow transition-all duration-700"
                     style="width: {{ $rate }}%"></div>
            </div>
            <div class="mt-3 flex justify-between text-xs text-gray-400">
                <span>0%</span><span>Target: 100%</span>
            </div>
        </div>

        {{-- Service Status Breakdown --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">⚙️ Service Requests Status</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-yellow-400 inline-block"></span>
                        <span class="text-sm text-gray-600">Pending</span>
                    </div>
                    <span class="font-bold text-gray-800">{{ number_format($serviceStats['pending']) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span>
                        <span class="text-sm text-gray-600">Completed</span>
                    </div>
                    <span class="font-bold text-gray-800">{{ number_format($serviceStats['completed']) }}</span>
                </div>
                <div class="border-t border-gray-100 pt-3 flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-700">Total</span>
                    <span class="font-extrabold text-deep-forest text-lg">{{ number_format($serviceStats['total']) }}</span>
                </div>
            </div>
        </div>

        {{-- Top Barangays --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">📍 Top Barangays by Population</h3>
            <div class="space-y-2">
                @foreach($barangayStats->take(5) as $b)
                @php $bPct = $totalResidents > 0 ? round(($b->count / $totalResidents) * 100, 1) : 0; @endphp
                <div>
                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                        <span class="font-medium truncate max-w-[150px]">{{ $b->barangay ?? 'Unknown' }}</span>
                        <span class="font-bold text-deep-forest">{{ number_format($b->count) }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full bg-sea-green" style="width: {{ $bPct }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Barangay Population Full Chart --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-5 flex items-center gap-2">🏘️ Population Distribution by Barangay</h3>
        <div class="space-y-3">
            @php $maxCount = $barangayStats->max('count') ?: 1; @endphp
            @foreach($barangayStats as $b)
            @php $pct = round(($b->count / $maxCount) * 100, 1); @endphp
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600 w-36 truncate flex-shrink-0">{{ $b->barangay ?? 'Unknown' }}</span>
                <div class="flex-1 bg-gray-100 rounded-full h-5 overflow-hidden">
                    <div class="h-5 rounded-full bg-gradient-to-r from-deep-forest to-sea-green flex items-center pl-2 transition-all"
                         style="width: {{ max($pct, 5) }}%">
                        <span class="text-white text-xs font-semibold">{{ number_format($b->count) }}</span>
                    </div>
                </div>
            </div>
            @endforeach
            @if($barangayStats->isEmpty())
            <p class="text-gray-400 text-sm text-center py-4">No barangay data available yet.</p>
            @endif
        </div>
    </div>

    {{-- Recent Activity Log (Read-only) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">🕐 Recent System Activity</h3>
            <span class="px-2 py-1 bg-yellow-50 text-yellow-700 text-xs rounded-full border border-yellow-200">👁️ View Only</span>
        </div>
        <div class="space-y-3">
            @forelse($recentLogs as $log)
            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                <div class="w-2 h-2 rounded-full mt-2 flex-shrink-0
                    {{ $log->severity === 'critical' ? 'bg-red-500' : ($log->severity === 'warning' ? 'bg-yellow-500' : 'bg-blue-500') }}"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-800">{{ $log->description }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $log->created_at->diffForHumans() }} · {{ $log->user_email }}</p>
                </div>
                <span class="px-2 py-0.5 text-xs rounded-full flex-shrink-0
                    {{ $log->severity === 'critical' ? 'bg-red-100 text-red-700' : ($log->severity === 'warning' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700') }}">
                    {{ ucfirst($log->severity ?? 'info') }}
                </span>
            </div>
            @empty
            <p class="text-gray-400 text-sm text-center py-4">No recent activity.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
