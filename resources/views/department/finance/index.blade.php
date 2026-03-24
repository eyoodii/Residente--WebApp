@extends('layouts.department')
@section('title', 'Financial Module')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'💰','title'=>'Financial Module','subtitle'=>'Revenue, collections, audit logs, and budget forecasting.'])

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('department.finance.index') }}" class="px-4 py-2 bg-deep-forest text-white rounded-xl text-sm font-semibold">Overview</a>
        <a href="{{ route('department.finance.transactions') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50">Transactions</a>
        @if(auth()->user()->department_role === 'ACCT')
        <a href="{{ route('department.finance.audit-log') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50">Audit Log</a>
        @endif
        @if(auth()->user()->department_role === 'BUDGT')
        <a href="{{ route('department.finance.forecast') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-50">Budget Forecast</a>
        @endif
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        @php $cards = [
            ['label'=>'Total Requests','value'=>$serviceStats['total'],'color'=>'blue'],
            ['label'=>'Pending','value'=>$serviceStats['pending'],'color'=>'yellow'],
            ['label'=>'Completed','value'=>$serviceStats['completed'],'color'=>'green'],
            ['label'=>'Rejected','value'=>$serviceStats['rejected'],'color'=>'red'],
        ]; @endphp
        @foreach($cards as $c)
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-400">{{ $c['label'] }}</p><p class="text-3xl font-black text-{{ $c['color'] }}-600">{{ number_format($c['value']) }}</p></div>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4">Recent Transactions</h3>
        @include('department.components._request-table', ['requests' => collect($recentTransactions), 'routeBase' => 'department.finance'])
    </div>
</div>
@endsection
