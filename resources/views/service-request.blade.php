@extends('layouts.citizen')

@section('title', 'Service Request #' . $serviceRequest->request_number . ' | RESIDENTE App')

@section('page-title', 'Request #' . $serviceRequest->request_number)
@section('page-subtitle', $serviceRequest->service->name)

@section('header-actions')
    <span class="px-3 py-1 {{ $serviceRequest->status_badge_color }} rounded-full font-bold text-xs uppercase">
        {{ $serviceRequest->status }}
    </span>
@endsection

@section('content')
    <div class="p-4 sm:p-7">
        <!-- Request Overview -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <p class="text-xs text-gray-600 uppercase font-semibold mb-2">Request Number</p>
                    <p class="text-lg font-bold text-deep-forest">{{ $serviceRequest->request_number }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 uppercase font-semibold mb-2">Service</p>
                    <p class="text-sm font-bold text-deep-forest">{{ $serviceRequest->service->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 uppercase font-semibold mb-2">Requested On</p>
                    <p class="text-sm font-bold text-deep-forest">{{ $serviceRequest->requested_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 uppercase font-semibold mb-2">Current Step</p>
                    <p class="text-sm font-bold text-tiger-orange">Step {{ $serviceRequest->current_step }} of {{ $serviceRequest->service->steps->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Progress Timeline -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-xl font-bold text-deep-forest mb-6 flex items-center gap-2">
                <span class="text-2xl">📍</span> Progress Timeline
            </h3>

            <div class="relative">
                @foreach($serviceRequest->service->steps as $step)
                @php
                    $isComplete = $step->step_number < $serviceRequest->current_step;
                    $isCurrent = $step->step_number == $serviceRequest->current_step;
                    $isPending = $step->step_number > $serviceRequest->current_step;
                @endphp

                <div class="flex gap-4 mb-4 relative {{ $isPending ? 'opacity-50' : '' }}">
                    @if(!$loop->last)
                    <div class="absolute left-5 top-10 bottom-[-1rem] w-0.5 {{ $isComplete || $isCurrent ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                    @endif

                    <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-sm z-10 text-sm
                        {{ $isComplete ? 'bg-green-500 text-white' : '' }}
                        {{ $isCurrent ? 'bg-tiger-orange text-white animate-pulse' : '' }}
                        {{ $isPending ? 'bg-gray-200 text-gray-500' : '' }}">
                        @if($isComplete)
                            ✓
                        @else
                            {{ $step->step_number }}
                        @endif
                    </div>

                    <div class="flex-1 rounded-lg p-3 {{ $isCurrent ? 'bg-orange-50 border-2 border-tiger-orange' : 'bg-gray-50' }}">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded-full mb-1
                                    {{ $step->is_client_step ? 'bg-tiger-orange text-white' : 'bg-sea-green text-white' }}">
                                    {{ $step->is_client_step ? '👤 Client Step' : '🏛️ Agency Action' }}
                                </span>

                                @if($isCurrent)
                                <span class="inline-block px-2 py-0.5 bg-yellow-400 text-yellow-900 text-[10px] font-bold rounded-full mb-1 ml-1">
                                    ⭐ Current Step
                                </span>
                                @endif

                                <h4 class="font-bold text-deep-forest text-sm">
                                    {{ $step->is_client_step ? 'Your Action Required' : 'Agency Processing' }}
                                </h4>
                            </div>
                            <div class="text-right">
                                @if($isComplete)
                                <span class="text-green-600 font-bold text-xs">✓ Completed</span>
                                @elseif($isCurrent)
                                <span class="text-tiger-orange font-bold text-xs">● In Progress</span>
                                @else
                                <span class="text-gray-400 font-bold text-xs">○ Pending</span>
                                @endif

                                @if($step->processing_time_minutes)
                                <p class="text-xs text-gray-600 mt-1">⏱️ {{ $step->processing_time_minutes }} min</p>
                                @endif
                            </div>
                        </div>

                        <p class="text-gray-700 mb-2 text-sm">{{ $step->description }}</p>

                        <div class="flex flex-wrap gap-3 text-xs text-gray-500">
                            @if($step->responsible_person)
                            <span class="flex items-center gap-1">
                                <span class="font-semibold">👨‍💼 Responsible:</span> {{ $step->responsible_person }}
                            </span>
                            @endif
                            @if($step->fee > 0)
                            <span class="flex items-center gap-1">
                                <span class="font-semibold">💰 Fee:</span> ₱{{ number_format($step->fee, 2) }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Requirements Reminder -->
        @if($serviceRequest->service->requirements->count() > 0 && $serviceRequest->status == 'pending')
        <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg">
            <h4 class="font-bold text-yellow-800 mb-3 flex items-center gap-2">
                <span class="text-xl">⚠️</span> Reminder: Requirements Needed
            </h4>
            <p class="text-sm text-yellow-700 mb-3">Please prepare the following documents before proceeding:</p>
            <ul class="grid grid-cols-1 md:grid-cols-2 gap-2">
                @foreach($serviceRequest->service->requirements as $requirement)
                <li class="text-sm text-yellow-800 flex items-start gap-2">
                    <span class="text-yellow-600 mt-0.5">✓</span>
                    <span>{{ $requirement->requirement }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="mt-6 flex gap-4">
            <a href="{{ route('services.my-requests') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-bold text-center transition">
                ← Back to My Requests
            </a>
            @if($serviceRequest->status == 'completed')
            <button class="flex-1 bg-sea-green hover:bg-opacity-90 text-white px-6 py-3 rounded-lg font-bold transition">
                Download Certificate
            </button>
            @endif
        </div>
    </div>
@endsection
