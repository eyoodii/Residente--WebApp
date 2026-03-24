@extends('layouts.admin')

@section('title', 'Activity Log Details')

@section('content')
<div class="p-8">
    <!-- Header with Back Button -->
    <div class="mb-8">
        <a href="{{ route('admin.activity-logs.index') }}" class="inline-flex items-center gap-2 text-sea-green hover:text-opacity-80 transition mb-4">
            <span>←</span> Back to Activity Logs
        </a>
        <h1 class="text-3xl font-bold text-deep-forest flex items-center gap-3">
            <span>📝</span> Activity Log Details
        </h1>
        <p class="text-gray-600 mt-2">Detailed information about this activity</p>
    </div>

    <!-- Activity Details Card -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-deep-forest">Activity Information</h3>
        </div>
        <div class="p-6 space-y-6">
            <!-- Timestamp and Severity -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Timestamp</label>
                    <p class="text-lg font-bold text-deep-forest">{{ $activityLog->created_at->format('F d, Y') }}</p>
                    <p class="text-sm text-gray-500">{{ $activityLog->created_at->format('h:i:s A') }} ({{ $activityLog->created_at->diffForHumans() }})</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Severity Level</label>
                    @php
                        $severityClass = match($activityLog->severity) {
                            'critical' => 'bg-red-100 text-red-800',
                            'high' => 'bg-tiger-orange bg-opacity-10 text-tiger-orange',
                            'medium' => 'bg-golden-glow bg-opacity-10 text-golden-glow',
                            default => 'bg-gray-100 text-gray-700'
                        };
                    @endphp
                    <span class="inline-flex px-4 py-2 text-base font-bold rounded uppercase {{ $severityClass }}">
                        {{ $activityLog->severity }}
                    </span>
                    @if($activityLog->is_suspicious)
                    <span class="inline-flex px-4 py-2 text-base font-bold bg-burnt-tangerine bg-opacity-10 text-burnt-tangerine rounded ml-2">
                        ⚠️ Suspicious Activity
                    </span>
                    @endif
                </div>
            </div>

            <!-- Action and Entity -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Action</label>
                    <p class="text-lg font-bold text-deep-forest">{{ ucfirst(str_replace('_', ' ', $activityLog->action)) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Entity</label>
                    <p class="text-lg font-bold text-deep-forest">
                        {{ $activityLog->entity_type ?? 'N/A' }}
                        @if($activityLog->entity_id)
                        <span class="text-gray-500">#{{ $activityLog->entity_id }}</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Description</label>
                <p class="text-base text-gray-900">{{ $activityLog->description }}</p>
            </div>
        </div>
    </div>

    <!-- User Information Card -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-deep-forest">User Information</h3>
        </div>
        <div class="p-6 space-y-4">
            @if($activityLog->resident)
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Resident</label>
                <p class="text-lg font-bold text-deep-forest">{{ $activityLog->resident->full_name }}</p>
                <p class="text-sm text-gray-600">{{ $activityLog->resident->email }}</p>
                @if($activityLog->resident->national_id)
                <p class="text-sm text-gray-500 font-mono">{{ $activityLog->resident->national_id }}</p>
                @endif
            </div>
            @else
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                <p class="text-lg font-bold text-deep-forest">{{ $activityLog->user_email ?? 'System' }}</p>
            </div>
            @endif

            @if($activityLog->user_role)
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Role</label>
                <span class="inline-flex px-3 py-1 text-sm font-bold rounded uppercase
                    {{ $activityLog->user_role === 'admin' ? 'bg-deep-forest text-white' : 'bg-gray-200 text-gray-700' }}">
                    {{ $activityLog->user_role }}
                </span>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">IP Address</label>
                    <p class="text-base text-gray-900 font-mono">{{ $activityLog->ip_address ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Request Method</label>
                    <span class="inline-flex px-3 py-1 text-sm font-bold bg-gray-100 text-gray-700 rounded font-mono">
                        {{ $activityLog->request_method ?? 'N/A' }}
                    </span>
                </div>
            </div>

            @if($activityLog->request_url)
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Request URL</label>
                <p class="text-sm text-gray-900 font-mono break-all">{{ $activityLog->request_url }}</p>
            </div>
            @endif

            @if($activityLog->user_agent)
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">User Agent</label>
                <p class="text-sm text-gray-900 break-all">{{ $activityLog->user_agent }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Data Changes Card (if applicable) -->
    @if($activityLog->old_values || $activityLog->new_values)
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-deep-forest">Data Changes</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($activityLog->old_values)
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">Old Values</label>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <pre class="text-sm text-gray-900 whitespace-pre-wrap">{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif

            @if($activityLog->new_values)
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">New Values</label>
                <div class="bg-sea-green bg-opacity-10 border border-sea-green rounded-lg p-4">
                    <pre class="text-sm text-gray-900 whitespace-pre-wrap">{{ json_encode($activityLog->new_values, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Metadata Card (if applicable) -->
    @if($activityLog->metadata)
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-deep-forest">Additional Metadata</h3>
        </div>
        <div class="p-6">
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <pre class="text-sm text-gray-900 whitespace-pre-wrap">{{ json_encode($activityLog->metadata, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
