{{--
    Shared request listing table.
    Props: $requests (paginated collection), $routeBase (string, e.g. 'department.service-requests')
--}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-xs text-gray-500 border-b">
                <th class="pb-3">Request #</th>
                <th class="pb-3">Applicant</th>
                <th class="pb-3">Service</th>
                <th class="pb-3">Status</th>
                <th class="pb-3">Filed</th>
                <th class="pb-3">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($requests as $req)
            <tr class="hover:bg-gray-50">
                <td class="py-3 font-mono text-xs font-bold">{{ $req->request_number }}</td>
                <td class="py-3 font-medium">{{ $req->resident?->full_name ?? '—' }}</td>
                <td class="py-3 text-gray-600">{{ $req->service?->name ?? '—' }}</td>
                <td class="py-3">
                    <span class="px-2 py-1 rounded text-xs font-bold capitalize
                        {{ match($req->status) {
                            'pending'          => 'bg-yellow-100 text-yellow-700',
                            'in-progress'      => 'bg-blue-100 text-blue-700',
                            'completed'        => 'bg-green-100 text-green-700',
                            'rejected'         => 'bg-red-100 text-red-700',
                            'ready-for-pickup' => 'bg-purple-100 text-purple-700',
                            default            => 'bg-gray-100 text-gray-700',
                        } }}">
                        {{ str_replace('-', ' ', $req->status) }}
                    </span>
                </td>
                <td class="py-3 text-gray-400 text-xs">{{ $req->requested_at?->format('M d, Y') }}</td>
                <td class="py-3">
                    @php $showRoute = $routeBase . '.show'; @endphp
                    @if(\Illuminate\Support\Facades\Route::has($showRoute))
                    <a href="{{ route($showRoute, $req) }}" class="text-deep-forest text-xs font-semibold hover:underline">View →</a>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="py-8 text-center text-gray-400">No requests found.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if(method_exists($requests, 'links'))
    <div class="mt-4">{{ $requests->links() }}</div>
    @endif
</div>
