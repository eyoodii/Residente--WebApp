@extends('layouts.department')
@section('title', 'Blotter Entry')
@section('content')
<div class="p-8 max-w-3xl mx-auto space-y-6">
    @include('department.components._module-header', ['icon'=>'📋','title'=>'Blotter Entry #'.$blotter->id,'subtitle'=>$blotter->created_at->format('F d, Y H:i')])

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div><dt class="text-xs text-gray-500 mb-1">Type</dt><dd><span class="px-2 py-0.5 bg-gray-100 rounded-full text-xs">{{ $blotter->action }}</span></dd></div>
            <div><dt class="text-xs text-gray-500 mb-1">Status</dt><dd><span class="px-2 py-0.5 rounded-full text-xs {{ ($blotter->metadata['resolved'] ?? false) ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ ($blotter->metadata['resolved'] ?? false) ? 'Resolved' : 'Open' }}</span></dd></div>
            <div class="col-span-2"><dt class="text-xs text-gray-500 mb-1">Description</dt><dd class="text-gray-800">{{ $blotter->description ?? $blotter->new_value ?? '—' }}</dd></div>
            <div><dt class="text-xs text-gray-500 mb-1">Logged by</dt><dd>{{ $blotter->resident?->full_name ?? 'SEPD Staff' }}</dd></div>
            <div><dt class="text-xs text-gray-500 mb-1">Date Logged</dt><dd>{{ $blotter->created_at->format('M d, Y H:i') }}</dd></div>
        </dl>
    </div>

    @if(!($blotter->metadata['resolved'] ?? false))
    <form action="{{ route('department.blotter.resolve', $blotter) }}" method="POST" class="flex justify-end">
        @csrf @method('PATCH')
        <button class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">✅ Mark as Resolved</button>
    </form>
    @else
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-sm text-green-700">
        ✅ This blotter entry has been resolved{{ isset($blotter->metadata['resolved_at']) ? ' on '.\Carbon\Carbon::parse($blotter->metadata['resolved_at'])->format('M d, Y') : '' }}.
    </div>
    @endif

    <a href="{{ route('department.blotter.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600">← Back to Blotter</a>
</div>
@endsection
