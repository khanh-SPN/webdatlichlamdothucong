<?php
declare(strict_types=1);

namespace App\Mailer\Transport;

use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\Mailer\AbstractTransport;
use Cake\Mailer\Message;

/**
 * Brevo (Sendinblue) HTTP API transport.
 *
 * Sends email via Brevo's transactional email API over HTTPS (port 443),
 * bypassing SMTP port blocks common on shared hosting.
 *
 * Config:
 *   'EmailTransport' => [
 *       'default' => [
 *           'className' => \App\Mailer\Transport\BrevoApiTransport::class,
 *           'apiKey' => 'xkeysib-xxxxxxxxxxxxxxx',  // From Brevo dashboard
 *       ],
 *   ],
 *
 * API docs: https://developers.brevo.com/reference/sendtransacemail
 */
class BrevoApiTransport extends AbstractTransport
{
    /**
     * Default config
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [
        'apiKey' => '',
        'endpoint' => 'https://api.brevo.com/v3/smtp/email',
        'timeout' => 30,
    ];

    /**
     * Send the email via Brevo HTTP API
     *
     * @param \Cake\Mailer\Message $message Email message
     * @return array{headers: string, message: string}
     * @throws \RuntimeException When API call fails
     */
    public function send(Message $message): array
    {
        $apiKey = (string)($this->getConfig('apiKey') ?: Configure::read('Brevo.apiKey', ''));
        if ($apiKey === '') {
            throw new \RuntimeException('Brevo API key is not configured. Set Brevo.apiKey or transport apiKey.');
        }

        $endpoint = (string)$this->getConfig('endpoint');
        $timeout = (int)$this->getConfig('timeout');

        // Build payload from CakePHP Message object
        $payload = $this->buildPayload($message);

        $ch = curl_init($endpoint);
        if ($ch === false) {
            throw new \RuntimeException('Failed to initialise cURL for Brevo API.');
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'accept: application/json',
                'content-type: application/json',
                'api-key: ' . $apiKey,
            ],
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_CONNECTTIMEOUT => 15,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        curl_close($ch);

        if ($response === false || $curlErr !== '') {
            Log::error("BREVO_API: cURL error: {$curlErr}");
            throw new \RuntimeException("Brevo API cURL error: {$curlErr}");
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            Log::error("BREVO_API: HTTP {$httpCode} response: {$response}");
            throw new \RuntimeException("Brevo API returned HTTP {$httpCode}: {$response}");
        }

        Log::info("BREVO_API: Email sent successfully (HTTP {$httpCode}): {$response}");

        return [
            'headers' => '',
            'message' => (string)$response,
        ];
    }

    /**
     * Convert CakePHP Message to Brevo API payload
     *
     * @param \Cake\Mailer\Message $message
     * @return array<string, mixed>
     */
    protected function buildPayload(Message $message): array
    {
        // Sender (from)
        $fromArr = $message->getFrom();
        $fromEmail = (string)array_key_first($fromArr);
        $fromName = (string)($fromArr[$fromEmail] ?? '');

        $payload = [
            'sender' => array_filter([
                'email' => $fromEmail,
                'name' => $fromName !== '' ? $fromName : null,
            ]),
            'to' => $this->mapRecipients($message->getTo()),
            'subject' => (string)$message->getSubject(),
        ];

        // Optional CC/BCC/ReplyTo
        $cc = $this->mapRecipients($message->getCc());
        if (!empty($cc)) {
            $payload['cc'] = $cc;
        }
        $bcc = $this->mapRecipients($message->getBcc());
        if (!empty($bcc)) {
            $payload['bcc'] = $bcc;
        }
        $replyTo = $this->mapRecipients($message->getReplyTo());
        if (!empty($replyTo)) {
            $payload['replyTo'] = $replyTo[0];
        }

        // Body (prefer HTML, fall back to text)
        $html = (string)$message->getBodyHtml();
        $text = (string)$message->getBodyText();
        if ($html !== '') {
            $payload['htmlContent'] = $html;
        }
        if ($text !== '') {
            $payload['textContent'] = $text;
        }
        if ($html === '' && $text === '') {
            // CakePHP may store body in getBodyString
            $payload['textContent'] = (string)$message->getBodyString();
        }

        // Attachments
        $attachments = $message->getAttachments();
        if (!empty($attachments)) {
            $apiAttachments = [];
            foreach ($attachments as $name => $attachment) {
                if (isset($attachment['file']) && is_file($attachment['file'])) {
                    $content = base64_encode((string)file_get_contents($attachment['file']));
                } elseif (isset($attachment['data'])) {
                    $content = base64_encode((string)$attachment['data']);
                } else {
                    continue;
                }
                $apiAttachments[] = [
                    'name' => is_string($name) ? $name : (string)($attachment['name'] ?? 'attachment'),
                    'content' => $content,
                ];
            }
            if (!empty($apiAttachments)) {
                $payload['attachment'] = $apiAttachments;
            }
        }

        return $payload;
    }

    /**
     * Convert CakePHP recipient array (email => name) to Brevo format
     *
     * @param array<string, string> $recipients
     * @return array<int, array{email: string, name?: string}>
     */
    protected function mapRecipients(array $recipients): array
    {
        $out = [];
        foreach ($recipients as $email => $name) {
            $entry = ['email' => (string)$email];
            if (!empty($name) && $name !== $email) {
                $entry['name'] = (string)$name;
            }
            $out[] = $entry;
        }
        return $out;
    }
}
