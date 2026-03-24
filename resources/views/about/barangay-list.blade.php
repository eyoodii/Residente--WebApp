@extends('layouts.public')

@section('title', 'List of Barangays')

@section('content')
<div class="py-8 md:py-12 lg:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-deep-forest mb-8 border-l-4 border-tiger-orange pl-4">List of Barangays</h1>
        
        <div class="mb-8">
            <div class="bg-sea-green text-white rounded-lg p-6 mb-6">
                <h2 class="text-2xl font-bold mb-2">10 Urban Barangays</h2>
                <p class="text-sm text-gray-100">Occupy 7,273.86 hectares (44.22% of total land area)</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                $urbanBarangays = [
                    'Centro', 'Centro West', 'Dalaya', 'Leron', 'Maddalero', 
                    'Pattao', 'Sta. Maria', 'Sta. Isabel', 'San Lorenzo', 'Tabbac'
                ];
                @endphp
                
                @foreach($urbanBarangays as $index => $barangay)
                <div class="bg-white hover:bg-sea-green hover:text-white rounded-lg p-4 border-2 border-sea-green hover:shadow-md transition cursor-pointer group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-sea-green group-hover:bg-white group-hover:text-sea-green text-white rounded-full flex items-center justify-center font-bold text-sm">
                            {{ $index + 1 }}
                        </div>
                        <span class="font-semibold">{{ $barangay }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mb-8">
            <div class="bg-tiger-orange text-white rounded-lg p-6 mb-6">
                <h2 class="text-2xl font-bold mb-2">20 Rural Barangays</h2>
                <p class="text-sm text-gray-100">Occupy 9,176.17 hectares (55.78% of total land area)</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                $ruralBarangays = [
                    'Ballang', 'Balza', 'Cabaritan', 'Calamegatan', 'Fula', 
                    'M. Antiporda', 'Mala Este', 'Mala Weste', 'Minanga Este', 'Minanga Weste',
                    'Paddaya Este', 'Paddaya Weste', 'Quinawegan', 'Remebella', 'San Isidro',
                    'San Juan', 'San Vicente', 'Villa Cielo', 'Villa Gracia', 'Villa Leonora'
                ];
                @endphp
                
                @foreach($ruralBarangays as $index => $barangay)
                <div class="bg-gray-50 hover:bg-tiger-orange hover:text-white rounded-lg p-4 border-2 border-tiger-orange hover:shadow-md transition cursor-pointer group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-tiger-orange group-hover:bg-white group-hover:text-tiger-orange text-white rounded-full flex items-center justify-center font-bold text-sm">
                            {{ $index + 11 }}
                        </div>
                        <span class="font-semibold">{{ $barangay }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            <div class="bg-golden-glow bg-opacity-10 rounded-xl p-6 border-l-4 border-golden-glow">
                <h3 class="font-bold text-deep-forest text-xl mb-2">Total: 30 Barangays</h3>
                <p class="text-gray-700 text-sm">Bisected by Buguey Lagoon into coastal and non-coastal areas</p>
            </div>
            <div class="bg-sea-green bg-opacity-10 rounded-xl p-6 border-l-4 border-sea-green">
                <h3 class="font-bold text-deep-forest text-lg mb-2">Largest</h3>
                <p class="text-gray-700 text-sm"><strong>Barangay Tabbac</strong><br>2,821.17 hectares (17.15%)</p>
            </div>
            <div class="bg-tiger-orange bg-opacity-10 rounded-xl p-6 border-l-4 border-tiger-orange">
                <h3 class="font-bold text-deep-forest text-lg mb-2">Smallest</h3>
                <p class="text-gray-700 text-sm"><strong>Barangay Sta. Maria</strong><br>21.87 hectares</p>
            </div>
        </div>

        <div class="mt-8 bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-deep-forest text-lg mb-4">Notable Population Facts</h3>
            <ul class="space-y-2 text-gray-700">
                <li class="flex items-start gap-2">
                    <span class="text-tiger-orange font-bold">•</span>
                    <span><strong>Barangay Pattao</strong> is the most populated with 3,295 residents (gateway to the municipality)</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-tiger-orange font-bold">•</span>
                    <span><strong>Barangay Centro</strong> (seat of government) is the second most populated with 2,012 residents</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-tiger-orange font-bold">•</span>
                    <span><strong>Barangay Sta. Maria</strong> is the most densely populated with 31 persons per hectare</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
