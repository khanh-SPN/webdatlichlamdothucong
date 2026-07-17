<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddLoginLockToUsers extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     *
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('users');

        $table->addColumn('failed_login_attempts', 'integer', [
            'default' => 0,
            'null'    => false,
            'signed'  => false,
            'limit'   => 11,
            'comment' => 'Number of failed login attempts'
        ])
        ->addColumn('last_failed_login', 'datetime', [
            'default' => null,
            'null'    => true,
            'comment' => 'Timestamp of the last failed login'
        ])
        ->update();
    }
}
