@extends('layouts.public')

@section('title', 'Barangay List Map')

@section('content')
<div class="py-8 md:py-12 lg:py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-deep-forest mb-8 border-l-4 border-tiger-orange pl-4">Barangay List Map</h1>
        
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
            <div class="bg-gray-50 p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-deep-forest mb-2">Barangay Map of Buguey</h2>
                <p class="text-gray-600">Visual representation showing all 30 barangays of Buguey organized by urban and rural classification</p>
            </div>
            <div class="relative">
                <iframe 
                    class="w-full h-[600px] border-0" 
                    src="https://maps.google.com/maps?q=Buguey,+Cagayan,+Philippines&t=&z=13&ie=UTF8&iwloc=&output=embed" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
            <div class="p-6 bg-gray-50 border-t border-gray-200">
                <p class="text-sm text-gray-600">
                    <strong>Note:</strong> This map shows the general location of Buguey Municipality. 
                    The 30 barangays are distributed across coastal and non-coastal areas, bisected by the Buguey Lagoon.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-sea-green text-white rounded-xl p-6">
                <h3 class="text-xl font-bold mb-4">10 Urban Barangays</h3>
                <p class="text-sm text-gray-100 mb-3">44.22% of total land area (7,273.86 ha)</p>
                <ul class="text-sm space-y-1 text-gray-100">
                    <li>• Centro, Centro West, Dalaya</li>
                    <li>• Leron, Maddalero, Pattao</li>
                    <li>• Sta. Maria, Sta. Isabel</li>
                    <li>• San Lorenzo, Tabbac</li>
                </ul>
            </div>
            
            <div class="bg-tiger-orange text-white rounded-xl p-6">
                <h3 class="text-xl font-bold mb-4">20 Rural Barangays</h3>
                <p class="text-sm text-gray-100 mb-3">55.78% of total land area (9,176.17 ha)</p>
                <ul class="text-sm space-y-1 text-gray-100">
                    <li>• Ballang, Balza, Cabaritan, Calamegatan</li>
                    <li>• Fula, M. Antiporda, Mala Este, Mala Weste</li>
                    <li>• Minanga Este, Minanga Weste</li>
                    <li>• Paddaya Este, Paddaya Weste</li>
                    <li>• And 8 more rural barangays...</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
