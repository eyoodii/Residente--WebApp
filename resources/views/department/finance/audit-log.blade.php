@extends('layouts.department')
@section('title', 'Audit Log')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'🔍','title'=>'Internal Audit Log','subtitle'=>'Full audit trail for internal financial compliance review.'])
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 border-b"><th class="pb-3">Time</th><th class="pb-3">User</th><th class="pb-3">Action</th><th class="pb-3">Description</th></tr></thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($logs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 text-xs text-gray-400 whitespace-nowrap">{{ $log->created_at->format('M d, H:i') }}</td>
                    <td class="py-2 font-medium">{{ $log->user?->full_name ?? 'System' }}</td>
                    <td class="py-2 font-mono text-xs">{{ $log->action }}</td>
                    <td class="py-2 text-gray-600 max-w-xs truncate">{{ $log->description }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $logs->links() }}</div>
    </div>
</div>
@endsection
