<?php
declare(strict_types=1);

namespace App\Service;

use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\Log\Log;

class OpenAiAvailabilityResponder
{
    /**
     * @param list<array{role: string, content: string}> $priorMessages
     */
    public function reply(
        string $userMessage,
        string $schedulePromptBlock,
        string $faqBlock = '',
        string $userBookingsBlock = '',
        array $priorMessages = [],
        string $siteMapBlock = ''
    ): ?string {
        $apiKey = (string)Configure::read('OpenAI.apiKey', '');
        if ($apiKey === '') {
            return null;
        }

        $model = (string)Configure::read('OpenAI.model', 'gpt-4o-mini');

        $system = <<<SYS
You are a friendly studio assistant for a creative workshop studio (pottery, knitting, candles, etc.).
Ground every answer in the sections below only: SITE MAP (where to go on this website), SCHEDULE, FAQ, and MY BOOKINGS when present.
When the user asks what exists on the site, where to find something, or wants a summary or overview, use SITE MAP plus FAQ/SCHEDULE as needed. Give a short, scannable answer: prefer 2–4 bullet lines, then name the most relevant pages from SITE MAP by title only (e.g. “Booking”, “FAQs”).
Do not paste raw http(s) URLs in your reply; the chat shows buttons for key pages.
If something is not covered in these sections, say you do not have that detail and name the closest page from SITE MAP (e.g. FAQs, Contact, Booking); never invent policies, prices, or session times.
For “my booking” questions, use only MY BOOKINGS when that section is present; if it says there are no bookings, say so clearly.
Keep answers concise (aim under 160 words unless they explicitly ask for a full list). Do not invent sessions, teachers, or pages.
SYS;

        $blocks = [];
        if ($siteMapBlock !== '') {
            $blocks[] = $siteMapBlock;
        }
        $blocks[] = "SCHEDULE\n" . $schedulePromptBlock;
        if ($faqBlock !== '') {
            $blocks[] = "FAQ\n" . $faqBlock;
        }
        if ($userBookingsBlock !== '') {
            $blocks[] = "MY BOOKINGS (signed-in user)\n" . $userBookingsBlock;
        }

        $systemContent = $system . "\n\n" . implode("\n\n", $blocks);

        $messages = [
            ['role' => 'system', 'content' => $systemContent],
        ];

        foreach ($priorMessages as $row) {
            $role = $row['role'] ?? '';
            $content = trim((string)($row['content'] ?? ''));
            if (!in_array($role, ['user', 'assistant'], true) || $content === '') {
                continue;
            }
            $messages[] = ['role' => $role, 'content' => $content];
        }

        $messages[] = ['role' => 'user', 'content' => $userMessage];

        $body = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => 0.3,
            'max_tokens' => 520,
        ];

        try {
            $client = new Client(['timeout' => 25]);
            $response = $client->post(
                'https://api.openai.com/v1/chat/completions',
                $body,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $apiKey,
                    ],
                    'type' => 'json',
                ]
            );
        } catch (\Throwable $e) {
            Log::warning('OpenAI request failed: ' . $e->getMessage());

            return null;
        }

        if (!$response->isOk()) {
            Log::warning('OpenAI HTTP ' . $response->getStatusCode() . ': ' . $response->getStringBody());

            return null;
        }

        $json = $response->getJson();
        $content = $json['choices'][0]['message']['content'] ?? null;
        if (!is_string($content) || $content === '') {
            return null;
        }

        return trim($content);
    }
}
