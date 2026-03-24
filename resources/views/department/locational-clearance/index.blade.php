@extends('layouts.department')
@section('title', 'Locational Clearance')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'📍','title'=>'Locational Clearance & Zoning','subtitle'=>'Process digital locational clearances and zoning certifications for CLUP compliance.'])
    <div class="grid grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-400">Pending</p><p class="text-3xl font-black text-yellow-600">{{ $stats['pending'] }}</p></div>
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-400">Approved</p><p class="text-3xl font-black text-green-600">{{ $stats['approved'] }}</p></div>
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-400">Rejected</p><p class="text-3xl font-black text-red-600">{{ $stats['rejected'] }}</p></div>
    </div>
    @include('department.components._request-table', ['requests' => $requests, 'routeBase' => 'department.locational-clearance'])
</div>
@endsection
