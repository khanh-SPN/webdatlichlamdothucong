<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddCheckoutGroupToBookingsPayments extends AbstractMigration
{
    public function up(): void
    {
        $bookings = $this->table('bookings');
        if (!$bookings->hasColumn('checkout_group')) {
            $bookings->addColumn('checkout_group', 'string', [
                'limit' => 36,
                'null' => true,
                'default' => null,
            ])->update();
            $bookings->addIndex(['checkout_group'])->update();
        }

        $payments = $this->table('payments');
        if (!$payments->hasColumn('checkout_group')) {
            $payments->addColumn('checkout_group', 'string', [
                'limit' => 36,
                'null' => true,
                'default' => null,
            ])->update();
            $payments->addIndex(['checkout_group'])->update();
        }
        if (!$payments->hasColumn('stripe_session_id')) {
            $payments->addColumn('stripe_session_id', 'string', [
                'limit' => 255,
                'null' => true,
                'default' => null,
            ])->update();
            $payments->addIndex(['stripe_session_id'])->update();
        }
    }

    public function down(): void
    {
        $payments = $this->table('payments');
        if ($payments->hasColumn('stripe_session_id')) {
            $payments->removeColumn('stripe_session_id')->update();
        }
        if ($payments->hasColumn('checkout_group')) {
            $payments->removeColumn('checkout_group')->update();
        }

        $bookings = $this->table('bookings');
        if ($bookings->hasColumn('checkout_group')) {
            $bookings->removeColumn('checkout_group')->update();
        }
    }
}
