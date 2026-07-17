<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Authentication\PasswordHasher\DefaultPasswordHasher;

class UsersTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('email');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        // REQUIRED by assignment
        $this->addBehavior('CanAuthenticate');
         $this->hasOne('Customers', [
        'foreignKey' => 'user_id',
        'dependent' => true,
    ]);
    }

    // ================= DEFAULT VALIDATION =================
    public function validationDefault(Validator $validator): Validator
    {
        $this->buildEmailValidation($validator);
        $this->buildPasswordValidation($validator);

        return $validator;
    }

    // ================= REGISTER VALIDATION =================
    public function validationRegister(Validator $validator): Validator
    {
        $this->buildEmailValidation($validator);
        $this->buildPasswordValidation($validator);

        return $validator;
    }

    // ================= EMAIL =================
    private function buildEmailValidation(Validator $validator): void
    {
        $validator
            ->email('email', false, 'Invalid email format')
            ->requirePresence('email', 'create')
            ->notEmptyString('email', 'Email is required')
            ->maxLength('email', 255)
            ->add('email', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => 'Email already exists'
            ]);
    }

    // ================= PASSWORD =================
    private function buildPasswordValidation(Validator $validator): void
    {
        // Password policy (applies to both registration and password resets):
        // - at least 8 characters
        // - at least one lowercase, one uppercase, one number, and one symbol
        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->minLength('password', 8, 'Password must be at least 8 characters')
            ->add('password', 'complexity', [
                'rule' => function ($value): bool {
                    $password = (string)$value;
                    return (bool)preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[^A-Za-z0-9]).{8,}$/', $password);
                },
                'message' => 'Password must include uppercase, lowercase, number, and symbol',
            ])
            ->requirePresence('password', 'create')
            ->notEmptyString('password', 'Password is required');
    }

    // ================= HASH PASSWORD =================
    public function beforeSave($event, $entity, $options)
    {
        if ($entity->isDirty('password') && !empty($entity->password)) {
            $entity->password = (new DefaultPasswordHasher())->hash($entity->password);
        }
    }

    // ================= RULES =================
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['email'], 'Email already exists'));
        return $rules;
    }

    // ================= AUTH FINDER =================
    public function findAuth(\Cake\ORM\Query $query, array $options)
    {
        return $query->select([
            'id',
            'email',
            'password'
        ]);
    }
}