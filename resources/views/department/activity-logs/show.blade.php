@extends('layouts.department')
@section('title', 'Activity Log Detail')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'🔍','title'=>'Log Entry #' . $activityLog->id,'subtitle'=>'Full detail of this audit log entry.'])
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><span class="text-gray-400 text-xs">Action</span><p class="font-mono font-bold">{{ $activityLog->action }}</p></div>
            <div><span class="text-gray-400 text-xs">Severity</span><p class="font-bold capitalize">{{ $activityLog->severity ?? 'info' }}</p></div>
            <div><span class="text-gray-400 text-xs">User</span><p>{{ $activityLog->user?->full_name ?? 'System' }}</p></div>
            <div><span class="text-gray-400 text-xs">IP Address</span><p class="font-mono">{{ $activityLog->ip_address ?? '—' }}</p></div>
            <div class="col-span-2"><span class="text-gray-400 text-xs">Description</span><p class="mt-1">{{ $activityLog->description }}</p></div>
            <div><span class="text-gray-400 text-xs">Timestamp</span><p>{{ $activityLog->created_at->format('F d, Y H:i:s') }}</p></div>
        </div>
    </div>
    <a href="{{ route('department.activity-logs.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-deep-forest">← Back to Logs</a>
</div>
@endsection
