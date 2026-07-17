<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

/**
 * Migration: Teacher Slot Management Enhancement
 * 
 * This migration:
 * 1. Adds new columns to teacher_availability_slots for better slot management
 * 2. Creates attendance_records table for tracking student attendance
 * 3. Creates slot_status_history table for audit trail
 */
class TeacherSlotManagementEnhancement extends AbstractMigration
{
    public function up(): void
    {
        // 1. Enhance teacher_availability_slots table
        $slots = $this->table('teacher_availability_slots');
        
        // Add proper time columns (more precise than time_label string)
        if (!$slots->hasColumn('start_time')) {
            $slots->addColumn('start_time', 'time', [
                'null' => false,
                'comment' => 'Session start time',
            ])->update();
        }
        
        if (!$slots->hasColumn('end_time')) {
            $slots->addColumn('end_time', 'time', [
                'null' => false,
                'comment' => 'Session end time',
            ])->update();
        }
        
        // Add status column for slot lifecycle management
        if (!$slots->hasColumn('status')) {
            $slots->addColumn('status', 'enum', [
                'values' => ['available', 'reserved', 'blocked', 'expired', 'cancelled'],
                'default' => 'available',
                'null' => false,
                'comment' => 'Slot status: available, reserved, blocked, expired, cancelled',
            ])->update();
        }
        
        // Add location column
        if (!$slots->hasColumn('location')) {
            $slots->addColumn('location', 'string', [
                'limit' => 255,
                'null' => true,
                'default' => null,
                'comment' => 'Physical location or room for the session',
            ])->update();
        }
        
        // Add is_recurring for recurring slot patterns
        if (!$slots->hasColumn('is_recurring')) {
            $slots->addColumn('is_recurring', 'boolean', [
                'default' => false,
                'null' => false,
                'comment' => 'Whether this slot is part of a recurring pattern',
            ])->update();
        }
        
        // Add recurrence_pattern for storing pattern details
        if (!$slots->hasColumn('recurrence_pattern')) {
            $slots->addColumn('recurrence_pattern', 'string', [
                'limit' => 100,
                'null' => true,
                'default' => null,
                'comment' => 'JSON or pattern string for recurring slots (e.g., weekly:monday)',
            ])->update();
        }
        
        // Add parent_slot_id for linking recurring instances
        if (!$slots->hasColumn('parent_slot_id')) {
            $slots->addColumn('parent_slot_id', 'integer', [
                'null' => true,
                'default' => null,
                'comment' => 'Reference to parent slot for recurring instances',
            ])->update();
        }
        
        // Add cancelled_at for tracking cancellations
        if (!$slots->hasColumn('cancelled_at')) {
            $slots->addColumn('cancelled_at', 'datetime', [
                'null' => true,
                'default' => null,
                'comment' => 'When the slot was cancelled',
            ])->update();
        }
        
        // Add cancellation_reason
        if (!$slots->hasColumn('cancellation_reason')) {
            $slots->addColumn('cancellation_reason', 'text', [
                'null' => true,
                'default' => null,
                'comment' => 'Reason for cancellation',
            ])->update();
        }
        
        // Add index for efficient queries
        $slots->addIndex(['teacher_id', 'session_date', 'status'])->update();
        $slots->addIndex(['lesson_id', 'status'])->update();
        $slots->addIndex(['status', 'session_date'])->update();

        // 2. Create attendance_records table
        if (!$this->hasTable('attendance_records')) {
            $attendance = $this->table('attendance_records');
            $attendance
                ->addColumn('slot_id', 'integer', [
                    'null' => false,
                    'comment' => 'Reference to teacher_availability_slots',
                ])
                ->addColumn('booking_id', 'integer', [
                    'null' => false,
                    'comment' => 'Reference to bookings',
                ])
                ->addColumn('student_id', 'integer', [
                    'null' => false,
                    'comment' => 'Reference to users (student)',
                ])
                ->addColumn('teacher_id', 'integer', [
                    'null' => false,
                    'comment' => 'Reference to teachers',
                ])
                ->addColumn('status', 'enum', [
                    'values' => ['present', 'absent', 'late', 'excused'],
                    'default' => 'present',
                    'null' => false,
                    'comment' => 'Attendance status',
                ])
                ->addColumn('marked_at', 'datetime', [
                    'null' => false,
                    'comment' => 'When attendance was marked',
                ])
                ->addColumn('marked_by', 'integer', [
                    'null' => false,
                    'comment' => 'User ID who marked the attendance (teacher)',
                ])
                ->addColumn('notes', 'text', [
                    'null' => true,
                    'default' => null,
                    'comment' => 'Optional notes about attendance',
                ])
                ->addColumn('is_locked', 'boolean', [
                    'default' => false,
                    'null' => false,
                    'comment' => 'Whether attendance is locked for editing',
                ])
                ->addColumn('locked_at', 'datetime', [
                    'null' => true,
                    'default' => null,
                    'comment' => 'When attendance was locked',
                ])
                ->addTimestamps()
                ->addForeignKey('slot_id', 'teacher_availability_slots', 'id', [
                    'delete' => 'CASCADE',
                    'update' => 'NO_ACTION',
                ])
                ->addForeignKey('booking_id', 'bookings', 'id', [
                    'delete' => 'CASCADE',
                    'update' => 'NO_ACTION',
                ])
                ->addForeignKey('student_id', 'users', 'id', [
                    'delete' => 'CASCADE',
                    'update' => 'NO_ACTION',
                ])
                ->addForeignKey('teacher_id', 'teachers', 'id', [
                    'delete' => 'CASCADE',
                    'update' => 'NO_ACTION',
                ])
                ->addForeignKey('marked_by', 'users', 'id', [
                    'delete' => 'CASCADE',
                    'update' => 'NO_ACTION',
                ])
                ->addIndex(['slot_id', 'student_id'], ['unique' => true])
                ->addIndex(['teacher_id', 'marked_at'])
                ->addIndex(['slot_id', 'status'])
                ->create();
        }

        // 3. Create slot_status_history table for audit trail
        if (!$this->hasTable('slot_status_history')) {
            $history = $this->table('slot_status_history');
            $history
                ->addColumn('slot_id', 'integer', [
                    'null' => false,
                ])
                ->addColumn('old_status', 'string', [
                    'limit' => 20,
                    'null' => true,
                    'default' => null,
                ])
                ->addColumn('new_status', 'string', [
                    'limit' => 20,
                    'null' => false,
                ])
                ->addColumn('changed_by', 'integer', [
                    'null' => false,
                    'comment' => 'User ID who made the change',
                ])
                ->addColumn('reason', 'text', [
                    'null' => true,
                    'default' => null,
                ])
                ->addColumn('created_at', 'datetime', [
                    'null' => false,
                    'default' => 'CURRENT_TIMESTAMP',
                ])
                ->addForeignKey('slot_id', 'teacher_availability_slots', 'id', [
                    'delete' => 'CASCADE',
                    'update' => 'NO_ACTION',
                ])
                ->addForeignKey('changed_by', 'users', 'id', [
                    'delete' => 'CASCADE',
                    'update' => 'NO_ACTION',
                ])
                ->addIndex(['slot_id', 'created_at'])
                ->create();
        }

        // 4. Create teacher_slots_view for calendar display (optional, depends on DB support)
        // This is a virtual table that can be used by the application layer instead

        // 5. Migrate existing data: convert time_label to start_time/end_time
        $this->migrateExistingTimeLabels();
    }

