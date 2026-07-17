<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class TeacherAvailabilityTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('teacher_availability');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Teachers', [
            'foreignKey' => 'teacher_id',
            'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('teacher_id')
            ->requirePresence('teacher_id', 'create')
            ->notEmptyString('teacher_id');

        $validator
            ->integer('day_of_week')
            ->greaterThanOrEqual('day_of_week', 0)
            ->lessThanOrEqual('day_of_week', 6)
            ->requirePresence('day_of_week', 'create')
            ->notEmptyString('day_of_week');

        $validator
            ->scalar('start_time')
            ->maxLength('start_time', 8)
            ->requirePresence('start_time', 'create')
            ->notEmptyString('start_time');

        $validator
            ->scalar('end_time')
            ->maxLength('end_time', 8)
            ->requirePresence('end_time', 'create')
            ->notEmptyString('end_time');

        $validator
            ->boolean('is_active')
            ->allowEmptyString('is_active');

        return $validator;
    }
}
