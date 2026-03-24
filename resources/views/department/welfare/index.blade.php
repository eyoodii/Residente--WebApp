@extends('layouts.department')
@section('title', 'Welfare Targeting')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'❤️','title'=>'Welfare Targeting System','subtitle'=>'Identify and locate vulnerable populations for aid distribution and relief operations.'])

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('department.welfare.index') }}" class="px-4 py-2 bg-deep-forest text-white rounded-xl text-sm font-semibold">Overview</a>
        <a href="{{ route('department.welfare.vulnerable') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50">All Vulnerable</a>
        <a href="{{ route('department.welfare.export') }}" class="px-4 py-2 bg-golden-glow text-white rounded-xl text-sm font-semibold hover:opacity-90">⬇ Export</a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-400">Total Vulnerable</p><p class="text-3xl font-black text-red-600">{{ number_format($totalVulnerable) }}</p></div>
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-400">Informal Settlers</p><p class="text-3xl font-black text-orange-600">{{ number_format($informalSettlers) }}</p></div>
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-400">Flood-Prone</p><p class="text-3xl font-black text-yellow-600">{{ number_format($floodProne) }}</p></div>
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-400">No Toilet</p><p class="text-3xl font-black text-gray-600">{{ number_format($withoutToilet) }}</p></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">Sector Summary</h3>
            <div class="space-y-2">
                @foreach($summary as $s)
                <div class="flex justify-between items-center p-3 bg-red-50 rounded-xl">
                    <a href="{{ route('department.welfare.by-sector', $s->vulnerable_sector) }}" class="text-sm font-medium text-red-800 hover:underline">{{ $s->vulnerable_sector }}</a>
                    <span class="text-sm font-bold text-red-700">{{ number_format($s->count) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">By Barangay</h3>
            <div class="space-y-2 max-h-72 overflow-y-auto">
                @foreach($byBarangay as $b)
                <div class="flex justify-between text-sm py-2 border-b border-gray-50">
                    <span>{{ $b->barangay }}</span>
                    <span class="font-bold text-deep-forest">{{ $b->count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
