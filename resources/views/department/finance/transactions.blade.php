@extends('layouts.department')
@section('title', 'Transactions')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'🧾','title'=>'Transaction Ledger','subtitle'=>'All completed and in-progress service request transactions.'])
    @include('department.components._request-table', ['requests' => $transactions, 'routeBase' => 'department.finance'])
</div>
@endsection
