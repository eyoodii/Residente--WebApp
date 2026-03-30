<?php

namespace App\Services;

use App\Models\ChatbotHandoff;
use App\Models\ChatbotKnowledge;
use App\Models\ChatbotSession;
use App\Models\ChatbotUnansweredQuery;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatbotService
{
    public function __construct(
        private GeminiService       $gemini,
        private ChatbotFlowResolver $flowResolver,
    ) {}

    // ─────────────────────────────────────────────────────────────────────────
    // Public entry point
    // ─────────────────────────────────────────────────────────────────────────

    public function handle(string $message, string $sessionId, array $transcript = []): array
    {
        $clean = $this->sanitize($message);

        // ── TIER 0 — Greetings & common phrases ───────────────────────────
        $greeting = $this->matchGreeting($clean);
        if ($greeting) {
            return $this->reply($greeting, type: 'greeting');
        }

        // ── TIER 1 — Tracking code ─────────────────────────────────────────
        if (preg_match('/\b(SR|REQ)-\d{4,8}-[A-Z0-9]+\b/i', $message, $match)) {
            return $this->fetchDocumentStatus($match[0], $sessionId);
        }

        // ── TIER 2 — Active guided form session ───────────────────────────
        $session = ChatbotSession::where('session_id', $sessionId)
            ->where('status', 'active')
            ->first();

        if ($session && $session->isInFlow()) {
            return $this->continueFlow($session, $clean);
        }

        // ── TIER 3 — Knowledge base keyword match ────────────────────────
        $match = $this->scoreKnowledgeBase($clean);

        if ($match && $match['score'] >= 70) {
            return $this->buildKbResponse($match['knowledge'], 'verified', $sessionId);
        }

        if ($match && $match['score'] >= 45) {
            return $this->buildKbResponse($match['knowledge'], 'disclaimer', $sessionId);
        }

        // ── TIER 4 — Gemini API (leashed) ────────────────────────────────
        $knowledgeContext = ChatbotKnowledge::active()
            ->get()
            ->map(fn ($k) => "TOPIC: {$k->intent_name} (Category: {$k->category})\nKeywords: " . implode(', ', $k->allKeywords()) . "\nResponse:\n{$k->official_response}")
            ->implode("\n\n---\n\n");

        $geminiResult = $this->gemini->askLeashed($clean, $knowledgeContext, $transcript);

        if ($geminiResult['confident']) {
            $this->logAiQuery($sessionId, $clean, $geminiResult['response']);

            return $this->reply(
                $geminiResult['response'],
                type: 'ai_assisted',
                badge: '🤖 AI-assisted — not yet human-verified',
            );
        }

        // ── TIER 5 — Handoff / safe fallback ─────────────────────────────
        $this->logUnanswered($sessionId, $clean, 'tier5', true, '');

        return $this->offerHandoff($sessionId, $clean, $transcript);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Tier 0 — Greeting & common phrases
    // ─────────────────────────────────────────────────────────────────────────

    private function matchGreeting(string $message): ?string
    {
        $greetings = [
            // English
            'hello', 'hi', 'hey', 'good morning', 'good afternoon', 'good evening',
            'good day', 'howdy', 'greetings', 'what can you do', 'help', 'help me',
            // Filipino / Taglish
            'kumusta', 'kamusta', 'magandang umaga', 'magandang hapon',
            'magandang gabi', 'magandang araw', 'uy', 'hoy', 'helo',
            'tulungan mo ako', 'tulong', 'ano magagawa mo', 'ano kaya mo',
        ];

        $clean = trim(preg_replace('/[!?.,]+/', '', $message));

        foreach ($greetings as $g) {
            if ($clean === $g || str_starts_with($clean, $g . ' ')) {
                return "👋 Kamusta! Ako ang RESIDENTE Chatbot ng **Municipality of Buguey, Cagayan**.\n\n" .
                    "Maaari kitang tulungan sa mga sumusunod:\n" .
                    "📋 **Permits & Clearances** — Business permit, barangay clearance, building permit\n" .
                    "📄 **Civil Registry** — Birth, death, marriage certificates\n" .
                    "🏥 **Health Services** — Health certificate, anti-rabies vaccine\n" .
                    "🤝 **Social Services** — Indigency certificate, MSWDO assistance\n" .
                    "🏠 **Real Property** — Tax payments, cedula, property assessment\n" .
                    "📦 **Track Documents** — I-type ang iyong tracking code (SR-XXXX-XXXX)\n\n" .
                    "I-type lang ang iyong tanong o gamitin ang quick buttons sa ibaba! 👇";
            }
        }

        // "Thank you" handling
        $thanks = ['thank you', 'thanks', 'salamat', 'maraming salamat', 'ty', 'tnx'];
        foreach ($thanks as $t) {
            if (str_contains($clean, $t)) {
                return "😊 Walang anuman! Kung may iba ka pang katanungan, huwag mag-atubiling magtanong. Nandito lang ako para tumulong!";
            }
        }

        return null;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Tier 1 — Tracking lookup
    // ─────────────────────────────────────────────────────────────────────────

    private function fetchDocumentStatus(string $code, string $sessionId): array
    {
        // Normalise to uppercase for comparison
        $upper = strtoupper($code);

        $request = ServiceRequest::where('request_number', $upper)->first();

        if (!$request) {
            return $this->reply(
                "Hindi ko mahanap ang tracking code na **{$upper}**. Pakitingnan ang iyong email o dashboard para sa tamang code.",
                type: 'info',
            );
        }

        // Intentionally leak only the status — never names, addresses, or IDs
        $status  = ucfirst($request->status);
        $service = $request->service?->name ?? 'Request';

        return $this->reply(
            "📋 **{$upper}** — {$service}\n" .
            "Status: **{$status}**\n\n" .
            "Para sa kumpletong detalye, mag-login sa iyong dashboard.",
            type: 'tracking',
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Tier 2 — Guided form flow continuation
    // ─────────────────────────────────────────────────────────────────────────

    private function continueFlow(ChatbotSession $session, string $input): array
    {
        return $this->flowResolver->advance($session, $input);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Tier 3 — Knowledge base scoring
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Score all active KB entries against the cleaned message.
     * Returns the best match with its score, or null if nothing found.
     *
     * Multi-signal scoring:
     *  1. Exact phrase match  → 25 points (multi-word keyword fully present)
     *  2. Partial phrase match → 20 points (all words present individually)
     *  3. Single word hit     → 12 points (exact, stemmed, or substring)
     *  4. Denominator capped at 5 so synonym-rich entries aren't penalised
     *
     * Taglish-aware: both EN and FIL keywords are checked.
     */
    private function scoreKnowledgeBase(string $message): ?array
    {
        $words     = $this->tokenise($message);
        $stemmed   = array_map(fn ($w) => $this->simpleStem($w), $words);
        $best      = null;
        $bestScore = 0;

        foreach (ChatbotKnowledge::active()->get() as $knowledge) {
            $keywords = array_map('strtolower', $knowledge->allKeywords());
            if (empty($keywords)) {
                continue;
            }

            $phraseHits  = 0;
            $wordHits    = 0;

            foreach ($keywords as $kw) {
                $kwWords = explode(' ', $kw);

                if (count($kwWords) > 1) {
                    // Multi-word phrase: check if fully present in message
                    if (str_contains($message, $kw)) {
                        $phraseHits++;
                        continue;
                    }
                    // Partial phrase: check if all words appear individually (or as substrings)
                    $allFound = true;
                    foreach ($kwWords as $part) {
                        if (strlen($part) <= 2) {
                            continue; // Skip tiny words like "ng", "sa", "at"
                        }
                        $partStem = $this->simpleStem($part);
                        $found = in_array($part, $words, true)
                              || in_array($partStem, $stemmed, true)
                              || $this->wordContainedIn($part, $words);
                        if (!$found) {
                            $allFound = false;
                            break;
                        }
                    }
                    if ($allFound) {
                        $phraseHits++;
                    }
                } else {
                    // Single word: exact, stemmed, or substring match
                    if (strlen($kw) <= 2) {
                        continue; // Skip very short keywords to avoid false positives
                    }
                    $kwStem = $this->simpleStem($kw);
                    if (in_array($kw, $words, true) || in_array($kwStem, $stemmed, true)) {
                        $wordHits++;
                    } elseif (str_contains($message, $kw)) {
                        $wordHits++;
                    } elseif ($this->wordContainedIn($kw, $words)) {
                        // Filipino morphology: "kagat" found in "kinagat", "bahay" in "pagbahay"
                        $wordHits++;
                    }
                }
            }

            $totalHits = $phraseHits + $wordHits;
            if ($totalHits === 0) {
                continue;
            }

            // Score: weighted hits, denominator capped to avoid penalising entries
            // with many synonyms (e.g. 15 keywords shouldn't need 15 matches)
            $rawPoints    = ($phraseHits * 25) + ($wordHits * 12);
            $denominator  = min(count($keywords), 5);
            $score        = (int) round(($rawPoints / ($denominator * 12)) * 100);

            // Bonus: intent name appears in message
            $intentWords = str_replace('_', ' ', $knowledge->intent_name);
            if (str_contains($message, $intentWords)) {
                $score = min(100, $score + 15);
            }

            // Bonus: category name appears in message
            if (str_contains($message, strtolower($knowledge->category))) {
                $score = min(100, $score + 5);
            }

            // Bonus: multiple phrase hits are high-confidence
            if ($phraseHits >= 2) {
                $score = min(100, $score + 10);
            }

            $score = min(100, $score);

            if ($score > $bestScore) {
                $bestScore = $score;
                $best      = ['knowledge' => $knowledge, 'score' => $score];
            }
        }

        return $best;
    }

    /**
     * Check if a keyword (or its stem) is contained within any user word.
     * Handles Filipino morphology: "kagat" in "kinagat", "tayo" in "pagtayo", etc.
     */
    private function wordContainedIn(string $keyword, array $words): bool
    {
        if (strlen($keyword) < 3) {
            return false;
        }
        foreach ($words as $word) {
            if (strlen($word) > strlen($keyword) && str_contains($word, $keyword)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Very simple English/Filipino stemmer: strips common suffixes and prefixes.
     * Not linguistically complete — just enough to match plurals and verb forms.
     */
    private function simpleStem(string $word): string
    {
        // Filipino prefixes (common verb/noun forms)
        $word = preg_replace('/^(nag|mag|pag|naka|maka|ipa|ipag|pa|ka|ma|i)/i', '', $word);
        // English suffixes
        $word = preg_replace('/(ments?|tion|sion|ness|ment|ing|ies|ous|ful|able|ible|ize|ise|ed|er|ly|s)$/i', '', $word);
        // Filipino suffixes
        $word = preg_replace('/(han|hin|an|in|ng)$/i', '', $word);

        return $word ?: $word;
    }

    private function buildKbResponse(ChatbotKnowledge $knowledge, string $confidence, string $sessionId): array
    {
        $knowledge->markMatched();

        // If this intent triggers a guided form flow, start it
        if ($knowledge->response_type === 'guided_form' && $knowledge->linked_form_flow) {
            return $this->startFlow($knowledge->linked_form_flow, $sessionId, $knowledge->official_response);
        }

        $badge = match ($confidence) {
            'verified'   => '✅ Verified by municipal staff',
            'disclaimer' => '⚠️ This answer may be partially matched — please confirm at the barangay hall',
            default      => null,
        };

        return $this->reply($knowledge->official_response, type: $confidence, badge: $badge);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Flow helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function startFlow(string $flowId, string $sessionId, string $preamble): array
    {
        $flow = $this->flowResolver->load($flowId);
        if (!$flow) {
            return $this->reply($preamble, type: 'verified');
        }

        $session = ChatbotSession::updateOrCreate(
            ['session_id' => $sessionId],
            [
                'user_id'        => Auth::id(),
                'current_flow'   => $flowId,
                'current_step'   => 1,
                'collected_data' => [],
                'status'         => 'active',
            ]
        );

        $firstStep = $flow['steps'][0] ?? null;

        return $this->reply(
            $preamble . "\n\n" . ($firstStep ? $firstStep['question'] : ''),
            type: 'guided_form',
            flowStep: $firstStep,
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Tier 5 — Handoff
    // ─────────────────────────────────────────────────────────────────────────

    public function initiateHandoff(string $sessionId, string $concern, array $transcript): array
    {
        $session = ChatbotSession::where('session_id', $sessionId)->first();

        ChatbotHandoff::create([
            'session_id'               => $sessionId,
            'user_id'                  => Auth::id(),
            'conversation_transcript'  => json_encode($transcript),
            'citizen_concern'          => Str::limit($concern, 1000),
            'status'                   => 'pending',
        ]);

        $session?->handOff();

        return $this->reply(
            "Naiintindihan ko na kailangan mo ng tulong mula sa aming staff. " .
            "Ang iyong concern ay nailipat na sa aming opisina. " .
            "Maaari ka ring tumawag sa **Buguey Municipal Hall** o pumunta personally.\n\n" .
            "🕐 Oras ng opisina: Lunes–Biyernes, 8AM–5PM",
            type: 'handoff',
        );
    }

    private function offerHandoff(string $sessionId, string $message, array $transcript): array
    {
        $this->logUnanswered($sessionId, $message, 'tier5', false, '');

        return $this->reply(
            "Paumanhin, wala akong sapat na impormasyon para sagutin ang iyong tanong ng may katiyakan.\n\n" .
            "Nais mo bang:",
            type: 'handoff_offer',
            actions: [
                ['label' => '📞 Makipag-usap sa staff', 'action' => 'request_handoff'],
                ['label' => '🔄 Magtanong ng iba', 'action' => 'retry'],
            ],
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Logging
    // ─────────────────────────────────────────────────────────────────────────

    private function logAiQuery(string $sessionId, string $message, string $response): void
    {
        ChatbotUnansweredQuery::create([
            'session_id'      => $sessionId,
            'original_message'=> $message,
            'tier_reached'    => 'tier4',
            'used_gemini'     => true,
            'gemini_response' => $response,
        ]);
    }

    private function logUnanswered(string $sessionId, string $message, string $tier, bool $usedGemini, string $geminiResp): void
    {
        ChatbotUnansweredQuery::create([
            'session_id'       => $sessionId,
            'original_message' => $message,
            'tier_reached'     => $tier,
            'used_gemini'      => $usedGemini,
            'gemini_response'  => $geminiResp ?: null,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Utilities
    // ─────────────────────────────────────────────────────────────────────────

    private function sanitize(string $message): string
    {
        // Strip HTML, normalise whitespace, lowercase for matching
        return strtolower(trim(preg_replace('/\s+/', ' ', strip_tags($message))));
    }

    private function tokenise(string $text): array
    {
        return preg_split('/[\s,.\-\/]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    }

    private function reply(
        string $text,
        string $type = 'info',
        ?string $badge = null,
        ?array $flowStep = null,
        ?array $actions = null,
    ): array {
        return array_filter([
            'text'      => $text,
            'type'      => $type,
            'badge'     => $badge,
            'flow_step' => $flowStep,
            'actions'   => $actions,
        ], fn ($v) => $v !== null);
    }
}
