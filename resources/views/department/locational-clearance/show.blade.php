@extends('layouts.department')
@section('title', 'Locational Clearance')
@section('content')
<div class="p-8 max-w-4xl mx-auto space-y-6">
    @include('department.components._module-header', ['icon'=>'📍','title'=>'Locational Clearance #'.$serviceRequest->request_number,'subtitle'=>'Submitted: '.$serviceRequest->requested_at?->format('F d, Y')])

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Details --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            <h3 class="font-semibold text-gray-800">Applicant Information</h3>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <div><dt class="text-xs text-gray-500">Full Name</dt><dd class="font-medium">{{ $serviceRequest->resident?->full_name }}</dd></div>
                <div><dt class="text-xs text-gray-500">Barangay</dt><dd>{{ $serviceRequest->resident?->barangay }}</dd></div>
                <div><dt class="text-xs text-gray-500">Purok</dt><dd>{{ $serviceRequest->resident?->purok }}</dd></div>
                <div><dt class="text-xs text-gray-500">Service</dt><dd>{{ $serviceRequest->service?->name }}</dd></div>
                <div><dt class="text-xs text-gray-500">Status</dt><dd><span class="px-2 py-0.5 rounded-full text-xs {{ $serviceRequest->status==='completed'?'bg-green-100 text-green-700':($serviceRequest->status==='rejected'?'bg-red-100 text-red-700':'bg-yellow-100 text-yellow-700') }}">{{ ucfirst(str_replace('-',' ',$serviceRequest->status)) }}</span></dd></div>
                <div><dt class="text-xs text-gray-500">Date Filed</dt><dd>{{ $serviceRequest->requested_at?->format('M d, Y') }}</dd></div>
                <div class="col-span-2"><dt class="text-xs text-gray-500">Notes</dt><dd>{{ $serviceRequest->notes ?: '—' }}</dd></div>
            </dl>
        </div>

        {{-- Actions --}}
        @if($serviceRequest->status === 'pending')
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-3">
            <h3 class="font-semibold text-gray-800">Actions</h3>
            <form action="{{ route('department.locational-clearance.approve', $serviceRequest) }}" method="POST">
                @csrf @method('PATCH')
                <textarea name="notes" rows="2" placeholder="Notes (optional)" class="w-full border rounded-xl p-2 text-sm mb-2"></textarea>
                <button class="w-full bg-green-600 text-white text-sm py-2 rounded-lg hover:bg-green-700">✅ Approve</button>
            </form>
            <form action="{{ route('department.locational-clearance.reject', $serviceRequest) }}" method="POST">
                @csrf @method('PATCH')
                <textarea name="reason" rows="2" placeholder="Rejection reason (required)" class="w-full border rounded-xl p-2 text-sm mb-2" required></textarea>
                <button class="w-full bg-red-600 text-white text-sm py-2 rounded-lg hover:bg-red-700">❌ Reject</button>
            </form>
        </div>
        @endif
    </div>

    <a href="{{ route('department.locational-clearance.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600">← Back to Locational Clearances</a>
</div>
@endsection
