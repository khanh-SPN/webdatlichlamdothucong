<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddSubjectToAnnouncements extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('announcements');
        if (!$table->hasColumn('subject')) {
            $table->addColumn('subject', 'string', [
                'limit' => 100,
                'null' => false,
                'default' => 'Announcement',
            ])->update();
        }
    }

    public function down(): void
    {
        $table = $this->table('announcements');
        if ($table->hasColumn('subject')) {
            $table->removeColumn('subject')->update();
        }
    }
}
