<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddQuantityToBookings extends AbstractMigration
{
    public function up(): void
    {
        $bookings = $this->table('bookings');
        if (!$bookings->hasColumn('quantity')) {
            $bookings->addColumn('quantity', 'integer', [
                'null' => false,
                'default' => 1,
                'signed' => false,
            ])->update();
        }
    }

    public function down(): void
    {
        $bookings = $this->table('bookings');
        if ($bookings->hasColumn('quantity')) {
            $bookings->removeColumn('quantity')->update();
        }
    }
}
