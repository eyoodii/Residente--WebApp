@extends('layouts.department')
@section('title', 'Budget Forecast')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'📈','title'=>'Budget Forecast','subtitle'=>'Service utilisation trends and population data for budget allocation planning.'])
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">📅 Monthly Service Requests ({{ now()->year }})</h3>
            <div class="space-y-2">
                @foreach($monthlyTrend as $mt)
                <div class="flex justify-between text-sm py-2 border-b border-gray-50">
                    <span>{{ \Carbon\Carbon::create($mt->year, $mt->month)->format('F') }}</span>
                    <span class="font-bold text-deep-forest">{{ number_format($mt->count) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">🗺️ Population by Barangay</h3>
            <div class="space-y-2 max-h-80 overflow-y-auto">
                @foreach($barangayPopulation as $bp)
                <div class="flex justify-between text-sm py-2 border-b border-gray-50">
                    <span>{{ $bp->barangay }}</span>
                    <span class="font-bold">{{ number_format($bp->count) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
