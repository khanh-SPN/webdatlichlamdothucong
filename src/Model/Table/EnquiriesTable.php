<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Enquiries Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Enquiry newEmptyEntity()
 * @method \App\Model\Entity\Enquiry newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Enquiry> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Enquiry get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Enquiry findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Enquiry patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Enquiry> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Enquiry|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Enquiry saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Enquiry>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Enquiry>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Enquiry>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Enquiry> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Enquiry>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Enquiry>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Enquiry>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Enquiry> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EnquiriesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('enquiries');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('first_name')
            ->maxLength('first_name', 100)
            ->notEmptyString('first_name', 'First name is required');

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 100)
            ->notEmptyString('last_name', 'Last name is required');

        $validator
            ->email('email')
            ->notEmptyString('email', 'Email is required');

        $validator
            ->scalar('phone')
            ->maxLength('phone', 20)
            ->add('phone', 'validAuPhone', [
                'rule' => function ($value) {
                    $v = trim((string)$value);
                    if ($v === '') {
                        return false;
                    }
                    // AU numbers only.
                    // Accepted examples: +61 412 345 678, +61412345678, 0412 345 678, (03) 9123 4567
                    if (preg_match('/^\+?[0-9 ()-]{8,20}$/', $v) !== 1) {
                        return false;
                    }
                    $digits = preg_replace('/\D+/', '', $v);
                    if ($digits === null) {
                        return false;
                    }
                    if (str_starts_with($digits, '61')) {
                        $nsn = substr($digits, 2);
                    } elseif (str_starts_with($digits, '0')) {
                        $nsn = substr($digits, 1);
                    } else {
                        return false;
                    }
                    if ($nsn === false || strlen($nsn) !== 9) {
                        return false;
                    }
                    return preg_match('/^[23478]\d{8}$/', $nsn) === 1;
                },
                'message' => 'Please enter a valid phone number (e.g. +61 412 345 678).',
            ])
            ->notEmptyString('phone', 'Phone is required');

        $validator
            ->scalar('subject')
            ->maxLength('subject', 255)
            ->notEmptyString('subject', 'Subject is required');

        $validator
            ->scalar('message')
            ->notEmptyString('message', 'Message is required');

        $validator
            ->scalar('status')
            ->maxLength('status', 20)
            ->notEmptyString('status');

        $validator
            ->integer('user_id')
            ->allowEmptyString('user_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
