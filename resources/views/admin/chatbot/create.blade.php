@extends('layouts.admin')

@section('title', 'New Knowledge Intent')
@section('subtitle', 'Add a new intent to the chatbot knowledge base')

@section('content')
<div class="px-7 py-6 max-w-3xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.chatbot.index') }}" class="text-slate-400 hover:text-slate-700 transition text-sm">
            ← Back
        </a>
        <h2 class="text-lg font-bold text-slate-800">New Knowledge Intent</h2>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        @include('admin.chatbot._form', ['knowledge' => null, 'action' => route('admin.chatbot.store'), 'method' => 'POST'])
    </div>
</div>
@endsection
