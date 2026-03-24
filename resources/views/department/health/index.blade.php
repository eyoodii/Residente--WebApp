@extends('layouts.department')
@section('title', 'Health Services')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'🏥','title'=>'Health Services Dashboard','subtitle'=>'Medical certificates, sanitary permits, immunisation schedules, and public health monitoring.'])

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('department.health.index') }}" class="px-4 py-2 bg-deep-forest text-white rounded-xl text-sm font-semibold">Overview</a>
        <a href="{{ route('department.health.sanitation') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50">Sanitation</a>
        <a href="{{ route('department.health.water-sources') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50">Water Sources</a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-400">Pending Health Requests</p><p class="text-3xl font-black text-yellow-600">{{ number_format($stats['pending']) }}</p></div>
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-400">Completed</p><p class="text-3xl font-black text-green-600">{{ number_format($stats['completed']) }}</p></div>
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-400">With Toilet</p><p class="text-3xl font-black text-blue-600">{{ number_format($sanitationSummary['with_toilet']) }}</p></div>
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-400">Without Toilet</p><p class="text-3xl font-black text-red-600">{{ number_format($sanitationSummary['without_toilet']) }}</p></div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4">Water Sources Distribution</h3>
        <div class="space-y-2">
            @foreach($waterSources as $ws)
            <div class="flex justify-between text-sm py-2 border-b border-gray-50">
                <span>{{ $ws->water_source }}</span>
                <span class="font-bold text-deep-forest">{{ number_format($ws->count) }}</span>
            </div>
            @endforeach
        </div>
    </div>

    @include('department.components._request-table', ['requests' => $healthRequests, 'routeBase' => 'department.service-requests'])
</div>
@endsection
