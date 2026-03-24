@extends('layouts.department')
@section('title', 'PhilSys Verifications')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'🪪','title'=>'PhilSys Verification Dashboard','subtitle'=>'Residents who have undergone PhilSys identity verification.'])

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 border-b"><th class="pb-3">Resident</th><th class="pb-3">PhilSys Transaction ID</th><th class="pb-3">Verified At</th><th class="pb-3">Status</th></tr></thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($verifications as $v)
                <tr class="hover:bg-purple-50">
                    <td class="py-2 font-medium">{{ $v->full_name }}</td>
                    <td class="py-2 font-mono text-xs">{{ $v->philsys_transaction_id ?? '—' }}</td>
                    <td class="py-2 text-gray-400 text-xs">{{ $v->philsys_verified_at?->format('M d, Y H:i') ?? '—' }}</td>
                    <td class="py-2"><span class="px-2 py-0.5 rounded-full text-xs {{ $v->philsys_verified_at ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ $v->philsys_verified_at ? 'Verified' : 'Pending' }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $verifications->links() }}</div>
    </div>
    <a href="{{ route('department.civil-registry.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600">← Back to Civil Registry</a>
</div>
@endsection
