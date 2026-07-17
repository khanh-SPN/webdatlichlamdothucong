<?php
declare(strict_types=1);

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\ORM\Entity;

class CanAuthenticateBehavior extends Behavior
{
    protected Table $table;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    /**
     * Check if user can login
     */
    public function canLogin(Entity $user): bool
    {
        // Example: check role
        if (!in_array($user->role, ['admin', 'customer', 'teacher'], true)) {
            return false;
        }

        // Example: check lock (if you want)
        if (
            isset($user->failed_login_attempts) &&
            $user->failed_login_attempts >= 5
        ) {
            return false;
        }

        return true;
    }
}