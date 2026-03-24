@extends('layouts.public')

@section('title', 'Demographic Profile')

@section('content')
<div class="py-8 md:py-12 lg:py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-deep-forest mb-8 border-l-4 border-tiger-orange pl-4">Demographic Profile</h1>
        <p class="text-gray-600 mb-8">Based on 2015 PSA Census Data</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-sea-green">
                <div class="text-3xl text-sea-green mb-2">👥</div>
                <h3 class="text-sm font-semibold text-gray-600 mb-1">Total Population</h3>
                <p class="text-2xl font-bold text-deep-forest">30,175</p>
                <p class="text-xs text-gray-500 mt-1">PSA Census 2015</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-tiger-orange">
                <div class="text-3xl text-tiger-orange mb-2">📈</div>
                <h3 class="text-sm font-semibold text-gray-600 mb-1">Annual Growth Rate</h3>
                <p class="text-2xl font-bold text-deep-forest">1.12%</p>
                <p class="text-xs text-gray-500 mt-1">Average annual</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-golden-glow">
                <div class="text-3xl text-deep-forest mb-2">📍</div>
                <h3 class="text-sm font-semibold text-gray-600 mb-1">Barangays</h3>
                <p class="text-2xl font-bold text-deep-forest">30</p>
                <p class="text-xs text-gray-500 mt-1">10 Urban, 20 Rural</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-burnt-tangerine">
                <div class="text-3xl text-burnt-tangerine mb-2">🏠</div>
                <h3 class="text-sm font-semibold text-gray-600 mb-1">Population Density</h3>
                <p class="text-2xl font-bold text-deep-forest">1.83-2</p>
                <p class="text-xs text-gray-500 mt-1">persons per hectare</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-8">
                <h2 class="text-2xl font-bold text-deep-forest mb-6">Gender Distribution</h2>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-700 font-semibold">Male</span>
                            <span class="text-deep-forest font-bold">51%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-sea-green h-3 rounded-full" style="width: 51%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">51 males per 100 population</p>
                    </div>
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-700 font-semibold">Female</span>
                            <span class="text-deep-forest font-bold">49%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-tiger-orange h-3 rounded-full" style="width: 49%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">49 females per 100 population</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-8">
                <h2 class="text-2xl font-bold text-deep-forest mb-6">Age Distribution</h2>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex justify-between items-center">
                        <span>0-19 years (Youth)</span> 
                        <span class="font-bold text-sea-green text-lg">41.63%</span>
                    </li>
                    <li class="flex justify-between items-center">
                        <span>20-64 years (Working Age)</span> 
                        <span class="font-bold text-tiger-orange text-lg">~52%</span>
                    </li>
                    <li class="flex justify-between items-center">
                        <span>65+ years (Senior Citizens)</span> 
                        <span class="font-bold text-burnt-tangerine text-lg">~6%</span>
                    </li>
                </ul>
                <div class="mt-4 p-3 bg-golden-glow bg-opacity-10 rounded">
                    <p class="text-sm text-gray-700">Buguey has a <strong>young demographic</strong>, with over 40% under 20 years old</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-8">
            <h2 class="text-2xl font-bold text-deep-forest mb-6">Labor Force & Employment</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="font-bold text-deep-forest text-lg mb-4">Labor Force Participation</h3>
                    <div class="space-y-3">
                        <div class="bg-sea-green bg-opacity-10 p-4 rounded-lg">
                            <p class="text-3xl font-bold text-sea-green">62.06%</p>
                            <p class="text-sm text-gray-700 mt-1">18,728 individuals in the labor force</p>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-deep-forest text-lg mb-4">Employment by Sector</h3>
                    <ul class="space-y-3 text-gray-700">
                        <li class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <span class="text-2xl">🌾</span>
                                <span>Agriculture</span>
                            </div>
                            <span class="font-bold text-sea-green text-lg">59.69%</span>
                        </li>
                        <li class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <span class="text-2xl">🏪</span>
                                <span>Services</span>
                            </div>
                            <span class="font-bold text-tiger-orange text-lg">35.86%</span>
                        </li>
                        <li class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <span class="text-2xl">🏭</span>
                                <span>Industry</span>
                            </div>
                            <span class="font-bold text-burnt-tangerine text-lg">4.45%</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-6 p-4 bg-tiger-orange bg-opacity-10 border-l-4 border-tiger-orange rounded-r-lg">
                <p class="text-sm text-gray-800">
                    <strong>Agriculture is the major employment generator</strong>, absorbing nearly 60% of the working population, 
                    followed by the service sector. About 80% of families are engaged in farming, fishing, and related activities.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
