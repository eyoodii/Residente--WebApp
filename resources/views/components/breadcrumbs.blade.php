<nav class="flex items-center space-x-2 text-sm mb-3">
    @foreach($breadcrumbs as $index => $breadcrumb)
        @if($loop->last)
            <span class="flex items-center">
                @if(!$loop->first)
                    <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                @endif
                <span class="font-semibold text-deep-forest bg-golden-glow bg-opacity-20 px-3 py-1 rounded-full">
                    {{ $breadcrumb['label'] }}
                </span>
            </span>
        @else
            <span class="flex items-center">
                @if(!$loop->first)
                    <svg class="w-4 h-4 mx-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                @endif
                <a href="{{ $breadcrumb['url'] }}" class="text-gray-600 hover:text-tiger-orange hover:underline transition flex items-center gap-1">
                    @if(isset($breadcrumb['icon']))
                        <span>{{ $breadcrumb['icon'] }}</span>
                    @endif
                    {{ $breadcrumb['label'] }}
                </a>
            </span>
        @endif
    @endforeach
</nav>
