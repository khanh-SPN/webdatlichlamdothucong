<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $teacher_id
 * @property int|null $workshop_id
 * @property \Cake\I18n\Date $session_date
 * @property string|null $time_label
 * @property \Cake\I18n\Time|null $start_time
 * @property \Cake\I18n\Time|null $end_time
 * @property string|null $status available|reserved|blocked|expired|cancelled
 * @property string|null $location
 * @property string|null $notes
 * @property int|null $capacity
 * @property bool $is_active
 * @property bool $is_recurring
 * @property string|null $recurrence_pattern
 * @property int|null $parent_slot_id
 * @property int $seats_booked
 * @property \Cake\I18n\DateTime|null $cancelled_at
 * @property string|null $cancellation_reason
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property \App\Model\Entity\Teacher $teacher
 * @property \App\Model\Entity\Workshop|null $workshop
 * @property \App\Model\Entity\Booking[] $bookings
 * @property \App\Model\Entity\AttendanceRecord[] $attendance_records
 */
class TeacherAvailabilitySlot extends Entity
{
    /**
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'teacher_id' => true,
        'workshop_id' => true,
        'session_date' => true,
        'time_label' => true,
        'start_time' => true,
        'end_time' => true,
        'status' => true,
        'location' => true,
        'notes' => true,
        'capacity' => true,
        'is_active' => true,
        'is_recurring' => true,
        'recurrence_pattern' => true,
        'parent_slot_id' => true,
        'seats_booked' => true,
        'cancelled_at' => true,
        'cancellation_reason' => true,
        'created' => true,
        'modified' => true,
        'teacher' => true,
        'workshop' => true,
        'bookings' => true,
        'attendance_records' => true,
    ];

    /**
     * Convert start_time string from DB to Time object automatically
     */
    protected function _getStartTime($value): ?\Cake\I18n\Time
    {
        if ($value === null) return null;
        if ($value instanceof \Cake\I18n\Time) return $value;
        try {
            return new \Cake\I18n\Time($value);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Convert end_time string from DB to Time object automatically
     */
    protected function _getEndTime($value): ?\Cake\I18n\Time
    {
        if ($value === null) return null;
        if ($value instanceof \Cake\I18n\Time) return $value;
        try {
            return new \Cake\I18n\Time($value);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Get available seats for this slot
     * Uses slot capacity if set, otherwise falls back to workshop capacity
     *
     * @param int|null $workshopCapacity The workshop's default capacity
     * @return int
     */
    public function getAvailableSeats(?int $workshopCapacity = null): int
    {
        $cap = $this->capacity ?? $workshopCapacity ?? 0;
        if ($cap <= 0) {
            return 0;
        }
        return max(0, $cap - ($this->seats_booked ?? 0));
    }

    /**
     * Check if slot is fully booked
     *
     * @param int|null $workshopCapacity
     * @return bool
     */
    public function isFullyBooked(?int $workshopCapacity = null): bool
    {
        return $this->getAvailableSeats($workshopCapacity) <= 0;
    }

    /**
     * Check if slot is bookable
     *
     * @return bool
     */
    public function isBookable(): bool
    {
        return ($this->is_active ?? true) === true && $this->status === 'available';
    }

    /**
     * Get status label
     *
     * @return string
     */
    public function getStatusLabel(): string
    {
        $labels = [
            'available' => 'Available',
            'reserved' => 'Reserved',
            'blocked' => 'Blocked',
            'expired' => 'Expired',
            'cancelled' => 'Cancelled',
        ];
        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get CSS class for status badge
     *
     * @return string
     */
    public function getStatusClass(): string
    {
        $classes = [
            'available' => 'bg-green-100 text-green-800 border-green-200',
            'reserved' => 'bg-blue-100 text-blue-800 border-blue-200',
            'blocked' => 'bg-gray-100 text-gray-800 border-gray-200',
            'expired' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
        ];
        return $classes[$this->status] ?? 'bg-neutral-100 text-neutral-800';
    }

    /**
     * Check if slot is cancelled
     *
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if slot has passed
     *
     * @return bool
     */
    public function hasPassed(): bool
    {
        $endTimeStr = is_string($this->end_time) ? $this->end_time : ($this->end_time ? $this->end_time->format('H:i:s') : '23:59:59');
        $slotDateTime = new \DateTime($this->session_date->format('Y-m-d') . ' ' . $endTimeStr);
        $now = new \DateTime();
        return $slotDateTime < $now;
    }

    /**
     * Get formatted time range
     *
     * @return string
     */
    public function getTimeRange(): string
    {
        if (!$this->start_time || !$this->end_time) {
            return $this->time_label ?? '—';
        }
        return $this->start_time->format('g:i A') . ' - ' . $this->end_time->format('g:i A');
    }

    /**
     * Calculate duration in minutes
     *
     * @return int
     */
    public function getDurationMinutes(): int
    {
        if (!$this->start_time || !$this->end_time) {
            return 0;
        }
        $startStr = is_string($this->start_time) ? $this->start_time : $this->start_time->format('H:i:s');
        $endStr   = is_string($this->end_time)   ? $this->end_time   : $this->end_time->format('H:i:s');
        $start = strtotime($startStr);
        $end = strtotime($endStr);
        return ($end - $start) / 60;
    }
}
