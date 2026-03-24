@extends('layouts.admin')

@section('title', 'Suspicious Activities')

@section('content')
<div class="p-8">
    <!-- Header with Back Button -->
    <div class="mb-8">
        <a href="{{ route('admin.activity-logs.index') }}" class="inline-flex items-center gap-2 text-sea-green hover:text-opacity-80 transition mb-4">
            <span>←</span> Back to All Activity Logs
        </a>
        <h1 class="text-3xl font-bold text-burnt-tangerine flex items-center gap-3">
            <span>⚠️</span> Suspicious Activities
        </h1>
        <p class="text-gray-600 mt-2">Activities flagged as potentially suspicious</p>
    </div>

    <!-- Alert Banner -->
    <div class="mb-8 bg-burnt-tangerine bg-opacity-10 border-l-4 border-burnt-tangerine rounded-lg p-6">
        <div class="flex items-start gap-4">
            <span class="text-3xl">⚠️</span>
            <div>
                <h3 class="font-bold text-burnt-tangerine text-lg mb-2">Security Alert</h3>
                <p class="text-gray-700">
                    These activities have been flagged as suspicious based on security rules. Please review them carefully and take appropriate action if necessary.
                </p>
            </div>
        </div>
    </div>

    <!-- Suspicious Activities Table -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-deep-forest">Suspicious Activities</h3>
            <p class="text-sm text-gray-600 mt-1">{{ $activityLogs->total() }} suspicious activities found</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Timestamp</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Severity</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($activityLogs as $log)
                    <tr class="bg-burnt-tangerine bg-opacity-5 hover:bg-opacity-10 transition">
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <p class="font-medium text-gray-900">{{ $log->created_at->format('M d, Y') }}</p>
                                <p class="text-gray-500">{{ $log->created_at->format('h:i A') }}</p>
                                <p class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                @if($log->resident)
                                <p class="font-medium text-gray-900">{{ $log->resident->full_name }}</p>
                                <p class="text-gray-500">{{ $log->resident->email }}</p>
                                @else
                                <p class="font-medium text-gray-900">{{ $log->user_email ?? 'Unknown' }}</p>
                                @endif
                                @if($log->user_role)
                                <span class="inline-flex px-2 py-0.5 text-xs font-bold rounded uppercase mt-1
                                    {{ $log->user_role === 'admin' ? 'bg-deep-forest text-white' : 'bg-gray-200 text-gray-700' }}">
                                    {{ $log->user_role }}
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <p class="font-bold text-burnt-tangerine">{{ ucfirst(str_replace('_', ' ', $log->action)) }}</p>
                                @if($log->entity_type)
                                <p class="text-gray-500">{{ $log->entity_type }} #{{ $log->entity_id }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-md">
                                {{ Str::limit($log->description, 80) }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-mono text-gray-700">{{ $log->ip_address ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $severityClass = match($log->severity) {
                                    'critical' => 'bg-red-100 text-red-800',
                                    'high' => 'bg-tiger-orange bg-opacity-10 text-tiger-orange',
                                    'medium' => 'bg-golden-glow bg-opacity-10 text-golden-glow',
                                    default => 'bg-gray-100 text-gray-700'
                                };
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-bold rounded uppercase {{ $severityClass }}">
                                {{ $log->severity }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.activity-logs.show', $log) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-burnt-tangerine text-white rounded-lg hover:bg-opacity-90 transition font-medium text-sm">
                                Investigate
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-3">
                                <span class="text-5xl">✅</span>
                                <p class="text-lg font-medium">No suspicious activities found</p>
                                <p class="text-sm text-gray-400">Your system is secure</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($activityLogs->hasPages())
        <div class="p-6 border-t border-gray-200">
            {{ $activityLogs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
