<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotApiKey;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ChatbotApiKeyController extends Controller
{
    public function index(): View
    {
        $apiKeys = ChatbotApiKey::with('creator')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.chatbot.api-keys.index', compact('apiKeys'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'expires_at' => ['nullable', 'date', 'after:today'],
        ]);

        $result = ChatbotApiKey::generate(
            name: $validated['name'],
            createdBy: Auth::id(),
            expiresAt: $validated['expires_at'] ? \Carbon\Carbon::parse($validated['expires_at']) : null,
        );

        return redirect()
            ->route('admin.chatbot.api-keys.index')
            ->with('new_key', $result['plain_key'])
            ->with('success', "API key \"{$validated['name']}\" created. Copy it now — it won't be shown again.");
    }

    public function revoke(ChatbotApiKey $apiKey): RedirectResponse
    {
        $apiKey->revoke();

        return redirect()
            ->route('admin.chatbot.api-keys.index')
            ->with('success', "API key \"{$apiKey->name}\" has been revoked.");
    }
}
