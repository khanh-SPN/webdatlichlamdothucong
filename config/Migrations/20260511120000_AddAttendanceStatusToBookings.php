<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddAttendanceStatusToBookings extends AbstractMigration
{
    public function up(): void
    {
        $bookings = $this->table('bookings');
        if (!$bookings->hasColumn('attendance_status')) {
            $bookings->addColumn('attendance_status', 'string', [
                'limit' => 20,
                'null' => true,
                'default' => null,
                'comment' => 'present|absent|late|excused',
            ])->update();
        }
        if (!$bookings->hasColumn('attendance_updated')) {
            $bookings->addColumn('attendance_updated', 'datetime', [
                'null' => true,
                'default' => null,
            ])->update();
        }
    }

    public function down(): void
    {
        $bookings = $this->table('bookings');
        if ($bookings->hasColumn('attendance_updated')) {
            $bookings->removeColumn('attendance_updated')->update();
        }
        if ($bookings->hasColumn('attendance_status')) {
            $bookings->removeColumn('attendance_status')->update();
        }
    }
}
