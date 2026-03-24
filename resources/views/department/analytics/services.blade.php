@extends('layouts.department')
@section('title', 'Service Utilisation')
@section('content')
<div class="space-y-6">
    @include('department.components._module-header', ['icon'=>'⚙️','title'=>'Service Utilisation','subtitle'=>'E-service request volumes, status breakdown, and monthly trends.'])
    @include('department.analytics._analytics-nav')
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        @foreach($servicesByStatus as $status => $row)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs text-gray-400 capitalize">{{ str_replace('-', ' ', $status) }}</p>
            <p class="text-3xl font-black text-deep-forest">{{ number_format($row->count) }}</p>
        </div>
        @endforeach
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-x-auto">
        <h3 class="font-bold text-gray-800 mb-4">📅 Monthly Request Volume ({{ now()->year }})</h3>
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 border-b"><th class="pb-3">Month</th><th class="pb-3">Requests</th></tr></thead>
            <tbody>
                @foreach($monthlyRequests as $mr)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="py-2">{{ \Carbon\Carbon::create($mr->year, $mr->month)->format('F Y') }}</td>
                    <td class="py-2 font-bold text-deep-forest">{{ number_format($mr->count) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
