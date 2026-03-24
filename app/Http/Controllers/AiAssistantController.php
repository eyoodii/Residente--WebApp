<?php

namespace App\Http\Controllers;

use App\Services\ClaudeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AiAssistantController extends Controller
{
    public function __construct(private ClaudeService $claude) {}

    public function index()
    {
        return view('ai-assistant.index');
    }

    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'messages'          => ['required', 'array', 'max:20'],
            'messages.*.role'   => ['required', 'in:user,assistant'],
            'messages.*.content'=> ['required', 'string', 'max:2000'],
        ]);

        // Sanitize each message content to prevent prompt injection
        $messages = array_map(function (array $msg) {
            return [
                'role'    => $msg['role'],
                'content' => Str::limit(strip_tags($msg['content']), 2000),
            ];
        }, $validated['messages']);

        try {
            $reply = $this->claude->chat($messages);

            return response()->json(['reply' => $reply]);
        } catch (\Exception $e) {
            return response()->json(
                ['error' => 'The assistant is temporarily unavailable. Please try again shortly.'],
                503
            );
        }
    }
}
