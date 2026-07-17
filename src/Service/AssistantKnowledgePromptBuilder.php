<?php
declare(strict_types=1);

namespace App\Service;

use Cake\I18n\Date;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * FAQ and signed-in user booking text for the home-page assistant prompts.
 */
class AssistantKnowledgePromptBuilder
{
    use LocatorAwareTrait;

    /**
     * Compact FAQ block for OpenAI (grounded answers only).
     */
    public function buildFaqBlock(int $maxChars = 12000): string
    {
        $faqs = $this->fetchTable('Faqs')->find()
            ->orderBy(['display_order' => 'ASC', 'id' => 'ASC'])
            ->all();

        if ($faqs->count() === 0) {
            return '';
        }

        $lines = ['Official FAQ entries (use only these for policies, what to bring, refunds, contact, etc.):', ''];
        $buf = implode("\n", $lines);

        foreach ($faqs as $faq) {
            $chunk = 'Q: ' . $faq->question . "\n" . 'A: ' . $faq->answer . "\n\n";
            if (strlen($buf . $chunk) > $maxChars) {
                break;
            }
            $buf .= $chunk;
        }

        return trim($buf);
    }

    /**
     * Plain-text summary of a user's non-cancelled bookings for prompts and local replies.
     */
    public function buildUserBookingsBlock(int $userId): string
    {
        $today = Date::today()->format('Y-m-d');

        $bookings = $this->fetchTable('Bookings')->find()
            ->where(['user_id' => $userId])
            ->whereInList('status', ['pending', 'confirmed'])
            ->contain(['Workshops'])
            ->orderBy(['booking_date' => 'ASC', 'Bookings.id' => 'ASC'])
            ->all();

        if ($bookings->count() === 0) {
            return 'This user has no pending or confirmed bookings on file.';
        }

        $lines = ['Bookings for this signed-in user (only use these for “my booking” questions):', ''];

        foreach ($bookings as $b) {
            $bd = $b->booking_date;
            $dateStr = is_object($bd) && method_exists($bd, 'format')
                ? $bd->format('Y-m-d')
                : (string)$bd;
            $workshopName = ($b->workshop ? $b->workshop->workshop_name : null) ?? 'Workshop';
            $lines[] = '• ' . $workshopName . ' on ' . $dateStr . ', status: ' . ($b->status ?? 'unknown')
                . ($dateStr < $today ? ' (past date)' : '');
        }

        return implode("\n", $lines);
    }

    /**
     * Local reply when OpenAI is unavailable but the user asked about their bookings.
     *
     * @return array{0: string, 1: bool} [text, show_booking_cta]
     */
    public function formatUserBookingsReply(int $userId): array
    {
        $block = $this->buildUserBookingsBlock($userId);
        if (str_contains($block, 'no pending or confirmed bookings')) {
            return [
                "You don't have any active bookings on file yet.\n\nWhen you're ready, you can book a workshop from our Booking page.",
                true,
            ];
        }

        $lines = ['Here’s what we have on file for your account:', ''];
        $bookings = $this->fetchTable('Bookings')->find()
            ->where(['user_id' => $userId])
            ->whereInList('status', ['pending', 'confirmed'])
            ->contain(['Workshops'])
            ->orderBy(['booking_date' => 'ASC'])
            ->all();

        foreach ($bookings as $b) {
            $bd = $b->booking_date;
            $dateStr = is_object($bd) && method_exists($bd, 'format')
                ? $bd->format('Y-m-d')
                : (string)$bd;
            $workshopName = ($b->workshop ? $b->workshop->workshop_name : null) ?? 'Workshop';
            $lines[] = '• ' . $workshopName . ' on ' . $dateStr . ' (' . ($b->status ?? '') . ')';
        }
        $lines[] = '';
        $lines[] = 'For changes or cancellations, see our FAQs or contact the studio. You can also visit the Booking page.';

        return [implode("\n", $lines), true];
    }

    /**
     * When OpenAI is unavailable, find the best-matching FAQ row by simple word overlap.
     */
    public function tryMatchFaqAnswer(string $message): ?string
    {
        $lower = mb_strtolower(trim($message));
        $words = preg_split('/[^\p{L}\p{N}]+/u', $lower, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $words = array_values(array_unique(array_filter($words, fn ($w) => mb_strlen($w) > 2)));

        if ($words === []) {
            return null;
        }

        $faqs = $this->fetchTable('Faqs')->find()->orderBy(['display_order' => 'ASC', 'id' => 'ASC'])->all();
        if ($faqs->count() === 0) {
            return null;
        }

        $best = null;
        $bestScore = 0;

        foreach ($faqs as $faq) {
            $qLower = mb_strtolower((string)$faq->question);
            $hay = $qLower . ' ' . mb_strtolower((string)$faq->answer);
            $score = 0;
            foreach ($words as $w) {
                $len = mb_strlen($w);
                if ($len < 4 && !in_array($w, ['bring', 'wear', 'book', 'faq'], true)) {
                    continue;
                }
                if (str_contains($qLower, $w)) {
                    $score += 4;
                } elseif (str_contains($hay, $w)) {
                    $score += 2;
                }
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $faq;
            }
        }

        if ($best === null || $bestScore < 4) {
            return null;
        }

        return $best->question . "\n\n" . $best->answer;
    }
}
