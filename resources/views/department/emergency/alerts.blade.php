@extends('layouts.department')
@section('title', 'Emergency Alerts')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'🚨','title'=>'Active Emergency Alerts','subtitle'=>'All broadcasted emergency notifications.'])
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
        @forelse($alerts as $alert)
        <div class="flex items-start gap-4 p-4 rounded-xl {{ str_contains(strtolower($alert->category ?? ''), 'emergency') ? 'bg-red-50 border border-red-200' : 'bg-yellow-50 border border-yellow-200' }}">
            <span class="text-2xl">{{ str_contains(strtolower($alert->category ?? ''), 'emergency') ? '🔴' : '🟡' }}</span>
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <span class="font-semibold text-sm">{{ $alert->title }}</span>
                    <span class="text-xs text-gray-500">{{ $alert->posted_at?->diffForHumans() }}</span>
                </div>
                <p class="text-sm mt-1 text-gray-700">{{ $alert->content }}</p>
                @if($alert->target_barangay)
                <span class="mt-1 inline-block text-xs bg-white px-2 py-0.5 rounded-full border">📍 {{ $alert->target_barangay }}</span>
                @endif
            </div>
        </div>
        @empty
        <p class="text-gray-400 text-center py-8">No active alerts.</p>
        @endforelse
        <div>{{ $alerts->links() }}</div>
    </div>
    <a href="{{ route('department.emergency.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600">← Back</a>
</div>
@endsection
