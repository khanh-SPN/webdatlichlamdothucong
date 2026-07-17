<?php
declare(strict_types=1);

namespace App\Controller;

use Stripe\Stripe;
use Stripe\Webhook;

class StripeController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        // Webhooks are machine-to-machine; do not require auth.
        $this->Authentication->allowUnauthenticated(['webhook']);
    }

    public function webhook()
    {
        $this->request->allowMethod(['post']);
        $this->autoRender = false;

        $stripeKey = (string)env('STRIPE_SECRET_KEY', '');
        $webhookSecret = (string)env('STRIPE_WEBHOOK_SECRET', '');

        if ($stripeKey === '' || $webhookSecret === '') {
            return $this->response
                ->withStatus(500)
                ->withType('application/json')
                ->withStringBody(json_encode(['error' => 'Stripe not configured']));
        }

        Stripe::setApiKey($stripeKey);

        $payload = (string)$this->request->getBody()->getContents();
        $sigHeader = (string)$this->request->getHeaderLine('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\Throwable $e) {
            return $this->response
                ->withStatus(400)
                ->withType('application/json')
                ->withStringBody(json_encode(['error' => 'Invalid signature']));
        }

        if ($event->type !== 'checkout.session.completed') {
            return $this->response
                ->withStatus(200)
                ->withType('application/json')
                ->withStringBody(json_encode(['received' => true]));
        }

        /** @var object $session */
        $session = $event->data->object;
        $checkoutSessionId = isset($session->id) ? (string)$session->id : '';
        $paymentIntentId = isset($session->payment_intent) ? (string)$session->payment_intent : null;

        $paymentIds = [];
        if (isset($session->metadata) && is_object($session->metadata) && isset($session->metadata->payment_ids)) {
            $decoded = json_decode((string)$session->metadata->payment_ids, true);
            if (is_array($decoded)) {
                foreach ($decoded as $pid) {
                    if (is_numeric($pid)) {
                        $paymentIds[] = (int)$pid;
                    }
                }
            }
        }

        $paymentsTable = $this->fetchTable('Payments');
        $bookingsTable = $this->fetchTable('Bookings');
        $conn = $paymentsTable->getConnection();

        $conn->transactional(function () use (
            $paymentsTable,
            $bookingsTable,
            $paymentIds,
            $checkoutSessionId,
            $paymentIntentId
        ) {
            $payments = [];

            if ($paymentIds !== []) {
                foreach ($paymentIds as $pid) {
                    try {
                        $payments[] = $paymentsTable->get($pid, contain: ['Bookings']);
                    } catch (\Throwable $e) {
                        // ignore missing ids
                    }
                }
            } elseif ($checkoutSessionId !== '') {
                $payments = $paymentsTable->find()
                    ->where(['stripe_checkout_session_id' => $checkoutSessionId])
                    ->contain(['Bookings'])
                    ->all()
                    ->toList();
            }

            foreach ($payments as $payment) {
                if ($payment->payment_status !== 'paid') {
                    $payment->payment_status = 'paid';
                }
                if ($checkoutSessionId !== '') {
                    $payment->stripe_checkout_session_id = $checkoutSessionId;
                }
                if ($paymentIntentId !== null && $paymentIntentId !== '') {
                    $payment->stripe_payment_intent_id = $paymentIntentId;
                }
                $paymentsTable->save($payment);

                if (!empty($payment->booking)) {
                    $booking = $payment->booking;
                    if ($booking->status !== 'confirmed') {
                        $booking->status = 'confirmed';
                        $bookingsTable->save($booking);
                    }
                }
            }
        });

        return $this->response
            ->withStatus(200)
            ->withType('application/json')
            ->withStringBody(json_encode(['received' => true]));
    }
}

