@extends('layouts.admin')

@section('title', 'Chatbot — Unanswered Queries & AI Audit')
@section('subtitle', 'Every question the bot couldn\'t answer — and every Gemini-assisted response')

@section('content')
<div class="px-7 py-6 space-y-6">

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

    {{-- Filters + Bulk action --}}
    <div class="flex items-center gap-3 flex-wrap">
        <div class="flex gap-1.5 bg-white rounded-xl p-1.5 shadow-sm border border-slate-200">
            @foreach(['' => 'All', 'unreviewed' => 'Unreviewed', 'gemini' => 'Gemini AI'] as $val => $label)
            <a href="{{ request()->fullUrlWithQuery(['filter' => $val]) }}"
               class="text-xs px-3 py-1.5 rounded-lg font-semibold transition
               {{ request('filter', '') === $val ? 'bg-deep-forest text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>

        <form method="POST" action="{{ route('admin.chatbot.bulk-reviewed') }}" class="ml-auto">
            @csrf @method('PATCH')
            <button type="submit" onclick="return confirm('Mark all non-AI unreviewed queries as reviewed?')"
                    class="text-xs text-slate-500 hover:text-slate-700 border border-slate-300 rounded-lg px-3 py-1.5 transition bg-white shadow-sm">
                ✓ Mark all as reviewed
            </button>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 text-slate-500 text-xs uppercase">
                    <th class="text-left px-4 py-3 w-1/2">Original Message</th>
                    <th class="text-left px-4 py-3">Tier Reached</th>
                    <th class="text-center px-4 py-3">Gemini Used</th>
                    <th class="text-center px-4 py-3">Reviewed</th>
                    <th class="text-left px-4 py-3">Date</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($queries as $q)
                <tr class="hover:bg-slate-50 transition {{ !$q->reviewed_by_admin ? 'bg-amber-50/50' : '' }}">
                    <td class="px-4 py-3">
                        <p class="text-slate-700 text-sm">{{ Str::limit($q->original_message, 120) }}</p>
                        @if($q->gemini_response)
                        <details class="mt-1">
                            <summary class="text-xs text-amber-600 cursor-pointer hover:text-amber-700">Gemini response ▼</summary>
                            <div class="mt-1 p-2 bg-amber-50 border border-amber-200 rounded text-xs text-amber-800 whitespace-pre-wrap">{{ $q->gemini_response }}</div>
                        </details>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-xs font-mono px-2 py-0.5 rounded bg-slate-100 text-slate-600">
                            {{ $q->tier_reached }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($q->used_gemini)
                            <span class="text-amber-600 text-xs font-bold">🤖 Yes</span>
                        @else
                            <span class="text-slate-400 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($q->reviewed_by_admin)
                            <span class="text-emerald-600 text-xs">✅</span>
                        @else
                            <span class="text-red-500 text-xs font-bold">Pending</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-400">{{ $q->created_at->diffForHumans() }}</td>
                    <td class="px-4 py-3">
                        @unless($q->reviewed_by_admin)
                        <form method="POST" action="{{ route('admin.chatbot.mark-reviewed', $q) }}">
                            @csrf @method('PATCH')
                            <button class="text-xs text-emerald-600 hover:text-emerald-800 font-medium transition">
                                Mark reviewed
                            </button>
                        </form>
                        @endunless
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-slate-400">No unanswered queries.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $queries->links() }}
</div>
@endsection
