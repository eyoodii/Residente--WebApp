<?php

namespace App\Http\Middleware;

use App\Models\ChatbotApiKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Validates the `Authorization: Bearer <api-key>` header
 * against the chatbot_api_keys table.
 */
class VerifyChatbotApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'error'   => 'API key required. Pass it as: Authorization: Bearer <your-key>',
            ], 401);
        }

        $apiKey = ChatbotApiKey::findByPlainKey($token);

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'error'   => 'Invalid or expired API key.',
            ], 401);
        }

        // Touch last_used_at (fire-and-forget, don't block the response)
        $apiKey->markUsed();

        // Make the key available downstream if needed
        $request->attributes->set('chatbot_api_key', $apiKey);

        return $next($request);
    }
}
