<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class StripeFieldsOnPayments extends AbstractMigration
{
    public function up(): void
    {
        $payments = $this->table('payments');

        if (!$payments->hasColumn('stripe_checkout_session_id')) {
            $payments
                ->addColumn('stripe_checkout_session_id', 'string', [
                    'limit' => 255,
                    'null' => true,
                    'default' => null,
                ])
                ->update();
        }

        if (!$payments->hasColumn('stripe_payment_intent_id')) {
            $payments
                ->addColumn('stripe_payment_intent_id', 'string', [
                    'limit' => 255,
                    'null' => true,
                    'default' => null,
                ])
                ->update();
        }

        if (!$payments->hasColumn('amount_cents')) {
            $payments
                ->addColumn('amount_cents', 'integer', [
                    'null' => true,
                    'default' => null,
                ])
                ->update();
        }

        if (!$payments->hasColumn('currency')) {
            $payments
                ->addColumn('currency', 'string', [
                    'limit' => 10,
                    'null' => true,
                    'default' => null,
                ])
                ->update();
        }

        // Helpful for lookups during webhook processing.
        $payments = $this->table('payments');
        if (method_exists($payments, 'hasIndex') && !$payments->hasIndex(['stripe_checkout_session_id'])) {
            $payments->addIndex(['stripe_checkout_session_id'])->update();
        }
        if (method_exists($payments, 'hasIndex') && !$payments->hasIndex(['stripe_payment_intent_id'])) {
            $payments->addIndex(['stripe_payment_intent_id'])->update();
        }
    }

    public function down(): void
    {
        $payments = $this->table('payments');

        if (method_exists($payments, 'hasIndex') && $payments->hasIndex(['stripe_payment_intent_id'])) {
            $payments->removeIndex(['stripe_payment_intent_id'])->update();
        }
        if (method_exists($payments, 'hasIndex') && $payments->hasIndex(['stripe_checkout_session_id'])) {
            $payments->removeIndex(['stripe_checkout_session_id'])->update();
        }

        if ($payments->hasColumn('currency')) {
            $payments->removeColumn('currency')->update();
        }
        if ($payments->hasColumn('amount_cents')) {
            $payments->removeColumn('amount_cents')->update();
        }
        if ($payments->hasColumn('stripe_payment_intent_id')) {
            $payments->removeColumn('stripe_payment_intent_id')->update();
        }
        if ($payments->hasColumn('stripe_checkout_session_id')) {
            $payments->removeColumn('stripe_checkout_session_id')->update();
        }
    }
}

