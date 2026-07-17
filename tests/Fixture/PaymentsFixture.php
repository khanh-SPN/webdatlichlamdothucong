<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PaymentsFixture
 */
class PaymentsFixture extends TestFixture
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
                'booking_id' => 1,
                'payment_method' => 'Lorem ipsum dolor sit amet',
                'payment_status' => 'Lorem ipsum dolor sit amet',
                'created' => '2026-03-23 08:18:09',
                'modified' => '2026-03-23 08:18:09',
            ],
        ];
        parent::init();
    }
}
