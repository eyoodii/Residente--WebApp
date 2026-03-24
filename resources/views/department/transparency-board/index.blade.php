@extends('layouts.department')
@section('title', 'Transparency Board')
@section('content')
<div class="p-8 space-y-6">
    @include('department.components._module-header', ['icon'=>'📢','title'=>'Transparency Board','subtitle'=>'Manage and publish public announcements for your municipality.'])

    <div class="flex justify-between items-center">
        <div class="flex gap-2">
            @foreach(['all'=>'All','published'=>'Published','draft'=>'Drafts'] as $key=>$label)
            <a href="{{ request()->fullUrlWithQuery(['filter'=>$key]) }}"
               class="px-4 py-2 rounded-full text-sm {{ request('filter','all') === $key ? 'bg-indigo-600 text-white' : 'bg-white border text-gray-600' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>
        <a href="{{ route('department.transparency-board.create') }}" class="inline-flex items-center gap-2 text-sm bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">+ New Post</a>
    </div>

    <div class="space-y-4">
        @foreach($announcements as $a)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-lg">📌</span>
                        <h3 class="font-semibold text-gray-800">{{ $a->title }}</h3>
                        <span class="px-2 py-0.5 text-xs rounded-full {{ $a->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $a->is_active ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 line-clamp-2">{{ $a->content }}</p>
                    <p class="text-xs text-gray-400 mt-2">
                        Posted: {{ $a->posted_at ? $a->posted_at->format('M d, Y') : 'Not yet posted' }}
                        @if($a->target_barangay) · 📍 {{ $a->target_barangay }} @endif
                    </p>
                </div>
                <div class="flex gap-2 shrink-0">
                    <a href="{{ route('department.transparency-board.edit', $a) }}" class="text-sm px-3 py-1.5 border border-gray-300 rounded-lg hover:bg-gray-50">Edit</a>
                    @if($a->is_active)
                    <form action="{{ route('department.transparency-board.unpublish', $a) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="text-sm px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200">Unpublish</button>
                    </form>
                    @else
                    <form action="{{ route('department.transparency-board.publish', $a) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="text-sm px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700">Publish</button>
                    </form>
                    @endif
                    <form action="{{ route('department.transparency-board.destroy', $a) }}" method="POST" onsubmit="return confirm('Delete this post?')">
                        @csrf @method('DELETE')
                        <button class="text-sm px-3 py-1.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-200">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
        <div>{{ $announcements->links() }}</div>
    </div>
</div>
@endsection
