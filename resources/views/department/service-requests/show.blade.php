@extends('layouts.department')
@section('title', 'Service Request Detail')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'📄','title'=>'Request #' . $serviceRequest->request_number,'subtitle'=>$serviceRequest->service?->name ?? 'Service Request'])
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="text-xs text-gray-400">Applicant</span><p class="font-bold">{{ $serviceRequest->resident?->full_name ?? '—' }}</p></div>
                <div><span class="text-xs text-gray-400">Service</span><p>{{ $serviceRequest->service?->name ?? '—' }}</p></div>
                <div><span class="text-xs text-gray-400">Status</span><p><span class="px-2 py-1 rounded text-xs font-bold bg-blue-100 text-blue-700 capitalize">{{ str_replace('-', ' ', $serviceRequest->status) }}</span></p></div>
                <div><span class="text-xs text-gray-400">Date Filed</span><p>{{ $serviceRequest->requested_at?->format('M d, Y') }}</p></div>
                <div class="col-span-2"><span class="text-xs text-gray-400">Notes</span><p class="mt-1">{{ $serviceRequest->notes ?: '—' }}</p></div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-3">
            <h3 class="font-bold text-gray-800">Actions</h3>
            @if($serviceRequest->status === 'pending')
            <form method="POST" action="{{ route('department.service-requests.approve', $serviceRequest) }}">
                @csrf @method('PATCH')
                <textarea name="notes" rows="2" placeholder="Notes (optional)" class="w-full border rounded-xl p-2 text-sm mb-2"></textarea>
                <button class="w-full px-4 py-2 bg-green-600 text-white rounded-xl text-sm font-semibold">✅ Approve</button>
            </form>
            <form method="POST" action="{{ route('department.service-requests.reject', $serviceRequest) }}">
                @csrf @method('PATCH')
                <textarea name="reason" rows="2" placeholder="Rejection reason (required)" class="w-full border rounded-xl p-2 text-sm mb-2" required></textarea>
                <button class="w-full px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-semibold">❌ Reject</button>
            </form>
            @elseif($serviceRequest->status === 'in-progress')
            <form method="POST" action="{{ route('department.service-requests.ready', $serviceRequest) }}">
                @csrf @method('PATCH')
                <button class="w-full px-4 py-2 bg-purple-600 text-white rounded-xl text-sm font-semibold">📦 Mark Ready for Pickup</button>
            </form>
            @else
            <p class="text-sm text-gray-400">No actions available for this status.</p>
            @endif
        </div>
    </div>
    <a href="{{ route('department.service-requests.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-deep-forest">← Back to Queue</a>
</div>
@endsection
