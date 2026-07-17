<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

/**
 * Rename `lessons` table to `workshops` and all related `lesson_id` / `lesson_*`
 * columns across the schema to use `workshop_id` / `workshop_*`.
 *
 * Safe to run on a fresh database where the old table already exists via earlier
 * migrations, or on a production instance that still has the `lessons` table.
 */
class RenameLessonsToWorkshops extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up(): void
    {
        // Skip if workshops table already exists (migration already applied manually)
        if ($this->hasTable('workshops')) {
            return;
        }

        // 1. Rename the main table
        $this->table('lessons')->rename('workshops')->update();

        // 2. Rename columns in workshops table
        $this->table('workshops')
            ->renameColumn('lesson_name', 'workshop_name')
            ->renameColumn('lesson_type', 'workshop_type')
            ->update();

        // 3. Rename lesson_id → workshop_id in bookings
        if ($this->table('bookings')->hasColumn('lesson_id')) {
            $this->table('bookings')
                ->dropForeignKey('lesson_id')
                ->update();

            $this->table('bookings')
                ->renameColumn('lesson_id', 'workshop_id')
                ->update();

            $this->table('bookings')
                ->addForeignKey('workshop_id', 'workshops', 'id', [
                    'delete' => 'RESTRICT',
                    'update' => 'NO_ACTION',
                ])
                ->update();
        }

        // 4. Rename lesson_id → workshop_id in materials
        if ($this->table('materials')->hasColumn('lesson_id')) {
            $this->table('materials')
                ->dropForeignKey('lesson_id')
                ->update();

            $this->table('materials')
                ->renameColumn('lesson_id', 'workshop_id')
                ->update();

            $this->table('materials')
                ->addForeignKey('workshop_id', 'workshops', 'id', [
                    'delete' => 'RESTRICT',
                    'update' => 'NO_ACTION',
                ])
                ->update();
        }

        // 5. Rename lesson_id → workshop_id in announcements
        if ($this->table('announcements')->hasColumn('lesson_id')) {
            $this->table('announcements')
                ->dropForeignKey('lesson_id')
                ->update();

            $this->table('announcements')
                ->renameColumn('lesson_id', 'workshop_id')
                ->update();

            $this->table('announcements')
                ->addForeignKey('workshop_id', 'workshops', 'id', [
                    'delete' => 'SET_NULL',
                    'update' => 'CASCADE',
                ])
                ->update();
        }

        // 6. Rename lesson_id → workshop_id in teacher_availability_slots
        if ($this->table('teacher_availability_slots')->hasColumn('lesson_id')) {
            $this->table('teacher_availability_slots')
                ->dropForeignKey('lesson_id')
                ->update();

            $this->table('teacher_availability_slots')
                ->renameColumn('lesson_id', 'workshop_id')
                ->update();

            $this->table('teacher_availability_slots')
                ->addForeignKey('workshop_id', 'workshops', 'id', [
                    'delete' => 'SET_NULL',
                    'update' => 'CASCADE',
                ])
                ->update();
        }
    }

    /**
     * Migrate Down — revert everything back to `lessons`.
     */
    public function down(): void
    {
        // Skip if lessons table already exists (already reverted)
        if ($this->hasTable('lessons')) {
            return;
        }

        // 6. Revert teacher_availability_slots
        if ($this->table('teacher_availability_slots')->hasColumn('workshop_id')) {
            $this->table('teacher_availability_slots')
                ->dropForeignKey('workshop_id')
                ->update();

            $this->table('teacher_availability_slots')
                ->renameColumn('workshop_id', 'lesson_id')
                ->update();
        }

        // 5. Revert announcements
        if ($this->table('announcements')->hasColumn('workshop_id')) {
            $this->table('announcements')
                ->dropForeignKey('workshop_id')
                ->update();

            $this->table('announcements')
                ->renameColumn('workshop_id', 'lesson_id')
                ->update();
        }

        // 4. Revert materials
        if ($this->table('materials')->hasColumn('workshop_id')) {
            $this->table('materials')
                ->dropForeignKey('workshop_id')
                ->update();

            $this->table('materials')
                ->renameColumn('workshop_id', 'lesson_id')
                ->update();
        }

        // 3. Revert bookings
        if ($this->table('bookings')->hasColumn('workshop_id')) {
            $this->table('bookings')
                ->dropForeignKey('workshop_id')
                ->update();

            $this->table('bookings')
                ->renameColumn('workshop_id', 'lesson_id')
                ->update();
        }

        // 2. Rename columns back
        $this->table('workshops')
            ->renameColumn('workshop_name', 'lesson_name')
            ->renameColumn('workshop_type', 'lesson_type')
            ->update();

        // 1. Rename table back
        $this->table('workshops')->rename('lessons')->update();

        // Re-add foreign keys referencing 'lessons'
        $this->table('bookings')
            ->addForeignKey('lesson_id', 'lessons', 'id', [
                'delete' => 'RESTRICT',
                'update' => 'NO_ACTION',
            ])
            ->update();

        $this->table('materials')
            ->addForeignKey('lesson_id', 'lessons', 'id', [
                'delete' => 'RESTRICT',
                'update' => 'NO_ACTION',
            ])
            ->update();

        $this->table('announcements')
            ->addForeignKey('lesson_id', 'lessons', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'CASCADE',
            ])
            ->update();

        $this->table('teacher_availability_slots')
            ->addForeignKey('lesson_id', 'lessons', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION',
            ])
            ->update();
    }
}
