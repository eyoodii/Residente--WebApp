@extends('layouts.public')

@section('title', 'E-Bugueyano')

@section('content')
<div class="py-8 md:py-12 lg:py-16 bg-gradient-to-br from-deep-forest to-sea-green text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold mb-4">E-Bugueyano Portal</h1>
            <p class="text-lg text-gray-100">Digital services for verified residents of Buguey</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-8 text-center hover:bg-opacity-20 transition">
                <div class="text-5xl mb-4">👤</div>
                <h3 class="font-bold text-xl mb-2">Personal Profile</h3>
                <p class="text-sm text-gray-100">Access and update your digital resident profile</p>
            </div>
            
            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-8 text-center hover:bg-opacity-20 transition">
                <div class="text-5xl mb-4">📋</div>
                <h3 class="font-bold text-xl mb-2">Service Requests</h3>
                <p class="text-sm text-gray-100">Track all your submitted service applications</p>
            </div>
            
            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-8 text-center hover:bg-opacity-20 transition">
                <div class="text-5xl mb-4">🔔</div>
                <h3 class="font-bold text-xl mb-2">Notifications</h3>
                <p class="text-sm text-gray-100">Receive updates about your barangay and municipality</p>
            </div>
        </div>

        <div class="text-center">
            @auth
                <a href="{{ route('dashboard') }}" class="bg-golden-glow hover:bg-white text-deep-forest px-8 py-4 rounded-lg font-bold shadow-lg transition inline-block text-lg">
                    Go to My Dashboard
                </a>
            @else
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-8 max-w-md mx-auto">
                    <h3 class="font-bold text-xl mb-4">Access E-Bugueyano Services</h3>
                    <p class="text-gray-100 mb-6">Register or log in to access personalized e-governance services</p>
                    <div class="flex gap-4 justify-center">
                        <a href="{{ route('register') }}" class="bg-golden-glow hover:bg-white text-deep-forest px-6 py-3 rounded-lg font-bold shadow-lg transition">
                            Register Now
                        </a>
                        <a href="{{ route('login') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 px-6 py-3 rounded-lg font-bold transition">
                            Log In
                        </a>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection
