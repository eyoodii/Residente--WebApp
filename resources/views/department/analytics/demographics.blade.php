@extends('layouts.department')
@section('title', 'Demographics')
@section('content')
<div class="space-y-6">
    @include('department.components._module-header', ['icon'=>'👥','title'=>'Demographic Profile','subtitle'=>'Age groups, gender distribution, and civil status breakdown.'])
    @include('department.analytics._analytics-nav')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">🎂 Age Groups</h3>
            <div class="space-y-3">
                @foreach($ageGroups as $label => $count)
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-xl">
                    <span class="text-sm font-medium text-blue-800">{{ $label }}</span>
                    <span class="text-sm font-bold text-blue-700">{{ number_format($count) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">⚧ Gender</h3>
            <div class="space-y-3">
                @foreach($genderBreakdown as $g)
                <div class="flex justify-between items-center p-3 bg-purple-50 rounded-xl">
                    <span class="text-sm font-medium text-purple-800">{{ $g->gender ?: 'N/A' }}</span>
                    <span class="text-sm font-bold text-purple-700">{{ number_format($g->count) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">💍 Civil Status</h3>
            <div class="space-y-3">
                @foreach($civilStatusBreakdown as $cs)
                <div class="flex justify-between items-center p-3 bg-orange-50 rounded-xl">
                    <span class="text-sm font-medium text-orange-800">{{ $cs->civil_status ?: 'N/A' }}</span>
                    <span class="text-sm font-bold text-orange-700">{{ number_format($cs->count) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
