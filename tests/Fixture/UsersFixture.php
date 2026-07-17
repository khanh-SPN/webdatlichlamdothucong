<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $hash = (new DefaultPasswordHasher())->hash('password');
        $this->records = [
            [
                'id' => 1,
                'email' => 'customer@example.com',
                'password' => $hash,
                'role' => 'customer',
                'nonce' => '',
                'nonce_expiry' => null,
                'created' => '2026-03-23 05:21:55',
                'modified' => '2026-03-23 05:21:55',
                'failed_login_attempts' => 0,
                'last_failed_login' => null,
            ],
            [
                'id' => 2,
                'email' => 'teacher@example.com',
                'password' => $hash,
                'role' => 'teacher',
                'nonce' => '',
                'nonce_expiry' => null,
                'created' => '2026-03-23 05:21:55',
                'modified' => '2026-03-23 05:21:55',
                'failed_login_attempts' => 0,
                'last_failed_login' => null,
            ],
        ];
        parent::init();
    }
}
