@extends('layouts.public')

@section('title', 'Map of Buguey')

@section('content')
<div class="py-8 md:py-12 lg:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-deep-forest mb-8 border-l-4 border-tiger-orange pl-4">Map of Buguey</h1>
        
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <iframe 
                class="w-full h-[600px] border-0" 
                src="https://maps.google.com/maps?q=Buguey,+Cagayan,+Philippines&t=&z=13&ie=UTF8&iwloc=&output=embed" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <div class="bg-sea-green bg-opacity-10 rounded-xl p-6 border-l-4 border-sea-green">
                <h3 class="font-bold text-deep-forest text-lg mb-3">Location & Size</h3>
                <ul class="space-y-2 text-gray-700 text-sm">
                    <li><strong>Province:</strong> Cagayan, Philippines</li>
                    <li><strong>Total Land Area:</strong> 16,450.05 hectares</li>
                    <li><strong>Municipality Rank:</strong> 8th smallest in Cagayan</li>
                </ul>
            </div>
            
            <div class="bg-tiger-orange bg-opacity-10 rounded-xl p-6 border-l-4 border-tiger-orange">
                <h3 class="font-bold text-deep-forest text-lg mb-3">Boundaries</h3>
                <ul class="space-y-2 text-gray-700 text-sm">
                    <li><strong>North:</strong> Babuyan Channel</li>
                    <li><strong>East:</strong> Sta. Teresita</li>
                    <li><strong>South:</strong> Lal-lo</li>
                    <li><strong>West:</strong> Aparri and Camalaniugan</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
