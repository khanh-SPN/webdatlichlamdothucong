<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateTeacherAvailabilitySlots extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('teacher_availability_slots');
        $table
            ->addColumn('teacher_id', 'integer', [
                'null' => false,
            ])
            ->addColumn('lesson_id', 'integer', [
                'null' => true,
                'default' => null,
            ])
            ->addColumn('session_date', 'date', [
                'null' => false,
            ])
            ->addColumn('time_label', 'string', [
                'limit' => 64,
                'null' => true,
                'default' => null,
            ])
            ->addColumn('notes', 'text', [
                'null' => true,
                'default' => null,
            ])
            ->addTimestamps()
            ->addForeignKey('teacher_id', 'teachers', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION',
            ])
            ->addForeignKey('lesson_id', 'lessons', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'NO_ACTION',
            ])
            ->addIndex(['teacher_id', 'session_date'])
            ->create();

        $now = date('Y-m-d H:i:s');
        $rows = [
            [
                'teacher_id' => 2,
                'lesson_id' => 4,
                'session_date' => '2026-04-05',
                'time_label' => '10:00 to 13:00',
                'notes' => 'Pottery for Beginners',
                'created' => $now,
                'modified' => $now,
            ],
            [
                'teacher_id' => 2,
                'lesson_id' => 5,
                'session_date' => '2026-04-12',
                'time_label' => '14:00 to 17:00',
                'notes' => 'Ceramic Design Masterclass',
                'created' => $now,
                'modified' => $now,
            ],
            [
                'teacher_id' => 3,
                'lesson_id' => 6,
                'session_date' => '2026-04-06',
                'time_label' => '09:30 to 12:00',
                'notes' => null,
                'created' => $now,
                'modified' => $now,
            ],
            [
                'teacher_id' => 5,
                'lesson_id' => 3,
                'session_date' => '2026-04-08',
                'time_label' => '11:00 to 14:00',
                'notes' => null,
                'created' => $now,
                'modified' => $now,
            ],
            [
                'teacher_id' => 4,
                'lesson_id' => 7,
                'session_date' => '2026-04-10',
                'time_label' => '10:00 to 13:30',
                'notes' => null,
                'created' => $now,
                'modified' => $now,
            ],
            [
                'teacher_id' => 6,
                'lesson_id' => 8,
                'session_date' => '2026-04-15',
                'time_label' => '13:00 to 16:00',
                'notes' => null,
                'created' => $now,
                'modified' => $now,
            ],
        ];
        $this->table('teacher_availability_slots')->insert($rows)->save();
    }

    public function down(): void
    {
        $this->table('teacher_availability_slots')->drop()->save();
    }
}
