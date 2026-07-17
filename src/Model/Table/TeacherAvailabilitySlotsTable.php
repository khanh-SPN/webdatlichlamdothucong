<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * @method \App\Model\Entity\TeacherAvailabilitySlot newEmptyEntity()
 * @method \App\Model\Entity\TeacherAvailabilitySlot newEntity(array $data, array $options = [])
 */
class TeacherAvailabilitySlotsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('teacher_availability_slots');
        $this->setDisplayField('session_date');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Teachers', [
            'foreignKey' => 'teacher_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Workshops', [
            'foreignKey' => 'workshop_id',
            'joinType' => 'LEFT',
        ]);
        $this->hasMany('Bookings', [
            'foreignKey' => 'slot_id',
        ]);
        $this->hasMany('AttendanceRecords', [
            'foreignKey' => 'slot_id',
        ]);
    }

    /**
     * Find active slots that are bookable
     *
     * @param \Cake\ORM\Query\SelectQuery $query
     * @param array $options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findActive(\Cake\ORM\Query\SelectQuery $query, array $options): \Cake\ORM\Query\SelectQuery
    {
        return $query->where(['TeacherAvailabilitySlots.is_active' => true]);
    }

    /**
     * Find available slots for a specific workshop
     *
     * @param int $workshopId
     * @param string|null $date Optional date filter (Y-m-d)
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findAvailableForWorkshop(int $workshopId, ?string $date = null): \Cake\ORM\Query\SelectQuery
    {
        $query = $this->find()
            ->where([
                'TeacherAvailabilitySlots.workshop_id' => $workshopId,
                'TeacherAvailabilitySlots.is_active' => true,
            ])
            ->contain(['Workshops', 'Teachers'])
            ->orderBy(['TeacherAvailabilitySlots.session_date' => 'ASC']);

        if ($date !== null) {
            $query->where(['TeacherAvailabilitySlots.session_date' => $date]);
        }

        return $query;
    }

    /**
     * Get slot with available seats calculated
     *
     * @param int $slotId
     * @return \App\Model\Entity\TeacherAvailabilitySlot|null
     */
    public function getWithAvailability(int $slotId): ?\App\Model\Entity\TeacherAvailabilitySlot
    {
        $slot = $this->get($slotId, contain: ['Workshops']);
        if (!$slot) {
            return null;
        }

        return $slot;
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('teacher_id')
            ->requirePresence('teacher_id', 'create')
            ->notEmptyString('teacher_id');

        $validator
            ->integer('workshop_id')
            ->allowEmptyString('workshop_id');

        $validator
            ->date('session_date')
            ->requirePresence('session_date', 'create')
            ->notEmptyDate('session_date');

        $validator
            ->scalar('time_label')
            ->maxLength('time_label', 64)
            ->allowEmptyString('time_label');

        $validator
            ->time('start_time')
            ->requirePresence('start_time', 'create')
            ->notEmptyTime('start_time');

        $validator
            ->time('end_time')
            ->requirePresence('end_time', 'create')
            ->notEmptyTime('end_time');

        $validator
            ->scalar('notes')
            ->allowEmptyString('notes');

        $validator
            ->integer('capacity')
            ->greaterThanOrEqual('capacity', 1)
            ->lessThanOrEqual('capacity', 100)
            ->allowEmptyString('capacity');

        $validator
            ->boolean('is_active')
            ->allowEmptyString('is_active');

        $validator
            ->integer('seats_booked')
            ->greaterThanOrEqual('seats_booked', 0)
            ->allowEmptyString('seats_booked');

        $validator
            ->inList('status', ['available', 'reserved', 'blocked', 'expired', 'cancelled'])
            ->allowEmptyString('status');

        $validator
            ->scalar('location')
            ->maxLength('location', 255)
            ->allowEmptyString('location');

        $validator
            ->boolean('is_recurring')
            ->allowEmptyString('is_recurring');

        $validator
            ->scalar('recurrence_pattern')
            ->maxLength('recurrence_pattern', 100)
            ->allowEmptyString('recurrence_pattern');

        $validator
            ->integer('parent_slot_id')
            ->allowEmptyString('parent_slot_id');

        $validator
            ->dateTime('cancelled_at')
            ->allowEmptyDateTime('cancelled_at');

        $validator
            ->scalar('cancellation_reason')
            ->allowEmptyString('cancellation_reason');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['teacher_id'], 'Teachers'), ['errorField' => 'teacher_id']);
        $rules->add($rules->existsIn(['workshop_id'], 'Workshops', ['allowNullableNulls' => true]), ['errorField' => 'workshop_id']);

        // Prevent overlapping slots for the same teacher on the same date
        $rules->addCreate(function ($entity) {
            return !$this->hasOverlappingSlot($entity);
        }, 'noOverlapping', [
            'errorField' => 'start_time',
            'message' => 'This time slot overlaps with an existing slot. Please choose a different time.',
        ]);

        // Prevent updating to overlapping times
        $rules->addUpdate(function ($entity) {
            return !$this->hasOverlappingSlot($entity);
        }, 'noOverlappingUpdate', [
            'errorField' => 'start_time',
            'message' => 'This time slot overlaps with an existing slot. Please choose a different time.',
        ]);

        return $rules;
    }

    /**
     * Check if a slot overlaps with existing slots
     * 
     * @param \App\Model\Entity\TeacherAvailabilitySlot $entity
     * @return bool
     */
    /**
     * Normalize a time value to 'H:i:s' string regardless of type
     */
    private function timeStr($time): string
    {
        if ($time === null) return '00:00:00';
        if (is_string($time)) {
            // Accept 'H:i' or 'H:i:s'
            return strlen($time) === 5 ? $time . ':00' : $time;
        }
        return $time->format('H:i:s');
    }

    public function hasOverlappingSlot($entity): bool
    {
        $conditions = [
            'teacher_id' => $entity->teacher_id,
            'session_date' => $entity->session_date,
            'status !=' => 'cancelled',
        ];

        if (!$entity->isNew()) {
            $conditions['id !='] = $entity->id;
        }

        $startStr = $this->timeStr($entity->start_time);
        $endStr   = $this->timeStr($entity->end_time);

        $overlapping = $this->find()
            ->where($conditions)
            ->where(function ($exp) use ($startStr, $endStr) {
                return $exp->or([
                    // New slot starts during existing slot
                    [
                        'start_time <=' => $startStr,
                        'end_time >' => $startStr,
                    ],
                    // New slot ends during existing slot
                    [
                        'start_time <' => $endStr,
                        'end_time >=' => $endStr,
                    ],
                    // New slot completely contains existing slot
                    [
                        'start_time >=' => $startStr,
                        'end_time <=' => $endStr,
                    ],
                ]);
            })
            ->first();

        return $overlapping !== null;
    }

    /**
     * Find slots for a teacher with filtering options
     * 
     * @param \Cake\ORM\Query\SelectQuery $query
     * @param array $options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findForTeacher(
        \Cake\ORM\Query\SelectQuery $query,
        int $teacher_id,
        ?string $from_date = null,
        ?string $to_date = null,
        ?string $status = null,
        ?int $workshop_id = null,
    ): \Cake\ORM\Query\SelectQuery {
        $query = $query
            ->where(['TeacherAvailabilitySlots.teacher_id' => $teacher_id])
            ->contain(['Workshops', 'Bookings']);

        if ($status !== null) {
            $query->where(['TeacherAvailabilitySlots.status' => $status]);
        }

        if ($from_date !== null) {
            $query->where(['TeacherAvailabilitySlots.session_date >=' => $from_date]);
        }

        if ($to_date !== null) {
            $query->where(['TeacherAvailabilitySlots.session_date <=' => $to_date]);
        }

        if ($workshop_id !== null) {
            $query->where(['TeacherAvailabilitySlots.workshop_id' => $workshop_id]);
        }

        return $query->orderBy(['TeacherAvailabilitySlots.session_date' => 'ASC', 'TeacherAvailabilitySlots.start_time' => 'ASC']);
    }

    /**
     * Find upcoming slots for a teacher
     * 
     * @param \Cake\ORM\Query\SelectQuery $query
     * @param array $options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findUpcoming(\Cake\ORM\Query\SelectQuery $query, array $options): \Cake\ORM\Query\SelectQuery
    {
        $today = date('Y-m-d');
        $now = date('H:i:s');

        return $query
            ->where([
                'TeacherAvailabilitySlots.teacher_id' => $options['teacher_id'],
                'TeacherAvailabilitySlots.status IN' => ['available', 'reserved'],
            ])
            ->where(function ($exp) use ($today, $now) {
                return $exp->or([
                    'TeacherAvailabilitySlots.session_date >' => $today,
                    [
                        'TeacherAvailabilitySlots.session_date' => $today,
                        'TeacherAvailabilitySlots.start_time >=' => $now,
                    ],
                ]);
            })
            ->orderBy(['TeacherAvailabilitySlots.session_date' => 'ASC', 'TeacherAvailabilitySlots.start_time' => 'ASC']);
    }

    /**
     * Find today's slots for a teacher
     * 
     * @param \Cake\ORM\Query\SelectQuery $query
     * @param array $options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findToday(\Cake\ORM\Query\SelectQuery $query, array $options): \Cake\ORM\Query\SelectQuery
    {
        $today = date('Y-m-d');

        return $query
            ->where([
                'TeacherAvailabilitySlots.teacher_id' => $options['teacher_id'],
                'TeacherAvailabilitySlots.session_date' => $today,
            ])
            ->orderBy(['TeacherAvailabilitySlots.start_time' => 'ASC']);
    }

    /**
     * Find slots that need attendance marking
     * 
     * @param \Cake\ORM\Query\SelectQuery $query
     * @param array $options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findNeedsAttendance(\Cake\ORM\Query\SelectQuery $query, array $options): \Cake\ORM\Query\SelectQuery
    {
        $today = date('Y-m-d');

        return $query
            ->where([
                'TeacherAvailabilitySlots.teacher_id' => $options['teacher_id'],
                'TeacherAvailabilitySlots.session_date <=' => $today,
                'TeacherAvailabilitySlots.status IN' => ['available', 'reserved'],
                'TeacherAvailabilitySlots.seats_booked >' => 0,
            ])
            ->leftJoinWith('AttendanceRecords')
            ->where(['AttendanceRecords.id IS NULL'])
            ->groupBy(['TeacherAvailabilitySlots.id'])
            ->orderBy(['TeacherAvailabilitySlots.session_date' => 'DESC']);
    }

    /**
     * Check if a slot can be cancelled
     * 
     * @param int $slotId
     * @return array ['can_cancel' => bool, 'reason' => string|null]
     */
    public function canCancel(int $slotId): array
    {
        $slot = $this->get($slotId, contain: ['Bookings']);

        if ($slot->status === 'cancelled') {
            return ['can_cancel' => false, 'reason' => 'Slot is already cancelled.'];
        }

        // Check if slot has confirmed bookings
        $confirmedBookings = array_filter($slot->bookings ?? [], function ($booking) {
            return $booking->status === 'confirmed';
        });

        if (!empty($confirmedBookings)) {
            return [
                'can_cancel' => false,
                'reason' => 'Cannot cancel slot with confirmed bookings. Please contact students first.',
            ];
        }

        // Check if slot is in the past
        $slotDateTime = new \DateTime($slot->session_date->format('Y-m-d') . ' ' . $slot->start_time->format('H:i:s'));
        $now = new \DateTime();

        if ($slotDateTime < $now) {
            return ['can_cancel' => false, 'reason' => 'Cannot cancel past slots.'];
        }

        return ['can_cancel' => true, 'reason' => null];
    }

    /**
     * Cancel a slot and update related records
     * 
     * @param int $slotId
     * @param string|null $reason
     * @param int $cancelledBy
     * @return bool
     */
    public function cancelSlot(int $slotId, ?string $reason, int $cancelledBy): bool
    {
        $slot = $this->get($slotId);
        
        $slot->status = 'cancelled';
        $slot->cancelled_at = new \DateTime();
        $slot->cancellation_reason = $reason;
        $slot->is_active = false;

        return (bool) $this->save($slot);
    }

    /**
     * Get slot statistics for a teacher
     * 
     * @param int $teacherId
     * @param string|null $fromDate
     * @param string|null $toDate
     * @return array
     */
    public function getTeacherStats(int $teacherId, ?string $fromDate = null, ?string $toDate = null): array
    {
        $conditions = ['teacher_id' => $teacherId];

        if ($fromDate) {
            $conditions['session_date >='] = $fromDate;
        }
        if ($toDate) {
            $conditions['session_date <='] = $toDate;
        }

        $stats = $this->find()
            ->select([
                'status',
                'count' => $this->find()->func()->count('*'),
                'total_seats' => $this->find()->func()->sum('capacity'),
                'total_booked' => $this->find()->func()->sum('seats_booked'),
            ])
            ->where($conditions)
            ->groupBy(['status'])
            ->toArray();

        $result = [
            'total_slots' => 0,
            'available' => 0,
            'reserved' => 0,
            'blocked' => 0,
            'cancelled' => 0,
            'expired' => 0,
            'total_capacity' => 0,
            'total_booked' => 0,
            'average_fill_rate' => 0,
        ];

        $totalSlots = 0;
        $totalCapacity = 0;
        $totalBooked = 0;

        foreach ($stats as $stat) {
            $result[$stat->status] = $stat->count;
            $result['total_slots'] += $stat->count;
            $totalCapacity += $stat->total_seats ?? 0;
            $totalBooked += $stat->total_booked ?? 0;
        }

        $result['total_capacity'] = $totalCapacity;
        $result['total_booked'] = $totalBooked;

        if ($totalCapacity > 0) {
            $result['average_fill_rate'] = round(($totalBooked / $totalCapacity) * 100, 2);
        }

        return $result;
    }

    /**
     * Auto-cancel expired slots that have no bookings
     * Expired = session_date + start_time < now AND seats_booked = 0 AND status = 'available'
     * 
     * @return array ['cancelled' => int, 'errors' => array]
     */
    public function autoCancelExpiredSlots(): array
    {
        $now = new \DateTime();
        $today = $now->format('Y-m-d');
        $currentTime = $now->format('H:i:s');

        // Find slots that have passed without any bookings
        $expiredSlots = $this->find()
            ->where([
                'status' => 'available',
                'seats_booked' => 0,
                'OR' => [
                    // Date is in the past
                    'session_date <' => $today,
                    // Date is today but time has passed
                    [
                        'session_date' => $today,
                        'start_time <' => $currentTime,
                    ],
                ],
            ])
            ->all();

        $cancelled = 0;
        $errors = [];

        foreach ($expiredSlots as $slot) {
            try {
                $slot->status = 'cancelled';
                $slot->cancelled_at = new \DateTime();
                $slot->cancellation_reason = 'Auto-cancelled: expired with no bookings';
                $slot->is_active = false;

                if ($this->save($slot)) {
                    $cancelled++;
                } else {
                    $errors[] = [
                        'slot_id' => $slot->id,
                        'message' => 'Failed to save',
                    ];
                }
            } catch (\Exception $e) {
                $errors[] = [
                    'slot_id' => $slot->id,
                    'message' => $e->getMessage(),
                ];
            }
        }

        return [
            'cancelled' => $cancelled,
            'errors' => $errors,
        ];
    }
}
