<?php
declare(strict_types=1);

namespace App\Service;

use Cake\I18n\Date;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * Builds structured JSON-safe context from the database for the AI assistant.
 */
class TeacherAvailabilityContextBuilder
{
    use LocatorAwareTrait;

    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        $teachersTable = $this->fetchTable('Teachers');
        $today = Date::today()->format('Y-m-d');

        $teachers = $teachersTable->find()
            ->contain([
                'Workshops' => function ($q) {
                    return $q->select(['id', 'teacher_id', 'workshop_name', 'workshop_type', 'price']);
                },
                'TeacherAvailabilitySlots' => function ($q) use ($today) {
                    return $q
                        ->select([
                            'id',
                            'teacher_id',
                            'workshop_id',
                            'session_date',
                            'time_label',
                            'notes',
                            'capacity',
                            'is_active',
                            'seats_booked',
                        ])
                        ->contain([
                            'Workshops' => function ($lq) {
                                return $lq->select(['id', 'workshop_name', 'capacity']);
                            },
                        ])
                        ->where([
                            'TeacherAvailabilitySlots.session_date >=' => $today,
                            'TeacherAvailabilitySlots.is_active' => true,
                        ])
                        ->orderBy(['TeacherAvailabilitySlots.session_date' => 'ASC']);
                },
            ])
            ->orderBy(['Teachers.name' => 'ASC'])
            ->all();

        $out = [
            'as_of' => $today,
            'timezone_note' => 'Dates are calendar days in the studio\'s local timezone.',
            'teachers' => [],
        ];

        foreach ($teachers as $t) {
            $workshops = [];
            foreach ($t->workshops as $workshop) {
                $workshops[] = [
                    'id' => $workshop->id,
                    'name' => $workshop->workshop_name,
                    'type' => $workshop->workshop_type,
                    'price' => (string)$workshop->price,
                ];
            }

            $slots = [];
            foreach ($t->teacher_availability_slots as $slot) {
                $sd = $slot->session_date;
                $dateStr = is_object($sd) && method_exists($sd, 'format')
                    ? $sd->format('Y-m-d')
                    : (string)$sd;
                
                // Calculate available seats using cached seats_booked
                $capacity = $slot->capacity ?? ($slot->workshop ? $slot->workshop->capacity : null) ?? 0;
                $available = max(0, $capacity - (int)($slot->seats_booked ?? 0));
                
                $slots[] = [
                    'workshop_id' => $slot->workshop_id,
                    'date' => $dateStr,
                    'time' => $slot->time_label,
                    'workshop_name' => $slot->workshop ? $slot->workshop->workshop_name : null,
                    'notes' => $slot->notes,
                    'capacity' => $capacity,
                    'seats_booked' => (int)($slot->seats_booked ?? 0),
                    'available' => $available,
                    'is_fully_booked' => $available <= 0,
                ];
            }

            $out['teachers'][] = [
                'id' => $t->id,
                'name' => $t->name,
                'specialization' => $t->specialization,
                'workshops' => $workshops,
                'upcoming_sessions' => $slots,
            ];
        }

        return $out;
    }

    /**
     * Plain-text schedule for fallback responses and prompts.
     */
    public function formatContextForPrompt(array $context): string
    {
        $lines = ['Official schedule data (only use this; do not invent times):', ''];

        foreach ($context['teachers'] as $t) {
            $lines[] = 'Teacher: ' . $t['name'];
            if (!empty($t['specialization'])) {
                $lines[] = '  Specialization: ' . $t['specialization'];
            }
            if ($t['workshops'] !== []) {
                $lines[] = '  Workshops they teach: ' . implode(', ', array_column($t['workshops'], 'name'));
            }
            if ($t['upcoming_sessions'] === []) {
                $lines[] = '  No upcoming published sessions on file.';
            } else {
                foreach ($t['upcoming_sessions'] as $s) {
                    $bit = '  • ' . $s['date'];
                    if (!empty($s['time'])) {
                        $bit .= ' ' . $s['time'];
                    }
                    if (!empty($s['workshop_name'])) {
                        $bit .= ' · ' . $s['workshop_name'];
                    }
                    if (!empty($s['notes'])) {
                        $bit .= ' (' . $s['notes'] . ')';
                    }
                    $lines[] = $bit;
                }
            }
            $lines[] = '';
        }

        return implode("\n", $lines);
    }

    /**
     * Friendly full schedule for chat (end users).
     *
     * @param array<string, mixed> $context
     */
    public function formatFullScheduleForUser(array $context): string
    {
        $lines = ['Here are all upcoming published sessions:', ''];

        $teachers = $context['teachers'] ?? [];
        if ($teachers === []) {
            return "We don’t have any teachers on file yet.\n\nCheck back soon or use our Booking page.";
        }

        foreach ($teachers as $t) {
            $lines[] = $t['name'] . (!empty($t['specialization']) ? ' · ' . $t['specialization'] : '');
            $sessions = $t['upcoming_sessions'] ?? [];
            if ($sessions === []) {
                $lines[] = '  • No upcoming published sessions.';
            } else {
                foreach ($sessions as $s) {
                    $bit = '  • ' . $s['date'];
                    if (!empty($s['time'])) {
                        $bit .= ' · ' . $s['time'];
                    }
                    if (!empty($s['workshop_name'])) {
                        $bit .= ' · ' . $s['workshop_name'];
                    }
                    $lines[] = $bit;
                }
            }
            $lines[] = '';
        }

        $lines[] = 'You can book from our Booking page when you are ready.';

        return implode("\n", $lines);
    }
}
