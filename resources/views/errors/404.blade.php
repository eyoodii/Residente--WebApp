@extends('errors.layout')

@section('title', 'Page Not Found')
@section('code', '404')
@section('message', 'Page Not Found')
@section('description', 'Oops! The page you are looking for might have been moved, deleted, or doesn\'t exist.')

@section('icon')
<svg class="w-32 h-32 text-sea-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
</svg>
@endsection

@section('actions')
<a href="{{ url('/') }}" class="inline-flex items-center px-6 py-3 bg-sea-green text-white font-semibold rounded-lg hover:bg-deep-forest transition-colors duration-200 shadow-lg hover:shadow-xl">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
    </svg>
    Go to Homepage
</a>

<button onclick="history.back()" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 font-semibold rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors duration-200">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
    </svg>
    Go Back
</button>
@endsection

@section('suggestions')
<div class="flex flex-wrap justify-center gap-3">
    <a href="{{ route('services.index') }}" class="text-sea-green hover:text-deep-forest text-sm font-medium">Services</a>
    <span class="text-gray-300">|</span>
    <a href="{{ url('/about') }}" class="text-sea-green hover:text-deep-forest text-sm font-medium">About Us</a>
    <span class="text-gray-300">|</span>
    @auth
    <a href="{{ route('dashboard') }}" class="text-sea-green hover:text-deep-forest text-sm font-medium">Dashboard</a>
    @else
    <a href="{{ route('login') }}" class="text-sea-green hover:text-deep-forest text-sm font-medium">Login</a>
    @endauth
</div>
@endsection
