<?php

namespace App\Http\Controllers;

use App\Models\ChatbotHandoff;
use App\Services\ChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function __construct(private ChatbotService $chatbot) {}

    /**
     * Main chat endpoint — receives a message + transcript, returns a structured reply.
     */
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message'      => ['required', 'string', 'max:1000'],
            'transcript'   => ['sometimes', 'array', 'max:50'],
            'transcript.*' => ['array'],
        ]);

        // Sanitise input at boundary
        $message    = Str::limit(strip_tags($validated['message']), 1000);
        $transcript = $validated['transcript'] ?? [];

        $sessionId  = $request->session()->getId();

        try {
            $result = $this->chatbot->handle($message, $sessionId, $transcript);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(
                ['text' => 'Paumanhin, may nangyaring error. Pakisubukan ulit sandali.', 'type' => 'error'],
                503
            );
        }
    }

    /**
     * Citizen confirms they want a live handoff.
     */
    public function handoff(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'concern'    => ['required', 'string', 'max:2000'],
            'transcript' => ['sometimes', 'array', 'max:50'],
        ]);

        $concern    = Str::limit(strip_tags($validated['concern']), 2000);
        $transcript = $validated['transcript'] ?? [];
        $sessionId  = $request->session()->getId();

        try {
            $result = $this->chatbot->initiateHandoff($sessionId, $concern, $transcript);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(
                ['text' => 'Hindi namin ma-proseso ang iyong handoff ngayon. Tumawag sa Municipal Hall.', 'type' => 'error'],
                503
            );
        }
    }

    /**
     * Quick-action buttons on the widget fire pre-set messages.
     */
    public function quickAction(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'string', 'in:track,permits,business_permit,talk_to_staff'],
        ]);

        $presets = [
            'track'          => 'I-track ang aking dokumento',
            'permits'        => 'Ano ang mga requirements para sa clearances at permits?',
            'business_permit'=> 'Gusto kong mag-apply ng business permit',
            'talk_to_staff'  => 'Gusto kong makipag-usap sa staff',
        ];

        $message   = $presets[$validated['action']];
        $sessionId = $request->session()->getId();

        try {
            $result = $this->chatbot->handle($message, $sessionId, []);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(
                ['text' => 'Hindi namin ma-proseso ang iyong kahilingan. Pakisubukan ulit.', 'type' => 'error'],
                503
            );
        }
    }
}
