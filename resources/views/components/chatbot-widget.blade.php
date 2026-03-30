{{--
    Eddie AI — Chatbot Widget
    Clean, professional floating chat widget.
--}}
<div
    x-data="residEnteChatbot()"
    x-init="init()"
    class="fixed bottom-6 right-6 flex flex-col items-end gap-3"
    style="z-index: 9999;"
    role="region"
    aria-label="Eddie AI Assistant"
>
    {{-- ── Chat Window ──────────────────────────────────────────────── --}}
    <div
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-3 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-3 scale-95"
        class="w-[360px] sm:w-[390px] bg-white rounded-2xl flex flex-col overflow-hidden"
        style="max-height: 570px; box-shadow: 0 20px 60px rgba(0,0,0,.18), 0 0 0 1px rgba(0,0,0,.06);"
    >
        {{-- Header --}}
        <div class="bg-[#0d2418] px-4 py-3 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
                {{-- Robot avatar --}}
                <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                        <rect x="11" y="1" width="2" height="3" rx="1" fill="#c6c013"/>
                        <circle cx="12" cy="1" r="1.5" fill="#c6c013"/>
                        <rect x="3" y="4" width="18" height="14" rx="3" fill="white" fill-opacity=".15" stroke="white" stroke-opacity=".3" stroke-width="1"/>
                        <rect x="6" y="9"  width="4" height="4" rx="1" fill="#c6c013"/>
                        <rect x="14" y="9" width="4" height="4" rx="1" fill="#c6c013"/>
                        <rect x="7"  y="16" width="2" height="1.5" rx=".4" fill="white" fill-opacity=".4"/>
                        <rect x="11" y="16" width="2" height="1.5" rx=".4" fill="white" fill-opacity=".4"/>
                        <rect x="15" y="16" width="2" height="1.5" rx=".4" fill="white" fill-opacity=".4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-semibold text-sm leading-tight">Eddie</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-white/50 text-[11px]" x-text="statusLabel"></span>
                    </div>
                </div>
            </div>
            <button
                @click="isOpen = false"
                class="text-white/40 hover:text-white/80 transition p-1 rounded-lg hover:bg-white/10"
                aria-label="Close chat"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Messages --}}
        <div
            id="chatbot-messages"
            class="flex-1 overflow-y-auto px-4 py-4 space-y-3 bg-gray-50"
            style="min-height: 200px; max-height: 360px;"
        >
            {{-- Welcome --}}
            <template x-if="messages.length === 0">
                <div class="space-y-3">
                    <div class="flex gap-2.5 items-start">
                        <div class="w-7 h-7 rounded-lg bg-[#0d2418] flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg viewBox="0 0 20 20" fill="none" class="w-3.5 h-3.5">
                                <rect x="9" y="0" width="2" height="3" rx="1" fill="#c6c013"/>
                                <rect x="2" y="3" width="16" height="12" rx="2.5" fill="white" fill-opacity=".15" stroke="white" stroke-opacity=".3" stroke-width=".8"/>
                                <rect x="4"  y="7" width="4" height="4" rx="1" fill="#c6c013"/>
                                <rect x="12" y="7" width="4" height="4" rx="1" fill="#c6c013"/>
                                <rect x="5"  y="13" width="2" height="1.5" rx=".4" fill="white" fill-opacity=".4"/>
                                <rect x="9"  y="13" width="2" height="1.5" rx=".4" fill="white" fill-opacity=".4"/>
                                <rect x="13" y="13" width="2" height="1.5" rx=".4" fill="white" fill-opacity=".4"/>
                            </svg>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-2xl rounded-tl-sm px-3.5 py-2.5 text-sm text-gray-700 shadow-sm max-w-[85%]">
                            <p class="font-semibold text-[#0d2418] mb-1 text-sm">Kumusta! Ako si Eddie. 👋</p>
                            <p class="text-gray-500 text-xs leading-relaxed">Paano kita matutulungan ngayon? Maaari kang magtanong tungkol sa mga serbisyo, requirements, o i-track ang iyong dokumento.</p>
                        </div>
                    </div>

                    {{-- Quick actions --}}
                    <div class="pl-9 space-y-2">
                        <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Madalas na tanungin</p>
                        <div class="flex flex-wrap gap-1.5">
                            <button @click="sendQuickAction('track')"
                                class="text-xs bg-white hover:bg-gray-50 text-gray-600 border border-gray-200 rounded-full px-3 py-1.5 transition hover:border-[#0d2418]/30 hover:text-[#0d2418]">
                                🔍 Track dokumento
                            </button>
                            <button @click="sendQuickAction('permits')"
                                class="text-xs bg-white hover:bg-gray-50 text-gray-600 border border-gray-200 rounded-full px-3 py-1.5 transition hover:border-[#0d2418]/30 hover:text-[#0d2418]">
                                📋 Requirements
                            </button>
                            <button @click="sendQuickAction('business_permit')"
                                class="text-xs bg-white hover:bg-gray-50 text-gray-600 border border-gray-200 rounded-full px-3 py-1.5 transition hover:border-[#0d2418]/30 hover:text-[#0d2418]">
                                🏢 Business permit
                            </button>
                            <button @click="sendQuickAction('talk_to_staff')"
                                class="text-xs bg-white hover:bg-gray-50 text-gray-500 border border-gray-200 rounded-full px-3 py-1.5 transition">
                                📞 Makipag-usap sa staff
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Dynamic messages --}}
            <template x-for="(msg, index) in messages" :key="index">
                <div>
                    {{-- Bot message --}}
                    <template x-if="msg.role === 'bot'">
                        <div class="flex gap-2.5 items-start">
                            <div class="w-7 h-7 rounded-lg bg-[#0d2418] flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg viewBox="0 0 20 20" fill="none" class="w-3.5 h-3.5">
                                    <rect x="9" y="0" width="2" height="3" rx="1" fill="#c6c013"/>
                                    <rect x="2" y="3" width="16" height="12" rx="2.5" fill="white" fill-opacity=".15" stroke="white" stroke-opacity=".3" stroke-width=".8"/>
                                    <rect x="4"  y="7" width="4" height="4" rx="1" fill="#c6c013"/>
                                    <rect x="12" y="7" width="4" height="4" rx="1" fill="#c6c013"/>
                                    <rect x="5"  y="13" width="2" height="1.5" rx=".4" fill="white" fill-opacity=".4"/>
                                    <rect x="9"  y="13" width="2" height="1.5" rx=".4" fill="white" fill-opacity=".4"/>
                                    <rect x="13" y="13" width="2" height="1.5" rx=".4" fill="white" fill-opacity=".4"/>
                                </svg>
                            </div>
                            <div class="max-w-[85%]">
                                <div
                                    :class="{
                                        'bg-white border-gray-200 shadow-sm': msg.type !== 'handoff' && msg.type !== 'handoff_offer',
                                        'bg-amber-50 border-amber-200': msg.type === 'handoff' || msg.type === 'handoff_offer',
                                    }"
                                    class="border rounded-2xl rounded-tl-sm px-3.5 py-2.5 text-sm text-gray-700"
                                >
                                    <div x-html="formatMessage(msg.text)" class="text-xs leading-relaxed"></div>

                                    <template x-if="msg.badge">
                                        <div class="mt-2 pt-2 border-t border-gray-100">
                                            <span class="text-[10px] font-medium"
                                                :class="msg.type === 'ai_assisted' ? 'text-amber-600' : 'text-emerald-600'"
                                                x-text="msg.badge">
                                            </span>
                                        </div>
                                    </template>

                                    <template x-if="msg.flow_step && msg.flow_step.choices">
                                        <div class="mt-2 flex flex-wrap gap-1.5">
                                            <template x-for="choice in msg.flow_step.choices" :key="choice">
                                                <button @click="sendMessage(choice)"
                                                    class="text-xs bg-[#0d2418] hover:bg-[#0d2418]/80 text-white rounded-full px-3 py-1.5 transition">
                                                    <span x-text="choice"></span>
                                                </button>
                                            </template>
                                        </div>
                                    </template>

                                    <template x-if="msg.actions && msg.actions.length">
                                        <div class="mt-2 flex flex-wrap gap-1.5">
                                            <template x-for="action in msg.actions" :key="action.action">
                                                <button @click="handleAction(action)"
                                                    :class="action.action === 'request_handoff'
                                                        ? 'bg-amber-500 hover:bg-amber-600 text-white'
                                                        : 'bg-gray-100 hover:bg-gray-200 text-gray-700'"
                                                    class="text-xs rounded-full px-3 py-1.5 transition font-medium">
                                                    <span x-text="action.label"></span>
                                                </button>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- User message --}}
                    <template x-if="msg.role === 'user'">
                        <div class="flex justify-end">
                            <div class="bg-[#0d2418] text-white rounded-2xl rounded-tr-sm px-3.5 py-2.5 text-xs max-w-[80%] leading-relaxed">
                                <span x-text="msg.text"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            {{-- Typing indicator --}}
            <template x-if="isTyping">
                <div class="flex gap-2.5 items-start">
                    <div class="w-7 h-7 rounded-lg bg-[#0d2418] flex items-center justify-center flex-shrink-0">
                        <svg viewBox="0 0 20 20" fill="none" class="w-3.5 h-3.5">
                            <rect x="9" y="0" width="2" height="3" rx="1" fill="#c6c013"/>
                            <rect x="2" y="3" width="16" height="12" rx="2.5" fill="white" fill-opacity=".15" stroke="white" stroke-opacity=".3" stroke-width=".8"/>
                            <rect x="4"  y="7" width="4" height="4" rx="1" fill="#c6c013"/>
                            <rect x="12" y="7" width="4" height="4" rx="1" fill="#c6c013"/>
                            <rect x="5"  y="13" width="2" height="1.5" rx=".4" fill="white" fill-opacity=".4"/>
                            <rect x="9"  y="13" width="2" height="1.5" rx=".4" fill="white" fill-opacity=".4"/>
                            <rect x="13" y="13" width="2" height="1.5" rx=".4" fill="white" fill-opacity=".4"/>
                        </svg>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-2xl rounded-tl-sm px-4 py-3 shadow-sm">
                        <div class="flex gap-1 items-center h-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400 animate-bounce" style="animation-delay:0ms"></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400 animate-bounce" style="animation-delay:150ms"></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400 animate-bounce" style="animation-delay:300ms"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Input --}}
        <div class="border-t border-gray-100 px-3 py-2.5 flex gap-2 flex-shrink-0 bg-white">
            <input
                x-model="inputText"
                @keydown.enter.prevent="sendCurrentInput()"
                :disabled="isTyping"
                type="text"
                placeholder="Mag-type ng tanong..."
                maxlength="500"
                class="flex-1 text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0d2418]/20 focus:border-[#0d2418]/40 bg-gray-50 disabled:opacity-50 transition text-gray-700 placeholder-gray-400"
                aria-label="Chat message input"
            />
            <button
                @click="sendCurrentInput()"
                :disabled="isTyping || !inputText.trim()"
                class="bg-[#0d2418] hover:bg-[#0d2418]/85 text-white rounded-xl px-3.5 py-2 transition disabled:opacity-40 flex-shrink-0"
                aria-label="Send message"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                </svg>
            </button>
        </div>

        {{-- Footer --}}
        <div class="bg-gray-50 border-t border-gray-100 px-3 py-1.5 text-center flex-shrink-0">
            <p class="text-[10px] text-gray-400">Official AI Assistant — Municipality of Buguey</p>
        </div>
    </div>

    {{-- ── Floating Trigger Button ──────────────────────────────────── --}}
    <button
        @click="isOpen = !isOpen"
        class="relative w-14 h-14 bg-[#0d2418] hover:bg-[#0d2418]/85 rounded-2xl shadow-lg hover:shadow-xl flex items-center justify-center text-white transition-all duration-200 hover:scale-105"
        :aria-label="isOpen ? 'Close Eddie AI' : 'Open Eddie AI'"
        :aria-expanded="isOpen.toString()"
    >
        {{-- Robot icon --}}
        <span x-show="!isOpen" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-7 h-7">
                <rect x="11" y="1"  width="2" height="3"  rx="1"   fill="#c6c013"/>
                <circle cx="12" cy="1" r="1.5" fill="#c6c013"/>
                <rect x="3"  y="4"  width="18" height="14" rx="3"   fill="white" fill-opacity=".15" stroke="white" stroke-opacity=".5" stroke-width="1"/>
                <rect x="6"  y="9"  width="4"  height="4"  rx="1"   fill="#c6c013"/>
                <rect x="14" y="9"  width="4"  height="4"  rx="1"   fill="#c6c013"/>
                <rect x="7"  y="16" width="2"  height="1.5" rx=".4" fill="white" fill-opacity=".5"/>
                <rect x="11" y="16" width="2"  height="1.5" rx=".4" fill="white" fill-opacity=".5"/>
                <rect x="15" y="16" width="2"  height="1.5" rx=".4" fill="white" fill-opacity=".5"/>
            </svg>
        </span>

        {{-- Close icon --}}
        <svg x-show="isOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>

        {{-- Unread badge --}}
        <template x-if="unreadCount > 0 && !isOpen">
            <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center border-2 border-white" x-text="unreadCount"></span>
        </template>
    </button>
