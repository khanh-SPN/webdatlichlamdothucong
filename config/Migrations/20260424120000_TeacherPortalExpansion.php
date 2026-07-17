<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class TeacherPortalExpansion extends AbstractMigration
{
    public function up(): void
    {
        $teachers = $this->table('teachers');
        if (!$teachers->hasColumn('bio')) {
            $teachers->addColumn('bio', 'text', ['null' => true, 'default' => null])->update();
        }
        if (!$teachers->hasColumn('photo')) {
            $teachers->addColumn('photo', 'string', ['limit' => 512, 'null' => true, 'default' => null])->update();
        }

        $lessons = $this->table('lessons');
        if (!$lessons->hasColumn('capacity')) {
            $lessons->addColumn('capacity', 'integer', ['null' => true, 'default' => null])->update();
        }

        if (!$this->hasTable('teacher_availability')) {
            $t = $this->table('teacher_availability');
            $t
                ->addColumn('teacher_id', 'integer', ['null' => false])
                ->addColumn('day_of_week', 'integer', ['null' => false, 'comment' => '0=Sun .. 6=Sat'])
                ->addColumn('start_time', 'time', ['null' => false])
                ->addColumn('end_time', 'time', ['null' => false])
                ->addColumn('is_active', 'boolean', ['default' => true, 'null' => false])
                ->addTimestamps()
                ->addForeignKey('teacher_id', 'teachers', 'id', [
                    'delete' => 'CASCADE',
                    'update' => 'NO_ACTION',
                ])
                ->addIndex(['teacher_id', 'day_of_week'])
                ->create();
        }

        if (!$this->hasTable('announcements')) {
            $a = $this->table('announcements');
            $a
                ->addColumn('teacher_id', 'integer', ['null' => false])
                ->addColumn('lesson_id', 'integer', ['null' => true, 'default' => null])
                ->addColumn('body', 'text', ['null' => false])
                ->addColumn('sent_at', 'datetime', ['null' => false])
                ->addTimestamps()
                ->addForeignKey('teacher_id', 'teachers', 'id', [
                    'delete' => 'CASCADE',
                    'update' => 'NO_ACTION',
                ])
                ->addForeignKey('lesson_id', 'lessons', 'id', [
                    'delete' => 'SET_NULL',
                    'update' => 'NO_ACTION',
                ])
                ->addIndex(['teacher_id', 'sent_at'])
                ->create();
        }
    }

    public function down(): void
    {
        if ($this->hasTable('announcements')) {
            $this->table('announcements')->drop()->save();
        }
        if ($this->hasTable('teacher_availability')) {
            $this->table('teacher_availability')->drop()->save();
        }

        $lessons = $this->table('lessons');
        if ($lessons->hasColumn('capacity')) {
            $lessons->removeColumn('capacity')->update();
        }

        $teachers = $this->table('teachers');
        if ($teachers->hasColumn('photo')) {
            $teachers->removeColumn('photo')->update();
        }
        if ($teachers->hasColumn('bio')) {
            $teachers->removeColumn('bio')->update();
        }
    }
}
