<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatbotHandoff;
use App\Services\ChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Stateless chatbot API controller.
 *
 * Unlike the web ChatbotController that relies on PHP sessions,
 * this controller requires the client to supply a `session_id`
 * (a UUID they generate) so conversations persist across requests.
 */
class ChatbotApiController extends Controller
{
    public function __construct(private ChatbotService $chatbot) {}

    /**
     * POST /api/v1/chatbot/chat
     *
     * Send a message and receive a structured reply.
     */
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message'      => ['required', 'string', 'max:1000'],
            'session_id'   => ['required', 'string', 'uuid', 'max:36'],
            'transcript'   => ['sometimes', 'array', 'max:50'],
            'transcript.*' => ['array'],
        ]);

        $message    = Str::limit(strip_tags($validated['message']), 1000);
        $transcript = $validated['transcript'] ?? [];
        $sessionId  = $validated['session_id'];

        try {
            $result = $this->chatbot->handle($message, $sessionId, $transcript);

            return response()->json([
                'success' => true,
                'data'    => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Paumanhin, may nangyaring error. Pakisubukan ulit sandali.',
            ], 503);
        }
    }

    /**
     * POST /api/v1/chatbot/handoff
     *
     * Request a live-agent handoff.
     */
    public function handoff(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'concern'    => ['required', 'string', 'max:2000'],
            'session_id' => ['required', 'string', 'uuid', 'max:36'],
            'transcript' => ['sometimes', 'array', 'max:50'],
        ]);

        $concern    = Str::limit(strip_tags($validated['concern']), 2000);
        $transcript = $validated['transcript'] ?? [];
        $sessionId  = $validated['session_id'];

        try {
            $result = $this->chatbot->initiateHandoff($sessionId, $concern, $transcript);

            return response()->json([
                'success' => true,
                'data'    => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Hindi namin ma-proseso ang iyong handoff ngayon.',
            ], 503);
        }
    }

    /**
     * POST /api/v1/chatbot/quick-action
     *
     * Fire a preset quick-action button.
     */
    public function quickAction(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'action'     => ['required', 'string', 'in:track,permits,business_permit,talk_to_staff'],
            'session_id' => ['required', 'string', 'uuid', 'max:36'],
        ]);

        $presets = [
            'track'           => 'I-track ang aking dokumento',
            'permits'         => 'Ano ang mga requirements para sa clearances at permits?',
            'business_permit' => 'Gusto kong mag-apply ng business permit',
            'talk_to_staff'   => 'Gusto kong makipag-usap sa staff',
        ];

        $message   = $presets[$validated['action']];
        $sessionId = $validated['session_id'];

        try {
            $result = $this->chatbot->handle($message, $sessionId, []);

            return response()->json([
                'success' => true,
                'data'    => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Hindi namin ma-proseso ang iyong kahilingan.',
            ], 503);
        }
    }

    /**
     * GET /api/v1/chatbot/session/{sessionId}
     *
     * Retrieve the current state of a chat session (flow progress, status).
     */
    public function session(Request $request, string $sessionId): JsonResponse
    {
        if (!Str::isUuid($sessionId)) {
            return response()->json(['success' => false, 'error' => 'Invalid session ID.'], 422);
        }

        $session = \App\Models\ChatbotSession::where('session_id', $sessionId)->first();

        if (!$session) {
            return response()->json([
                'success' => true,
                'data'    => ['status' => 'new', 'flow' => null],
            ]);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'status'       => $session->status,
                'flow'         => $session->current_flow,
                'step'         => $session->current_step,
                'created_at'   => $session->created_at->toIso8601String(),
                'updated_at'   => $session->updated_at->toIso8601String(),
            ],
        ]);
    }
}
