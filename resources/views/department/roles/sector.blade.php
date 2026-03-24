@extends('layouts.department')

@section('title', match($user->department_role) {
    'AGRI'  => 'Agriculture & Livelihood Dashboard',
    'BPLO'  => 'Business Permits & Licensing',
    'REGST' => 'Civil Registry Dashboard',
    'SEPD'  => 'Security & Enforcement Dashboard',
    'SBSEC' => 'Sangguniang Bayan Information Portal',
    default => 'Sector Dashboard',
})
@section('subtitle', $config['department'] ?? 'Sector Office')

@section('content')
<div class="p-8 space-y-8">

    {{-- Welcome Banner --}}
    @php
        $bannerEmoji = match($user->department_role) {
            'AGRI'  => '🌾', 'BPLO' => '🏢', 'REGST' => '📜',
            'SEPD'  => '🚓', 'SBSEC' => '📋', default => '🏛️',
        };
    @endphp
    <div class="bg-gradient-to-r from-deep-forest to-sea-green text-white rounded-2xl p-7 shadow-xl flex items-center justify-between">
        <div>
            <p class="text-golden-glow text-xs font-bold uppercase tracking-widest mb-1">{{ $config['department'] ?? '' }}</p>
            <h2 class="text-3xl font-extrabold">{{ $user->department_label }}</h2>
            <p class="text-gray-200 mt-1 text-sm max-w-xl">{{ $config['description'] }}</p>
        </div>
        <div class="text-5xl opacity-20 hidden lg:block">{{ $bannerEmoji }}</div>
    </div>

    {{-- ============================================================ --}}
    {{-- AGRI: AGRICULTURE & LIVELIHOOD --}}
    {{-- ============================================================ --}}
    @if($user->department_role === 'AGRI')

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-xl">🌾</div>
            <div>
                <p class="text-xs text-gray-400">Farmers / Fisherfolk</p>
                <p class="text-2xl font-black text-green-700">{{ number_format($agricultureFarmers) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-xl">🐠</div>
            <div>
                <p class="text-xs text-gray-400">Service Requests</p>
                <p class="text-2xl font-black text-blue-700">{{ number_format($serviceStats['total']) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-yellow-50 flex items-center justify-center text-xl">⏳</div>
            <div>
                <p class="text-xs text-gray-400">Pending</p>
                <p class="text-2xl font-black text-yellow-700">{{ number_format($serviceStats['pending']) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-xl">💡</div>
            <div>
                <p class="text-xs text-gray-400">Oplan CLEOPATRA</p>
                <p class="text-sm font-bold text-purple-700 mt-0.5">Active</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">🌿 Livelihood Sector Programs</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $programs = [
                    ['icon'=>'🌾','label'=>'Crops / Farming','key'=>'crops'],
                    ['icon'=>'🦐','label'=>'Aquaculture','key'=>'aquaculture'],
                    ['icon'=>'🐄','label'=>'Livestock','key'=>'livestock'],
                    ['icon'=>'🐠','label'=>'Capture Fisheries','key'=>'fisheries'],
                ];
            @endphp
            @foreach($programs as $prog)
            <div class="text-center bg-green-50 p-4 rounded-xl border border-green-100">
                <p class="text-3xl mb-2">{{ $prog['icon'] }}</p>
                <p class="text-sm font-semibold text-green-800">{{ $prog['label'] }}</p>
                <a href="{{ route('admin.residents.index') }}" class="mt-2 inline-block text-xs text-green-600 underline">View Residents →</a>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- BPLO: BUSINESS PERMITS --}}
    {{-- ============================================================ --}}
    @elseif($user->department_role === 'BPLO')

    <div class="grid grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
            <p class="text-xs text-gray-400 mb-1">Total Applications</p>
            <p class="text-4xl font-black text-deep-forest">{{ number_format($serviceStats['total']) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
            <p class="text-xs text-gray-400 mb-1">Pending Approval</p>
            <p class="text-4xl font-black text-yellow-600">{{ number_format($serviceStats['pending']) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
            <p class="text-xs text-gray-400 mb-1">Approved / Issued</p>
            <p class="text-4xl font-black text-green-600">{{ number_format($serviceStats['completed']) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">📋 Recent Business Applications</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-500 border-b border-gray-100">
                        <th class="pb-3 font-semibold">Applicant</th>
                        <th class="pb-3 font-semibold">Service</th>
                        <th class="pb-3 font-semibold">Filed</th>
                        <th class="pb-3 font-semibold">Status</th>
                        <th class="pb-3 font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($serviceRequests as $req)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2.5 text-gray-700">{{ $req->resident?->full_name ?? 'Unknown' }}</td>
                        <td class="py-2.5 text-gray-600 text-xs">{{ $req->service?->name ?? 'N/A' }}</td>
                        <td class="py-2.5 text-gray-400 text-xs whitespace-nowrap">{{ $req->created_at->format('M d, Y') }}</td>
                        <td class="py-2.5">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $req->status === 'completed' ? 'bg-green-100 text-green-700' :
                                   ($req->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                                   ($req->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700')) }}">
                                {{ ucfirst($req->status) }}
                            </span>
                        </td>
                        <td class="py-2.5">
                            <a href="{{ route('admin.services.index') }}" class="text-xs text-sea-green underline">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-6 text-center text-gray-400">No applications found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- REGST: CIVIL REGISTRY --}}
    {{-- ============================================================ --}}
    @elseif($user->department_role === 'REGST')

    <div class="grid grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
            <p class="text-xs text-gray-400 mb-1">Total Service Requests</p>
            <p class="text-4xl font-black text-deep-forest">{{ number_format($serviceStats['total']) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
            <p class="text-xs text-gray-400 mb-1">Pending Verification</p>
            <p class="text-4xl font-black text-yellow-600">{{ number_format($serviceStats['pending']) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-center">
            <p class="text-xs text-gray-400 mb-1">Unverified Residents</p>
            <p class="text-4xl font-black text-red-500">{{ number_format($unverifiedResidents->count()) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">🔍 Unverified Residents (Pending Identity Confirmation)</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-500 border-b border-gray-100">
                        <th class="pb-3 font-semibold">Name</th>
                        <th class="pb-3 font-semibold">Barangay</th>
                        <th class="pb-3 font-semibold">Registered</th>
                        <th class="pb-3 font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($unverifiedResidents as $res)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2.5 text-gray-800 font-medium">{{ $res->full_name }}</td>
                        <td class="py-2.5 text-gray-500 text-xs">{{ $res->barangay }}</td>
                        <td class="py-2.5 text-gray-400 text-xs">{{ $res->created_at->format('M d, Y') }}</td>
                        <td class="py-2.5">
                            <a href="{{ route('admin.verification.dashboard') }}" class="text-xs text-sea-green underline">Verify →</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-6 text-center text-gray-400">All residents are verified! ✅</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- SEPD: SECURITY & ENFORCEMENT --}}
    {{-- ============================================================ --}}
    @elseif($user->department_role === 'SEPD')

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">🚓 System Activity Log — Security Monitor</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-500 border-b border-gray-100">
                        <th class="pb-3 font-semibold">Time</th>
                        <th class="pb-3 font-semibold">User</th>
                        <th class="pb-3 font-semibold">Action</th>
                        <th class="pb-3 font-semibold">Description</th>
                        <th class="pb-3 font-semibold">Severity</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($securityLogs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2.5 text-gray-400 whitespace-nowrap text-xs">{{ $log->created_at->format('M d, H:i') }}</td>
                        <td class="py-2.5 text-gray-700 text-xs truncate max-w-[140px]">{{ $log->user_email }}</td>
                        <td class="py-2.5 font-mono text-xs text-gray-600">{{ $log->action }}</td>
                        <td class="py-2.5 text-gray-600 text-xs max-w-[200px] truncate">{{ $log->description }}</td>
                        <td class="py-2.5">
                            <span class="px-2 py-0.5 rounded-full text-xs
                                {{ $log->severity === 'critical' ? 'bg-red-100 text-red-700' :
                                   ($log->severity === 'warning' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700') }}">
                                {{ ucfirst($log->severity ?? 'info') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-6 text-center text-gray-400">No security logs found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- SBSEC: TRANSPARENCY BOARD --}}
    {{-- ============================================================ --}}
    @elseif($user->department_role === 'SBSEC')

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">📢 Publish LGU Announcement</h3>
        </div>
        <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-xl text-xs text-yellow-700 mb-4 flex items-center gap-2">
            <span>⚠️</span> Announcement publishing routes are being set up. Contact Super Admin to enable this feature.
        </div>
        <form method="POST" action="#" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Title *</label>
                    <input type="text" name="title" placeholder="e.g. Barangay Ordinance No. 2026-01"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sea-green focus:border-sea-green">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Type</label>
                    <select name="type" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sea-green">
                        <option value="ordinance">Ordinance</option>
                        <option value="resolution">Resolution</option>
                        <option value="memorandum">Memorandum</option>
                        <option value="notice">Public Notice</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Content *</label>
                <textarea name="content" rows="4" placeholder="Full text of the ordinance, resolution, or memorandum..."
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-sea-green resize-none"></textarea>
            </div>
            <input type="hidden" name="is_published" value="1">
            <input type="hidden" name="posted_at" value="{{ now() }}">
            <button type="submit"
                class="px-6 py-2.5 bg-deep-forest text-white font-bold rounded-xl hover:bg-sea-green transition-colors text-sm">
                📋 Publish to Transparency Board
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4">📋 Published Documents</h3>
        <div class="space-y-3">
            @forelse($announcements as $ann)
            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100 hover:bg-gray-100 transition">
                <span class="text-xl mt-0.5">📄</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800">{{ $ann->title }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Published {{ $ann->posted_at?->diffForHumans() ?? '' }}</p>
                </div>
                <span class="px-2 py-0.5 bg-deep-forest/10 text-deep-forest text-xs rounded-full">{{ ucfirst($ann->type ?? 'notice') }}</span>
            </div>
            @empty
            <p class="text-gray-400 text-sm text-center py-6">No documents published yet.</p>
            @endforelse
        </div>
    </div>

    @endif

</div>
@endsection
