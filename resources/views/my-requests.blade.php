@extends('layouts.citizen')

@section('title', 'My Service Requests | RESIDENTE App')

@section('page-title', 'My Service Requests')
@section('page-subtitle', 'Track all your service requests and their progress')

@section('header-actions')
    <a href="{{ route('services.index') }}" class="bg-tiger-orange hover:bg-burnt-tangerine text-white px-4 py-2 rounded-lg font-bold text-sm shadow-sm transition whitespace-nowrap">
        + New Request
    </a>
@endsection

@section('content')
    <div class="p-4 sm:p-7">
        @if($requests->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm p-6 sm:p-12 text-center">
            <div class="text-6xl mb-4">📋</div>
            <h2 class="text-xl sm:text-2xl font-bold text-deep-forest mb-2">No Service Requests Yet</h2>
            <p class="text-gray-600 mb-6">Start by requesting a service from our directory</p>
            <a href="{{ route('services.index') }}" class="inline-block bg-tiger-orange hover:bg-burnt-tangerine text-white px-6 py-3 rounded-lg font-bold transition">
                Browse Services
            </a>
        </div>
        @else
        <!-- Requests List -->
        <div class="space-y-4">
            @foreach($requests as $request)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden">
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <h3 class="text-base sm:text-lg font-bold text-deep-forest">{{ $request->service->name }}</h3>
                                <span class="px-3 py-1 {{ $request->status_badge_color }} rounded-full font-bold text-xs uppercase flex-shrink-0">
                                    {{ $request->status }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-1">
                                <span class="font-semibold">Request #:</span> {{ $request->request_number }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <span class="font-semibold">Department:</span> {{ $request->service->department }}
                            </p>
                        </div>
                        <div class="sm:text-right flex-shrink-0">
                            <p class="text-xs text-gray-500 mb-1">Requested on</p>
                            <p class="text-sm font-semibold text-gray-700">{{ $request->requested_at->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $request->requested_at->format('h:i A') }}</p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-semibold text-gray-600">Progress</span>
                            <span class="text-xs font-semibold text-tiger-orange">
                                Step {{ $request->current_step }} of {{ $request->service->steps->count() }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-tiger-orange h-2 rounded-full transition-all"
                                 style="width: {{ ($request->current_step / $request->service->steps->count()) * 100 }}%">
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3">
                        <a href="{{ route('service-request.show', $request->request_number) }}"
                           class="flex-1 bg-sea-green hover:bg-opacity-90 text-white px-4 py-2 rounded-lg font-semibold text-sm text-center transition">
                            View Details & Timeline
                        </a>
                        @if($request->status == 'completed')
                        <button class="bg-golden-glow hover:bg-opacity-90 text-deep-forest px-4 py-2 rounded-lg font-semibold text-sm transition">
                            Download
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $requests->links() }}
        </div>
        @endif
    </div>
@endsection
