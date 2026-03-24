@extends('layouts.public')

@section('title', 'Memos')

@section('content')
<div class="py-8 md:py-12 lg:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-deep-forest mb-4">Official Memos & Circulars</h1>
            <p class="text-lg text-gray-600">Municipal memorandums and official communications</p>
        </div>

        <div class="max-w-4xl mx-auto space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                <div class="p-6 flex items-start gap-4">
                    <div class="w-12 h-12 bg-tiger-orange bg-opacity-20 text-tiger-orange rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
                        📋
                    </div>
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="font-bold text-deep-forest text-lg">Sample Memorandum Title</h3>
                            <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">Feb 27, 2026</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">Brief description of the memorandum content goes here...</p>
                        <div class="flex items-center gap-4">
                            <span class="text-xs text-gray-500">Memo No: 2026-001</span>
                            <button class="text-tiger-orange font-bold text-sm hover:text-burnt-tangerine transition">View PDF →</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="text-gray-400 text-6xl mb-4">📄</div>
                <h3 class="text-xl font-bold text-gray-600 mb-2">More Memos Coming Soon</h3>
                <p class="text-gray-500">Official memorandums will be posted here.</p>
            </div>
        </div>
    </div>
</div>
@endsection
