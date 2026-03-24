@php
    $analyticsNav = [
        ['label' => 'Overview',           'route' => 'department.analytics.index'],
        ['label' => 'Barangay Breakdown', 'route' => 'department.analytics.barangay'],
        ['label' => 'Demographics',       'route' => 'department.analytics.demographics'],
        ['label' => 'Service Utilisation','route' => 'department.analytics.services'],
    ];
@endphp
<div class="flex flex-wrap gap-2">
    @foreach($analyticsNav as $nav)
        @if(request()->routeIs($nav['route']))
            <span class="px-5 py-2.5 rounded-xl text-sm font-bold bg-emerald-500 text-white shadow-lg shadow-emerald-500/25 cursor-default select-none">
                {{ $nav['label'] }}
            </span>
        @else
            <a href="{{ route($nav['route']) }}"
               class="px-5 py-2.5 rounded-xl text-sm font-bold bg-white border border-slate-200 text-slate-600 shadow-sm hover:bg-slate-50 hover:border-slate-300 hover:text-slate-800 transition-all duration-150 active:bg-slate-100">
                {{ $nav['label'] }}
            </a>
        @endif
    @endforeach
</div>
