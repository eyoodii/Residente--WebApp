@extends('errors.layout')

@section('title', 'Maintenance')
@section('code', '503')
@section('message', 'Under Maintenance')
@section('description', 'We\'re currently performing scheduled maintenance to improve your experience. We\'ll be back shortly!')

@section('icon')
<svg class="w-32 h-32 text-sea-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
</svg>
@endsection

@section('actions')
<button onclick="window.location.reload()" class="inline-flex items-center px-6 py-3 bg-sea-green text-white font-semibold rounded-lg hover:bg-deep-forest transition-colors duration-200 shadow-lg hover:shadow-xl">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
    </svg>
    Check Again
</button>
@endsection

@section('suggestions')
<div class="text-sm text-gray-600">
    <p class="mb-2">
        <strong>Expected Downtime:</strong> {{ $exception->getMessage() ?: 'A few minutes' }}
    </p>
    <p>
        Thank you for your patience while we make improvements to the system.
    </p>
</div>
@endsection
