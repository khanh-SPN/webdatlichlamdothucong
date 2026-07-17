<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * BookingsFixture
 */
class BookingsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'user_id' => 1,
                'workshop_id' => 1,
                'booking_date' => '2026-06-01',
                'status' => 'confirmed',
                'quantity' => 1,
                'checkout_group' => null,
                'attendance_status' => null,
                'attendance_updated' => null,
                'created' => '2026-03-23 08:18:21',
                'modified' => '2026-03-23 08:18:21',
            ],
        ];
        parent::init();
    }
}
