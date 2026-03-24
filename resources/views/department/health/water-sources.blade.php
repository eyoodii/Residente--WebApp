@extends('layouts.department')
@section('title', 'Water Sources')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'💧','title'=>'Water Sources by Barangay','subtitle'=>'Household water source distribution across barangays.'])
    @foreach($byBarangay as $barangay => $sources)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-3">{{ $barangay }}</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($sources as $s)
            <span class="px-3 py-1.5 bg-blue-50 text-blue-800 rounded-xl text-sm">{{ $s->water_source }}: <strong>{{ $s->count }}</strong></span>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
@endsection
