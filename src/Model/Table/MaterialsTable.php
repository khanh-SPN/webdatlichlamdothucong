<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Materials Model
 *
 * @property \App\Model\Table\WorkshopsTable&\Cake\ORM\Association\BelongsTo $Workshops
 *
 * @method \App\Model\Entity\Material newEmptyEntity()
 * @method \App\Model\Entity\Material newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Material> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Material get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Material findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Material patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Material> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Material|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Material saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Material>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Material>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Material>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Material> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Material>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Material>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Material>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Material> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MaterialsTable extends Table
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

        $this->setTable('materials');
        $this->setDisplayField('material_name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Workshops', [
            'foreignKey' => 'workshop_id',
            'joinType' => 'LEFT',
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
            ->scalar('material_name')
            ->maxLength('material_name', 255)
            ->requirePresence('material_name', 'create')
            ->notEmptyString('material_name');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->integer('quantity_required')
            ->allowEmptyString('quantity_required');

        $validator
            ->integer('workshop_id')
            ->notEmptyString('workshop_id');

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
        $rules->add($rules->existsIn(['workshop_id'], 'Workshops'), ['errorField' => 'workshop_id']);

        return $rules;
    }
}
