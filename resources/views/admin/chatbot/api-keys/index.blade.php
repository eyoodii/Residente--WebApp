@extends('layouts.admin')

@section('title', 'Chatbot — API Keys')
@section('subtitle', 'Manage API keys for external chatbot integrations')

@section('content')
<div class="px-7 py-6 space-y-6">

    {{-- Flash: newly created key (shown once) --}}
    @if(session('new_key'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
        <p class="text-emerald-700 font-bold text-sm mb-1">✅ API Key Created — Copy It Now!</p>
        <div class="flex items-center gap-2">
            <code id="newKeyDisplay" class="bg-white border border-emerald-200 text-emerald-800 px-3 py-2 rounded text-sm font-mono flex-1 select-all break-all">{{ session('new_key') }}</code>
            <button onclick="navigator.clipboard.writeText(document.getElementById('newKeyDisplay').textContent).then(()=>this.textContent='Copied!')"
                    class="bg-emerald-600 text-white text-xs font-bold px-3 py-2 rounded hover:bg-emerald-700 transition whitespace-nowrap">
                Copy
            </button>
        </div>
        <p class="text-xs text-emerald-600 mt-2">⚠️ This key will not be shown again. Store it securely.</p>
    </div>
    @endif

    @if(session('success') && !session('new_key'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3">
        <p class="text-emerald-700 text-sm">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Create new key --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <h2 class="text-base font-semibold text-slate-800 mb-3">Generate New API Key</h2>
        <form action="{{ route('admin.chatbot.api-keys.store') }}" method="POST" class="flex flex-wrap items-end gap-4">
            @csrf
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Key Name / Label</label>
                <input type="text" name="name" required placeholder="e.g. Mobile App, Kiosk"
                       class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm text-slate-800 placeholder-slate-400 focus:border-deep-forest focus:ring-1 focus:ring-deep-forest/30 focus:outline-none">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="w-48">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Expires At (optional)</label>
                <input type="date" name="expires_at" min="{{ now()->addDay()->format('Y-m-d') }}"
                       class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-sm text-slate-800 focus:border-deep-forest focus:ring-1 focus:ring-deep-forest/30 focus:outline-none">
            </div>
            <button type="submit"
                    class="bg-deep-forest text-white text-sm font-semibold px-5 py-2 rounded-lg hover:opacity-90 transition shadow-sm">
                Generate Key
            </button>
        </form>
    </div>

    {{-- Existing keys table --}}
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-500 uppercase text-xs border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Key Prefix</th>
                    <th class="px-4 py-3">Created By</th>
                    <th class="px-4 py-3">Last Used</th>
                    <th class="px-4 py-3">Expires</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($apiKeys as $key)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-4 py-3 text-slate-800 font-medium">{{ $key->name }}</td>
                    <td class="px-4 py-3 font-mono text-slate-500 text-xs">{{ $key->plain_key_prefix }}…</td>
                    <td class="px-4 py-3 text-slate-600">{{ $key->creator?->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $key->last_used_at?->diffForHumans() ?? 'Never' }}</td>
                    <td class="px-4 py-3 text-slate-500">
                        @if($key->expires_at)
                            <span @class(['text-red-500' => $key->isExpired()])>
                                {{ $key->expires_at->format('M d, Y') }}
                            </span>
                        @else
                            <span class="text-slate-400">Never</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($key->is_active && !$key->isExpired())
                            <span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded text-xs font-semibold">Active</span>
                        @elseif($key->isExpired())
                            <span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded text-xs font-semibold">Expired</span>
                        @else
                            <span class="bg-red-100 text-red-600 px-2 py-0.5 rounded text-xs font-semibold">Revoked</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        @if($key->is_active)
                        <form action="{{ route('admin.chatbot.api-keys.revoke', $key) }}" method="POST" class="inline"
                              onsubmit="return confirm('Revoke this API key? Any app using it will lose access immediately.')">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-semibold transition">
                                Revoke
                            </button>
                        </form>
                        @else
                            <span class="text-slate-300 text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-slate-400">No API keys yet. Generate one above.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($apiKeys->hasPages())
        <div class="px-4 py-3 border-t border-slate-200">
            {{ $apiKeys->links() }}
        </div>
        @endif
    </div>

    {{-- API Documentation Quick Reference --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <h2 class="text-base font-semibold text-slate-800 mb-3">Quick API Reference</h2>
        <div class="text-sm text-slate-600 space-y-3">
            <p>Base URL: <code class="bg-slate-100 border border-slate-200 px-2 py-0.5 rounded text-deep-forest font-mono text-xs">{{ url('/api/v1/chatbot') }}</code></p>
            <p>Auth: <code class="bg-slate-100 border border-slate-200 px-2 py-0.5 rounded text-slate-700 font-mono text-xs">Authorization: Bearer &lt;your-key&gt;</code></p>

            <div class="mt-4 space-y-2">
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 font-mono text-xs">
                    <span class="text-emerald-700 font-bold">POST</span> <span class="text-slate-800">/api/v1/chatbot/chat</span><br>
                    <span class="text-slate-400">Body:</span> <span class="text-slate-600">{"message": "...", "session_id": "uuid-v4", "transcript": [...]}</span>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 font-mono text-xs">
                    <span class="text-emerald-700 font-bold">POST</span> <span class="text-slate-800">/api/v1/chatbot/handoff</span><br>
                    <span class="text-slate-400">Body:</span> <span class="text-slate-600">{"concern": "...", "session_id": "uuid-v4", "transcript": [...]}</span>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 font-mono text-xs">
                    <span class="text-emerald-700 font-bold">POST</span> <span class="text-slate-800">/api/v1/chatbot/quick-action</span><br>
                    <span class="text-slate-400">Body:</span> <span class="text-slate-600">{"action": "track|permits|business_permit|talk_to_staff", "session_id": "uuid-v4"}</span>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 font-mono text-xs">
                    <span class="text-blue-600 font-bold">GET</span> <span class="text-slate-800">/api/v1/chatbot/session/{"{session_id}"}</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
