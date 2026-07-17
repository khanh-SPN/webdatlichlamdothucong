<?php
declare(strict_types=1);

namespace App\Service;

use Cake\Mailer\Mailer;
use Cake\Core\Configure;
use Cake\Log\Log;

/**
 * Email Service - Centralized email sending
 */
class EmailService
{
    /**
     * Send contact form confirmation email
     *
     * @param string $userEmail User's email
     * @param string $fullName User's full name
     * @param string $subject Contact form subject
     * @param string $message Contact form message
     * @return bool True if sent successfully
     */
    public function sendContactConfirmation(
        string $userEmail,
        string $fullName,
        string $subject,
        string $message
    ): bool {
        try {
            $mailer = new Mailer();
            $mailer
                ->setTransport('default')
                ->setFrom(Configure::read('Email.from', ['candlecraft.fit3047@gmail.com' => 'Candlecraft']))
                ->setTo($userEmail)
                ->setSubject('We received your message - ' . substr($subject, 0, 50))
                ->setEmailFormat('html')
                ->viewBuilder()
                ->setTemplate('contact_confirmation')
                ->setLayout('default')
                ->setVars([
                    'fullName' => $fullName,
                    'subject' => $subject,
                    'message' => $message,
                    'receivedAt' => date('Y-m-d H:i:s'),
                ])
            ;

            $mailer->send();
            Log::info("Contact confirmation email sent to {$userEmail}");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send contact confirmation email: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Send payment receipt email
     *
     * @param string $userEmail User's email
     * @param string $userName User's full name
     * @param array<string> $bookingDetails Array of booking details for template
     * @param float $totalPrice Total price
     * @param float $discountAmount Discount amount
     * @param float $finalPrice Final price after discount
     * @param string $paymentMethod Payment method (e.g., 'Stripe')
     * @return bool True if sent successfully
     */
    public function sendPaymentReceipt(
        string $userEmail,
        string $userName,
        array $bookingDetails,
        float $totalPrice,
        float $discountAmount,
        float $finalPrice,
        string $paymentMethod = 'Stripe'
    ): bool {
        try {
            $mailer = new Mailer();
            $mailer
                ->setTransport('default')
                ->setFrom(Configure::read('Email.from', ['candlecraft.fit3047@gmail.com' => 'Candlecraft']))
                ->setTo($userEmail)
                ->setSubject('Payment Receipt - Candlelight Studio')
                ->setEmailFormat('html')
                ->viewBuilder()
                ->setTemplate('payment_receipt')
                ->setLayout('default')
                ->setVars([
                    'userName' => $userName,
                    'bookingDetails' => $bookingDetails,
                    'totalPrice' => $totalPrice,
                    'discountAmount' => $discountAmount,
                    'discountPercent' => $discountAmount > 0 ? (int)(($discountAmount / $totalPrice) * 100) : 0,
                    'finalPrice' => $finalPrice,
                    'paymentMethod' => $paymentMethod,
                    'paymentDate' => date('Y-m-d H:i:s'),
                    'receiptId' => 'RCPT-' . strtoupper(substr(md5((string)time()), 0, 8)),
                ])
            ;

            $mailer->send();
            Log::info("Payment receipt email sent to {$userEmail}");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send payment receipt email: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Send booking confirmation email for pending bookings (payment required)
     *
     * @param string $userEmail User's email
     * @param string $userName User's full name
     * @param array<string> $bookingDetails Array of booking details
     * @param float $totalPrice Total price
     * @return bool True if sent successfully
     */
    public function sendBookingConfirmation(
        string $userEmail,
        string $userName,
        array $bookingDetails,
        float $totalPrice = 0
    ): bool {
        Log::info("EMAIL_SERVICE_BOOKING: Starting sendBookingConfirmation for {$userEmail}, userName={$userName}, items=" . count($bookingDetails));

        try {
            $from = Configure::read('Email.from', ['candlecraft.fit3047@gmail.com' => 'Candlecraft']);
            Log::info("EMAIL_SERVICE_BOOKING: Config from=" . json_encode($from));

            $mailer = new Mailer();
            Log::info("EMAIL_SERVICE_BOOKING: Mailer created");

            $mailer
                ->setTransport('default')
                ->setFrom($from)
                ->setTo($userEmail)
                ->setSubject('Your Booking is Reserved – Complete Payment | Candlecraft')
                ->setEmailFormat('html')
                ->viewBuilder()
                ->setTemplate('booking_confirmation')
                ->setLayout('default')
                ->setVars([
                    'userName' => $userName,
                    'bookingDetails' => $bookingDetails,
                    'totalPrice' => $totalPrice,
                    'createdAt' => date('Y-m-d H:i:s'),
                ])
            ;
            Log::info("EMAIL_SERVICE_BOOKING: Mailer configured, about to send...");

            $result = $mailer->send();
            Log::info("EMAIL_SERVICE_BOOKING: Email SENT to {$userEmail}, result=" . ($result ? 'success' : 'returned false'));
            return true;
        } catch (\Exception $e) {
            Log::error("EMAIL_SERVICE_BOOKING: FAILED to send to {$userEmail}: {$e->getMessage()}");
            Log::error("EMAIL_SERVICE_BOOKING: Exception code: {$e->getCode()}");
            Log::error("EMAIL_SERVICE_BOOKING: Stack: " . substr($e->getTraceAsString(), 0, 500));
            return false;
        }
    }

    /**
     * Send teacher announcement to students
     *
     * @param array<string> $studentEmails Array of student emails
     * @param string $teacherName Instructor's name
     * @param string $workshopName Workshop name
     * @param string $subject Announcement subject
     * @param string $message Announcement message
     * @return int Number of emails sent successfully
     */
    public function sendTeacherAnnouncement(
        array $studentEmails,
        string $teacherName,
        string $workshopName,
        string $subject,
        string $message
    ): int {
        $sent = 0;
        foreach ($studentEmails as $email) {
            try {
                $mailer = new Mailer();
                $mailer
                    ->setTransport('default')
                    ->setFrom(Configure::read('Email.from', ['candlecraft.fit3047@gmail.com' => 'Candlecraft']))
                    ->setTo($email)
                    ->setSubject($subject . ' - ' . $workshopName)
                    ->setEmailFormat('html')
                    ->viewBuilder()
                    ->setTemplate('teacher_announcement')
                    ->setLayout('default')
                    ->setVars([
                        'teacherName' => $teacherName,
                        'workshopName' => $workshopName,
                        'subject' => $subject,
                        'message' => $message,
                        'sentAt' => date('Y-m-d H:i:s'),
                    ])
                ;

                $mailer->send();
                $sent++;
            } catch (\Exception $e) {
                Log::error("Failed to send announcement to {$email}: {$e->getMessage()}");
            }
        }
        Log::info("Announcement sent to {$sent} out of " . count($studentEmails) . " students");
        return $sent;
    }
}
