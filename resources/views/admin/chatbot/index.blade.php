@extends('layouts.admin')

@section('title', 'Chatbot — Knowledge Base')
@section('subtitle', 'Manage intents, unanswered queries and handoff queue')

@section('content')
<div class="px-7 py-6 space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">Knowledge Base, Unanswered Queries & Handoff Queue</p>
        <a href="{{ route('admin.chatbot.create') }}"
           class="bg-deep-forest text-white text-sm font-semibold px-4 py-2 rounded-lg hover:opacity-90 transition flex items-center gap-2 shadow-sm">
            ＋ New Intent
        </a>
    </div>

    {{-- Stat cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        @php
        $cards = [
            ['label'=>'Total Intents',    'val'=>$stats['total'],      'color'=>'bg-blue-600'],
            ['label'=>'Active',           'val'=>$stats['active'],     'color'=>'bg-emerald-600'],
            ['label'=>'Stale (90+ days)', 'val'=>$stats['stale'],      'color'=>'bg-amber-500'],
            ['label'=>'Unreviewed AI',    'val'=>$stats['unanswered'], 'color'=>'bg-red-500', 'link'=>route('admin.chatbot.unanswered')],
            ['label'=>'Pending Handoffs', 'val'=>$stats['handoffs'],   'color'=>'bg-purple-600', 'link'=>route('admin.chatbot.handoffs')],
        ];
        @endphp
        @foreach($cards as $card)
        <a href="{{ $card['link'] ?? '#' }}" class="block {{ $card['color'] }} rounded-xl p-4 text-white hover:opacity-90 transition shadow-sm">
            <p class="text-3xl font-black">{{ $card['val'] }}</p>
            <p class="text-xs opacity-80 mt-0.5">{{ $card['label'] }}</p>
        </a>
        @endforeach
    </div>

    {{-- Nav tabs --}}
    <div class="flex gap-1.5 bg-white rounded-xl p-1.5 shadow-sm border border-slate-200 w-fit">
        <a href="{{ route('admin.chatbot.index') }}"
           class="px-4 py-2 text-sm font-semibold rounded-lg transition
           {{ request()->routeIs('admin.chatbot.index') ? 'bg-deep-forest text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
            Knowledge Base
        </a>
        <a href="{{ route('admin.chatbot.unanswered') }}"
           class="flex items-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-lg transition
           {{ request()->routeIs('admin.chatbot.unanswered') ? 'bg-deep-forest text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
            Unanswered / AI Audit
            @if($stats['unanswered'] > 0)
                <span class="bg-red-500 text-white text-xs font-bold rounded-full px-1.5 py-0.5">{{ $stats['unanswered'] }}</span>
            @endif
        </a>
        <a href="{{ route('admin.chatbot.handoffs') }}"
           class="flex items-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-lg transition
           {{ request()->routeIs('admin.chatbot.handoffs') ? 'bg-deep-forest text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
            Handoff Queue
            @if($stats['handoffs'] > 0)
                <span class="bg-purple-500 text-white text-xs font-bold rounded-full px-1.5 py-0.5">{{ $stats['handoffs'] }}</span>
            @endif
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg px-4 py-3">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Search --}}
    <form method="GET" class="flex gap-2">
        <input name="search" value="{{ request('search') }}" type="text"
               placeholder="Search intent or category…"
               class="flex-1 bg-white border border-slate-300 text-slate-800 text-sm rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-deep-forest/30 placeholder-slate-400 shadow-sm">
        <button type="submit" class="bg-deep-forest text-white text-sm font-semibold px-4 py-2 rounded-lg hover:opacity-90 transition shadow-sm">
            Search
        </button>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 text-slate-500 text-xs uppercase">
                    <th class="text-left px-4 py-3">Intent</th>
                    <th class="text-left px-4 py-3">Category</th>
                    <th class="text-left px-4 py-3">Type</th>
                    <th class="text-center px-4 py-3">Hits</th>
                    <th class="text-center px-4 py-3">Status</th>
                    <th class="text-left px-4 py-3">Last Verified</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($knowledges as $k)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-4 py-3 font-mono text-xs text-emerald-700 font-semibold">{{ $k->intent_name }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $k->category }}</td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium
                            {{ $k->response_type === 'guided_form' ? 'bg-blue-100 text-blue-700' : ($k->response_type === 'external_link' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                            {{ $k->response_type }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center text-slate-600">{{ number_format($k->times_matched) }}</td>
                    <td class="px-4 py-3 text-center">
                        <form method="POST" action="{{ route('admin.chatbot.toggle', $k) }}" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs px-2 py-0.5 rounded-full font-medium transition
                                {{ $k->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-red-100 text-red-600 hover:bg-red-200' }}">
                                {{ $k->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-500">
                        @if($k->last_verified_at)
                            @if($k->last_verified_at->lt(now()->subDays(90)))
                                <span class="text-amber-600">⚠️ {{ $k->last_verified_at->diffForHumans() }}</span>
                            @else
                                {{ $k->last_verified_at->diffForHumans() }}
                            @endif
                        @else
                            <span class="text-red-500">Never</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 flex items-center gap-2 justify-end">
                        <a href="{{ route('admin.chatbot.edit', $k) }}"
                           class="text-xs text-blue-600 hover:text-blue-800 transition font-medium">Edit</a>
                        <form method="POST" action="{{ route('admin.chatbot.destroy', $k) }}"
                              onsubmit="return confirm('Delete this intent?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-500 hover:text-red-700 transition font-medium">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-slate-400">No knowledge entries found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $knowledges->appends(request()->query())->links() }}
</div>
@endsection
