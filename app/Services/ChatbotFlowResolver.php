<?php

namespace App\Services;

use App\Models\ChatbotSession;

/**
 * Loads guided-form flow configs and advances through their steps,
 * building a dynamic, personalised response from collected_data.
 */
class ChatbotFlowResolver
{
    private string $flowDir;

    public function __construct()
    {
        $this->flowDir = config_path('chatbot_flows');
    }

    /** Load a flow config by its ID. Returns null if not found. */
    public function load(string $flowId): ?array
    {
        $path = $this->flowDir . DIRECTORY_SEPARATOR . $flowId . '.php';

        if (!file_exists($path)) {
            return null;
        }

        return require $path;
    }

    /**
     * Advance a flow session by one step.
     * - Saves the citizen's current answer to collected_data.
     * - Returns the next question, or the final resolution if complete.
     */
    public function advance(ChatbotSession $session, string $input): array
    {
        $flow = $this->load($session->current_flow);

        if (!$flow) {
            $session->complete();
            return $this->fallback();
        }

        $steps      = $flow['steps'] ?? [];
        $stepIndex  = $session->current_step - 1; // 0-based index

        // Save the answer for the current step
        if (isset($steps[$stepIndex])) {
            $savesTo = $steps[$stepIndex]['saves_to'];
            $session->storeAnswer($savesTo, $input);
        }

        $nextIndex = $stepIndex + 1;

        // More steps to go
        if (isset($steps[$nextIndex])) {
            $session->update(['current_step' => $nextIndex + 1]);
            $nextStep = $steps[$nextIndex];

            return [
                'text'      => $nextStep['question'],
                'type'      => 'guided_form',
                'flow_step' => $nextStep,
            ];
        }

        // All steps collected — resolve the final answer
        $session->complete();
        return $this->resolve($flow, $session->collected_data ?? []);
    }

    /**
     * Build a personalised resolution message from collected answers.
     */
    private function resolve(array $flow, array $data): array
    {
        $resolver = $flow['completion'] ?? 'generic';

        if ($resolver === 'based_on_answers') {
            return $this->dynamicResolution($flow['flow_id'], $data);
        }

        return [
            'text' => $flow['completion_text'] ?? 'Salamat! Pakipunta sa munisipyo para sa karagdagang impormasyon.',
            'type' => 'verified',
            'badge' => '✅ Verified by municipal staff',
        ];
    }

    /**
     * Dynamic resolution: different requirement lists based on collected answers.
     * Expand this as more flows are added.
     */
    private function dynamicResolution(string $flowId, array $data): array
    {
        return match ($flowId) {
            'business_permit_new'    => $this->resolveBusinessPermit($data),
            'barangay_clearance'     => $this->resolveBarangayClearance($data),
            'indigency_certificate'  => $this->resolveIndigency($data),
            default                  => [
                'text' => 'Salamat sa iyong mga sagot! Pumunta sa Municipal Hall para sa karagdagang impormasyon.',
                'type' => 'verified',
            ],
        };
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Per-flow resolution logic
    // ─────────────────────────────────────────────────────────────────────────

    private function resolveBusinessPermit(array $data): array
    {
        $isNew      = ($data['business_age'] ?? '') === 'Brand new (0–1 year)';
        $type       = $data['business_type'] ?? 'Sole Proprietor';
        $residential = strtolower($data['is_residential'] ?? 'no') === 'yes';

        $requirements = [
            '✅ Duly accomplished Business Permit Application Form',
            '✅ Barangay Business Clearance',
            '✅ DTI/SEC/CDA Registration Certificate',
            '✅ Lease Contract / Tax Declaration (proof of location)',
            '✅ Valid government-issued ID',
        ];

        if ($isNew) {
            $requirements[] = '✅ Mayor\'s Permit Fee (new: ₱500 minimum)';
        } else {
            $requirements[] = '✅ Previous year\'s Mayor\'s Permit (for renewal)';
            $requirements[] = '✅ Proof of tax compliance (BIR)';
        }

        if ($residential) {
            $requirements[] = '⚠️ Locational Clearance required (MPDC office)';
        }

        if ($type === 'Corporation') {
            $requirements[] = '✅ Articles of Incorporation + GIS from SEC';
        } elseif ($type === 'Partnership') {
            $requirements[] = '✅ Articles of Partnership from SEC';
        }

        $text = "**Mga Requirements para sa " . ($isNew ? 'Bagong' : 'Renewal ng') . " Business Permit ({$type}):**\n\n" .
                implode("\n", $requirements) . "\n\n" .
                "📍 Pumunta sa **BPLO Office**, Municipal Hall\n" .
                "🕐 Lunes–Biyernes, 8AM–5PM\n" .
                "💰 Processing time: 3–5 araw ng trabaho";

        return ['text' => $text, 'type' => 'verified', 'badge' => '✅ Verified by BPLO'];
    }

    private function resolveBarangayClearance(array $data): array
    {
        $purpose = $data['purpose'] ?? 'general';

        $requirements = [
            '✅ Valid government-issued ID',
            '✅ Proof of residency (utility bill or barangay certificate)',
            '✅ Accomplished application form (available at barangay hall)',
            '✅ Clearance fee: ₱50–₱150 (depending on purpose)',
        ];

        if (str_contains(strtolower($purpose), 'employment')) {
            $requirements[] = '✅ Job Order / Employment Contract (if for employment)';
        }

        $text = "**Mga Requirements para sa Barangay Clearance:**\n\n" .
                implode("\n", $requirements) . "\n\n" .
                "📍 Pumunta sa iyong **Barangay Hall**\n" .
                "🕐 Lunes–Biyernes, 8AM–5PM\n" .
                "⏱️ Processing time: Same day (1–2 oras)";

        return ['text' => $text, 'type' => 'verified', 'badge' => '✅ Verified by municipal staff'];
    }

    private function resolveIndigency(array $data): array
    {
        $requirements = [
            '✅ Valid government-issued ID ng nagpapetisyon',
            '✅ Proof of residency',
            '✅ Barangay Certificate of Indigency (mula sa barangay hall)',
            '✅ Accomplished MSWDO application form',
            '✅ Identification of intended use (medical, burial, scholarship, etc.)',
        ];

        $text = "**Mga Requirements para sa Indigency Certificate:**\n\n" .
                implode("\n", $requirements) . "\n\n" .
                "📍 Pumunta sa **MSWDO Office**, Municipal Hall\n" .
                "🕐 Lunes–Biyernes, 8AM–5PM\n" .
                "⏱️ Processing time: 1–2 araw ng trabaho\n" .
                "💰 Libre (no fee)";

        return ['text' => $text, 'type' => 'verified', 'badge' => '✅ Verified by MSWDO'];
    }

    private function fallback(): array
    {
        return [
            'text' => 'May nangyaring mali. Pakisubukan ulit o makipag-ugnayan sa aming staff.',
            'type' => 'error',
        ];
    }
}
