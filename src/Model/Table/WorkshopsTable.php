<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Workshops Model
 *
 * @property \App\Model\Table\TeachersTable&\Cake\ORM\Association\BelongsTo $Teachers
 * @property \App\Model\Table\BookingsTable&\Cake\ORM\Association\HasMany $Bookings
 * @property \App\Model\Table\MaterialsTable&\Cake\ORM\Association\HasMany $Materials
 *
 * @method \App\Model\Entity\Workshop newEmptyEntity()
 * @method \App\Model\Entity\Workshop newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Workshop> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Workshop get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Workshop findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Workshop patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Workshop> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Workshop|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Workshop saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Workshop>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Workshop>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Workshop>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Workshop> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Workshop>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Workshop>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Workshop>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Workshop> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WorkshopsTable extends Table
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

        $this->setTable('workshops');
        $this->setDisplayField('workshop_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Teachers', [
            'foreignKey' => 'teacher_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Bookings', [
            'foreignKey' => 'workshop_id',
        ]);
        $this->hasMany('Materials', [
            'foreignKey' => 'workshop_id',
        ]);
        $this->hasMany('Announcements', [
            'foreignKey' => 'workshop_id',
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
            ->scalar('workshop_name')
            ->maxLength('workshop_name', 255)
            ->requirePresence('workshop_name', 'create')
            ->notEmptyString('workshop_name');

        $validator
            ->scalar('workshop_type')
            ->maxLength('workshop_type', 100)
            ->allowEmptyString('workshop_type');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->decimal('price')
            ->requirePresence('price', 'create')
            ->notEmptyString('price');

        $validator
            ->integer('teacher_id')
            ->notEmptyString('teacher_id');

        $validator
            ->integer('capacity')
            ->greaterThanOrEqual('capacity', 1)
            ->lessThanOrEqual('capacity', 500)
            ->allowEmptyString('capacity');

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
        $rules->add($rules->existsIn(['teacher_id'], 'Teachers'), ['errorField' => 'teacher_id']);

        return $rules;
    }
}
