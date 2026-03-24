@extends('layouts.department')
@section('title', 'Fisheries')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'🎣','title'=>'Fisheries','subtitle'=>'Residents classified under fisheries livelihood.'])
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 border-b"><th class="pb-3">Name</th><th class="pb-3">Barangay</th><th class="pb-3">Purok</th><th class="pb-3">Age</th><th class="pb-3">Sex</th></tr></thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($residents as $r)
                <tr class="hover:bg-blue-50">
                    <td class="py-2 font-medium">{{ $r->full_name }}</td>
                    <td class="py-2">{{ $r->barangay }}</td>
                    <td class="py-2">{{ $r->purok }}</td>
                    <td class="py-2">{{ $r->age ?? '—' }}</td>
                    <td class="py-2">{{ $r->gender ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $residents->links() }}</div>
    </div>
    <a href="{{ route('department.livelihood.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600">← Back</a>
</div>
@endsection
