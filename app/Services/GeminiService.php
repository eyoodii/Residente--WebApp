<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Leashed Gemini API wrapper.
 *
 * "Leashed" means the system instruction constrains Gemini to only answer
 * from the approved knowledge base injected at call time.  If Gemini cannot
 * find an answer in that context it must reply with the single word UNCERTAIN.
 */
class GeminiService
{
    private string $apiKey;
    private string $model;
    private string $endpoint;

    public function __construct()
    {
        $this->apiKey   = config('services.gemini.api_key', '');
        $this->model    = config('services.gemini.model', 'gemini-1.5-flash');
        $this->endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";
    }

    /**
     * Ask Gemini a question, leashed to the given knowledge context.
     *
     * @return array{confident: bool, response: string}
     */
    public function askLeashed(string $message, string $knowledgeContext, array $transcript = []): array
    {
        if (empty($this->apiKey) || $this->apiKey === 'your-api-key-here') {
            return ['confident' => false, 'response' => ''];
        }

        $systemInstruction = <<<PROMPT
You are "RESIDENTE Bot", the OFFICIAL virtual assistant for the Municipality of Buguey, Cagayan, Philippines.

STRICT RULES:
1. You may ONLY answer using the APPROVED KNOWLEDGE BASE provided below.
2. If the citizen's question is NOT clearly answerable from the knowledge base, respond ONLY with: UNCERTAIN
3. NEVER invent, guess, or hallucinate requirements, fees, processing times, office names, phone numbers, or legal information.
4. NEVER provide information about other municipalities — only Buguey, Cagayan.
5. If a question is partially answerable, answer ONLY the part you can confirm and note what you cannot confirm.

RESPONSE GUIDELINES:
- Answer in **Taglish** (natural mix of Filipino and English), matching how Buguey residents actually speak.
- Use bullet points (✅ for requirements, 📍 for locations, 🕐 for schedules, 💰 for fees, ⏱️ for processing times).
- Keep answers concise but complete — include ALL relevant requirements, fees, and steps.
- When multiple topics could match, pick the MOST relevant one based on the citizen's exact question.
- If the citizen asks about a process, explain the steps clearly in order.
- Provide the specific office to visit (e.g., "BPLO Office", "Civil Registrar", "MHO").

CONTEXT: The Municipality of Buguey is in the province of Cagayan, Philippines. The Municipal Hall houses most government offices. Office hours are Monday–Friday, 8AM–5PM.

APPROVED KNOWLEDGE BASE:
{$knowledgeContext}
PROMPT;

        // Build conversation history for context
        $contents = [];
        if (!empty($transcript)) {
            $recentTranscript = array_slice($transcript, -6); // Last 6 messages for context
            foreach ($recentTranscript as $entry) {
                $role = ($entry['role'] ?? 'user') === 'bot' ? 'model' : 'user';
                $text = $entry['text'] ?? $entry['message'] ?? '';
                if (!empty($text)) {
                    $contents[] = ['role' => $role, 'parts' => [['text' => $text]]];
                }
            }
        }
        // Always add the current message last
        $contents[] = ['role' => 'user', 'parts' => [['text' => $message]]];

        try {
            $response = Http::timeout(15)->post(
                $this->endpoint . '?key=' . $this->apiKey,
                [
                    'system_instruction' => [
                        'parts' => [['text' => $systemInstruction]],
                    ],
                    'contents' => $contents,
                    'generationConfig' => [
                        'maxOutputTokens' => 800,
                        'temperature'     => 0.1,
                        'topP'            => 0.8,
                        'topK'            => 20,
                    ],
                    'safetySettings' => [
                        ['category' => 'HARM_CATEGORY_HARASSMENT',        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                        ['category' => 'HARM_CATEGORY_HATE_SPEECH',       'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                        ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                        ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'],
                    ],
                ]
            );

            if ($response->failed()) {
                Log::error('Gemini API HTTP error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                throw new \RuntimeException('Gemini API returned HTTP ' . $response->status());
            }

            $raw = trim(
                $response->json('candidates.0.content.parts.0.text', 'UNCERTAIN')
            );

            // Check for UNCERTAIN anywhere in a short response
            if (empty($raw) || strtoupper($raw) === 'UNCERTAIN' || str_starts_with(strtoupper($raw), 'UNCERTAIN')) {
                return ['confident' => false, 'response' => ''];
            }

            return ['confident' => true, 'response' => $raw];
        } catch (\Exception $e) {
            Log::error('GeminiService error', ['message' => $e->getMessage()]);
            return ['confident' => false, 'response' => ''];
        }
    }
}
