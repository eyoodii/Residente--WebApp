@extends('layouts.app')

@section('title', 'AI Services Assistant')

@section('header')
    <div class="px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-blue-600 flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-900">AI Services Assistant</h1>
                <p class="text-sm text-slate-500">Ask about barangay services, requirements, and how to apply</p>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Chat container --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col" style="height: 70vh; min-height: 500px;">

        {{-- Messages area --}}
        <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-4 scroll-smooth">

            {{-- Greeting bubble --}}
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-500 to-blue-600 flex-shrink-0 flex items-center justify-center shadow">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/>
                    </svg>
                </div>
                <div class="bg-slate-50 border border-slate-100 rounded-2xl rounded-tl-sm px-4 py-3 max-w-[85%]">
                    <p class="text-sm text-slate-700 leading-relaxed">
                        Hello! I'm your Barangay Services Assistant for the Municipality of Buguey. 👋<br><br>
                        I can help you with information about available services, requirements, processing times, and fees. What would you like to know?
                    </p>
                </div>
            </div>

            {{-- Suggestion chips --}}
            <div id="suggestion-chips" class="flex flex-wrap gap-2 pl-11">
                @foreach([
                    'What services are available?',
                    'How do I get a Barangay Clearance?',
                    'What are the requirements for a Business Permit?',
                    'How long does processing take?'
                ] as $suggestion)
                    <button onclick="sendSuggestion(this)"
                        class="text-xs bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-full px-3 py-1.5 transition-colors font-medium">
                        {{ $suggestion }}
                    </button>
                @endforeach
            </div>

        </div>

        {{-- Divider --}}
        <div class="border-t border-slate-100"></div>

        {{-- Input area --}}
        <div class="p-4">
            <form id="chat-form" class="flex items-end gap-3">
                @csrf
                <div class="flex-1 relative">
                    <textarea
                        id="user-input"
                        rows="1"
                        maxlength="2000"
                        placeholder="Ask about a barangay service..."
                        class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition"
                    ></textarea>
                </div>
                <button type="submit" id="send-btn"
                    class="w-10 h-10 rounded-xl bg-emerald-500 hover:bg-emerald-600 disabled:bg-slate-200 disabled:cursor-not-allowed flex items-center justify-center transition-colors flex-shrink-0">
                    <svg id="send-icon" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <svg id="loading-icon" class="w-4 h-4 text-slate-400 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                </button>
            </form>
            <p class="text-xs text-slate-400 mt-2 text-center">
                AI responses are for guidance only. Visit the barangay hall for official transactions.
            </p>
        </div>
    </div>

</div>

@push('scripts')
<script>
const chatMessages = document.getElementById('chat-messages');
const chatForm     = document.getElementById('chat-form');
const userInput    = document.getElementById('user-input');
const sendBtn      = document.getElementById('send-btn');
const sendIcon     = document.getElementById('send-icon');
const loadingIcon  = document.getElementById('loading-icon');
const chips        = document.getElementById('suggestion-chips');

// Conversation history sent to the backend
let history = [];

function escapeHtml(text) {
    return text
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;')
        .replace(/\n/g, '<br>');
}

function appendMessage(role, text) {
    const isUser = role === 'user';

    const wrapper = document.createElement('div');
    wrapper.className = `flex items-start gap-3 ${isUser ? 'justify-end' : ''}`;

    if (!isUser) {
        wrapper.innerHTML = `
            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-500 to-blue-600 flex-shrink-0 flex items-center justify-center shadow">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/>
                </svg>
            </div>
            <div class="bg-slate-50 border border-slate-100 rounded-2xl rounded-tl-sm px-4 py-3 max-w-[85%]">
                <p class="text-sm text-slate-700 leading-relaxed">${escapeHtml(text)}</p>
            </div>`;
    } else {
        wrapper.innerHTML = `
            <div class="bg-emerald-500 rounded-2xl rounded-tr-sm px-4 py-3 max-w-[85%]">
                <p class="text-sm text-white leading-relaxed">${escapeHtml(text)}</p>
            </div>`;
    }

    chatMessages.appendChild(wrapper);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function setLoading(loading) {
    sendBtn.disabled = loading;
    sendIcon.classList.toggle('hidden', loading);
    loadingIcon.classList.toggle('hidden', !loading);
}

async function sendMessage(text) {
    const trimmed = text.trim();
    if (!trimmed) return;

    // Remove suggestion chips after first message
    if (chips) chips.remove();

    appendMessage('user', trimmed);
    history.push({ role: 'user', content: trimmed });
    userInput.value = '';
    userInput.style.height = 'auto';
    setLoading(true);

    try {
        const res = await fetch('{{ route("ai-assistant.chat") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ messages: history }),
        });

        const data = await res.json();

        if (!res.ok) {
            appendMessage('assistant', data.error ?? 'Something went wrong. Please try again.');
        } else {
            appendMessage('assistant', data.reply);
            history.push({ role: 'assistant', content: data.reply });
        }
    } catch (e) {
        appendMessage('assistant', 'Connection error. Please check your internet and try again.');
    } finally {
        setLoading(false);
        userInput.focus();
    }
}

chatForm.addEventListener('submit', (e) => {
    e.preventDefault();
    sendMessage(userInput.value);
});

// Send on Enter (Shift+Enter = new line)
userInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage(userInput.value);
    }
});

// Auto-resize textarea
userInput.addEventListener('input', () => {
    userInput.style.height = 'auto';
    userInput.style.height = Math.min(userInput.scrollHeight, 120) + 'px';
});

function sendSuggestion(btn) {
    sendMessage(btn.textContent.trim());
}
</script>
@endpush

@endsection
