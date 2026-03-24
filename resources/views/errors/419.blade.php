@extends('errors.layout')

@section('title', 'Session Expired')
@section('code', '419')
@section('message', 'Session Expired')
@section('description', 'Your session has expired due to inactivity. This happens to keep your account secure. Please refresh the page or log in again.')

@section('icon')
<svg class="w-32 h-32 text-tiger-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
</svg>
@endsection

@section('actions')
<button onclick="window.location.reload()" class="inline-flex items-center px-6 py-3 bg-sea-green text-white font-semibold rounded-lg hover:bg-deep-forest transition-colors duration-200 shadow-lg hover:shadow-xl">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
    </svg>
    Refresh Page
</button>

<a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-white text-gray-700 font-semibold rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors duration-200">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
    </svg>
    Login Again
</a>
@endsection

@section('suggestions')
<p class="text-sm text-gray-600">
    <strong>Tip:</strong> Make sure to save your work frequently when filling out forms to prevent data loss.
</p>
@endsection
