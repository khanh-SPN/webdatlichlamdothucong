<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AnnouncementsFixture
 */
class AnnouncementsFixture extends TestFixture
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
                'teacher_id' => 1,
                'workshop_id' => null,
                'body' => 'Welcome to the studio.',
                'sent_at' => '2026-05-01 10:00:00',
                'created' => '2026-05-01 10:00:00',
                'modified' => '2026-05-01 10:00:00',
            ],
        ];
        parent::init();
    }
}
