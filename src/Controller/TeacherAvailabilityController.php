<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\AssistantKnowledgePromptBuilder;
use App\Service\OpenAiAvailabilityResponder;
use App\Service\TeacherAvailabilityContextBuilder;
use App\Service\WebsiteKnowledgeService;
use Cake\Http\Response;
use Cake\Routing\Router;
use Cake\Log\Log;

class TeacherAvailabilityController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->Authentication->allowUnauthenticated(['context', 'ask']);
    }

    public function context(): Response
    {
        $this->request->allowMethod(['get']);

        $builder = new TeacherAvailabilityContextBuilder();
        $data = $builder->build();

        return $this->jsonResponse($data);
    }

    public function ask(): Response
    {
        $this->request->allowMethod(['post']);

        try {
            ['message' => $message, 'history' => $history] = $this->parseAskBody();
        } catch (\Throwable $e) {
            return $this->jsonResponse(['error' => 'Bad request body.'], 400);
        }

        if ($message === '') {
            return $this->jsonResponse(['error' => 'Missing message'], 422);
        }
        if (mb_strlen($message) > 2000) {
            return $this->jsonResponse(['error' => 'Message too long'], 422);
        }

        try {
            return $this->askCore($message, $history);
        } catch (\Throwable $e) {
            Log::warning('Chat ask exception: ' . get_class($e) . ': ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

            return $this->jsonResponse([
                'error' => 'Server error: ' . $e->getMessage(),
                'detail' => get_class($e) . ' in ' . basename($e->getFile()) . ':' . $e->getLine(),
            ], 500);
        }
    }

    private function askCore(string $message, array $history): Response
    {
        $identity = $this->request->getAttribute('identity');
        $userId = $this->resolveUserId($identity);

        try {
            $builder = new TeacherAvailabilityContextBuilder();
            $context = $builder->build();
        } catch (\Throwable $e) {
            Log::warning('Context build failed: ' . $e->getMessage());
            $context = ['teachers' => []];
        }

        $knowledge = new AssistantKnowledgePromptBuilder();
        $faqBlock = $knowledge->buildFaqBlock();
        $siteWeb = new WebsiteKnowledgeService();
        $siteBlock = $siteWeb->buildSiteMapPromptBlock();

        $extras = [
            'login_url' => Router::url('/pages/login', true),
            'faq_url' => Router::url('/faqs', true),
        ];

        if ($this->isMyBookingQuery($message) && $userId === null) {
            $reply = "To see your bookings, please sign in to your account first.\n\n"
                . 'After you log in, open this assistant again and ask “What are my bookings?”';

            return $this->jsonResponse(array_merge([
                'reply' => $reply,
                'source' => 'local',
                'reply_type' => 'login_required',
                'show_booking_cta' => true,
                'booking_links' => [],
                'suggested_links' => $this->suggestedLinksPayload($siteWeb, $message, 'login_required'),
            ], $extras));
        }

        $scopeSchedule = $this->isAvailabilityRelatedQuery($message, $context);
        $scopeFaq = $this->isFaqRelatedQuery($message);
        $scopeMyBookings = $userId !== null && $this->isMyBookingQuery($message);
        $scopeSite = $this->isSiteExplorationQuery($message);

        if (!$scopeSchedule && !$scopeFaq && !$scopeMyBookings && !$scopeSite) {
            $reply = $this->offTopicGuidanceReply();

            return $this->jsonResponse(array_merge([
                'reply' => $reply,
                'source' => 'local',
                'reply_type' => 'off_topic',
                'show_booking_cta' => $this->replySuggestsBooking($reply),
                'booking_links' => [],
                'suggested_links' => $this->suggestedLinksPayload($siteWeb, $message, 'off_topic'),
            ], $extras));
        }

        if ($scopeSchedule && $this->wantsFullScheduleListing($message)) {
            $reply = $builder->formatFullScheduleForUser($context);
            $scheduleLinks = $this->shouldOfferSessionBookingLinks($message)
                ? $this->buildBookingLinksForMessage($message, $context)
                : [];

            return $this->jsonResponse(array_merge([
                'reply' => $reply,
                'source' => 'local',
                'reply_type' => 'schedule_list',
                'show_booking_cta' => $this->replySuggestsBooking($reply),
                'booking_links' => $scheduleLinks,
                'suggested_links' => $this->suggestedLinksPayload($siteWeb, $message, 'schedule_list'),
            ], $extras));
        }

        $scheduleBlock = $builder->formatContextForPrompt($context);
        $userBookingsBlock = '';
        if ($userId !== null && $this->isMyBookingQuery($message)) {
            $userBookingsBlock = $knowledge->buildUserBookingsBlock($userId);
        }

        $faqForModel = $faqBlock;
        if ($faqForModel === '' && $scopeFaq) {
            $faqForModel = 'No FAQ entries are loaded in the database yet. Say you cannot find that in the FAQ and suggest visiting the FAQ page or contacting the studio.';
        }

        $openAi = new OpenAiAvailabilityResponder();
        $reply = $openAi->reply($message, $scheduleBlock, $faqForModel, $userBookingsBlock, $history, $siteBlock);
        $source = 'openai';
        $replyType = 'assistant';

        if ($reply === null) {
            if ($scopeMyBookings && $userId !== null) {
                [$reply, $cta] = $knowledge->formatUserBookingsReply($userId);
                $replyType = 'my_bookings';
                $source = 'local';

                return $this->jsonResponse(array_merge([
                    'reply' => $reply,
                    'source' => $source,
                    'reply_type' => $replyType,
                    'show_booking_cta' => $cta,
                    'booking_links' => [],
                    'suggested_links' => $this->suggestedLinksPayload($siteWeb, $message, 'my_bookings'),
                ], $extras));
            }

            if ($this->isBookingHowToQuery($message)) {
                $reply = $this->howToBookGuidanceReply();
                $replyType = 'booking_help';
                $source = 'local';
            } elseif (($matched = $knowledge->tryMatchFaqAnswer($message)) !== null) {
                $reply = $matched;
                $replyType = 'faq_local';
                $source = 'local';
            } elseif ($scopeFaq || $this->isFaqRelatedQuery($message)) {
                $reply = $this->genericFaqGuidanceReply();
                $replyType = 'faq_local';
                $source = 'local';
            } elseif ($scopeSite) {
                $pages = $siteWeb->matchPages($message, 5);
                if ($pages === []) {
                    $pages = $siteWeb->defaultDiscoveryLinks();
                }
                $reply = $siteWeb->formatLocalSiteGuide($pages);
                $replyType = 'site_guide';
                $source = 'local';
            } else {
                $reply = $this->fallbackReply($message, $context, $scheduleBlock);
                $source = 'local';
            }
        }

        $bookingLinks = $this->shouldOfferSessionBookingLinks($message)
            ? $this->buildBookingLinksForMessage($message, $context)
            : [];

        return $this->jsonResponse(array_merge([
            'reply' => $reply,
            'source' => $source,
            'reply_type' => $replyType,
            'show_booking_cta' => $this->replySuggestsBooking($reply),
            'booking_links' => $bookingLinks,
            'suggested_links' => $this->suggestedLinksPayload($siteWeb, $message, (string)$replyType),
        ], $extras));
    }

    /**
     * Curated “search results” for the chat UI (keyword match over public pages).
     *
     * @return list<array{label: string, url: string, hint: string}>
     */
    private function suggestedLinksPayload(WebsiteKnowledgeService $site, string $message, string $replyType): array
    {
        if ($replyType === 'booking_help') {
            return [
                [
                    'label' => 'Booking',
                    'url' => Router::url('/booking', true),
                    'hint' => 'Choose a workshop, pick a date, and pay.',
                ],
                [
                    'label' => 'FAQs',
                    'url' => Router::url('/faqs', true),
                    'hint' => 'Policies and practical details.',
                ],
            ];
        }
        if ($replyType === 'faq_local') {
            return [
                [
                    'label' => 'FAQs',
                    'url' => Router::url('/faqs', true),
                    'hint' => 'Full answers to common questions.',
                ],
                [
                    'label' => 'Contact',
                    'url' => Router::url('/contact', true),
                    'hint' => 'Message the studio.',
                ],
            ];
        }

        $m = $site->matchPages($message, 5);
        if ($m !== []) {
            return $m;
        }
        if ($replyType === 'off_topic') {
            return $site->defaultDiscoveryLinks();
        }
        if ($replyType === 'login_required') {
            return $site->authDiscoveryLinks();
        }
        if ($replyType === 'site_guide') {
            return $site->defaultDiscoveryLinks();
        }
        if ($replyType === 'schedule_list') {
            $booking = Router::url('/booking', true);

            return [
                ['label' => 'Booking', 'url' => $booking, 'hint' => 'Reserve a spot from published sessions.'],
            ];
        }

        return [];
    }

    private function isSiteExplorationQuery(string $message): bool
    {
        $lower = mb_strtolower(trim($message));
        if ($lower === '') {
            return false;
        }

        $patterns = [
            '/\b(summarize|summary|tldr|overview|walkthrough|walk\s*through|quick\s+tour)\b/u',
            '/\b(explain|describe)\b.*\b(website|web\s*site|this\s+site|page|pages|studio)\b/u',
            '/\b(what|which)\b.*\b(pages?|sections?|areas?)\b.*\b(site|website)\b/u',
            '/\b(where|how)\s+(do|can)\s+i\s+(find|go|get\s+to|access)\b/u',
            '/\b(which\s+page|what\s+page|where\s+on\s+the\s+site)\b/u',
            '/\b(site\s*map|sitemap|navigation|navigate)\b/u',
            '/\b(about)\s+(the\s+)?(studio|you|us|company|academy|business)\b/u',
            '/\b(who\s+are\s+you|tell\s+me\s+about)\b.*\b(studio|academy|site)\b/u',
            '/\b(home|landing)\s+page\b/u',
            '/\bworkshops?\s+page\b/u',
            '/\b(faq|faqs)\s+page\b/u',
            '/\b(booking|book)\s+page\b/u',
            '/\bcontact\b.*\b(page|form|studio)\b/u',
            '/\b(message|enquiry|inquiry|reach)\s+(the\s+)?studio\b/u',
            '/\b(register|sign\s*up|create\s+an?\s+account)\b/u',
            '/\b(log\s*in|sign\s*in)\b.*\b(account|page)\b/u',
            '/\bcandlecraft\b/u',
            '/\b(this|your|our)\s+website\b/u',
            '/\bweb\s*site\b/u',
            '/\bwhat\b.*\b(offer|offers|available)\b.*\b(on\s+your\s+)?(site|website)\b/u',
            '/\bsearch\b.*\b(site|website|page)\b/u',
            '/\b(find|looking\s+for)\b.*\b(on\s+)?(your\s+)?(site|website)\b/u',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $lower) === 1) {
                return true;
            }
        }

        return false;
    }

    private function replySuggestsBooking(string $reply): bool
    {
        if ($reply === '') {
            return false;
        }

        $lower = mb_strtolower($reply, 'UTF-8');
        // Collapse Unicode spaces (incl. NBSP) so "Booking page" variants still match
        $flat = preg_replace('/[\s\p{Z}]+/u', ' ', $lower) ?? $lower;

        return str_contains($flat, 'booking page')
            || (bool)preg_match('/booking\s+page/ui', $reply);
    }

    private function offTopicGuidanceReply(): string
    {
        return "I can help with our public website (where to book, read FAQs, or contact us), published class schedule, policies in the FAQs, and your own bookings when you’re signed in.\n\n"
            . "Try: “Summarize your workshops page”, “Where do I contact you?”, “What should I bring?”, or “Show all upcoming sessions.”";
    }

    /**
     * @param mixed $identity
     */
    private function resolveUserId(mixed $identity): ?int
    {
        if ($identity === null) {
            return null;
        }
        if (method_exists($identity, 'getIdentifier')) {
            $id = $identity->getIdentifier();
            if ($id !== null && $id !== '') {
                return (int)$id;
            }
        }
        if (isset($identity->id)) {
            return (int)$identity->id;
        }

        return null;
    }

    /**
     * @return array{message: string, history: list<array{role: string, content: string}>}
     */
    private function parseAskBody(): array
    {
        $message = '';
        $history = [];
        $contentType = (string)$this->request->getHeaderLine('Content-Type');
        if (str_contains($contentType, 'application/json')) {
            $raw = (string)$this->request->getBody();
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $message = trim((string)($decoded['message'] ?? ''));
                $history = $this->normalizeHistory($decoded['history'] ?? null);
            }
        }
        if ($message === '') {
            $message = trim((string)$this->request->getData('message'));
        }

        return ['message' => $message, 'history' => $history];
    }

    /**
     * @param mixed $raw
     *
     * @return list<array{role: string, content: string}>
     */
    private function normalizeHistory($raw): array
    {
        if (!is_array($raw)) {
            return [];
        }
        $out = [];
        $slice = array_slice($raw, -4);
        foreach ($slice as $row) {
            if (!is_array($row)) {
                continue;
            }
            $role = $row['role'] ?? '';
            $content = trim((string)($row['content'] ?? ''));
            if (!in_array($role, ['user', 'assistant'], true) || $content === '') {
                continue;
            }
            if (mb_strlen($content) > 1500) {
                $content = mb_substr($content, 0, 1500) . '…';
            }
            $out[] = ['role' => $role, 'content' => $content];
        }

        return $out;
    }

    private function isMyBookingQuery(string $message): bool
    {
        $lower = mb_strtolower(trim($message));

        return (bool)preg_match(
            '/\b(my|our)\s+(booking|bookings|reservation|reservations|class|classes|session|sessions|lesson|lessons|spot|spots)\b/u',
            $lower
        )
            || (bool)preg_match('/\b(what|when|where)\s+.+\s+(my|our)\s+(booking|class|session|lesson)\b/u', $lower)
            || (bool)preg_match('/\bdid\s+i\s+book\b/u', $lower)
            || (bool)preg_match('/\bshow\s+(my|our)\s+(booking|bookings)\b/u', $lower);
    }

    private function isBookingHowToQuery(string $message): bool
    {
        $lower = mb_strtolower(trim($message));
        $patterns = [
            '/\bhow\s+(do|can|could)\s+(i|we)\s+(book|reserve|register|sign\s+up)\b/u',
            '/\bhow\s+to\s+book\b/u',
            '/\bwhere\s+(can|do|could)\s+(i|we)\s+book\b/u',
            '/\bwhere\s+to\s+book\b/u',
            '/\bhow\s+does\s+booking\s+work\b/u',
        ];
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $lower) === 1) {
                return true;
            }
        }

        return false;
    }

    /** Avoid attaching random session deep-links to generic “how do I book?” questions (e.g. “workshop” matching a workshop name). */
    private function shouldOfferSessionBookingLinks(string $message): bool
    {
        return !$this->isBookingHowToQuery($message);
    }

    private function howToBookGuidanceReply(): string
    {
        return "Here’s how booking works:\n\n"
            . "1. Open our Booking page.\n"
            . "2. Choose a workshop, pick a date, and submit.\n"
            . "3. Complete payment to confirm your spot.\n\n"
            . 'Use the Booking and FAQs buttons below to open those pages.';
    }

    private function genericFaqGuidanceReply(): string
    {
        return "That’s usually answered in our FAQs (what to bring, refunds, contact, and more).\n\n"
            . 'Use the FAQs or Contact buttons below. If you still need help, send us a message from Contact.';
    }

    private function isFaqRelatedQuery(string $message): bool
    {
        $lower = mb_strtolower(trim($message));
        $patterns = [
            '/\bwhat\s+should\s+i\s+bring\b/u',
            '/\bwhat\s+(do\s+i|should\s+i|to)\s+bring\b/u',
            '/\b(what|should)\s+(do\s+i|to)\s+bring\b/u',
            '/\bbring\b.*\b(class|workshop|session|lesson)\b/u',
            '/\bwhat\s+to\s+bring\b/u',
            '/\b(wear|clothing|clothes|shoes|apron)\b/u',
            '/\b(refund|cancel|cancellation|reschedule|policy|policies)\b/u',
            '/\b(parking|address|location|directions|where\s+is\s+the\s+studio|find\s+you)\b/u',
            '/\b(age|children|kids?|minor)\b/u',
            '/\b(beginner|first\s+time|never\s+tried|no\s+experience)\b/u',
            '/\b(open|opening|hours?|close|closing)\b/u',
            '/\b(payment|pay|card|stripe|invoice)\b/u',
            '/\b(contact|email|phone|call)\b/u',
            '/\bfaq|frequently\s+asked\b/u',
            '/\b(gift|voucher|certificate)\b/u',
            '/\b(what\s+to\s+expect|what\s+happens|prepare)\b/u',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $lower) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * Deep links to the booking form with workshop and date prefilled when the message matches sessions.
     *
     * @param array<string, mixed> $context
     *
     * @return list<array{label: string, url: string}>
     */
    private function buildBookingLinksForMessage(string $message, array $context): array
    {
        $lower = mb_strtolower($message);
        $links = [];
        $seen = [];

        foreach ($context['teachers'] ?? [] as $t) {
            $teacherMatch = $this->messageMentionsTeacher($t, $lower);
            foreach ($t['upcoming_sessions'] ?? [] as $s) {
                $workshopId = isset($s['workshop_id']) ? (int)$s['workshop_id'] : 0;
                $date = (string)($s['date'] ?? '');
                if ($workshopId <= 0 || $date === '') {
                    continue;
                }
                if (!$this->sessionMatchesMessage($lower, $teacherMatch, $t, $s)) {
                    continue;
                }
                $key = $workshopId . '|' . $date;
                if (isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;
                $label = 'Book';
                if (!empty($s['workshop_name'])) {
                    $label .= ': ' . $s['workshop_name'];
                }
                $label .= ' · ' . $date;
                if (!empty($s['time'])) {
                    $label .= ' ' . $s['time'];
                }
                $links[] = [
                    'label' => $label,
                    'url' => Router::url([
                        'controller' => 'Bookings',
                        'action' => 'add',
                        '?' => [
                            'workshop_id' => $workshopId,
                            'booking_date' => $date,
                        ],
                    ], true),
                ];
                if (count($links) >= 5) {
                    return $links;
                }
            }
        }

        return $links;
    }

    /**
     * @param array<string, mixed> $teacher
     * @param array<string, mixed> $session
     */
    private function sessionMatchesMessage(string $lowerMessage, bool $teacherMatchedByName, array $teacher, array $session): bool
    {
        if ($teacherMatchedByName) {
            return true;
        }
        $workshopName = (string)($session['workshop_name'] ?? '');
        if ($workshopName !== '' && $this->textMentionsPhrase($lowerMessage, $workshopName)) {
            return true;
        }
        $date = (string)($session['date'] ?? '');
        if ($date !== '' && str_contains($lowerMessage, $date)) {
            return true;
        }
        $notes = (string)($session['notes'] ?? '');
        if ($notes !== '' && $this->textMentionsPhrase($lowerMessage, $notes)) {
            return true;
        }

        return $this->messageMentionsTeacher($teacher, $lowerMessage);
    }

    private function wantsFullScheduleListing(string $message): bool
    {
        $lower = mb_strtolower($message);

        $patterns = [
            '/\b(show|list|display|view|see|give\s+me|tell\s+me)\s+(me\s+)?(all|every|the\s+full|the\s+complete)\s+(the\s+)?(upcoming\s+)?(session|sessions|workshop|workshops|class|classes|lesson|lessons)\b/u',
            '/\b(show|list|display|view|see)\s+(me\s+)?all\s+upcoming\b/u',
            '/\b(all|every)\s+(the\s+)?(upcoming\s+)?(session|sessions|workshop|workshops|class|classes|lesson|lessons)\b/u',
            '/\b(full|complete|entire)\s+schedule\b/u',
            '/\b(full|complete)\s+list\s+of\s+(the\s+)?(upcoming\s+)?(session|sessions|workshops?|classes?)\b/u',
            '/\bwhat\s+.+\b(all|every)\s+(the\s+)?(upcoming\s+)?(session|sessions|workshops?|classes?)\b/u',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $lower) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<string, mixed> $context
     */
    private function isAvailabilityRelatedQuery(string $message, array $context): bool
    {
        $lower = mb_strtolower(trim($message));
        if ($lower === '') {
            return false;
        }

        $intentPatterns = [
            '/\bwhen\b/u',
            '/\bwhere\b.*\b(class|classes|workshop|workshops|lesson|lessons|session|sessions|studio)\b/u',
            '/\b(which|what)\s+(day|days|date|dates|workshop|workshops|class|classes|lesson|lessons|session|sessions|teacher|teachers)\b/u',
            '/\bwhat\s+time\b.*\b(class|classes|workshop|workshops|lesson|lessons|session|sessions)\b/u',
            '/\bwhat\b.*\b(available|availability|schedule|run|on)\b/u',
            '/\bwho\s+(teach|teaches|is\s+teaching|are\s+teaching|runs)\b/u',
            '/\bhow\s+(do|can|to)\s+(i\s+)?(book|register|sign\s+up|join)\b/u',
            '/\b(show|list|give)\s+(me\s+)?(the\s+)?(schedule|sessions|workshops|classes|availability)\b/u',
            '/\b(show|list|display|view|see)\s+(me\s+)?(all|every)\s+(the\s+)?(upcoming\s+)?(session|sessions|workshop|workshops|class|classes|lesson|lessons)\b/u',
            '/\b(show|list|display|view|see)\s+(me\s+)?all\s+upcoming\b/u',
            '/\b(all|every)\s+(the\s+)?(upcoming\s+)?(session|sessions|workshop|workshops|class|classes|lesson|lessons)\b/u',
            '/\b(full|complete|entire)\s+schedule\b/u',
            '/\b(schedule|schedules|availability|available|upcoming|timetable|calendar)\b/u',
            '/\b(teach|teaches|teaching|teacher|teachers|instructor)\b/u',
            '/\b(workshops?|lessons?|sessions?|classes)\b/u',
            '/\b(book|booking|booked|enrol|enroll|registration)\b/u',
            '/\b(price|cost|fee|fees)\b/u',
            '/\b(spot|spots|space|spaces|capacity)\b/u',
            '/\b(morning|afternoon|evening)\b/u',
            '/\b(next|this)\s+(week|month)\b/u',
            '/\b(january|february|march|april|may|june|july|august|september|october|november|december|jan|feb|mar|apr|jun|jul|aug|sep|sept|oct|nov|dec)\b/u',
            '/\b(monday|tuesday|wednesday|thursday|friday|saturday|sunday|weekend|weekday)\b/u',
        ];

        foreach ($intentPatterns as $pattern) {
            if (preg_match($pattern, $lower) === 1) {
                return true;
            }
        }

        foreach ($context['teachers'] ?? [] as $t) {
            if ($this->messageMentionsTeacher($t, $lower)) {
                return true;
            }
        }

        foreach ($context['teachers'] ?? [] as $t) {
            foreach ($t['workshops'] ?? [] as $workshop) {
                if ($this->textMentionsPhrase($lower, (string)($workshop['name'] ?? ''))) {
                    return true;
                }
            }
            foreach ($t['upcoming_sessions'] ?? [] as $s) {
                if ($this->textMentionsPhrase($lower, (string)($s['workshop_name'] ?? ''))) {
                    return true;
                }
                if ($this->textMentionsPhrase($lower, (string)($s['notes'] ?? ''))) {
                    return true;
                }
            }
            if ($this->wordsFromTextMatchMessage($lower, (string)($t['specialization'] ?? ''))) {
                return true;
            }
        }

        return false;
    }

    private function textMentionsPhrase(string $lowerMessage, string $phrase): bool
    {
        $phrase = trim(mb_strtolower($phrase));
        if ($phrase === '') {
            return false;
        }
        if (str_contains($lowerMessage, $phrase)) {
            return true;
        }

        return $this->wordsFromTextMatchMessage($lowerMessage, $phrase);
    }

    private function wordsFromTextMatchMessage(string $lowerMessage, string $lowerText): bool
    {
        $words = preg_split('/[^a-z0-9]+/u', mb_strtolower($lowerText), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        foreach ($words as $w) {
            if (mb_strlen($w) < 4) {
                continue;
            }
            if (preg_match('/\b' . preg_quote($w, '/') . '\b/u', $lowerMessage) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<string, mixed> $context
     */
    private function fallbackReply(string $message, array $context, string $scheduleBlock): string
    {
        $lower = mb_strtolower($message);
        $matched = [];
        foreach ($context['teachers'] as $t) {
            if ($this->messageMentionsTeacher($t, $lower)) {
                $matched[] = $t;
            }
        }

        if ($matched !== []) {
            $parts = [];
            foreach ($matched as $t) {
                $parts[] = $this->formatSingleTeacherScheduleReply($t);
            }

            return implode("\n\n", $parts);
        }

        return "I’m not sure how to answer that from the schedule. Try a teacher’s name, a workshop type, or ask to show all upcoming sessions.\n\nYou can book from our Booking page when you are ready.";
    }

    /**
     * @param array<string, mixed> $teacher
     */
    private function messageMentionsTeacher(array $teacher, string $lowerMessage): bool
    {
        $name = trim((string)($teacher['name'] ?? ''));
        if ($name === '') {
            return false;
        }

        $full = mb_strtolower($name);
        if (str_contains($lowerMessage, $full)) {
            return true;
        }

        $tokens = preg_split('/\s+/u', $full, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        foreach ($tokens as $token) {
            if (mb_strlen($token) < 3) {
                continue;
            }
            $quoted = preg_quote($token, '/');
            if (preg_match('/\b' . $quoted . '\b/u', $lowerMessage) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<string, mixed> $t
     */
    private function formatSingleTeacherScheduleReply(array $t): string
    {
        $lines = ["Here's what we have on file for {$t['name']}:"];
        if ($t['upcoming_sessions'] === []) {
            $lines[] = 'No upcoming published session dates.';
        } else {
            foreach ($t['upcoming_sessions'] as $s) {
                $line = '• ' . $s['date'];
                if (!empty($s['time'])) {
                    $line .= ' ' . $s['time'];
                }
                if (!empty($s['workshop_name'])) {
                    $line .= ' · ' . $s['workshop_name'];
                }
                $lines[] = $line;
            }
        }
        $lines[] = '';
        $lines[] = 'You can book from our Booking page when you are ready.';

        return implode("\n", $lines);
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function jsonResponse(array $payload, int $status = 200): Response
    {
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        return $this->response
            ->withStatus($status)
            ->withType('application/json')
            ->withStringBody($json);
    }
}
