@php
    $collectionsNav = [
        ['label' => 'Overview',      'route' => 'department.master-collections.index',        'export' => false],
        ['label' => 'Households',    'route' => 'department.master-collections.households',    'export' => false],
        ['label' => 'Demographics',  'route' => 'department.master-collections.demographics',  'export' => false],
        ['label' => '⬇ Export JSON', 'route' => 'department.master-collections.export',        'export' => true],
    ];
@endphp
<div class="flex flex-wrap gap-2">
    @foreach($collectionsNav as $nav)
        @if(request()->routeIs($nav['route']))
            <span class="px-5 py-2.5 rounded-xl text-sm font-bold {{ $nav['export'] ? 'bg-amber-500' : 'bg-emerald-500' }} text-white shadow-lg {{ $nav['export'] ? 'shadow-amber-500/25' : 'shadow-emerald-500/25' }} cursor-default select-none">
                {{ $nav['label'] }}
            </span>
        @else
            <a href="{{ route($nav['route']) }}"
               class="px-5 py-2.5 rounded-xl text-sm font-bold {{ $nav['export'] ? 'bg-amber-50 border border-amber-200 text-amber-700 hover:bg-amber-100 hover:border-amber-300' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-slate-300 hover:text-slate-800' }} shadow-sm transition-all duration-150 active:scale-95">
                {{ $nav['label'] }}
            </a>
        @endif
    @endforeach
</div>
