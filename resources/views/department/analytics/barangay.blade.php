@extends('layouts.department')
@section('title', 'Barangay Breakdown')
@section('content')
<div class="space-y-6">
    @include('department.components._module-header', ['icon'=>'🗺️','title'=>'Barangay Breakdown','subtitle'=>'Resident count and verification rate per barangay.'])
    @include('department.analytics._analytics-nav')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 border-b"><th class="pb-3">Barangay</th><th class="pb-3">Total</th><th class="pb-3">Verified</th><th class="pb-3">Rate</th></tr></thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($barangayStats as $b)
                @php $rate = $b->total > 0 ? round(($b->verified/$b->total)*100,1) : 0; @endphp
                <tr class="hover:bg-gray-50">
                    <td class="py-3 font-medium text-gray-800">{{ $b->barangay }}</td>
                    <td class="py-3 text-gray-600">{{ number_format($b->total) }}</td>
                    <td class="py-3 text-green-700 font-semibold">{{ number_format($b->verified) }}</td>
                    <td class="py-3"><span class="px-2 py-1 rounded-lg text-xs font-bold {{ $rate >= 75 ? 'bg-green-100 text-green-700' : ($rate >= 40 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">{{ $rate }}%</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
