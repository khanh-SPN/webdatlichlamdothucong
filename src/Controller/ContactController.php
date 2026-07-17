<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Response;
use Cake\Http\Client;
use Cake\Core\Configure;
use App\Service\EmailService;

class ContactController extends AppController
{
    public function submit(): ?Response
    {
        $this->request->allowMethod(['post']);

        // Get & sanitize input
        $fullName = strip_tags(trim((string)$this->request->getData('full_name')));
        $email    = trim((string)$this->request->getData('email'));
        $phone    = trim((string)$this->request->getData('phone'));
        $subject  = strip_tags(trim((string)$this->request->getData('subject')));
        $message  = strip_tags(trim((string)$this->request->getData('message')));
        $token    = $this->request->getData('cf-turnstile-response');

        // Required fields
        if ($fullName === '' || $email === '' || $phone === '' || $subject === '' || $message === '') {
            $this->Flash->error('Vui lòng điền tất cả các trường.');
            return $this->redirect($this->referer() ?? '/');
        }

        // Email validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->Flash->error('Vui lòng nhập địa chỉ email hợp lệ.');
            return $this->redirect($this->referer() ?? '/');
        }

        // Phone validation (basic)
        $phoneClean = preg_replace('/\D+/', '', $phone);
        if ($phoneClean === null || strlen($phoneClean) < 8 || strlen($phoneClean) > 15) {
            $this->Flash->error('Vui lòng nhập số điện thoại hợp lệ.');
            return $this->redirect($this->referer() ?? '/');
        }

        // Length limits
        if (mb_strlen($fullName) > 100 || mb_strlen($subject) > 200) {
            $this->Flash->error('Đầu vào quá dài.');
            return $this->redirect($this->referer() ?? '/');
        }

        if (mb_strlen($message) > 1000) {
            $this->Flash->error('Tin nhắn quá dài (tối đa 1000 ký tự).');
            return $this->redirect($this->referer() ?? '/');
        }

        // Basic spam pattern
        if (preg_match('/^(.)\1{10,}$/', $message)) {
            $this->Flash->error('Tin nhắn trông giống spam.');
            return $this->redirect($this->referer() ?? '/');
        }

        // Rate limiting (15 seconds)
        $session = $this->request->getSession();
        $lastSent = $session->read('contact_last_sent');

        if ($lastSent && time() - $lastSent < 15) {
            $this->Flash->error('Vui lòng đợi vài giây trước khi gửi tin nhắn khác.');
            return $this->redirect($this->referer() ?? '/');
        }

        // CAPTCHA check
        if (empty($token)) {
            $this->Flash->error('Vui lòng hoàn thành CAPTCHA.');
            return $this->redirect($this->referer() ?? '/');
        }

        $http = new Client();

        $response = $http->post(
            'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            [
                'secret' => Configure::read('Captcha.turnstile.secretKey'),
                'response' => $token,
                'remoteip' => $this->request->clientIp(),
            ]
        );

        if (!$response->isOk()) {
            $this->Flash->error('Xác minh CAPTCHA thất bại. Vui lòng thử lại.');
            return $this->redirect($this->referer() ?? '/');
        }

        $result = $response->getJson();

        if (empty($result['success'])) {
            $this->Flash->error('Xác minh CAPTCHA thất bại. Vui lòng thử lại.');
            return $this->redirect($this->referer() ?? '/');
        }

        // Save rate limit timestamp AFTER success
        $session->write('contact_last_sent', time());

        // Save to database if Enquiries table exists
        try {
            $enquiriesTable = $this->fetchTable('Enquiries');
            $enquiry = $enquiriesTable->newEmptyEntity();
            $enquiry->full_name = $fullName;
            $enquiry->email = $email;
            $enquiry->phone = $phoneClean;
            $enquiry->subject = $subject;
            $enquiry->message = $message;
            $enquiry->status = 'received';
            $enquiriesTable->save($enquiry);
        } catch (\Exception $e) {
            // Log but don't fail if database save fails
            \Cake\Log\Log::warning("Could not save enquiry to database: {$e->getMessage()}");
        }

        // Send confirmation email to user
        $emailService = new EmailService();
        $emailService->sendContactConfirmation($email, $fullName, $subject, $message);

        // Success message
        $this->Flash->success('Cảm ơn tin nhắn của bạn. Chúng tôi sẽ phản hồi sớm. Email xác nhận đã được gửi đến ' . h($email) . '.');

        return $this->redirect($this->referer() ?? '/');
    }
}