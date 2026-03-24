@extends('layouts.department')
@section('title', 'Service Requests')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'📬','title'=>'Service Request Queue','subtitle'=>'Process and manage incoming citizen service requests for your department.'])
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        @php $cards = [
            ['label'=>'Pending','value'=>$stats['pending'],'color'=>'yellow'],
            ['label'=>'In Progress','value'=>$stats['in_progress'],'color'=>'blue'],
            ['label'=>'Ready for Pickup','value'=>$stats['ready_for_pickup'],'color'=>'purple'],
            ['label'=>'Completed','value'=>$stats['completed'],'color'=>'green'],
        ]; @endphp
        @foreach($cards as $c)
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-400">{{ $c['label'] }}</p><p class="text-3xl font-black text-{{ $c['color'] }}-600">{{ number_format($c['value']) }}</p></div>
        @endforeach
    </div>
    @include('department.components._request-table', ['requests' => $requests, 'routeBase' => 'department.service-requests'])
</div>
@endsection
