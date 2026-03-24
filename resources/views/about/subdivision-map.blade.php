@extends('layouts.public')

@section('title', 'Subdivision Map')

@section('content')
<div class="py-8 md:py-12 lg:py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-deep-forest mb-8 border-l-4 border-tiger-orange pl-4">Subdivision Map of Buguey</h1>
        
        <div class="bg-white rounded-xl shadow-sm p-8 mb-8">
            <p class="text-gray-600 mb-6">Administrative subdivision map showing land area distribution across all barangays</p>
            <div class="bg-gradient-to-br from-golden-glow to-tiger-orange rounded-lg h-96 flex items-center justify-center text-white p-8">
                <div class="text-center">
                    <div class="text-6xl mb-4">📊</div>
                    <p class="text-2xl font-bold mb-2">Administrative Subdivision Map</p>
                    <p class="text-gray-100">Detailed land use and subdivision map coming soon</p>
                    <p class="text-sm text-gray-200 mt-4">Will display barangay boundaries and land area percentages</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-8">
            <h2 class="text-2xl font-bold text-deep-forest mb-6">Land Area Distribution</h2>
            <p class="text-gray-600 mb-6">Total municipal land area: <strong>16,450.05 hectares</strong></p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-golden-glow bg-opacity-10 rounded-lg p-6 border-l-4 border-golden-glow">
                    <h3 class="font-bold text-deep-forest mb-2">Largest Barangay</h3>
                    <p class="text-2xl font-bold text-tiger-orange">Tabbac</p>
                    <p class="text-sm text-gray-700 mt-2">2,821.17 hectares</p>
                    <p class="text-xs text-gray-600">17.15% of total area</p>
                </div>
                
                <div class="bg-sea-green bg-opacity-10 rounded-lg p-6 border-l-4 border-sea-green">
                    <h3 class="font-bold text-deep-forest mb-2">Smallest Barangay</h3>
                    <p class="text-2xl font-bold text-sea-green">Sta. Maria</p>
                    <p class="text-sm text-gray-700 mt-2">21.87 hectares</p>
                    <p class="text-xs text-gray-600">Most densely populated</p>
                </div>
                
                <div class="bg-tiger-orange bg-opacity-10 rounded-lg p-6 border-l-4 border-tiger-orange">
                    <h3 class="font-bold text-deep-forest mb-2">Classification</h3>
                    <p class="text-sm text-gray-700 mt-2"><strong>Urban:</strong> 7,273.86 ha (44.22%)</p>
                    <p class="text-sm text-gray-700"><strong>Rural:</strong> 9,176.17 ha (55.78%)</p>
                </div>
            </div>
            
            <div class="mt-8 p-6 bg-gray-50 rounded-lg">
                <h3 class="font-bold text-deep-forest mb-3">Key Geographic Feature</h3>
                <p class="text-gray-700"><strong>Buguey Lagoon</strong> bisects the municipality into coastal and non-coastal areas, creating distinct ecological and economic zones.</p>
            </div>
        </div>
    </div>
</div>
@endsection
