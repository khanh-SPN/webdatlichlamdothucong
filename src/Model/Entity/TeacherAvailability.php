<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $teacher_id
 * @property int $day_of_week
 * @property \Cake\I18n\FrozenTime|string $start_time
 * @property \Cake\I18n\FrozenTime|string $end_time
 * @property bool $is_active
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property \App\Model\Entity\Teacher $teacher
 */
class TeacherAvailability extends Entity
{
    protected array $_accessible = [
        'teacher_id' => true,
        'day_of_week' => true,
        'start_time' => true,
        'end_time' => true,
        'is_active' => true,
        'created' => true,
        'modified' => true,
        'teacher' => true,
    ];
}
