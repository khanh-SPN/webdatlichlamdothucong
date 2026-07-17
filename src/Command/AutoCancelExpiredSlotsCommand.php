<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Command\Command;

/**
 * Auto-cancel expired slots that have no bookings
 */
class AutoCancelExpiredSlotsCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $slotsTable = $this->fetchTable('TeacherAvailabilitySlots');
        
        $io->info('Checking for expired slots without bookings...');
        
        $result = $slotsTable->autoCancelExpiredSlots();
        
        if ($result['cancelled'] > 0) {
            $io->success("Cancelled {$result['cancelled']} expired slots.");
        } else {
            $io->info('No expired slots to cancel.');
        }
        
        if (!empty($result['errors'])) {
            $io->warning('Errors occurred:');
            foreach ($result['errors'] as $error) {
                $io->error("  - Slot {$error['slot_id']}: {$error['message']}");
            }
        }
        
        return static::CODE_SUCCESS;
    }
}
