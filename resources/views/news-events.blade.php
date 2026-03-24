@extends('layouts.public')

@section('title', 'News & Events')

@section('content')
<div class="py-8 md:py-12 lg:py-16 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-deep-forest mb-4">News & Events</h1>
            <p class="text-lg text-gray-600">Stay updated with the latest news and announcements from the Municipality of Buguey</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($announcements as $announcement)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <span class="inline-block px-3 py-1 {{ $announcement->category_badge_color }} text-xs font-bold rounded-full">
                            {{ $announcement->category }}
                        </span>
                        <span class="text-xs text-gray-500">{{ $announcement->formatted_posted_at }}</span>
                    </div>
                    
                    <h3 class="font-bold text-deep-forest text-lg mb-2">{{ $announcement->title }}</h3>
                    <p class="text-sm text-gray-600 mb-4">{{ Str::limit($announcement->content, 150) }}</p>
                    
                    @if($announcement->target_barangay)
                    <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        <span>Barangay {{ $announcement->target_barangay }}</span>
                    </div>
                    @endif
                    
                    <button class="text-tiger-orange font-bold text-sm hover:text-burnt-tangerine transition">
                        Read More →
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16">
                <div class="text-gray-400 text-6xl mb-4">📰</div>
                <h3 class="text-xl font-bold text-gray-600 mb-2">No News Yet</h3>
                <p class="text-gray-500">Check back soon for updates and announcements.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($announcements->hasPages())
        <div class="mt-12">
            {{ $announcements->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