    public function down(): void
    {
        // Drop tables in reverse order
        if ($this->hasTable('slot_status_history')) {
            $this->table('slot_status_history')->drop()->save();
        }

        if ($this->hasTable('attendance_records')) {
            $this->table('attendance_records')->drop()->save();
        }

        // Remove added columns from teacher_availability_slots
        $slots = $this->table('teacher_availability_slots');
        $columnsToRemove = [
            'start_time', 'end_time', 'status', 'location', 'is_recurring',
            'recurrence_pattern', 'parent_slot_id', 'cancelled_at', 'cancellation_reason'
        ];

        foreach ($columnsToRemove as $column) {
            if ($slots->hasColumn($column)) {
                $slots->removeColumn($column)->update();
            }
        }
    }

    /**
     * Migrate existing time_label data to start_time and end_time columns
     */
    private function migrateExistingTimeLabels(): void
    {
        // Get all slots with time_label data
        $rows = $this->getAdapter()->fetchAll(
            "SELECT id, time_label FROM teacher_availability_slots WHERE time_label IS NOT NULL"
        );

        foreach ($rows as $row) {
            $timeLabel = $row['time_label'];
            $slotId = $row['id'];

            // Parse time ranges like "10:00 to 13:00" or "10:00 - 13:00"
            $times = $this->parseTimeRange($timeLabel);
            
            if ($times) {
                $this->getAdapter()->execute(
                    "UPDATE teacher_availability_slots 
                     SET start_time = '{$times['start']}', 
                         end_time = '{$times['end']}',
                         status = 'available'
                     WHERE id = {$slotId}"
                );
            }
        }
    }

    /**
     * Parse time range string into start and end times
     * 
     * @param string $timeLabel
     * @return array|null ['start' => 'HH:MM:SS', 'end' => 'HH:MM:SS']
     */
    private function parseTimeRange(string $timeLabel): ?array
    {
        // Try different patterns
        $patterns = [
            '/(\d{1,2}):(\d{2})\s*(?:to|–|-)\s*(\d{1,2}):(\d{2})/i',  // 10:00 to 13:00
            '/(\d{1,2}):(\d{2})/',  // Just one time
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $timeLabel, $matches)) {
                if (count($matches) >= 5) {
                    // Has start and end times
                    $start = sprintf('%02d:%02d:00', $matches[1], $matches[2]);
                    $end = sprintf('%02d:%02d:00', $matches[3], $matches[4]);
                    return ['start' => $start, 'end' => $end];
                } elseif (count($matches) >= 3) {
                    // Only start time, assume 1 hour duration
                    $start = sprintf('%02d:%02d:00', $matches[1], $matches[2]);
                    $endHour = (int)$matches[1] + 1;
                    $end = sprintf('%02d:%02d:00', $endHour, $matches[2]);
                    return ['start' => $start, 'end' => $end];
                }
            }
        }

        return null;
    }
}
