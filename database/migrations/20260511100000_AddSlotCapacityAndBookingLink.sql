-- Migration: Add capacity to slots and link bookings to slots
-- Created: 2026-05-11
-- Purpose: Make teacher_availability_slots the source of truth for bookings

-- 1. Add capacity and is_active to teacher_availability_slots
ALTER TABLE `teacher_availability_slots`
ADD COLUMN `capacity` INT UNSIGNED DEFAULT NULL COMMENT 'Max seats for this session (NULL = use lesson.capacity)',
ADD COLUMN `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Whether this session is bookable',
ADD COLUMN `seats_booked` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Cached count of booked seats';

-- 2. Add slot_id to bookings to link to specific session
ALTER TABLE `bookings`
ADD COLUMN `slot_id` INT UNSIGNED DEFAULT NULL COMMENT 'Links to teacher_availability_slots',
ADD INDEX `slot_id` (`slot_id`),
ADD CONSTRAINT `fk_bookings_slot` FOREIGN KEY (`slot_id`) 
    REFERENCES `teacher_availability_slots` (`id`) 
    ON DELETE SET NULL 
    ON UPDATE CASCADE;

-- 3. Add trigger to auto-update seats_booked when booking changes
DELIMITER //

CREATE TRIGGER `update_slot_booked_after_insert` 
AFTER INSERT ON `bookings`
FOR EACH ROW
BEGIN
    IF NEW.slot_id IS NOT NULL AND NEW.status IN ('confirmed', 'pending') THEN
        UPDATE `teacher_availability_slots` 
        SET `seats_booked` = `seats_booked` + NEW.quantity
        WHERE `id` = NEW.slot_id;
    END IF;
END//

CREATE TRIGGER `update_slot_booked_after_update`
AFTER UPDATE ON `bookings`
FOR EACH ROW
BEGIN
    -- If slot changed or status changed
    IF OLD.slot_id IS NOT NULL THEN
        IF NEW.slot_id IS NULL OR NEW.status = 'cancelled' OR NEW.slot_id != OLD.slot_id THEN
            UPDATE `teacher_availability_slots` 
            SET `seats_booked` = `seats_booked` - OLD.quantity
            WHERE `id` = OLD.slot_id;
        END IF;
    END IF;
    
    IF NEW.slot_id IS NOT NULL AND NEW.status IN ('confirmed', 'pending') AND 
       (OLD.slot_id IS NULL OR OLD.slot_id != NEW.slot_id OR OLD.status = 'cancelled') THEN
        UPDATE `teacher_availability_slots` 
        SET `seats_booked` = `seats_booked` + NEW.quantity
        WHERE `id` = NEW.slot_id;
    END IF;
END//

CREATE TRIGGER `update_slot_booked_after_delete`
AFTER DELETE ON `bookings`
FOR EACH ROW
BEGIN
    IF OLD.slot_id IS NOT NULL AND OLD.status IN ('confirmed', 'pending') THEN
        UPDATE `teacher_availability_slots` 
        SET `seats_booked` = `seats_booked` - OLD.quantity
        WHERE `id` = OLD.slot_id;
    END IF;
END//

DELIMITER ;

-- 4. Initialize seats_booked for existing slots based on current bookings
-- (Run this after migration if you have existing data)
-- UPDATE `teacher_availability_slots` s
-- SET `seats_booked` = (
--     SELECT COALESCE(SUM(b.quantity), 0)
--     FROM `bookings` b
--     WHERE b.slot_id = s.id
--     AND b.status IN ('confirmed', 'pending')
-- );
