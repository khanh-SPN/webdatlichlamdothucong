-- Migration: Rename lessons to workshops
-- Created: 2026-05-21
-- Purpose: Rename 'lessons' table to 'workshops' and all related columns from lesson_* to workshop_*

-- 1. Rename the main table
RENAME TABLE `lessons` TO `workshops`;

-- 2. Rename columns in workshops table
ALTER TABLE `workshops`
  CHANGE COLUMN `lesson_name` `workshop_name` VARCHAR(255) NOT NULL,
  CHANGE COLUMN `lesson_type` `workshop_type` VARCHAR(100) DEFAULT NULL;

-- 3. Rename lesson_id to workshop_id in bookings table
ALTER TABLE `bookings`
  DROP FOREIGN KEY IF EXISTS `bookings_ibfk_2`,
  DROP INDEX IF EXISTS `lesson_id`;

ALTER TABLE `bookings`
  CHANGE COLUMN `lesson_id` `workshop_id` INT UNSIGNED NOT NULL;

ALTER TABLE `bookings`
  ADD INDEX `workshop_id` (`workshop_id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`workshop_id`) REFERENCES `workshops` (`id`);

-- 4. Rename lesson_id to workshop_id in materials table
ALTER TABLE `materials`
  DROP FOREIGN KEY IF EXISTS `materials_ibfk_1`,
  DROP INDEX IF EXISTS `lesson_id`;

ALTER TABLE `materials`
  CHANGE COLUMN `lesson_id` `workshop_id` INT UNSIGNED NOT NULL;

ALTER TABLE `materials`
  ADD INDEX `workshop_id` (`workshop_id`),
  ADD CONSTRAINT `materials_ibfk_1` FOREIGN KEY (`workshop_id`) REFERENCES `workshops` (`id`);

-- 5. Rename lesson_id to workshop_id in announcements table
ALTER TABLE `announcements`
  DROP FOREIGN KEY IF EXISTS `announcements_ibfk_2`,
  DROP INDEX IF EXISTS `lesson_id`;

ALTER TABLE `announcements`
  CHANGE COLUMN `lesson_id` `workshop_id` INT UNSIGNED DEFAULT NULL;

ALTER TABLE `announcements`
  ADD INDEX `workshop_id` (`workshop_id`),
  ADD CONSTRAINT `announcements_ibfk_2` FOREIGN KEY (`workshop_id`) REFERENCES `workshops` (`id`);

-- 6. Rename lesson_id to workshop_id in teacher_availability_slots table
ALTER TABLE `teacher_availability_slots`
  DROP FOREIGN KEY IF EXISTS `fk_slots_lesson`,
  DROP INDEX IF EXISTS `lesson_id`;

ALTER TABLE `teacher_availability_slots`
  CHANGE COLUMN `lesson_id` `workshop_id` INT UNSIGNED DEFAULT NULL;

ALTER TABLE `teacher_availability_slots`
  ADD INDEX `workshop_id` (`workshop_id`),
  ADD CONSTRAINT `fk_slots_workshop` FOREIGN KEY (`workshop_id`) REFERENCES `workshops` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
