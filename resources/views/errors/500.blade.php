@extends('errors.layout')

@section('title', 'Server Error')
@section('code', '500')
@section('message', 'Something Went Wrong')
@section('description', 'We\'re experiencing technical difficulties. Our team has been notified and is working to fix this. Please try again in a few moments.')

@section('icon')
<svg class="w-32 h-32 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
</svg>
@endsection

@section('actions')
<button onclick="window.location.reload()" class="inline-flex items-center px-6 py-3 bg-sea-green text-white font-semibold rounded-lg hover:bg-deep-forest transition-colors duration-200 shadow-lg hover:shadow-xl">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
    </svg>
    Try Again
</button>

<a href="{{ url('/') }}" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 font-semibold rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors duration-200">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
    </svg>
    Go to Homepage
</a>
@endsection

@section('suggestions')
<p class="text-sm text-gray-600 mb-2">
    If this problem persists, please contact the administrator.
</p>
<p class="text-xs text-gray-400">
    Error Reference: {{ now()->format('YmdHis') }}-{{ Str::random(6) }}
</p>
@endsection
