@extends('layouts.citizen')

@section('title', 'E-Services Directory | RESIDENTE App')

@section('page-title', 'E-Services Directory')
@section('page-subtitle', 'Browse all available LGU services by department')

@section('header-actions')
    <a href="{{ route('services.my-requests') }}" class="bg-tiger-orange hover:bg-burnt-tangerine text-white px-4 py-2 rounded-lg font-bold text-sm shadow-sm transition">
        View My Requests
    </a>
@endsection

@section('content')
    <div class="p-4 sm:p-7">
        <div class="space-y-6">
            <div class="bg-white px-4 sm:px-6 py-4 border-b border-gray-100 flex flex-wrap gap-2 justify-between items-center rounded-t-xl shadow-sm">
                <h2 class="text-xl sm:text-2xl font-extrabold text-deep-forest">LGU Buguey Service Directory</h2>
                <span class="bg-sea-green text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">Select a department</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 pb-8">

                @foreach($servicesByDepartment as $department => $services)
                    @php
                        $departmentConfig = [
                            'Municipal Health Office' => ['icon' => '⚕️', 'color' => 'sea-green'],
                            'Municipal Civil Registrar' => ['icon' => '📜', 'color' => 'tiger-orange'],
                            "Mayor's Office" => ['icon' => '🏛️', 'color' => 'golden-glow'],
                            'Municipal Planning and Development Office' => ['icon' => '🗺️', 'color' => 'burnt-tangerine'],
                        ];
                        $config = $departmentConfig[$department] ?? ['icon' => '📋', 'color' => 'sea-green'];
                        $departmentId = \Illuminate\Support\Str::slug($department);
                    @endphp

                    <div id="{{ $departmentId }}" class="bg-white rounded-xl shadow-sm border-t-4 border-{{ $config['color'] }} overflow-hidden hover:shadow-md transition scroll-mt-6">
                        <div class="p-6 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
                            <div class="w-12 h-12 bg-{{ $config['color'] }} bg-opacity-20 text-{{ $config['color'] }} rounded-full flex items-center justify-center text-2xl">
                                {{ $config['icon'] }}
                            </div>
                            <h3 class="font-bold text-deep-forest text-lg">{{ $department }}</h3>
                        </div>
                        <ul class="p-4 divide-y divide-gray-100 text-sm text-gray-700 h-64 overflow-y-auto">
                            @foreach($services as $service)
                                <li class="py-2 {{ $service->is_active ? 'hover:text-tiger-orange cursor-pointer' : 'opacity-50' }} transition">
                                    @if($service->is_active)
                                        <a href="{{ route('services.show', $service->slug) }}" class="flex justify-between items-center group">
                                            <span>{{ $service->name }}</span>
                                            <div class="flex items-center gap-2 text-xs">
                                                <span class="text-gray-500">{{ $service->formatted_fee }}</span>
                                                <span class="group-hover:translate-x-1 transition-transform">→</span>
                                            </div>
                                        </a>
                                    @else
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-400">{{ $service->name }}</span>
                                            <div class="flex items-center gap-2 text-xs">
                                                <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded-full font-semibold">Unavailable</span>
                                            </div>
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach

                <!-- Document Verification (static) -->
                <div class="bg-deep-forest text-white rounded-xl shadow-sm overflow-hidden relative border border-gray-800">
                    <div class="absolute top-0 right-0 p-4 opacity-10 text-6xl">✅</div>
                    <div class="p-5 relative z-10 border-b border-sea-green border-opacity-30">
                        <h3 class="font-bold text-golden-glow text-lg">Document Verification</h3>
                        <p class="text-xs text-gray-300 mt-1">Scan or input ID to verify authenticity</p>
                    </div>
                    <ul class="p-4 space-y-3 text-sm relative z-10">
                        <li>
                            <button class="w-full bg-white bg-opacity-10 hover:bg-opacity-20 text-left px-4 py-3 rounded transition flex justify-between items-center">
                                Barangay Verify Certificate <span class="text-tiger-orange">→</span>
                            </button>
                        </li>
                        <li>
                            <button class="w-full bg-white bg-opacity-10 hover:bg-opacity-20 text-left px-4 py-3 rounded transition flex justify-between items-center">
                                ATOP Verify Certificate <span class="text-tiger-orange">→</span>
                            </button>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Additional Information Section -->
            <div class="bg-gradient-to-r from-sea-green to-deep-forest text-white rounded-xl shadow-lg p-8 mt-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold mb-2">Need Help Choosing a Service?</h3>
                        <p class="text-gray-100 text-sm">Contact our support team for guidance on which service fits your needs.</p>
                    </div>
                    <button class="bg-golden-glow hover:bg-white text-deep-forest px-6 py-3 rounded-lg font-bold shadow-lg transition">
                        Contact Support
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
