@extends('layouts.department')

@section('title', match($user->department_role) {
    'TRESR' => 'Revenue & Collections',
    'ACCT'  => 'Internal Audit Dashboard',
    'BUDGT' => 'Budget Forecasting Dashboard',
    default => 'Financial Dashboard',
})
@section('subtitle', $config['department'] ?? 'Financial Office')

@section('content')
<div class="p-8 space-y-8">

    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-deep-forest to-sea-green text-white rounded-2xl p-7 shadow-xl flex items-center justify-between">
        <div>
            <p class="text-golden-glow text-xs font-bold uppercase tracking-widest mb-1">{{ $config['department'] ?? '' }}</p>
            <h2 class="text-3xl font-extrabold">{{ $user->department_label }}</h2>
            <p class="text-gray-200 mt-1 text-sm max-w-lg">{{ $config['description'] }}</p>
        </div>
        <div class="text-5xl opacity-20 hidden lg:block">💰</div>
    </div>

    {{-- Service Stats KPIs --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        @php
            $colors = [
                ['bg-blue-50','text-blue-700'],
                ['bg-yellow-50','text-yellow-700'],
                ['bg-green-50','text-green-700'],
                ['bg-red-50','text-red-700'],
            ];
            $kpis = [
                ['icon'=>'📋','label'=>'Total Requests','value'=>$serviceStats['total']],
                ['icon'=>'⏳','label'=>'Pending','value'=>$serviceStats['pending']],
                ['icon'=>'✅','label'=>'Completed','value'=>$serviceStats['completed']],
                ['icon'=>'❌','label'=>'Rejected','value'=>$serviceStats['rejected']],
            ];
        @endphp
        @foreach($kpis as $i => $k)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl {{ $colors[$i][0] }} flex items-center justify-center text-xl flex-shrink-0">{{ $k['icon'] }}</div>
            <div>
                <p class="text-xs text-gray-400">{{ $k['label'] }}</p>
                <p class="text-2xl font-black {{ $colors[$i][1] }}">{{ number_format($k['value']) }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Completion Rate --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">📈 Service Completion Rate</h3>
            @php
                $rate = $serviceStats['total'] > 0 ? round(($serviceStats['completed'] / $serviceStats['total']) * 100, 1) : 0;
                $pendingRate = $serviceStats['total'] > 0 ? round(($serviceStats['pending'] / $serviceStats['total']) * 100, 1) : 0;
            @endphp
            <div class="text-center mb-6">
                <p class="text-6xl font-black text-sea-green">{{ $rate }}%</p>
                <p class="text-sm text-gray-400 mt-1">Service Completion Rate</p>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-5 overflow-hidden flex">
                <div class="h-5 bg-green-500 transition-all" style="width: {{ $rate }}%"></div>
                <div class="h-5 bg-yellow-400 transition-all" style="width: {{ $pendingRate }}%"></div>
            </div>
            <div class="flex justify-between text-xs text-gray-400 mt-2">
                <span class="flex items-center gap-1"><span class="w-2 h-2 bg-green-500 rounded-full inline-block"></span> Completed</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 bg-yellow-400 rounded-full inline-block"></span> Pending</span>
            </div>
        </div>

        {{-- Population by Barangay (Budget Forecasting) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">🏘️ Population per Barangay <span class="text-xs text-gray-400 font-normal">(Budget Basis)</span></h3>
            @php $maxPop = $barangayPopulation->max('count') ?: 1; @endphp
            <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                @forelse($barangayPopulation as $b)
                @php $pct = round(($b->count / $maxPop) * 100, 1); @endphp
                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-600 w-28 truncate flex-shrink-0">{{ $b->barangay ?? 'N/A' }}</span>
                    <div class="flex-1 bg-gray-100 rounded-full h-4 overflow-hidden">
                        <div class="h-4 rounded-full bg-deep-forest/70" style="width: {{ max($pct,3) }}%"></div>
                    </div>
                    <span class="text-xs font-semibold text-gray-700 w-8 text-right">{{ $b->count }}</span>
                </div>
                @empty
                    <p class="text-gray-400 text-sm text-center py-4">No data available.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Audit Log (ACCT & TRESR) --}}
    @if(in_array($user->department_role, ['ACCT', 'TRESR']))
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">📝 System Audit Log</h3>
            @if($user->department_role === 'ACCT')
            <span class="px-2 py-1 bg-yellow-50 text-yellow-700 text-xs rounded-full border border-yellow-200 font-semibold">👁️ Read-Only</span>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-500 border-b border-gray-100">
                        <th class="pb-3 font-semibold">Time</th>
                        <th class="pb-3 font-semibold">User</th>
                        <th class="pb-3 font-semibold">Role</th>
                        <th class="pb-3 font-semibold">Action</th>
                        <th class="pb-3 font-semibold">Description</th>
                        <th class="pb-3 font-semibold">Severity</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($auditLogs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2.5 text-gray-400 whitespace-nowrap text-xs">{{ $log->created_at->format('M d, H:i') }}</td>
                        <td class="py-2.5 text-gray-700 max-w-[140px] truncate">{{ $log->user_email }}</td>
                        <td class="py-2.5"><span class="px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded text-xs">{{ $log->user_role }}</span></td>
                        <td class="py-2.5 text-gray-700 text-xs font-mono">{{ $log->action }}</td>
                        <td class="py-2.5 text-gray-600 max-w-[200px] truncate text-xs">{{ $log->description }}</td>
                        <td class="py-2.5">
                            <span class="px-2 py-0.5 rounded-full text-xs
                            {{ $log->severity === 'critical' ? 'bg-red-100 text-red-700' : ($log->severity === 'warning' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700') }}">
                                {{ ucfirst($log->severity ?? 'info') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-6 text-center text-gray-400">No audit records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
