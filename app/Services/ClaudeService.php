<?php

namespace App\Services;

use Anthropic\Client;
use Illuminate\Support\Facades\Log;

class ClaudeService
{
    private Client $client;
    private string $model;

    // System prompt inspired by Claude Code patterns:
    // - Clear role & scope definition (agent-prompt patterns)
    // - Security boundary (security-monitor patterns)
    // - Concise output (tone-and-style patterns)
    private const SYSTEM_PROMPT = <<<'PROMPT'
You are a Barangay Services Assistant for the Municipality of Buguey, Cagayan.
Your role is to help residents understand available government services, requirements, fees, and how to apply online through the Residente App.

Guidelines:
- Be concise and friendly. Respond in 2–4 short paragraphs at most.
- Answer only questions about barangay/municipal services, requirements, application processes, and the Residente App.
- If asked about a specific service, mention: who can avail it, requirements needed, processing time, fee, and next steps.
- If a resident asks something outside your scope (medical advice, legal counsel, national government services), politely redirect them to the appropriate office.
- Never fabricate requirements or fees. If you are unsure, say so and direct them to the barangay hall.
- Do not request or store personal information like IDs, passwords, or PhilSys numbers.

Available services include (but are not limited to): Barangay Clearance, Business Permit, Building Permit, Indigency Certificate, Locational Clearance, Civil Registry, Blotter Report, Livelihood Assistance, Health Services (anti-rabies, immunization, dental), and Welfare Targeting.

Always end with a helpful prompt like "Would you like to know the requirements for a specific service?" if the message is a general inquiry.
PROMPT;

    public function __construct()
    {
        $this->client = new Client(apiKey: config('services.anthropic.api_key'));
        $this->model  = config('services.anthropic.model', 'claude-3-5-haiku-20241022');
    }

    /**
     * Send a conversation to Claude and return the assistant reply text.
     *
     * @param  array<int, array{role: string, content: string}>  $messages
     */
    public function chat(array $messages): string
    {
        try {
            $response = $this->client->messages->create(
                model: $this->model,
                maxTokens: 1024,
                system: self::SYSTEM_PROMPT,
                messages: $messages,
            );

            foreach ($response->content as $block) {
                if ($block->type === 'text') {
                    return $block->text;
                }
            }

            return 'I could not generate a response. Please try again.';
        } catch (\Exception $e) {
            Log::error('Claude API error', ['message' => $e->getMessage()]);
            throw $e;
        }
    }
}
