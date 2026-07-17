<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CompanyInfos Model
 *
 * @method \App\Model\Entity\CompanyInfo newEmptyEntity()
 * @method \App\Model\Entity\CompanyInfo newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\CompanyInfo> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CompanyInfo get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\CompanyInfo findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\CompanyInfo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\CompanyInfo> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\CompanyInfo|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\CompanyInfo saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\CompanyInfo>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\CompanyInfo>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\CompanyInfo>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\CompanyInfo> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\CompanyInfo>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\CompanyInfo>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\CompanyInfo>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\CompanyInfo> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CompanyInfosTable extends Table
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

        $this->setTable('company_infos');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmptyString('name');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->scalar('phone')
            ->maxLength('phone', 20)
            ->add('phone', 'validPhone', [
                'rule' => function ($value) {
                    $v = trim((string)$value);
                    if ($v === '') {
                        return true;
                    }
                    // AU numbers only (admin-managed public contact number).
                    // Accepted examples: +61 412 345 678, +61412345678, 0412 345 678, (03) 9123 4567
                    if (preg_match('/^\+?[0-9 ()-]{8,20}$/', $v) !== 1) {
                        return false;
                    }
                    $digits = preg_replace('/\D+/', '', $v);
                    if ($digits === null) {
                        return false;
                    }
                    // Normalise to national significant number (drop AU prefix).
                    if (str_starts_with($digits, '61')) {
                        $nsn = substr($digits, 2);
                    } elseif (str_starts_with($digits, '0')) {
                        $nsn = substr($digits, 1);
                    } else {
                        return false;
                    }

                    // AU NSN is 9 digits: [2|3|4|7|8]xxxxxxxx
                    if ($nsn === false || strlen($nsn) !== 9) {
                        return false;
                    }
                    if (preg_match('/^[23478]\d{8}$/', $nsn) !== 1) {
                        return false;
                    }

                    return true;
                },
                'message' => 'Please enter a valid phone number (e.g. +61 412 345 678).',
            ])
            ->allowEmptyString('phone');

        $validator
            ->scalar('address')
            ->maxLength('address', 255)
            ->allowEmptyString('address');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        return $validator;
    }
}
