@extends('layouts.admin')

@section('title', 'Edit Knowledge Intent')
@section('subtitle', 'Update an existing intent in the chatbot knowledge base')

@section('content')
<div class="px-7 py-6 max-w-3xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.chatbot.index') }}" class="text-slate-400 hover:text-slate-700 transition text-sm">
            ← Back
        </a>
        <h2 class="text-lg font-bold text-slate-800">Edit: <span class="font-mono text-emerald-700">{{ $knowledge->intent_name }}</span></h2>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        @include('admin.chatbot._form', [
            'knowledge' => $knowledge,
            'action'    => route('admin.chatbot.update', $knowledge),
            'method'    => 'PUT',
        ])
    </div>
</div>
@endsection
