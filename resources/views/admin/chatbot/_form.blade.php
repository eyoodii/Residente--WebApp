{{-- Shared form partial for create/edit --}}
<form method="POST" action="{{ $action }}" class="space-y-5">
    @csrf
    @method($method)

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 space-y-1">
            @foreach($errors->all() as $err)
                <p>• {{ $err }}</p>
            @endforeach
        </div>
    @endif

    {{-- Intent Name --}}
    <div>
        <label class="block text-xs font-semibold text-slate-600 mb-1">Intent Name <span class="text-red-500">*</span></label>
        <input name="intent_name" type="text" required
               value="{{ old('intent_name', $knowledge?->intent_name) }}"
               placeholder="e.g. business_permit_new"
               class="w-full bg-white border border-slate-300 text-slate-800 text-sm rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-deep-forest/30 placeholder-slate-400">
        <p class="text-xs text-slate-400 mt-1">Snake_case, unique identifier used by the engine.</p>
    </div>

    {{-- Category --}}
    <div>
        <label class="block text-xs font-semibold text-slate-600 mb-1">Category <span class="text-red-500">*</span></label>
        <input name="category" type="text" required
               value="{{ old('category', $knowledge?->category) }}"
               placeholder="e.g. Permits, Health, Civil Registry"
               class="w-full bg-white border border-slate-300 text-slate-800 text-sm rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-deep-forest/30 placeholder-slate-400">
    </div>

    {{-- EN Keywords --}}
    <div>
        <label class="block text-xs font-semibold text-slate-600 mb-1">English Keywords <span class="text-red-500">*</span></label>
        <input name="trigger_keywords_en" type="text" required
               value="{{ old('trigger_keywords_en', $knowledge ? implode(', ', $knowledge->trigger_keywords_en ?? []) : '') }}"
               placeholder="business, permit, requirements, mayor"
               class="w-full bg-white border border-slate-300 text-slate-800 text-sm rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-deep-forest/30 placeholder-slate-400">
        <p class="text-xs text-slate-400 mt-1">Comma-separated keywords. More = stronger matching.</p>
    </div>

    {{-- FIL Keywords --}}
    <div>
        <label class="block text-xs font-semibold text-slate-600 mb-1">Filipino Keywords <span class="text-red-500">*</span></label>
        <input name="trigger_keywords_fil" type="text" required
               value="{{ old('trigger_keywords_fil', $knowledge ? implode(', ', $knowledge->trigger_keywords_fil ?? []) : '') }}"
               placeholder="negosyo, permiso, kailangan, paano"
               class="w-full bg-white border border-slate-300 text-slate-800 text-sm rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-deep-forest/30 placeholder-slate-400">
    </div>

    {{-- Official Response --}}
    <div>
        <label class="block text-xs font-semibold text-slate-600 mb-1">Official Response <span class="text-red-500">*</span></label>
        <textarea name="official_response" required rows="6"
                  placeholder="Write the verified, official answer here. Use **bold** for emphasis and newlines for formatting."
                  class="w-full bg-white border border-slate-300 text-slate-800 text-sm rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-deep-forest/30 placeholder-slate-400 resize-y">{{ old('official_response', $knowledge?->official_response) }}</textarea>
        <p class="text-xs text-slate-400 mt-1">Supports **bold** markdown. Shown verbatim to citizens when score ≥ 80%.</p>
    </div>

    {{-- Response Type --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Response Type <span class="text-red-500">*</span></label>
            <select name="response_type" required
                    class="w-full bg-white border border-slate-300 text-slate-800 text-sm rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-deep-forest/30">
                @foreach(['text', 'guided_form', 'external_link'] as $type)
                <option value="{{ $type }}" {{ old('response_type', $knowledge?->response_type) === $type ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Linked Flow (if guided_form)</label>
            <input name="linked_form_flow" type="text"
                   value="{{ old('linked_form_flow', $knowledge?->linked_form_flow) }}"
                   placeholder="e.g. business_permit_new"
                   class="w-full bg-white border border-slate-300 text-slate-800 text-sm rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-deep-forest/30 placeholder-slate-400">
        </div>
    </div>

    {{-- Active toggle --}}
    <div class="flex items-center gap-3">
        <input name="is_active" type="checkbox" id="is_active" value="1"
               {{ old('is_active', $knowledge?->is_active ?? true) ? 'checked' : '' }}
               class="w-4 h-4 rounded accent-emerald-600">
        <label for="is_active" class="text-sm text-slate-600">Active (enabled in the engine)</label>
    </div>

    {{-- Submit --}}
    <div class="flex gap-3 pt-2">
        <button type="submit"
                class="bg-deep-forest text-white font-semibold text-sm px-6 py-2.5 rounded-lg hover:opacity-90 transition shadow-sm">
            {{ $knowledge ? 'Save Changes' : 'Create Intent' }}
        </button>
        <a href="{{ route('admin.chatbot.index') }}"
           class="text-slate-500 hover:text-slate-700 text-sm px-4 py-2.5 rounded-lg border border-slate-300 transition bg-white">
            Cancel
        </a>
    </div>
</form>
