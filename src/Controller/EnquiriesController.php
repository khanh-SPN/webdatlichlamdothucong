<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\Log\Log;

class EnquiriesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        
        $this->Authentication->allowUnauthenticated(['add']);
    }

    // ================= ADD ENQUIRY =================
    public function add()
    {
        $this->request->allowMethod(['post']);

        $enquiry = $this->Enquiries->newEmptyEntity();

        $data = $this->request->getData();
        $turnstileToken = $data['cf-turnstile-response'] ?? null;
        unset($data['cf-turnstile-response']);

        $secret = trim((string)(Configure::read('Captcha.turnstile.secretKey') ?? ''));
        $debug = (bool)Configure::read('debug');

        if ($secret !== '') {
            if ($turnstileToken === null || $turnstileToken === '') {
                $this->Flash->error('Please complete the verification before sending your message.');

                return $this->redirect($this->referer() ?? '/');
            }

            try {
                $http = new Client(['timeout' => 10]);
                $verify = $http->post(
                    'https://challenges.cloudflare.com/turnstile/v0/siteverify',
                    [
                        'secret' => $secret,
                        'response' => $turnstileToken,
                    ]
                );
            } catch (\Throwable $e) {
                Log::error('Turnstile verify request failed: ' . $e->getMessage());
                $this->Flash->error('Verification failed. Please try again.');

                return $this->redirect($this->referer() ?? '/');
            }

            if (!$verify->isOk()) {
                Log::error('Turnstile verify HTTP ' . $verify->getStatusCode() . ': ' . $verify->getStringBody());
                $this->Flash->error('Verification failed. Please try again.');

                return $this->redirect($this->referer() ?? '/');
            }

            $verifyJson = $verify->getJson();
            if (empty($verifyJson['success'])) {
                Log::error('Turnstile verify rejected: ' . json_encode([
                    'error-codes' => $verifyJson['error-codes'] ?? null,
                    'hostname' => $verifyJson['hostname'] ?? null,
                    'action' => $verifyJson['action'] ?? null,
                    'cdata' => $verifyJson['cdata'] ?? null,
                ]));
                $this->Flash->error('Verification failed. Please complete the check again.');

                return $this->redirect($this->referer() ?? '/');
            }
        } elseif ($debug) {
            Log::warning('Turnstile skipped: TURNSTILE_SECRET_KEY is not set (debug mode).');
        } else {
            Log::error('Turnstile secret key missing while DEBUG is false; contact form cannot be verified.');

            $this->Flash->error(
                'This form cannot accept messages until Turnstile is fully configured (set TURNSTILE_SECRET_KEY).'
            );

            return $this->redirect($this->referer() ?? '/');
        }

        Log::debug('ENQUIRY DATA: ' . json_encode($data));

        // Default enquiry status
        $data['status'] = 'pending';

        
        $user = $this->request->getAttribute('identity');
        if ($user) {
            $data['user_id'] = $user->id;
        }

        $enquiry = $this->Enquiries->patchEntity($enquiry, $data);

        if ($enquiry->getErrors()) {
            Log::error('ENQUIRY VALIDATION ERROR: ' . json_encode($enquiry->getErrors()));
        }

        if ($this->Enquiries->save($enquiry)) {
            Log::debug('ENQUIRY SAVED ID: ' . $enquiry->id);

            $this->Flash->success('Your message has been sent successfully');

        } else {
            Log::error('ENQUIRY SAVE FAILED');

            $this->Flash->error('Failed to send message. Please try again');
        }

        return $this->redirect('/');
    }

    // ================= ADMIN LIST =================
    public function index()
    {
        $this->checkAdmin();

        $enquiries = $this->paginate(
            $this->Enquiries->find()->orderBy(['created' => 'DESC'])
        );

        $this->set(compact('enquiries'));
    }

    // ================= UPDATE STATUS =================
    public function updateStatus($id, $status)
    {
        $this->checkAdmin();

        $enquiry = $this->Enquiries->get($id);

        $enquiry->status = $status;

        if ($this->Enquiries->save($enquiry)) {
            $this->Flash->success('Status updated');
        } else {
            $this->Flash->error('Update failed');
        }

        return $this->redirect(['action' => 'index']);
    }

    // ================= DELETE =================
    public function delete($id)
    {
        $this->checkAdmin();

        $this->request->allowMethod(['post']);

        $enquiry = $this->Enquiries->get($id);

        if ($this->Enquiries->delete($enquiry)) {
            $this->Flash->success('Deleted');
        } else {
            $this->Flash->error('Delete failed');
        }

        return $this->redirect(['action' => 'index']);
    }

    // ================= ADMIN CHECK =================
    private function checkAdmin()
    {
        $user = $this->request->getAttribute('identity');

        if (!$user || $user->role !== 'admin') {
            throw new \Cake\Http\Exception\ForbiddenException('Access denied');
        }
    }
    public function view($id)
{
    $this->checkAdmin();

    $enquiry = $this->Enquiries->get($id);
    $this->set(compact('enquiry'));
}
    public function markReplied($id)
{
    $this->checkAdmin();

    $enquiry = $this->Enquiries->get($id);
    $enquiry->status = 'replied';

    if ($this->Enquiries->save($enquiry)) {
        $this->Flash->success('Marked as replied');
    } else {
        $this->Flash->error('Failed');
    }

    return $this->redirect(['action' => 'index']);
}
}