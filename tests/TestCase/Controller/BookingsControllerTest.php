<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\BookingsController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\BookingsController Test Case
 *
 * @link \App\Controller\BookingsController
 */
class BookingsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Bookings',
        'app.Users',
        'app.Workshops',
        'app.Payments',
    ];

    public function testAddRenders(): void
    {
        $this->get('/bookings/add');
        $this->assertResponseOk();
    }

    public function testAddPostWithoutLoginRedirectsBack(): void
    {
        $this->enableRetainFlashMessages();

        $this->post('/bookings/add', [
            'workshop_id' => 3,
            'booking_date' => '2026-04-10',
        ]);

        $this->assertResponseSuccess();
        $this->assertResponseCode(302);
    }
}
