@extends('layouts.department')
@section('title', 'Master Data Collections')
@section('content')
<div class="space-y-6">
    @include('department.components._module-header', ['icon'=>'🗂️','title'=>'Master Data Collections','subtitle'=>'Household, housing, and demographic profiles for the Municipality of Buguey.'])

    @include('department.master-collections._collections-nav')

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        @php $cards = [
            ['label'=>'Total Citizens','value'=>$totalResidents,'icon'=>'👤'],
            ['label'=>'Households','value'=>$totalHouseholds,'icon'=>'🏠'],
            ['label'=>'Verified','value'=>$verifiedCount,'icon'=>'✅'],
            ['label'=>'PhilSys Verified','value'=>$withPhilsys,'icon'=>'🪪'],
        ]; @endphp
        @foreach($cards as $c)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-xs text-gray-400">{{ $c['label'] }}</p>
            <p class="text-3xl font-black text-deep-forest">{{ $c['icon'] }} {{ number_format($c['value']) }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">🏗️ House Materials</h3>
            <div class="space-y-2">
                @foreach($houseMaterialStats as $hm)
                <div class="flex justify-between text-sm py-2 border-b border-gray-50">
                    <span>{{ $hm->house_materials }}</span>
                    <span class="font-bold text-deep-forest">{{ number_format($hm->count) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">💧 Water Sources</h3>
            <div class="space-y-2">
                @foreach($waterSourceStats as $ws)
                <div class="flex justify-between text-sm py-2 border-b border-gray-50">
                    <span>{{ $ws->water_source }}</span>
                    <span class="font-bold text-deep-forest">{{ number_format($ws->count) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">🚽 Sanitation</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-green-50 rounded-xl">
                    <span class="text-sm text-green-800">With Sanitary Toilet</span>
                    <span class="font-bold text-green-700">{{ number_format($sanitationStats['with_toilet']) }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-red-50 rounded-xl">
                    <span class="text-sm text-red-800">Without Toilet</span>
                    <span class="font-bold text-red-700">{{ number_format($sanitationStats['without_toilet']) }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-orange-50 rounded-xl">
                    <span class="text-sm text-orange-800">Flood-Prone Households</span>
                    <span class="font-bold text-orange-700">{{ number_format($floodProneCount) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
