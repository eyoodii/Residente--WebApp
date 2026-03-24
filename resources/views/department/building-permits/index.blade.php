@extends('layouts.department')
@section('title', 'Building Permits')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'🏗️','title'=>'Building Permit Management','subtitle'=>'Review and approve building permit applications. Monitor flood-prone zones for infrastructure prioritisation.'])
    <div class="grid grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-400">Pending</p><p class="text-3xl font-black text-yellow-600">{{ $stats['pending'] }}</p></div>
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-400">Approved</p><p class="text-3xl font-black text-green-600">{{ $stats['approved'] }}</p></div>
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-400">Rejected</p><p class="text-3xl font-black text-red-600">{{ $stats['rejected'] }}</p></div>
    </div>

    {{-- Flood-Prone Overview --}}
    <div class="bg-orange-50 border border-orange-200 rounded-2xl p-6">
        <h3 class="font-bold text-orange-800 mb-3">🌊 Flood-Prone Zones by Barangay</h3>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            @foreach($floodProneByBarangay as $fp)
            <div class="bg-white rounded-xl p-3 text-center">
                <p class="text-xs text-gray-500">{{ $fp->barangay }}</p>
                <p class="text-xl font-black text-orange-600">{{ $fp->count }}</p>
            </div>
            @endforeach
        </div>
    </div>

    @include('department.components._request-table', ['requests' => $requests, 'routeBase' => 'department.building-permits'])
</div>
@endsection
