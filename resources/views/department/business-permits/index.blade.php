@extends('layouts.department')
@section('title', 'Business Permit Applications')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'🏪','title'=>'Business Permit Applications','subtitle'=>'Manage and process business permit requests.'])

    {{-- Tabs --}}
    <div class="flex gap-2 flex-wrap">
        @foreach(['pending'=>'Pending','under_review'=>'Under Review','approved'=>'Approved','rejected'=>'Rejected'] as $key=>$label)
        <a href="{{ request()->fullUrlWithQuery(['status'=>$key]) }}"
           class="px-4 py-2 rounded-full text-sm {{ request('status',$key==='pending'?'pending':'') === $key ? 'bg-indigo-600 text-white' : 'bg-white border text-gray-600' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 border-b"><th class="pb-3">Reference #</th><th class="pb-3">Applicant</th><th class="pb-3">Business Name</th><th class="pb-3">Submitted</th><th class="pb-3">Status</th><th class="pb-3"></th></tr></thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($requests as $req)
                <tr class="hover:bg-indigo-50">
                    <td class="py-2 font-mono text-xs">{{ $req->request_number }}</td>
                    <td class="py-2 font-medium">{{ $req->resident?->full_name }}</td>
                    <td class="py-2">{{ $req->notes ?? '–' }}</td>
                    <td class="py-2 text-gray-400 text-xs">{{ $req->created_at->format('M d, Y') }}</td>
                    <td class="py-2"><span class="px-2 py-0.5 rounded-full text-xs {{ $req->status==='approved'?'bg-green-100 text-green-700':($req->status==='rejected'?'bg-red-100 text-red-700':'bg-yellow-100 text-yellow-700') }}">{{ ucfirst($req->status) }}</span></td>
                    <td class="py-2 text-right"><a href="{{ route('department.business-permits.show', $req) }}" class="text-indigo-600 text-xs hover:underline">View</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $requests->links() }}</div>
    </div>
</div>
@endsection
