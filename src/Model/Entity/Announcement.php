<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $teacher_id
 * @property int|null $workshop_id
 * @property string $subject
 * @property string $body
 * @property \Cake\I18n\DateTime $sent_at
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property \App\Model\Entity\Teacher $teacher
 * @property \App\Model\Entity\Workshop|null $workshop
 */
class Announcement extends Entity
{
    protected array $_accessible = [
        'teacher_id' => true,
        'workshop_id' => true,
        'subject' => true,
        'body' => true,
        'sent_at' => true,
        'created' => true,
        'modified' => true,
        'teacher' => true,
        'workshop' => true,
    ];
}
