@extends('layouts.department')
@section('title', 'New Blotter Entry')
@section('content')
<div class="p-8 max-w-2xl mx-auto space-y-6">
    @include('department.components._module-header', ['icon'=>'📋','title'=>'New Blotter Entry','subtitle'=>'Log an incident, violation, or complaint.'])

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-700">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('department.blotter.store') }}" method="POST" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Incident Type</label>
            <select name="action" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent" required>
                <option value="">Select type...</option>
                <option value="violation" {{ old('action')==='violation'?'selected':'' }}>Violation</option>
                <option value="incident" {{ old('action')==='incident'?'selected':'' }}>Incident</option>
                <option value="blotter" {{ old('action')==='blotter'?'selected':'' }}>Blotter Case</option>
                <option value="complaint" {{ old('action')==='complaint'?'selected':'' }}>Complaint</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Describe the incident..." required>{{ old('description') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Subject Resident (optional — leave blank if unknown)</label>
            <input type="text" name="subject_name" value="{{ old('subject_name') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Name of involved person">
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('department.blotter.index') }}" class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700">Submit Entry</button>
        </div>
    </form>
</div>
@endsection
