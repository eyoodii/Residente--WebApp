<?php

use App\Http\Controllers\Api\ChatbotApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Stateless JSON API endpoints. All routes are prefixed with /api
| and use the 'api' middleware group (no CSRF, no sessions).
|
*/

// ── Chatbot API v1 ──────────────────────────────────────────────────────────
// Requires a valid API key via the `chatbot.api` middleware.
// Rate-limited to 60 requests per minute per key.
Route::prefix('v1/chatbot')->name('api.chatbot.')->middleware(['chatbot.api', 'throttle:60,1'])->group(function () {
    Route::post('/chat',         [ChatbotApiController::class, 'chat'])->name('chat');
    Route::post('/handoff',      [ChatbotApiController::class, 'handoff'])->name('handoff');
    Route::post('/quick-action', [ChatbotApiController::class, 'quickAction'])->name('quick-action');
    Route::get('/session/{sessionId}', [ChatbotApiController::class, 'session'])->name('session');
});
