@extends('layouts.department')
@section('title', 'Blotter Records')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'📋','title'=>'Blotter / Incident Records','subtitle'=>'Logged security incidents, violations, and complaints.'])

    <div class="flex justify-between items-center">
        <div class="flex gap-2 flex-wrap">
            @foreach(['all'=>'All','open'=>'Open','resolved'=>'Resolved'] as $key=>$label)
            <a href="{{ request()->fullUrlWithQuery(['status'=>$key]) }}"
               class="px-4 py-2 rounded-full text-sm {{ request('status','all') === $key ? 'bg-red-600 text-white' : 'bg-white border text-gray-600' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>
        <a href="{{ route('department.blotter.create') }}" class="inline-flex items-center gap-2 text-sm bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">+ New Entry</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 border-b"><th class="pb-3">Ref #</th><th class="pb-3">Subject</th><th class="pb-3">Type</th><th class="pb-3">Date</th><th class="pb-3">Status</th><th class="pb-3"></th></tr></thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($blotters as $b)
                <tr class="hover:bg-red-50">
                    <td class="py-2 font-mono text-xs">{{ $b->id }}</td>
                    <td class="py-2 font-medium">{{ $b->description ?? Str::limit($b->new_value ?? '—', 40) }}</td>
                    <td class="py-2 text-xs"><span class="px-2 py-0.5 bg-gray-100 rounded-full">{{ $b->action }}</span></td>
                    <td class="py-2 text-gray-400 text-xs">{{ $b->created_at->format('M d, Y') }}</td>
                    <td class="py-2"><span class="px-2 py-0.5 rounded-full text-xs {{ ($b->metadata['resolved'] ?? false) ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ ($b->metadata['resolved'] ?? false) ? 'Resolved' : 'Open' }}</span></td>
                    <td class="py-2 text-right"><a href="{{ route('department.blotter.show', $b) }}" class="text-red-600 text-xs hover:underline">View</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $blotters->links() }}</div>
    </div>
</div>
@endsection
