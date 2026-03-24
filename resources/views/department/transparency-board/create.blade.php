@extends('layouts.department')
@section('title', 'New Announcement')
@section('content')
<div class="p-8 max-w-2xl mx-auto space-y-6">
    @include('department.components._module-header', ['icon'=>'📢','title'=>'New Announcement','subtitle'=>'Draft and publish a transparency board post.'])

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-700">
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('department.transparency-board.store') }}" method="POST" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Body / Content <span class="text-red-500">*</span></label>
            <textarea name="content" rows="6" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>{{ old('content') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Target Barangay (optional — leave blank for all)</label>
            <input type="text" name="target_barangay" value="{{ old('target_barangay') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="e.g. Barangay San Jose">
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="publish_now" id="publish_now" value="1" {{ old('publish_now') ? 'checked' : '' }} class="rounded">
            <label for="publish_now" class="text-sm text-gray-700">Publish immediately</label>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('department.transparency-board.index') }}" class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Save Post</button>
        </div>
    </form>
</div>
@endsection
