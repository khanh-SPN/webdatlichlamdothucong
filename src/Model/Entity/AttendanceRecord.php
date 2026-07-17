<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AttendanceRecord Entity
 * 
 * @property int $id
 * @property int $slot_id
 * @property int $booking_id
 * @property int $student_id
 * @property int $teacher_id
 * @property string $status present|absent|late|excused
 * @property \Cake\I18n\DateTime $marked_at
 * @property int $marked_by
 * @property string|null $notes
 * @property bool $is_locked
 * @property \Cake\I18n\DateTime|null $locked_at
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * 
 * @property \App\Model\Entity\TeacherAvailabilitySlot $teacher_availability_slot
 * @property \App\Model\Entity\Booking $booking
 * @property \App\Model\Entity\User $student
 * @property \App\Model\Entity\Teacher $teacher
 * @property \App\Model\Entity\User $marked_by_user
 */
class AttendanceRecord extends Entity
{
    protected array $_accessible = [
        'slot_id' => true,
        'booking_id' => true,
        'student_id' => true,
        'teacher_id' => true,
        'status' => true,
        'marked_at' => true,
        'marked_by' => true,
        'notes' => true,
        'is_locked' => true,
        'locked_at' => true,
        'created' => true,
        'modified' => true,
        'teacher_availability_slot' => true,
        'booking' => true,
        'student' => true,
        'teacher' => true,
        'marked_by_user' => true,
    ];

    /**
     * Get human-readable status label
     * 
     * @return string
     */
    public function getStatusLabel(): string
    {
        $labels = [
            'present' => 'Present',
            'absent' => 'Absent',
            'late' => 'Late',
            'excused' => 'Excused',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Get CSS class for status badge
     * 
     * @return string
     */
    public function getStatusClass(): string
    {
        $classes = [
            'present' => 'bg-green-100 text-green-800 border-green-200',
            'absent' => 'bg-red-100 text-red-800 border-red-200',
            'late' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'excused' => 'bg-blue-100 text-blue-800 border-blue-200',
        ];

        return $classes[$this->status] ?? 'bg-neutral-100 text-neutral-800';
    }

    /**
     * Check if record can be edited
     * 
     * @return bool
     */
    public function canEdit(): bool
    {
        return !$this->is_locked;
    }
}
