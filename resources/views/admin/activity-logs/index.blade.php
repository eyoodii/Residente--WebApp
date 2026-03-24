@extends('layouts.admin')

@section('title', 'Activity Logs')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-deep-forest flex items-center gap-3">
            <span>📝</span> Activity Logs
        </h1>
        <p class="text-gray-600 mt-2">Monitor system activity and security events</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Activities</p>
                    <p class="text-3xl font-bold text-deep-forest">{{ number_format($activityLogs->total()) }}</p>
                </div>
                <div class="w-12 h-12 bg-sea-green bg-opacity-10 rounded-full flex items-center justify-center">
                    <span class="text-2xl">📊</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Suspicious Activities</p>
                    <p class="text-3xl font-bold text-burnt-tangerine">{{ number_format($suspiciousCount) }}</p>
                </div>
                <div class="w-12 h-12 bg-burnt-tangerine bg-opacity-10 rounded-full flex items-center justify-center">
                    <span class="text-2xl">⚠️</span>
                </div>
            </div>
            @if($suspiciousCount > 0)
            <a href="{{ route('admin.activity-logs.suspicious') }}" class="mt-3 inline-flex items-center text-sm text-burnt-tangerine hover:underline">
                View Suspicious →
            </a>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Critical Actions</p>
                    <p class="text-3xl font-bold text-tiger-orange">{{ number_format($criticalCount) }}</p>
                </div>
                <div class="w-12 h-12 bg-tiger-orange bg-opacity-10 rounded-full flex items-center justify-center">
                    <span class="text-2xl">🔥</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                    <select name="action" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                        <option value="">All Actions</option>
                        @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Severity</label>
                    <select name="severity" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                        <option value="">All Severities</option>
                        <option value="low" {{ request('severity') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('severity') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('severity') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ request('severity') === 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sea-green focus:border-transparent">
                </div>
            </div>
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="suspicious" value="1" {{ request('suspicious') ? 'checked' : '' }} class="w-4 h-4 text-sea-green border-gray-300 rounded focus:ring-sea-green">
                    <span class="text-sm text-gray-700">Show suspicious only</span>
                </label>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="px-6 py-2 bg-sea-green text-white rounded-lg hover:bg-opacity-90 transition font-bold">
                    🔍 Filter
                </button>
                @if(request()->hasAny(['action', 'severity', 'from_date', 'to_date', 'suspicious']))
                <a href="{{ route('admin.activity-logs.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-bold">
                    Clear Filters
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Activity Logs Table -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-deep-forest">Activity Logs</h3>
            <p class="text-sm text-gray-600 mt-1">{{ $activityLogs->total() }} activities found</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Timestamp</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Severity</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($activityLogs as $log)
                    <tr class="hover:bg-gray-50 transition {{ $log->is_suspicious ? 'bg-burnt-tangerine bg-opacity-5' : '' }}">
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <p class="font-medium text-gray-900">{{ $log->created_at->format('M d, Y') }}</p>
                                <p class="text-gray-500">{{ $log->created_at->format('h:i A') }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                @if($log->resident)
                                <p class="font-medium text-gray-900">{{ $log->resident->full_name }}</p>
                                <p class="text-gray-500">{{ $log->resident->email }}</p>
                                @else
                                <p class="font-medium text-gray-900">{{ $log->user_email ?? 'System' }}</p>
                                @endif
                                @if($log->user_role)
                                <span class="inline-flex px-2 py-0.5 text-xs font-bold rounded uppercase
                                    {{ $log->user_role === 'admin' ? 'bg-deep-forest text-white' : 'bg-gray-200 text-gray-700' }}">
                                    {{ $log->user_role }}
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <p class="font-bold text-deep-forest">{{ ucfirst(str_replace('_', ' ', $log->action)) }}</p>
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
                            <div class="flex flex-col gap-1">
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
                                @if($log->is_suspicious)
                                <span class="inline-flex px-2 py-1 text-xs font-bold bg-burnt-tangerine bg-opacity-10 text-burnt-tangerine rounded">
                                    ⚠️ Suspicious
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.activity-logs.show', $log) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-sea-green text-white rounded-lg hover:bg-opacity-90 transition font-medium text-sm">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            No activity logs found. Try adjusting your filters.
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