</div>

<script>
function residEnteChatbot() {
    return {
        isOpen: false,
        isTyping: false,
        inputText: '',
        messages: [],
        transcript: [],
        unreadCount: 0,

        get statusLabel() {
            if (this.isTyping) return 'Nagta-type…';
            return 'Online';
        },

        init() {},

        async sendCurrentInput() {
            const text = this.inputText.trim();
            if (!text) return;
            this.inputText = '';
            await this.sendMessage(text);
        },

        async sendMessage(text) {
            this.pushMessage('user', text);
            this.isTyping = true;
            await new Promise(r => setTimeout(r, 300));
            try {
                const res = await fetch('/chatbot/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ message: text, transcript: this.transcript.slice(-20) }),
                });
                if (!res.ok) throw new Error('Request failed');
                this.handleBotResponse(await res.json());
            } catch {
                this.pushBotMessage({ text: 'Paumanhin, may error. Pakisubukan ulit sandali.', type: 'error' });
            } finally {
                this.isTyping = false;
                this.$nextTick(() => this.scrollBottom());
            }
        },

        async sendQuickAction(action) {
            this.isTyping = true;
            await new Promise(r => setTimeout(r, 300));
            try {
                const res = await fetch('/chatbot/quick-action', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ action }),
                });
                if (!res.ok) throw new Error();
                this.handleBotResponse(await res.json());
            } catch {
                this.pushBotMessage({ text: 'May error. Pakisubukan ulit.', type: 'error' });
            } finally {
                this.isTyping = false;
                this.$nextTick(() => this.scrollBottom());
            }
        },

        handleBotResponse(data) {
            if (!data || !data.text) return;
            this.pushBotMessage(data);
        },

        pushMessage(role, text, extra = {}) {
            this.messages.push({ role, text, ...extra });
            this.transcript.push({ role: role === 'bot' ? 'assistant' : 'user', content: text });
            if (role === 'bot' && !this.isOpen) this.unreadCount++;
            this.$nextTick(() => this.scrollBottom());
        },

        pushBotMessage(data) {
            this.messages.push({ role: 'bot', ...data });
            this.transcript.push({ role: 'assistant', content: data.text });
            if (!this.isOpen) this.unreadCount++;
        },

        async handleAction(action) {
            if (action.action === 'request_handoff') await this.requestHandoff();
            else if (action.action === 'retry') { this.messages = []; this.transcript = []; }
        },

        async requestHandoff() {
            this.isTyping = true;
            const lastUserMsg = [...this.transcript].reverse().find(m => m.role === 'user');
            try {
                const res = await fetch('/chatbot/handoff', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        concern: lastUserMsg?.content ?? 'Hindi na-specify',
                        transcript: this.transcript.slice(-30),
                    }),
                });
                if (!res.ok) throw new Error();
                this.handleBotResponse(await res.json());
            } catch {
                this.pushBotMessage({ text: 'Hindi ma-proseso ang handoff ngayon. Tumawag sa Municipal Hall.', type: 'error' });
            } finally {
                this.isTyping = false;
                this.$nextTick(() => this.scrollBottom());
            }
        },

        formatMessage(text) {
            if (!text) return '';
            return text
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\n/g, '<br>');
        },

        scrollBottom() {
            const el = document.getElementById('chatbot-messages');
            if (el) el.scrollTop = el.scrollHeight;
        },

        set isOpen(val) { this._isOpen = val; if (val) this.unreadCount = 0; },
        get isOpen()    { return this._isOpen ?? false; },
    };
}
</script>
