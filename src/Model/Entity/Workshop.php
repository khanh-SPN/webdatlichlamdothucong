<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Workshop Entity
 *
 * @property int $id
 * @property string $workshop_name
 * @property string|null $workshop_type
 * @property string|null $description
 * @property string $price
 * @property int|null $capacity
 * @property int $teacher_id
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Teacher $teacher
 * @property \App\Model\Entity\Booking[] $bookings
 * @property \App\Model\Entity\Material[] $materials
 */
class Workshop extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'workshop_name' => true,
        'workshop_type' => true,
        'description' => true,
        'price' => true,
        'capacity' => true,
        'teacher_id' => true,
        'created' => true,
        'modified' => true,
        'teacher' => true,
        'bookings' => true,
        'materials' => true,
    ];
}
