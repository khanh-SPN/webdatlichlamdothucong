<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Teachers Model
 *
 * @property \App\Model\Table\WorkshopsTable&\Cake\ORM\Association\HasMany $Workshops
 *
 * @method \App\Model\Entity\Teacher newEmptyEntity()
 * @method \App\Model\Entity\Teacher newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Teacher> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Teacher get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Teacher findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Teacher patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Teacher> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Teacher|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Teacher saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Teacher>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Teacher>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Teacher>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Teacher> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Teacher>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Teacher>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Teacher>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Teacher> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TeachersTable extends Table
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

        $this->setTable('teachers');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Workshops', [
            'foreignKey' => 'teacher_id',
        ]);
        $this->hasMany('TeacherAvailabilitySlots', [
            'foreignKey' => 'teacher_id',
        ]);
        $this->hasMany('TeacherAvailability', [
            'foreignKey' => 'teacher_id',
            'className' => 'TeacherAvailability',
        ]);
        $this->hasMany('Announcements', [
            'foreignKey' => 'teacher_id',
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->scalar('phone')
            ->maxLength('phone', 20)
            ->allowEmptyString('phone');

        $validator
            ->scalar('specialization')
            ->maxLength('specialization', 255)
            ->allowEmptyString('specialization');

        $validator
            ->scalar('bio')
            ->allowEmptyString('bio');

        $validator
            ->scalar('photo')
            ->maxLength('photo', 512)
            ->allowEmptyString('photo');

        return $validator;
    }
}
