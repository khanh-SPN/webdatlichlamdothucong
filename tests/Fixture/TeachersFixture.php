<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TeachersFixture
 */
class TeachersFixture extends TestFixture
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
                'name' => 'Test Teacher',
                'email' => 'teacher@example.com',
                'phone' => '+61000000000',
                'specialization' => 'Pottery',
                'created' => '2026-03-23 07:23:40',
                'modified' => '2026-03-23 07:23:40',
            ],
        ];
        parent::init();
    }
}
