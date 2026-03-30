<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotHandoff;
use App\Models\ChatbotKnowledge;
use App\Models\ChatbotUnansweredQuery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ChatbotAdminController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // Knowledge Base
    // ─────────────────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $knowledges = ChatbotKnowledge::query()
            ->when($request->search, fn ($q) =>
                $q->where('intent_name', 'like', "%{$request->search}%")
                  ->orWhere('category', 'like', "%{$request->search}%")
            )
            ->orderByDesc('times_matched')
            ->paginate(20);

        $stats = [
            'total'       => ChatbotKnowledge::count(),
            'active'      => ChatbotKnowledge::where('is_active', true)->count(),
            'stale'       => ChatbotKnowledge::active()->stale()->count(),
            'unanswered'  => ChatbotUnansweredQuery::unreviewed()->count(),
            'handoffs'    => ChatbotHandoff::pending()->count(),
        ];

        return view('admin.chatbot.index', compact('knowledges', 'stats'));
    }

    public function create(): View
    {
        return view('admin.chatbot.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->prepareData($this->validateKnowledge($request));
        $data['verified_by']      = Auth::id();
        $data['last_verified_at'] = now();

        ChatbotKnowledge::create($data);

        return redirect()->route('admin.chatbot.index')
            ->with('success', 'Knowledge entry created successfully.');
    }

    public function edit(ChatbotKnowledge $chatbot): View
    {
        return view('admin.chatbot.edit', ['knowledge' => $chatbot]);
    }

    public function update(Request $request, ChatbotKnowledge $chatbot): RedirectResponse
    {
        $data = $this->prepareData($this->validateKnowledge($request, $chatbot->id));
        $data['verified_by']      = Auth::id();
        $data['last_verified_at'] = now();

        $chatbot->update($data);

        return redirect()->route('admin.chatbot.index')
            ->with('success', 'Knowledge entry updated.');
    }

    public function destroy(ChatbotKnowledge $chatbot): RedirectResponse
    {
        $chatbot->delete();

        return redirect()->route('admin.chatbot.index')
            ->with('success', 'Knowledge entry deleted.');
    }

    public function toggleActive(ChatbotKnowledge $chatbot): RedirectResponse
    {
        $chatbot->update(['is_active' => !$chatbot->is_active]);

        return back()->with('success', 'Status updated.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Unanswered queries (AI audit)
    // ─────────────────────────────────────────────────────────────────────────

    public function unanswered(Request $request): View
    {
        $queries = ChatbotUnansweredQuery::query()
            ->when($request->filter === 'unreviewed', fn ($q) => $q->unreviewed())
            ->when($request->filter === 'gemini', fn ($q) => $q->where('used_gemini', true))
            ->latest()
            ->paginate(25);

        $stats = [
            'unanswered' => ChatbotUnansweredQuery::unreviewed()->count(),
            'handoffs'   => ChatbotHandoff::pending()->count(),
        ];

        return view('admin.chatbot.unanswered', compact('queries', 'stats'));
    }

    public function markReviewed(ChatbotUnansweredQuery $query): RedirectResponse
    {
        $query->update(['reviewed_by_admin' => true]);

        return back()->with('success', 'Marked as reviewed.');
    }

    public function bulkMarkReviewed(Request $request): RedirectResponse
    {
        ChatbotUnansweredQuery::unreviewed()
            ->where('used_gemini', false)
            ->update(['reviewed_by_admin' => true]);

        return back()->with('success', 'All non-AI unreviewed queries marked as reviewed.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Handoff queue
    // ─────────────────────────────────────────────────────────────────────────

    public function handoffs(Request $request): View
    {
        $handoffs = ChatbotHandoff::query()
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20);

        $stats = [
            'unanswered' => ChatbotUnansweredQuery::unreviewed()->count(),
            'handoffs'   => ChatbotHandoff::pending()->count(),
        ];

        return view('admin.chatbot.handoffs', compact('handoffs', 'stats'));
    }

    public function updateHandoff(Request $request, ChatbotHandoff $handoff): RedirectResponse
    {
        $request->validate([
            'status'      => ['required', 'in:pending,in_progress,resolved'],
            'assigned_to' => ['nullable', 'string', 'max:100'],
        ]);

        $handoff->update($request->only('status', 'assigned_to'));

        return back()->with('success', 'Handoff updated.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function validateKnowledge(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'intent_name'          => ['required', 'string', 'max:100',
                                       "unique:chatbot_knowledges,intent_name,{$ignoreId}"],
            'category'             => ['required', 'string', 'max:100'],
            'trigger_keywords_en'  => ['required', 'string'],  // raw textarea, split on save
            'trigger_keywords_fil' => ['required', 'string'],
            'official_response'    => ['required', 'string', 'max:5000'],
            'response_type'        => ['required', 'in:text,guided_form,external_link'],
            'linked_form_flow'     => ['nullable', 'string', 'max:100'],
            'is_active'            => ['sometimes', 'boolean'],
        ], [], [
            'trigger_keywords_en'  => 'English keywords',
            'trigger_keywords_fil' => 'Filipino keywords',
        ]);
    }

    /**
     * Convert raw validated data (keywords as strings) into array-ready arrays.
     */
    protected function prepareData(array $data): array
    {
        $data['trigger_keywords_en']  = $this->splitKeywords($data['trigger_keywords_en']);
        $data['trigger_keywords_fil'] = $this->splitKeywords($data['trigger_keywords_fil']);
        $data['is_active'] = $data['is_active'] ?? true;

        return $data;
    }

    private function splitKeywords(string $raw): array
    {
        return array_values(array_filter(
            array_map('trim', explode(',', $raw))
        ));
    }
}
