@extends('layouts.department')
@section('title', 'Civil Registry')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'📄','title'=>'Civil Registry','subtitle'=>'Manage civil documents: birth, marriage, and death certificates.'])

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach([['📋','Pending',$stats['pending'],'pending'],['✅','Completed',$stats['completed'],'completed'],['❌','Rejected',$stats['rejected'],'rejected']] as [$icon,$label,$count,$status])
        <a href="{{ request()->fullUrlWithQuery(['status'=>$status]) }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md">
            <div class="text-2xl mb-1">{{ $icon }}</div>
            <div class="text-3xl font-bold">{{ $count }}</div>
            <div class="text-sm text-gray-500">{{ $label }}</div>
        </a>
        @endforeach
    </div>

    <div class="flex gap-3">
        <a href="{{ route('department.civil-registry.verification') }}" class="inline-flex items-center gap-2 text-sm bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">🪪 PhilSys Verifications</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-xs text-gray-500 border-b"><th class="pb-3">Reference #</th><th class="pb-3">Applicant</th><th class="pb-3">Type</th><th class="pb-3">Date</th><th class="pb-3">Status</th><th class="pb-3"></th></tr></thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($requests as $req)
                <tr class="hover:bg-purple-50">
                    <td class="py-2 font-mono text-xs">{{ $req->request_number }}</td>
                    <td class="py-2 font-medium">{{ $req->resident?->full_name }}</td>
                    <td class="py-2 text-xs">{{ $req->service?->name }}</td>
                    <td class="py-2 text-gray-400 text-xs">{{ $req->created_at->format('M d, Y') }}</td>
                    <td class="py-2"><span class="px-2 py-0.5 rounded-full text-xs {{ $req->status==='approved'?'bg-green-100 text-green-700':($req->status==='rejected'?'bg-red-100 text-red-700':'bg-yellow-100 text-yellow-700') }}">{{ ucfirst($req->status) }}</span></td>
                    <td class="py-2 text-right"><a href="{{ route('department.civil-registry.show', $req) }}" class="text-purple-600 text-xs hover:underline">View</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $requests->links() }}</div>
    </div>
</div>
@endsection
