<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

/**
 * Migration: Fix slot defaults and migrate existing data
 * 
 * This migration:
 * 1. Sets default values for new columns
 * 2. Migrates existing data (time_label -> start_time/end_time)
 * 3. Sets status for existing slots
 */
class FixSlotDefaultsAndData extends AbstractMigration
{
    public function up(): void
    {
        $slots = $this->table('teacher_availability_slots');
        
        // 1. Set default values for columns if they don't have defaults
        // Note: SQLite doesn't support altering defaults easily, so we'll update data instead
        
        // 2. Update existing rows that have NULL status
        $this->execute("
            UPDATE teacher_availability_slots 
            SET status = 'available' 
            WHERE status IS NULL
        ");
        
        // 3. Update existing rows that have NULL start_time/end_time
        // Try to parse from time_label format "10:00 to 13:00"
        $rows = $this->getAdapter()->fetchAll(
            "SELECT id, time_label FROM teacher_availability_slots WHERE start_time IS NULL"
        );
        
        foreach ($rows as $row) {
            $times = $this->parseTimeRange($row['time_label'] ?? '');
            if ($times) {
                $this->execute(sprintf(
                    "UPDATE teacher_availability_slots 
                     SET start_time = '%s', end_time = '%s'
                     WHERE id = %d",
                    $times['start'],
                    $times['end'],
                    $row['id']
                ));
            } else {
                // Default to 9:00 - 12:00 if can't parse
                $this->execute(sprintf(
                    "UPDATE teacher_availability_slots 
                     SET start_time = '09:00:00', end_time = '12:00:00'
                     WHERE id = %d",
                    $row['id']
                ));
            }
        }
        
        // 4. Update seats_booked based on actual bookings count
        $this->execute("
            UPDATE teacher_availability_slots 
            SET seats_booked = (
                SELECT COALESCE(SUM(quantity), 0) 
                FROM bookings 
                WHERE bookings.slot_id = teacher_availability_slots.id 
                AND bookings.status = 'confirmed'
            )
            WHERE seats_booked = 0 OR seats_booked IS NULL
        ");
        
        // 5. Set default capacity if not set (use 10 as default)
        $this->execute("
            UPDATE teacher_availability_slots 
            SET capacity = 10 
            WHERE capacity IS NULL OR capacity = 0
        ");
    }

    public function down(): void
    {
        // No need to revert data fixes
    }

    /**
     * Parse time range string
     */
    private function parseTimeRange(string $timeLabel): ?array
    {
        // Pattern: "10:00 to 13:00" or "10:00 - 13:00"
        if (preg_match('/(\d{1,2}):(\d{2})\s*(?:to|–|-)\s*(\d{1,2}):(\d{2})/i', $timeLabel, $matches)) {
            $start = sprintf('%02d:%02d:00', $matches[1], $matches[2]);
            $end = sprintf('%02d:%02d:00', $matches[3], $matches[4]);
            return ['start' => $start, 'end' => $end];
        }
        
        // Single time - assume 1 hour duration
        if (preg_match('/(\d{1,2}):(\d{2})/', $timeLabel, $matches)) {
            $start = sprintf('%02d:%02d:00', $matches[1], $matches[2]);
            $endHour = (int)$matches[1] + 1;
            $end = sprintf('%02d:%02d:00', $endHour, $matches[2]);
            return ['start' => $start, 'end' => $end];
        }
        
        return null;
    }
}
