@extends('layouts.department')
@section('title', 'Livelihood Programs')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'🌾','title'=>'Agriculture & Livelihood','subtitle'=>'Overview of registered agricultural households, farmers & fishers.'])
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([['🚜','Farmers',$stats['farmers'],route('department.livelihood.farmers'),'green'],['🎣','Fisheries',$stats['fisheries'],route('department.livelihood.fisheries'),'blue'],['🐄','Livestock',$stats['livestock'],route('department.livelihood.livestock'),'yellow'],['🐠','Aquaculture',$stats['aquaculture'],route('department.livelihood.aquaculture'),'teal']] as [$icon,$label,$count,$href,$col])
        <a href="{{ $href }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition">
            <div class="text-3xl mb-2">{{ $icon }}</div>
            <div class="text-2xl font-bold">{{ number_format($count) }}</div>
            <div class="text-sm text-gray-500">{{ $label }}</div>
        </a>
        @endforeach
    </div>
</div>
@endsection
