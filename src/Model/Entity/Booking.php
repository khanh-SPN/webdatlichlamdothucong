<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Booking Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $workshop_id
 * @property int|null $slot_id
 * @property \Cake\I18n\Date|null $booking_date
 * @property string|null $status
 * @property string|null $checkout_group
 * @property int $quantity
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Workshop $workshop
 * @property \App\Model\Entity\TeacherAvailabilitySlot|null $slot
 * @property \App\Model\Entity\Payment[] $payments
 */
class Booking extends Entity
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
        'user_id' => true,
        'workshop_id' => true,
        'slot_id' => true,
        'booking_date' => true,
        'status' => true,
        'checkout_group' => true,
        'quantity' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'workshop' => true,
        'slot' => true,
        'payments' => true,
    ];
}
