@extends('layouts.department')
@section('title', 'Emergency Management')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'🚨','title'=>'Emergency Management — DRRMO','subtitle'=>'Broadcast emergency alerts and access flood-prone household data for rescue operations.'])

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('department.emergency.index') }}" class="px-4 py-2 bg-deep-forest text-white rounded-xl text-sm font-semibold">Overview</a>
        <a href="{{ route('department.emergency.flood-prone') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50">Flood-Prone List</a>
        <a href="{{ route('department.emergency.alerts') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50">Broadcast History</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Broadcast Form --}}
        <div class="bg-red-50 border-2 border-red-200 rounded-2xl p-6">
            <h3 class="font-bold text-red-800 mb-4 flex items-center gap-2">🔔 Broadcast Emergency Alert</h3>
            <form method="POST" action="{{ route('department.emergency.broadcast') }}" class="space-y-3">
                @csrf
                <input type="text" name="title" placeholder="Alert Title (e.g. Typhoon Warning: Evacuate Now)" required class="w-full border border-red-200 rounded-xl p-3 text-sm focus:ring-red-400 focus:border-red-400">
                <textarea name="content" rows="4" placeholder="Detailed message for citizens..." required class="w-full border border-red-200 rounded-xl p-3 text-sm"></textarea>
                <select name="target_barangay" class="w-full border border-red-200 rounded-xl p-3 text-sm">
                    <option value="">📢 All Barangays</option>
                    @foreach($floodProneByBarangay as $fp)
                    <option value="{{ $fp->barangay }}">{{ $fp->barangay }} ({{ $fp->count }} flood-prone)</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-xl font-bold text-sm hover:bg-red-700 transition">🚨 Broadcast Now</button>
            </form>
        </div>

        {{-- Flood-Prone Summary --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">🌊 Flood-Prone Zones — <span class="text-orange-600">{{ number_format($floodProneCount) }} households</span></h3>
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @foreach($floodProneByBarangay as $fp)
                <div class="flex justify-between items-center p-3 bg-orange-50 rounded-xl">
                    <span class="text-sm font-medium text-orange-800">{{ $fp->barangay }}</span>
                    <span class="text-sm font-bold text-orange-700">{{ $fp->count }} households</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Recent Alerts --}}
    @if($recentAlerts->count())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4">Recent Alerts</h3>
        <div class="space-y-3">
            @foreach($recentAlerts as $alert)
            <div class="p-3 bg-red-50 rounded-xl border border-red-100">
                <p class="font-bold text-red-800 text-sm">{{ $alert->title }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $alert->posted_at->format('M d, Y H:i') }} • {{ $alert->target_barangay ?? 'All Barangays' }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
