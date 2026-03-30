@extends('layouts.admin')

@section('title', 'Chatbot — Handoff Queue')
@section('subtitle', 'Citizens who requested to speak with staff')

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

    {{-- Status filter --}}
    <div class="flex gap-1.5 bg-white rounded-xl p-1.5 shadow-sm border border-slate-200 w-fit">
        @foreach(['' => 'All', 'pending' => 'Pending', 'in_progress' => 'In Progress', 'resolved' => 'Resolved'] as $val => $label)
        <a href="{{ request()->fullUrlWithQuery(['status' => $val]) }}"
           class="text-xs px-3 py-1.5 rounded-lg font-semibold transition
           {{ request('status', '') === $val ? 'bg-deep-forest text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- Cards --}}
    <div class="space-y-4">
        @forelse($handoffs as $h)
        <div class="bg-white border border-slate-200 rounded-xl p-5 space-y-3 shadow-sm
            {{ $h->status === 'pending' ? 'border-l-4 border-l-amber-400' : ($h->status === 'in_progress' ? 'border-l-4 border-l-blue-400' : '') }}">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-mono text-slate-400">{{ $h->session_id }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium
                            {{ $h->status === 'pending' ? 'bg-amber-100 text-amber-700' : ($h->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700') }}">
                            {{ str_replace('_', ' ', ucfirst($h->status)) }}
                        </span>
                        <span class="text-xs text-slate-400">{{ $h->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-slate-800 text-sm font-medium">{{ Str::limit($h->citizen_concern, 200) }}</p>
                    @if($h->assigned_to)
                        <p class="text-xs text-emerald-700 mt-1">Assigned to: {{ $h->assigned_to }}</p>
                    @endif
                </div>

                <form method="POST" action="{{ route('admin.chatbot.update-handoff', $h) }}" class="flex-shrink-0">
                    @csrf @method('PATCH')
                    <div class="flex gap-2 items-end">
                        <div>
                            <label class="text-xs text-slate-500 block mb-1">Assign to</label>
                            <input name="assigned_to" type="text" value="{{ $h->assigned_to }}"
                                   placeholder="Department / Staff"
                                   class="bg-white border border-slate-300 text-slate-800 text-xs rounded px-2 py-1.5 focus:outline-none focus:ring-1 focus:ring-deep-forest/30 w-36">
                        </div>
                        <div>
                            <label class="text-xs text-slate-500 block mb-1">Status</label>
                            <select name="status" class="bg-white border border-slate-300 text-slate-800 text-xs rounded px-2 py-1.5 focus:outline-none focus:ring-1 focus:ring-deep-forest/30">
                                @foreach(['pending', 'in_progress', 'resolved'] as $s)
                                <option value="{{ $s }}" {{ $h->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="bg-deep-forest text-white text-xs font-semibold px-3 py-1.5 rounded hover:opacity-90 transition shadow-sm">
                            Update
                        </button>
                    </div>
                </form>
            </div>

            {{-- Transcript toggle --}}
            <details class="bg-slate-50 border border-slate-200 rounded-lg overflow-hidden">
                <summary class="px-3 py-2 text-xs text-slate-500 cursor-pointer hover:text-slate-700">
                    View conversation transcript ▼
                </summary>
                <div class="px-3 py-2 text-xs text-slate-700 font-mono whitespace-pre-wrap max-h-48 overflow-y-auto border-t border-slate-100">
                    {{ $h->conversation_transcript }}
                </div>
            </details>
        </div>
        @empty
        <div class="text-center text-slate-400 py-12 bg-white rounded-xl border border-slate-200 shadow-sm">
            No handoffs in this queue.
        </div>
        @endforelse
    </div>

    {{ $handoffs->links() }}
</div>
@endsection
