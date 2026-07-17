<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AttendanceRecords Model
 * 
 * Handles attendance tracking for teacher slots
 * 
 * @method \App\Model\Entity\AttendanceRecord newEmptyEntity()
 * @method \App\Model\Entity\AttendanceRecord newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\AttendanceRecord[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AttendanceRecord get($primaryKey, $options = [])
 * @method \App\Model\Entity\AttendanceRecord findOrCreate($search, ?callable $callback = null, $options = [])
 */
class AttendanceRecordsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('attendance_records');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('TeacherAvailabilitySlots', [
            'foreignKey' => 'slot_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Bookings', [
            'foreignKey' => 'booking_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Students', [
            'className' => 'Users',
            'foreignKey' => 'student_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Teachers', [
            'foreignKey' => 'teacher_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('MarkedByUser', [
            'className' => 'Users',
            'foreignKey' => 'marked_by',
            'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('slot_id')
            ->requirePresence('slot_id', 'create')
            ->notEmptyString('slot_id');

        $validator
            ->integer('booking_id')
            ->requirePresence('booking_id', 'create')
            ->notEmptyString('booking_id');

        $validator
            ->integer('student_id')
            ->requirePresence('student_id', 'create')
            ->notEmptyString('student_id');

        $validator
            ->integer('teacher_id')
            ->requirePresence('teacher_id', 'create')
            ->notEmptyString('teacher_id');

        $validator
            ->inList('status', ['present', 'absent', 'late', 'excused'])
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        $validator
            ->dateTime('marked_at')
            ->requirePresence('marked_at', 'create')
            ->notEmptyDateTime('marked_at');

        $validator
            ->integer('marked_by')
            ->requirePresence('marked_by', 'create')
            ->notEmptyString('marked_by');

        $validator
            ->scalar('notes')
            ->allowEmptyString('notes');

        $validator
            ->boolean('is_locked')
            ->allowEmptyString('is_locked');

        $validator
            ->dateTime('locked_at')
            ->allowEmptyDateTime('locked_at');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['slot_id'], 'TeacherAvailabilitySlots'), ['errorField' => 'slot_id']);
        $rules->add($rules->existsIn(['booking_id'], 'Bookings'), ['errorField' => 'booking_id']);
        $rules->add($rules->existsIn(['student_id'], 'Students'), ['errorField' => 'student_id']);
        $rules->add($rules->existsIn(['teacher_id'], 'Teachers'), ['errorField' => 'teacher_id']);
        $rules->add($rules->existsIn(['marked_by'], 'MarkedByUser'), ['errorField' => 'marked_by']);

        // Prevent duplicate attendance records for same slot and student
        $rules->add(function ($entity) {
            $conditions = [
                'slot_id' => $entity->slot_id,
                'student_id' => $entity->student_id,
            ];
            
            if (!$entity->isNew()) {
                $conditions['id !='] = $entity->id;
            }
            
            return !$this->exists($conditions);
        }, 'uniqueAttendance', [
            'errorField' => 'student_id',
            'message' => 'Attendance already recorded for this student in this session.',
        ]);

        // Prevent editing locked records
        $rules->addUpdate(function ($entity) {
            if (!$entity->is_locked) {
                return true;
            }
            
            // Check if existing record is locked
            $existing = $this->get($entity->id);
            if ($existing->is_locked) {
                return false;
            }
            
            return true;
        }, 'preventLockedEdit', [
            'errorField' => 'is_locked',
            'message' => 'This attendance record is locked and cannot be modified.',
        ]);

        return $rules;
    }

    /**
     * Find attendance records for a specific slot
     * 
     * @param \Cake\ORM\Query\SelectQuery $query
     * @param array $options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findForSlot(\Cake\ORM\Query\SelectQuery $query, array $options): \Cake\ORM\Query\SelectQuery
    {
        return $query
            ->where(['AttendanceRecords.slot_id' => $options['slot_id']])
            ->contain(['Students', 'Bookings'])
            ->orderBy(['Students.name' => 'ASC']);
    }

    /**
     * Find attendance records for a teacher
     * 
     * @param \Cake\ORM\Query\SelectQuery $query
     * @param array $options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findForTeacher(\Cake\ORM\Query\SelectQuery $query, array $options): \Cake\ORM\Query\SelectQuery
    {
        $query = $query
            ->where(['AttendanceRecords.teacher_id' => $options['teacher_id']])
            ->contain(['TeacherAvailabilitySlots', 'Students']);

        if (!empty($options['from_date'])) {
            $query->where(['TeacherAvailabilitySlots.session_date >=' => $options['from_date']]);
        }

        if (!empty($options['to_date'])) {
            $query->where(['TeacherAvailabilitySlots.session_date <=' => $options['to_date']]);
        }

        return $query->orderBy(['TeacherAvailabilitySlots.session_date' => 'DESC']);
    }

    /**
     * Find attendance statistics for a student
     * 
     * @param int $studentId
     * @param int|null $teacherId Optional teacher filter
     * @return array
     */
    public function getStudentStats(int $studentId, ?int $teacherId = null): array
    {
        $conditions = ['AttendanceRecords.student_id' => $studentId];
        
        if ($teacherId !== null) {
            $conditions['AttendanceRecords.teacher_id'] = $teacherId;
        }

        $records = $this->find()
            ->select(['status', 'count' => $this->find()->func()->count('*')])
            ->where($conditions)
            ->groupBy(['status'])
            ->toArray();

        $stats = [
            'total' => 0,
            'present' => 0,
            'absent' => 0,
            'late' => 0,
            'excused' => 0,
            'attendance_rate' => 0,
        ];

        foreach ($records as $record) {
            $stats[$record->status] = $record->count;
            $stats['total'] += $record->count;
        }

        // Calculate attendance rate (present + late) / total * 100
        if ($stats['total'] > 0) {
            $stats['attendance_rate'] = round(
                (($stats['present'] + $stats['late']) / $stats['total']) * 100,
                2
            );
        }

        return $stats;
    }

    /**
     * Bulk mark attendance for multiple students
     * 
     * @param int $slotId
     * @param int $teacherId
     * @param int $markedBy
     * @param array $attendanceData Array of ['student_id', 'booking_id', 'status', 'notes']
     * @return array ['success' => int, 'errors' => array]
     */
    public function bulkMarkAttendance(int $slotId, int $teacherId, int $markedBy, array $attendanceData): array
    {
        $results = ['success' => 0, 'errors' => []];
        $now = new \DateTime();

        foreach ($attendanceData as $data) {
            $entity = $this->newEntity([
                'slot_id' => $slotId,
                'booking_id' => $data['booking_id'],
                'student_id' => $data['student_id'],
                'teacher_id' => $teacherId,
                'status' => $data['status'] ?? 'present',
                'marked_at' => $now,
                'marked_by' => $markedBy,
                'notes' => $data['notes'] ?? null,
                'is_locked' => false,
            ]);

            if ($this->save($entity)) {
                $results['success']++;
            } else {
                $results['errors'][] = [
                    'student_id' => $data['student_id'],
                    'errors' => $entity->getErrors(),
                ];
            }
        }

        return $results;
    }

    /**
     * Lock attendance records for a slot (prevent further edits)
     * 
     * @param int $slotId
     * @return int Number of records locked
     */
    public function lockAttendanceForSlot(int $slotId): int
    {
        return $this->updateAll(
            [
                'is_locked' => 1,
                'locked_at' => date('Y-m-d H:i:s'),
            ],
            ['slot_id' => $slotId]
        );
    }
}
