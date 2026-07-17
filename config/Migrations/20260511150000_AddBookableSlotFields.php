<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddBookableSlotFields extends BaseMigration
{
    public function up(): void
    {
        $slots = $this->table('teacher_availability_slots');
        if (!$slots->hasColumn('capacity')) {
            $slots->addColumn('capacity', 'integer', [
                'null' => true,
                'default' => null,
                'signed' => false,
                'comment' => 'Max seats for this session (NULL = use lesson.capacity)',
            ]);
        }
        if (!$slots->hasColumn('is_active')) {
            $slots->addColumn('is_active', 'boolean', [
                'null' => false,
                'default' => true,
                'comment' => 'Whether this session is bookable',
            ]);
        }
        if (!$slots->hasColumn('seats_booked')) {
            $slots->addColumn('seats_booked', 'integer', [
                'null' => false,
                'default' => 0,
                'signed' => false,
                'comment' => 'Cached count of booked seats',
            ]);
        }
        $slots->update();

        $bookings = $this->table('bookings');
        if (!$bookings->hasColumn('slot_id')) {
            $bookings->addColumn('slot_id', 'integer', [
                'null' => true,
                'default' => null,
                'comment' => 'Links to teacher_availability_slots',
            ]);
            $bookings->addIndex(['slot_id']);
            $bookings->update();

            $bookings = $this->table('bookings');
            $bookings->addForeignKey('slot_id', 'teacher_availability_slots', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'CASCADE',
            ])->update();
        }
    }

    public function down(): void
    {
        $bookings = $this->table('bookings');
        if ($bookings->hasColumn('slot_id')) {
            $bookings->removeColumn('slot_id')->update();
        }

        $slots = $this->table('teacher_availability_slots');
        foreach (['capacity', 'is_active', 'seats_booked'] as $column) {
            if ($slots->hasColumn($column)) {
                $slots->removeColumn($column);
            }
        }
        $slots->update();
    }
}
