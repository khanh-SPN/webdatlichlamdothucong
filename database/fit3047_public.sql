-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th5 13, 2026 lúc 09:30 PM
-- Phiên bản máy phục vụ: 11.4.10-MariaDB
-- Phiên bản PHP: 8.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `u26s1112_fit3047`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `body` text NOT NULL,
  `sent_at` datetime NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `subject` varchar(100) NOT NULL DEFAULT 'Announcement'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `announcements`
--

INSERT INTO `announcements` (`id`, `teacher_id`, `lesson_id`, `body`, `sent_at`, `created`, `modified`, `subject`) VALUES
(1, 2, 5, 'sfdrgthjjk', '2026-05-13 07:50:47', '2026-05-13 07:50:47', '2026-05-13 07:50:47', 'Pottery');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `attendance_records`
--

CREATE TABLE `attendance_records` (
  `id` int(11) NOT NULL,
  `slot_id` int(11) NOT NULL COMMENT 'Reference to teacher_availability_slots',
  `booking_id` int(11) NOT NULL COMMENT 'Reference to bookings',
  `student_id` int(11) NOT NULL COMMENT 'Reference to users (student)',
  `teacher_id` int(11) NOT NULL COMMENT 'Reference to teachers',
  `status` enum('present','absent','late','excused') NOT NULL DEFAULT 'present' COMMENT 'Attendance status',
  `marked_at` datetime NOT NULL COMMENT 'When attendance was marked',
  `marked_by` int(11) NOT NULL COMMENT 'User ID who marked the attendance (teacher)',
  `notes` text DEFAULT NULL COMMENT 'Optional notes about attendance',
  `is_locked` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Whether attendance is locked for editing',
  `locked_at` datetime DEFAULT NULL COMMENT 'When attendance was locked',
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `booking_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `checkout_group` varchar(36) DEFAULT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `slot_id` int(11) DEFAULT NULL COMMENT 'Links to teacher_availability_slots'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `lesson_id`, `booking_date`, `status`, `created`, `modified`, `checkout_group`, `quantity`, `slot_id`) VALUES
(1, 1, 4, '2026-05-05', 'approved', '2026-04-22 22:59:38', '2026-04-30 00:16:06', NULL, 1, NULL),
(2, 4, 8, '2026-04-24', 'pending', '2026-04-23 00:06:35', '2026-04-23 00:06:35', NULL, 1, NULL),
(3, 4, 8, '2026-04-27', 'pending', '2026-04-23 00:20:17', '2026-04-23 00:20:17', NULL, 1, NULL),
(4, 5, 4, '2026-05-16', 'confirmed', '2026-04-26 02:59:25', '2026-04-26 03:00:59', NULL, 1, NULL),
(5, 1, 4, '2026-05-03', 'cancelled', '2026-04-30 00:16:29', '2026-04-30 00:46:13', NULL, 1, NULL),
(6, 1, 3, '2026-05-01', 'cancelled', '2026-04-30 00:16:38', '2026-04-30 00:46:09', NULL, 1, NULL),
(7, 1, 3, '2026-04-30', 'cancelled', '2026-04-30 00:16:46', '2026-04-30 00:46:21', NULL, 1, NULL),
(8, 1, 4, '2026-05-01', 'cancelled', '2026-04-30 00:16:52', '2026-04-30 00:46:17', NULL, 1, NULL),
(9, 1, 4, '2026-05-22', 'cancelled', '2026-04-30 00:23:17', '2026-04-30 00:46:05', NULL, 1, NULL),
(10, 1, 3, '2026-05-15', 'cancelled', '2026-04-30 00:29:09', '2026-04-30 00:46:01', NULL, 1, NULL),
(11, 1, 3, '2026-05-16', 'cancelled', '2026-04-30 00:29:34', '2026-04-30 00:45:57', NULL, 1, NULL),
(12, 1, 3, '2026-05-16', 'cancelled', '2026-04-30 00:31:27', '2026-04-30 00:45:53', NULL, 1, NULL),
(13, 1, 4, '2026-05-14', 'cancelled', '2026-04-30 00:41:22', '2026-04-30 00:45:46', NULL, 1, NULL),
(14, 5, 3, '2026-05-11', 'confirmed', '2026-04-30 01:29:40', '2026-04-30 01:30:38', NULL, 1, NULL),
(15, 7, 3, '2026-05-16', 'pending', '2026-04-30 01:47:18', '2026-04-30 01:47:18', NULL, 1, NULL),
(16, 1, 4, '2026-05-20', 'cancelled', '2026-05-04 01:34:40', '2026-05-13 07:19:43', NULL, 1, NULL),
(17, 8, 6, '2026-05-06', 'pending', '2026-05-05 03:49:24', '2026-05-05 03:49:24', NULL, 1, NULL),
(18, 8, 6, '2026-05-06', 'pending', '2026-05-05 03:49:34', '2026-05-05 03:49:34', NULL, 1, NULL),
(19, 1, 4, '2026-05-28', 'confirmed', '2026-05-07 00:56:20', '2026-05-07 00:57:12', NULL, 1, NULL),
(20, 9, 5, '2026-05-20', 'pending', '2026-05-11 01:02:34', '2026-05-11 01:02:34', NULL, 1, NULL),
(21, 6, 3, '2026-05-15', 'confirmed', '2026-05-11 01:56:39', '2026-05-11 01:56:54', NULL, 1, NULL),
(22, 10, 3, '2026-05-20', 'cancelled', '2026-05-13 07:51:16', '2026-05-13 08:05:19', '8331c976-9592-4fac-a2c4-1cd9dcc5f5b1', 2, 31),
(23, 1, 8, '2026-05-22', 'pending', '2026-05-13 07:59:04', '2026-05-13 07:59:04', 'f27ad9ef-5a81-48ea-9a82-67856859802d', 4, 47),
(24, 10, 6, '2026-05-16', 'cancelled', '2026-05-13 08:02:35', '2026-05-13 08:05:49', 'e1c9d8a3-2071-4146-93fe-ee27945fd3ca', 1, 35),
(25, 10, 3, '2026-05-21', 'confirmed', '2026-05-13 08:07:51', '2026-05-13 08:08:08', 'ebbd13f4-008d-4d91-bb9f-f80c8cc1e961', 1, 32),
(26, 10, 3, '2026-05-20', 'confirmed', '2026-05-13 08:11:04', '2026-05-13 08:15:10', '9fa971b8-8d3a-4ba8-8906-2bdf13257f52', 2, 31),
(27, 10, 6, '2026-05-15', 'confirmed', '2026-05-13 08:42:36', '2026-05-13 08:44:04', '3873b1f0-e756-4cd4-a504-91c02b301c0e', 1, 34),
(28, 10, 4, '2026-05-18', 'pending', '2026-05-13 08:50:05', '2026-05-13 08:50:05', 'a2d4b722-383f-4a1c-86d7-df852bcfcc0f', 1, 10),
(29, 8, 4, '2026-05-18', 'confirmed', '2026-05-13 10:59:48', '2026-05-13 11:00:07', '33874ee7-3c24-455d-b36a-dd3c721bc15b', 3, 10),
(30, 8, 4, '2026-05-15', 'confirmed', '2026-05-13 11:00:45', '2026-05-13 11:00:58', '47eb3d39-db8a-49c3-bafa-4d1b3c3c1597', 2, 8);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `company_infos`
--

CREATE TABLE `company_infos` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `company_infos`
--

INSERT INTO `company_infos` (`id`, `name`, `email`, `phone`, `address`, `description`, `created`, `modified`) VALUES
(1, 'CandleCraft Academy', 'CandleCraftAcademy@gmail.com', '+61412345678', 'melbourne', 'CandleCraft Academy is a creative workshop space offering hands on experiences in candle making, pottery, knitting, and other crafts. We aim to inspire creativity and relaxation through guided sessions led by experienced instructors.', '2026-03-23 08:56:13', '2026-04-30 00:17:29');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `customers`
--

INSERT INTO `customers` (`id`, `user_id`, `name`, `phone`, `address`, `created`, `modified`) VALUES
(1, 2, 'Nguyen Van A', '098765423', 'ABC,D,TN', '2026-03-23 08:16:49', '2026-03-23 08:17:21'),
(2, 3, '', '', '', '2026-03-23 09:43:00', '2026-03-23 09:43:00'),
(4, 5, '', '', '', '2026-03-23 10:18:26', '2026-03-23 10:18:26'),
(5, 6, '', '', '', '2026-03-23 13:45:36', '2026-03-23 13:45:36'),
(6, 7, 'Log', '0411223344', NULL, '2026-04-22 11:26:52', '2026-04-22 11:26:52'),
(7, 8, 'Alex', '4411223311', '', '2026-04-22 12:10:12', '2026-05-05 03:49:10'),
(8, 9, 'John', '042222222', '', '2026-04-22 13:07:13', '2026-05-11 01:00:11'),
(12, 2, 'Leo', '0412345555', NULL, '2026-04-22 23:11:26', '2026-04-22 23:11:26'),
(13, 3, 'zhang', '04111111111', NULL, '2026-04-22 23:32:23', '2026-04-22 23:32:23'),
(14, 4, 'Monisha Biswas', '0490915612', NULL, '2026-04-23 00:04:16', '2026-04-23 00:04:16'),
(15, 5, 'Hung Hoang', '0490915111', NULL, '2026-04-26 02:56:06', '2026-04-26 02:56:06'),
(16, 7, 'Rain Zhang', '04111111111', NULL, '2026-04-30 01:46:15', '2026-04-30 01:46:15'),
(17, 8, 'Alex', '044112211', NULL, '2026-05-05 03:48:23', '2026-05-05 03:48:23'),
(18, 9, 'Tester', '0422333444', NULL, '2026-05-11 00:58:58', '2026-05-11 00:58:58'),
(19, 10, 'Le Hoang Quoc Hung', '0490915666', 'au', '2026-05-13 07:50:50', '2026-05-13 07:50:50');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `enquiries`
--

CREATE TABLE `enquiries` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'new',
  `user_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `enquiries`
--

INSERT INTO `enquiries` (`id`, `first_name`, `last_name`, `email`, `phone`, `subject`, `message`, `status`, `user_id`, `created`, `modified`) VALUES
(1, 'HA', 'Nguyen', 'hanguyen@gmail.com', '09189231123', 'a', 'ass', 'replied', NULL, '2026-03-23 07:03:25', '2026-03-23 07:11:54'),
(3, 'RAIN', 'ZHANG', 'szha0331@student.monash.edu', '01234567', 'Pottery', '<script>alert(\'xss-test\')</script>', 'replied', NULL, '2026-05-05 04:05:00', '2026-05-07 00:58:33'),
(4, 'RAIN', 'ZHANG', 'szha0331@student.monash.edu', '04411223344', 'Pottery', '<script>alert(\'xss-test\')</script>', 'replied', NULL, '2026-05-05 04:06:18', '2026-05-07 00:58:29'),
(5, 'RAIN', 'ZHANG', 'szha0331@student.monash.edu', '0449919725', 'Pottery', 'test; whoami', 'pending', NULL, '2026-05-05 04:08:55', '2026-05-05 04:08:55'),
(6, 'Bob', 'Wang', 'Bob@gmail.com', '0111', 'Pottery', 'xzdsfggchhvj', 'replied', NULL, '2026-05-05 04:16:36', '2026-05-07 00:58:25'),
(7, 'Bob', 'Wang', 'Bob@gmail.com', '0111', 'Pottery', 'dzsfxghj', 'pending', NULL, '2026-05-05 04:17:19', '2026-05-05 04:17:19'),
(8, '67', '67', '67@67.com', '6767676767', '67', '676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776676767676767676676767676767676767676767676767676776', 'replied', NULL, '2026-05-06 23:58:44', '2026-05-07 00:58:20'),
(9, 'RAIN', 'ZHANG', 'szha0331@student.monash.edu', '0449919725', 'Pottery', 'zdxfghfjkl', 'pending', 6, '2026-05-13 06:07:53', '2026-05-13 06:07:53'),
(10, 'RAIN', 'ZHANG', 'szha0331@student.monash.edu', '0449919725', 'Pottery', 'dfxghjkl', 'pending', 6, '2026-05-13 06:39:28', '2026-05-13 06:39:28'),
(11, 'RAIN', 'ZHANG', 'szha0331@student.monash.edu', '0449919725', 'Pottery', 'sdfserfefsefes', 'pending', 6, '2026-05-13 06:40:35', '2026-05-13 06:40:35'),
(12, 'RAIN', 'ZHANG', 'szha0331@student.monash.edu', '0449919725', 'Pottery', 'SAQWDEFRGTHJK', 'pending', 6, '2026-05-13 07:01:49', '2026-05-13 07:01:49');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `category`, `display_order`, `created`, `modified`) VALUES
(2, 'How do I book a workshop?', 'You can browse available lessons, select your preferred date, and complete the booking through our website.', 'booking', 1, '2026-04-01 19:20:27', '2026-04-01 19:20:27'),
(3, 'Can I change my booking date?', 'Yes, you can reschedule your booking at least 24 hours before the workshop starts.', 'booking', 2, '2026-04-01 19:20:27', '2026-04-01 19:20:27'),
(4, 'Can I cancel my booking?', 'Bookings can be cancelled up to 24 hours before the session. Late cancellations may not be refunded.', 'booking', 3, '2026-04-01 19:20:27', '2026-04-01 19:20:27'),
(5, 'What payment methods do you accept?', 'We accept credit/debit cards, PayPal, and selected local payment methods.', 'payment', 4, '2026-04-01 19:20:27', '2026-04-01 19:20:27'),
(6, 'Is my payment secure?', 'Yes, all payments are processed through secure and encrypted payment gateways.', 'payment', 5, '2026-04-01 19:20:27', '2026-04-01 19:20:27'),
(7, 'Will I receive a payment confirmation?', 'Yes, a confirmation email will be sent immediately after successful payment.', 'payment', 6, '2026-04-01 19:20:27', '2026-04-01 19:20:27'),
(8, 'Do I need prior experience?', 'No prior experience is required. All workshops are beginner friendly.', 'general', 7, '2026-04-01 19:20:27', '2026-04-01 19:20:27'),
(9, 'What should I bring to the workshop?', 'All materials are provided. Just bring yourself and your creativity!', 'general', 8, '2026-04-01 19:20:27', '2026-04-01 19:20:27'),
(10, 'Are your workshops suitable for children?', 'Yes, we offer sessions suitable for children aged 8 and above.', 'general', 9, '2026-04-01 19:20:27', '2026-04-01 19:20:27'),
(11, 'What is your refund policy?', 'Refunds are available for cancellations made at least 24 hours in advance.', 'policy', 10, '2026-04-01 19:20:27', '2026-04-01 19:20:27'),
(12, 'Do you offer group discounts?', 'Yes, we provide special pricing for group bookings. Please contact us for details.', 'policy', 11, '2026-04-01 19:20:27', '2026-04-01 19:20:27'),
(13, 'Can I book a private workshop?', 'Yes, private sessions are available upon request for individuals or groups.', 'policy', 12, '2026-04-01 19:20:27', '2026-04-01 19:20:27'),
(14, 'Are workshops suitable for children?', 'Our workshops are generally suitable for participants of all ages. However, some sessions may have specific age recommendations depending on the activity.', 'General', 1, '2026-04-29 14:08:50', '2026-04-29 14:08:50'),
(15, 'Is there a minimum age requirement?', 'The recommended minimum age for most workshops is 10 years old. Please check individual workshop details for specific requirements.', 'General', 5, '2026-04-29 14:08:50', '2026-04-29 14:08:50'),
(16, 'Do children need to be accompanied by an adult?', 'Yes, children under 16 must be accompanied by an adult during the workshop.', 'General', 3, '2026-04-29 14:08:50', '2026-04-29 14:08:50');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lessons`
--

CREATE TABLE `lessons` (
  `id` int(11) NOT NULL,
  `lesson_name` varchar(255) NOT NULL,
  `lesson_type` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `lessons`
--

INSERT INTO `lessons` (`id`, `lesson_name`, `lesson_type`, `description`, `price`, `teacher_id`, `created`, `modified`, `capacity`) VALUES
(3, 'Advanced Candle Art', 'Workshop', 'Create layered and decorative candles.', 65.00, 5, '2026-04-01 19:22:31', '2026-04-01 19:22:31', NULL),
(4, 'Pottery for Beginners', 'Workshop', 'Introduction to clay shaping and pottery basics.', 50.00, 2, '2026-04-01 19:22:31', '2026-04-01 19:22:31', NULL),
(5, 'Ceramic Design Masterclass', 'Masterclass', 'Advanced ceramic crafting techniques. Not suitable for the freshman', 80.00, 2, '2026-04-01 19:22:31', '2026-05-13 10:53:49', 1),
(6, 'Knitting Basics', 'Workshop', 'Learn knitting techniques for beginners.', 40.00, 3, '2026-04-01 19:22:31', '2026-04-01 19:22:31', NULL),
(7, 'Creative DIY Crafts', 'Workshop', 'Explore multiple crafting techniques.', 55.00, 4, '2026-04-01 19:22:31', '2026-04-01 19:22:31', NULL),
(8, 'Clay Sculpture Workshop', 'Workshop', 'Create sculptures using clay.', 60.00, 6, '2026-04-01 19:22:31', '2026-04-01 19:22:31', NULL),
(9, 'Multi class pass (3 classes)', 'Pass', 'A flexible bundle for three classes. Purchase now, then book your dates across candle making, pottery, and knitting over multiple visits.', 135.00, 2, '2026-05-13 06:57:47', '2026-05-13 06:57:47', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `materials`
--

CREATE TABLE `materials` (
  `id` int(11) NOT NULL,
  `material_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity_required` int(11) DEFAULT NULL,
  `lesson_id` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `materials`
--

INSERT INTO `materials` (`id`, `material_name`, `description`, `quantity_required`, `lesson_id`, `created`, `modified`) VALUES
(5, 'Clay', 'Premium pottery clay', 3, 3, '2026-04-01 19:22:38', '2026-04-01 19:22:38'),
(6, 'Pottery Tools', 'Basic shaping tools', 1, 3, '2026-04-01 19:22:38', '2026-04-01 19:22:38'),
(7, 'Glaze', 'Ceramic finishing glaze', 1, 4, '2026-04-01 19:22:38', '2026-04-01 19:22:38'),
(8, 'Yarn', 'Soft knitting yarn', 2, 5, '2026-04-01 19:22:38', '2026-04-01 19:22:38'),
(9, 'Knitting Needles', 'Standard knitting needles', 1, 5, '2026-04-01 19:22:38', '2026-04-01 19:22:38'),
(10, 'Craft Kit', 'Mixed crafting materials', 1, 6, '2026-04-01 19:22:38', '2026-04-01 19:22:38'),
(11, 'Modeling Clay', 'Clay for sculpture', 3, 7, '2026-04-01 19:22:38', '2026-04-01 19:22:38');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT 'unpaid',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `stripe_checkout_session_id` varchar(255) DEFAULT NULL,
  `stripe_payment_intent_id` varchar(255) DEFAULT NULL,
  `amount_cents` int(11) DEFAULT NULL,
  `currency` varchar(10) DEFAULT NULL,
  `checkout_group` varchar(36) DEFAULT NULL,
  `stripe_session_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `payments`
--

INSERT INTO `payments` (`id`, `booking_id`, `payment_method`, `payment_status`, `created`, `modified`, `stripe_checkout_session_id`, `stripe_payment_intent_id`, `amount_cents`, `currency`, `checkout_group`, `stripe_session_id`) VALUES
(1, 1, 'stripe', 'unpaid', '2026-04-22 22:59:38', '2026-04-22 22:59:38', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, 'stripe', 'unpaid', '2026-04-23 00:06:35', '2026-04-23 00:06:35', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 3, 'stripe', 'unpaid', '2026-04-23 00:20:17', '2026-04-23 00:20:17', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 4, 'stripe', 'paid', '2026-04-26 02:59:25', '2026-04-26 03:00:59', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 5, 'stripe', 'unpaid', '2026-04-30 00:16:29', '2026-04-30 00:16:29', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 6, 'stripe', 'unpaid', '2026-04-30 00:16:38', '2026-04-30 00:16:38', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 7, 'stripe', 'unpaid', '2026-04-30 00:16:46', '2026-04-30 00:16:46', NULL, NULL, NULL, NULL, NULL, NULL),
(8, 8, 'stripe', 'unpaid', '2026-04-30 00:16:52', '2026-04-30 00:16:52', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 9, 'stripe', 'unpaid', '2026-04-30 00:23:17', '2026-04-30 00:23:17', NULL, NULL, NULL, NULL, NULL, NULL),
(10, 10, 'stripe', 'unpaid', '2026-04-30 00:29:09', '2026-04-30 00:29:09', NULL, NULL, NULL, NULL, NULL, NULL),
(11, 11, 'stripe', 'unpaid', '2026-04-30 00:29:34', '2026-04-30 00:29:34', NULL, NULL, NULL, NULL, NULL, NULL),
(12, 12, 'stripe', 'unpaid', '2026-04-30 00:31:27', '2026-04-30 00:31:27', NULL, NULL, NULL, NULL, NULL, NULL),
(13, 13, 'stripe', 'unpaid', '2026-04-30 00:41:22', '2026-04-30 00:41:22', NULL, NULL, NULL, NULL, NULL, NULL),
(14, 14, 'stripe', 'paid', '2026-04-30 01:29:40', '2026-04-30 01:30:38', NULL, NULL, NULL, NULL, NULL, NULL),
(15, 15, 'stripe', 'unpaid', '2026-04-30 01:47:18', '2026-04-30 01:47:18', NULL, NULL, NULL, NULL, NULL, NULL),
(16, 16, 'stripe', 'unpaid', '2026-05-04 01:34:40', '2026-05-04 01:34:40', NULL, NULL, NULL, NULL, NULL, NULL),
(17, 17, 'stripe', 'unpaid', '2026-05-05 03:49:24', '2026-05-05 03:49:24', NULL, NULL, NULL, NULL, NULL, NULL),
(18, 18, 'stripe', 'unpaid', '2026-05-05 03:49:34', '2026-05-05 03:49:34', NULL, NULL, NULL, NULL, NULL, NULL),
(19, 19, 'stripe', 'paid', '2026-05-07 00:56:20', '2026-05-07 00:57:12', NULL, NULL, NULL, NULL, NULL, NULL),
(20, 20, 'stripe', 'unpaid', '2026-05-11 01:02:34', '2026-05-11 01:02:34', NULL, NULL, NULL, NULL, NULL, NULL),
(21, 21, 'stripe', 'paid', '2026-05-11 01:56:39', '2026-05-11 01:56:54', NULL, NULL, NULL, NULL, NULL, NULL),
(22, 22, 'stripe', 'unpaid', '2026-05-13 07:51:16', '2026-05-13 08:01:31', NULL, NULL, 6500, 'usd', '8331c976-9592-4fac-a2c4-1cd9dcc5f5b1', 'cs_test_a12V6QXxoCi5ksMmzeKG6zPAGhkAr3HCcylHPU9i5eDYs9QiQACy6512oX'),
(23, 23, 'stripe', 'unpaid', '2026-05-13 07:59:04', '2026-05-13 07:59:36', NULL, NULL, 6000, 'usd', 'f27ad9ef-5a81-48ea-9a82-67856859802d', 'cs_test_a19PzThGwIabcimKdO9aXbXVJBIBIa4W9qBq0wY5uLzvGl1uvrjgq2D1d8'),
(24, 24, 'stripe', 'unpaid', '2026-05-13 08:02:35', '2026-05-13 08:02:36', NULL, NULL, 4000, 'usd', 'e1c9d8a3-2071-4146-93fe-ee27945fd3ca', 'cs_test_a1FxPc8P30qdAlt1B6cC4pc9CUBCApVeCcnHNan8jkPbaOzARAmVcmDvOJ'),
(25, 25, 'stripe', 'paid', '2026-05-13 08:07:51', '2026-05-13 08:08:08', NULL, NULL, 6500, 'usd', 'ebbd13f4-008d-4d91-bb9f-f80c8cc1e961', 'cs_test_a1h5Z3ZcPwGdh90mrBOPbhxfjSJIJZWgl4S93ryY1hOhkPiEAwnaQMVcIO'),
(26, 26, 'stripe', 'paid', '2026-05-13 08:11:04', '2026-05-13 08:15:10', NULL, NULL, 6500, 'usd', '9fa971b8-8d3a-4ba8-8906-2bdf13257f52', 'cs_test_a18Jjt2phJ3GyIk7KKof7Qczys4WAIAtvcMtXiUreUxOrOwAjzRuLb2TZD'),
(27, 27, 'stripe', 'paid', '2026-05-13 08:42:36', '2026-05-13 08:44:04', NULL, NULL, 4000, 'usd', '3873b1f0-e756-4cd4-a504-91c02b301c0e', 'cs_test_a1wopJjq59esBE2LeUWCX4dvWw0pogxL8g6ZBcj1aX6C3tcbFQWfjCOlWj'),
(28, 28, 'stripe', 'unpaid', '2026-05-13 08:50:05', '2026-05-13 08:50:06', NULL, NULL, 5000, 'usd', 'a2d4b722-383f-4a1c-86d7-df852bcfcc0f', 'cs_test_a1estsifsOOVriVFBeY9L4oqnG4Vhl8c8X1zxf2H8hOP5cDMEo05ZLLekG'),
(29, 29, 'stripe', 'paid', '2026-05-13 10:59:48', '2026-05-13 11:00:07', NULL, NULL, 5000, 'usd', '33874ee7-3c24-455d-b36a-dd3c721bc15b', 'cs_test_a1bCGIQ39XxCTe5DD72fYGSknJ2WZfjVE4gofCAAnx8L54qJ3cJN2hduQg'),
(30, 30, 'stripe', 'paid', '2026-05-13 11:00:45', '2026-05-13 11:00:58', NULL, NULL, 5000, 'usd', '47eb3d39-db8a-49c3-bafa-4d1b3c3c1597', 'cs_test_a1HnYdupxU0B9gUgLUjE7j3l1fu7pWOcaXAXUZTxwngr2C8b9GdylJs7qq');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phinxlog`
--

CREATE TABLE `phinxlog` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `breakpoint` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phinxlog`
--

INSERT INTO `phinxlog` (`version`, `migration_name`, `start_time`, `end_time`, `breakpoint`) VALUES
(20260331155109, 'AddLoginLockToUsers', '2026-03-31 15:51:33', '2026-03-31 15:51:33', 0),
(20260402120000, 'CreateTeacherAvailabilitySlots', '2026-05-13 06:57:47', '2026-05-13 06:57:47', 0),
(20260420120000, 'SeedSampleTeacherUser', '2026-05-13 06:57:47', '2026-05-13 06:57:47', 0),
(20260420140000, 'EnsureSampleTeacherUser', '2026-05-13 06:57:47', '2026-05-13 06:57:47', 0),
(20260424120000, 'TeacherPortalExpansion', '2026-05-13 06:57:47', '2026-05-13 06:57:47', 0),
(20260425125000, 'SeedMultiClassPassLesson', '2026-05-13 06:57:47', '2026-05-13 06:57:47', 0),
(20260427103500, 'StripeFieldsOnPayments', '2026-05-13 06:57:47', '2026-05-13 06:57:47', 0),
(20260429120000, 'AddCheckoutGroupToBookingsPayments', '2026-05-13 06:57:47', '2026-05-13 06:57:47', 0),
(20260430113000, 'AddQuantityToBookings', '2026-05-13 06:57:47', '2026-05-13 06:57:47', 0),
(20260511150000, 'AddBookableSlotFields', '2026-05-13 06:57:47', '2026-05-13 06:57:47', 0),
(20260512080000, 'AddSubjectToAnnouncements', '2026-05-13 06:57:47', '2026-05-13 06:57:47', 0),
(20260513114453, 'TeacherSlotManagementEnhancement', '2026-05-13 11:27:56', '2026-05-13 11:27:56', 0),
(20260513115245, 'FixSlotDefaultsAndData', '2026-05-13 11:27:56', '2026-05-13 11:27:56', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `slot_status_history`
--

CREATE TABLE `slot_status_history` (
  `id` int(11) NOT NULL,
  `slot_id` int(11) NOT NULL,
  `old_status` varchar(20) DEFAULT NULL,
  `new_status` varchar(20) NOT NULL,
  `changed_by` int(11) NOT NULL COMMENT 'User ID who made the change',
  `reason` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `student_attendance_summary`
--

CREATE TABLE `student_attendance_summary` (
  `id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `teacher_id` int(10) UNSIGNED NOT NULL,
  `total_classes` int(11) NOT NULL DEFAULT 0,
  `attended` int(11) NOT NULL DEFAULT 0,
  `absent` int(11) NOT NULL DEFAULT 0,
  `late` int(11) NOT NULL DEFAULT 0,
  `excused` int(11) NOT NULL DEFAULT 0,
  `attendance_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `last_updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `photo` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `email`, `phone`, `specialization`, `created`, `modified`, `bio`, `photo`) VALUES
(2, 'Emma Wilson', 'emma.wilson@candlecraft.com', '+61 401 234 567', 'Candle Making Specialist', '2026-04-01 19:22:16', '2026-05-13 07:02:56', '<script> \"XSS Attack\" </script>', NULL),
(3, 'Liam Brown', 'liam.brown@candlecraft.com', '+61 402 345 678', 'Pottery & Ceramics Expert', '2026-04-01 19:22:16', '2026-04-01 19:22:16', NULL, NULL),
(4, 'Sophia Nguyen', 'sophia.nguyen@candlecraft.com', '+61 403 456 789', 'Knitting & Textile Crafts', '2026-04-01 19:22:16', '2026-04-01 19:22:16', NULL, NULL),
(5, 'Oliver Smith', 'oliver.smith@candlecraft.com', '+61 404 567 890', 'DIY & Creative Workshops', '2026-04-01 19:22:16', '2026-04-01 19:22:16', NULL, NULL),
(6, 'Isabella Garcia', 'isabella.garcia@candlecraft.com', '+61 405 678 901', 'Advanced Candle Design', '2026-04-01 19:22:16', '2026-04-01 19:22:16', NULL, NULL),
(7, 'Noah Johnson', 'noah.johnson@candlecraft.com', '+61 406 789 012', 'Sculpture & Clay Modeling', '2026-04-01 19:22:16', '2026-04-01 19:22:16', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `teacher_availability`
--

CREATE TABLE `teacher_availability` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `day_of_week` int(11) NOT NULL COMMENT '0=Sun .. 6=Sat',
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `teacher_availability`
--

INSERT INTO `teacher_availability` (`id`, `teacher_id`, `day_of_week`, `start_time`, `end_time`, `is_active`, `created`, `modified`) VALUES
(9, 3, 5, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(10, 3, 6, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(11, 4, 1, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(12, 4, 2, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(13, 4, 3, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(14, 4, 5, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(15, 4, 6, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(16, 5, 1, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(17, 5, 2, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(18, 5, 3, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(19, 5, 4, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(20, 5, 5, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(21, 5, 6, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(22, 6, 1, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(23, 6, 3, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(24, 6, 4, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(25, 6, 5, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(26, 6, 6, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(27, 7, 1, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(28, 7, 2, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(29, 7, 3, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(30, 7, 4, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(31, 7, 6, '09:00:00', '17:00:00', 1, '2026-05-13 07:06:11', '2026-05-13 07:06:11'),
(38, 2, 1, '13:00:00', '14:00:00', 1, '2026-05-13 07:56:51', '2026-05-13 07:56:51'),
(39, 2, 2, '14:00:00', '15:00:00', 1, '2026-05-13 07:56:51', '2026-05-13 07:56:51'),
(40, 2, 3, '15:00:00', '16:00:00', 1, '2026-05-13 07:56:51', '2026-05-13 07:56:51'),
(41, 2, 4, '16:00:00', '17:00:00', 1, '2026-05-13 07:56:51', '2026-05-13 07:56:51'),
(42, 2, 5, '09:00:00', '10:00:00', 1, '2026-05-13 07:56:51', '2026-05-13 07:56:51'),
(43, 2, 6, '10:00:00', '11:00:00', 1, '2026-05-13 07:56:51', '2026-05-13 07:56:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `teacher_availability_slots`
--

CREATE TABLE `teacher_availability_slots` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `session_date` date NOT NULL,
  `time_label` varchar(64) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `capacity` int(10) UNSIGNED DEFAULT NULL COMMENT 'Max seats for this session (NULL = use lesson.capacity)',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Whether this session is bookable',
  `seats_booked` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Cached count of booked seats',
  `start_time` time NOT NULL COMMENT 'Session start time',
  `end_time` time NOT NULL COMMENT 'Session end time',
  `status` enum('available','reserved','blocked','expired','cancelled') NOT NULL DEFAULT 'available' COMMENT 'Slot status: available, reserved, blocked, expired, cancelled',
  `location` varchar(255) DEFAULT NULL COMMENT 'Physical location or room for the session',
  `is_recurring` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Whether this slot is part of a recurring pattern',
  `recurrence_pattern` varchar(100) DEFAULT NULL COMMENT 'JSON or pattern string for recurring slots (e.g., weekly:monday)',
  `parent_slot_id` int(11) DEFAULT NULL COMMENT 'Reference to parent slot for recurring instances',
  `cancelled_at` datetime DEFAULT NULL COMMENT 'When the slot was cancelled',
  `cancellation_reason` text DEFAULT NULL COMMENT 'Reason for cancellation'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `teacher_availability_slots`
--

INSERT INTO `teacher_availability_slots` (`id`, `teacher_id`, `lesson_id`, `session_date`, `time_label`, `notes`, `created`, `modified`, `capacity`, `is_active`, `seats_booked`, `start_time`, `end_time`, `status`, `location`, `is_recurring`, `recurrence_pattern`, `parent_slot_id`, `cancelled_at`, `cancellation_reason`) VALUES
(7, 2, 4, '2026-05-14', '09:00 to 17:00', NULL, '2026-05-13 07:01:02', '2026-05-13 11:27:56', 3, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(8, 2, 4, '2026-05-15', '09:00 to 17:00', NULL, '2026-05-13 07:01:02', '2026-05-13 11:27:56', 2, 1, 2, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(9, 2, 4, '2026-05-16', '09:00 to 17:00', NULL, '2026-05-13 07:01:02', '2026-05-13 11:27:56', 5, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(10, 2, 4, '2026-05-18', '09:00 to 17:00', NULL, '2026-05-13 07:01:02', '2026-05-13 11:27:56', 5, 1, 4, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(11, 2, 4, '2026-05-19', '09:00 to 17:00', NULL, '2026-05-13 07:01:02', '2026-05-13 11:27:56', 6, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(12, 2, 4, '2026-05-20', '09:00 to 17:00', NULL, '2026-05-13 07:01:02', '2026-05-13 11:27:56', 3, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(13, 2, 4, '2026-05-21', '09:00 to 17:00', NULL, '2026-05-13 07:01:02', '2026-05-13 11:27:56', 2, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(14, 2, 4, '2026-05-22', '09:00 to 17:00', NULL, '2026-05-13 07:01:02', '2026-05-13 11:27:56', 5, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(15, 2, 4, '2026-05-23', '09:00 to 17:00', NULL, '2026-05-13 07:01:02', '2026-05-13 11:27:56', 5, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(16, 2, 4, '2026-05-25', '09:00 to 17:00', NULL, '2026-05-13 07:01:02', '2026-05-13 11:27:56', 3, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(17, 2, 5, '2026-05-14', '09:00 to 17:00', NULL, '2026-05-13 07:01:39', '2026-05-13 11:27:56', 4, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(18, 2, 5, '2026-05-15', '09:00 to 17:00', NULL, '2026-05-13 07:01:39', '2026-05-13 11:27:56', 3, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(19, 2, 5, '2026-05-16', '09:00 to 17:00', NULL, '2026-05-13 07:01:39', '2026-05-13 11:27:56', 4, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(20, 2, 5, '2026-05-18', '09:00 to 17:00', NULL, '2026-05-13 07:01:39', '2026-05-13 11:27:56', 5, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(21, 2, 5, '2026-05-27', '09:00 to 17:00', NULL, '2026-05-13 07:01:39', '2026-05-13 11:27:56', 1, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(22, 2, 5, '2026-05-28', '09:00 to 17:00', NULL, '2026-05-13 07:01:39', '2026-05-13 11:27:56', 3, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(23, 2, 5, '2026-05-29', '09:00 to 17:00', NULL, '2026-05-13 07:01:39', '2026-05-13 11:27:56', 2, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(24, 2, 9, '2026-05-19', '09:00 to 17:00', NULL, '2026-05-13 07:02:10', '2026-05-13 11:27:56', 6, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(25, 2, 9, '2026-05-20', '09:00 to 17:00', NULL, '2026-05-13 07:02:10', '2026-05-13 11:27:56', 6, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(26, 2, 9, '2026-05-29', '09:00 to 17:00', NULL, '2026-05-13 07:02:10', '2026-05-13 11:27:56', 6, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(27, 2, 9, '2026-05-30', '09:00 to 17:00', NULL, '2026-05-13 07:02:10', '2026-05-13 11:27:56', 3, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(28, 2, 9, '2026-06-01', '09:00 to 17:00', NULL, '2026-05-13 07:02:10', '2026-05-13 11:27:56', 12, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(29, 2, 9, '2026-06-02', '09:00 to 17:00', NULL, '2026-05-13 07:02:10', '2026-05-13 11:27:56', 5, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(30, 2, 9, '2026-06-03', '09:00 to 17:00', NULL, '2026-05-13 07:02:10', '2026-05-13 11:27:56', 5, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(31, 5, 3, '2026-05-20', '09:00 to 17:00', NULL, '2026-05-13 07:06:42', '2026-05-13 11:27:56', 5, 1, 2, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(32, 5, 3, '2026-05-21', '09:00 to 17:00', NULL, '2026-05-13 07:06:42', '2026-05-13 11:27:56', 5, 1, 1, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(33, 5, 3, '2026-05-22', '09:00 to 17:00', NULL, '2026-05-13 07:06:42', '2026-05-13 11:27:56', 5, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(34, 3, 6, '2026-05-15', '09:00 to 17:00', NULL, '2026-05-13 07:07:07', '2026-05-13 11:27:56', 3, 1, 1, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(35, 3, 6, '2026-05-16', '09:00 to 17:00', NULL, '2026-05-13 07:07:07', '2026-05-13 11:27:56', 3, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(36, 3, 6, '2026-05-22', '09:00 to 17:00', NULL, '2026-05-13 07:07:07', '2026-05-13 11:27:56', 3, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(37, 3, 6, '2026-05-23', '09:00 to 17:00', NULL, '2026-05-13 07:07:07', '2026-05-13 11:27:56', 3, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(38, 3, 6, '2026-05-29', '09:00 to 17:00', NULL, '2026-05-13 07:07:07', '2026-05-13 11:27:56', 3, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(39, 3, 6, '2026-05-30', '09:00 to 17:00', NULL, '2026-05-13 07:07:07', '2026-05-13 11:27:56', 3, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(40, 4, 7, '2026-05-18', '09:00 to 17:00', NULL, '2026-05-13 07:07:25', '2026-05-13 11:27:56', 4, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(41, 4, 7, '2026-05-19', '09:00 to 17:00', NULL, '2026-05-13 07:07:25', '2026-05-13 11:27:56', 4, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(42, 4, 7, '2026-05-20', '09:00 to 17:00', NULL, '2026-05-13 07:07:25', '2026-05-13 11:27:56', 3, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(43, 4, 7, '2026-05-22', '09:00 to 17:00', NULL, '2026-05-13 07:07:25', '2026-05-13 11:27:56', 3, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(44, 4, 7, '2026-05-23', '09:00 to 17:00', NULL, '2026-05-13 07:07:25', '2026-05-13 11:27:56', 3, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(45, 6, 8, '2026-05-20', '09:00 to 17:00', NULL, '2026-05-13 07:08:24', '2026-05-13 11:27:56', 4, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(46, 6, 8, '2026-05-21', '09:00 to 17:00', NULL, '2026-05-13 07:08:24', '2026-05-13 11:27:56', 4, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(47, 6, 8, '2026-05-22', '09:00 to 17:00', NULL, '2026-05-13 07:08:24', '2026-05-13 11:27:56', 4, 1, 4, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL),
(48, 6, 8, '2026-05-23', '09:00 to 17:00', NULL, '2026-05-13 07:08:24', '2026-05-13 11:27:56', 4, 1, 0, '09:00:00', '17:00:00', 'available', NULL, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'customer',
  `nonce` varchar(255) DEFAULT NULL,
  `nonce_expiry` datetime DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `failed_login_attempts` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Number of failed login attempts',
  `last_failed_login` datetime DEFAULT NULL COMMENT 'Timestamp of the last failed login'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `nonce`, `nonce_expiry`, `created`, `modified`, `failed_login_attempts`, `last_failed_login`) VALUES
(1, 'admin@gmail.com', '$2y$10$9KnSyyf/cg7Etvfr4ZvEEexU5KS2TxAzIWVAdup15CufUCECZVkBq', 'admin', 'baddd1fe-7d27-4426-a82a-2302065742fa', '2026-05-04 02:40:23', '2026-04-22 22:54:54', '2026-05-04 01:40:23', 0, NULL),
(2, 'Leo@gmail.com', '$2y$10$mTOphBOOjXDvOS7gZ98ioeK5lk22QBwTDYyJIGuX8DCFijI.RGR7u', 'customer', NULL, NULL, '2026-04-22 23:11:26', '2026-04-22 23:14:10', 0, NULL),
(3, 'zhang@gmail.com', '$2y$10$ifGfsdq3LZROMU.U8xN8xOFPgMW0e27RjO8f8LA/TiLgvJFY9MdcS', 'customer', NULL, NULL, '2026-04-22 23:32:23', '2026-04-22 23:40:48', 1, '2026-04-22 23:40:48'),
(4, 'monisha.biswas@monash.edu', '$2y$10$L19QL3fwpyVYEdalMo4.HuQ3h8knLAyFg3F4dNcg3li1WTL1IwR9.', 'customer', NULL, NULL, '2026-04-23 00:04:16', '2026-04-23 00:04:16', 0, NULL),
(5, 'hunghoang@gmail.com', '$2y$10$q/QayZBv78MM5KfrIeviJO7EDJbV67RvYI9AkqjH9p47m8jmBIzmq', 'customer', NULL, NULL, '2026-04-26 02:56:06', '2026-04-30 01:29:31', 0, NULL),
(6, 'emma.wilson@candlecraft.com', '$2y$12$/eTcTF5CkCngf4zj8ZcCt.4SxWCgtHzkM2vye6hEysfVf1BmImHRa', 'teacher', NULL, NULL, '2026-04-29 19:33:59', '2026-05-13 10:41:23', 0, NULL),
(7, 'rain@monash.edu', '$2y$10$IADljVIf006KtkmJeiUGIOxkhJYF4OHZGD93T5Ia1yNvMc2Z8gzei', 'customer', NULL, NULL, '2026-04-30 01:46:15', '2026-04-30 01:46:15', 0, NULL),
(8, 'Alex@gamil.com', '$2y$10$KZCW19Yn/C2FrY2rxBZdTu/3OH5GyLeyKVKEabH1gc9PHtqcX0fny', 'customer', NULL, NULL, '2026-05-05 03:48:23', '2026-05-05 03:48:23', 0, NULL),
(9, 'tester@example.com', '$2y$12$GySISh5L2FBnBMN5WyP0N.accYWVpGk1dchG1Vyn26xMMN/Q20K0.', 'customer', NULL, NULL, '2026-05-11 00:58:58', '2026-05-11 00:58:58', 0, NULL),
(10, 'lhoa0020@student.monash.edu', '$2y$10$JCaqr6.uh8tH0eHzFhOjyOaqilUDqtb3z/ziROx/KAjBVEr0OKZ76', 'customer', NULL, NULL, '2026-05-13 07:50:50', '2026-05-13 07:50:50', 0, NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`,`sent_at`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Chỉ mục cho bảng `attendance_records`
--
ALTER TABLE `attendance_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slot_id` (`slot_id`,`student_id`),
  ADD KEY `teacher_id` (`teacher_id`,`marked_at`),
  ADD KEY `slot_id_2` (`slot_id`,`status`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `marked_by` (`marked_by`);

--
-- Chỉ mục cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `lesson_id` (`lesson_id`),
  ADD KEY `checkout_group` (`checkout_group`),
  ADD KEY `slot_id` (`slot_id`);

--
-- Chỉ mục cho bảng `company_infos`
--
ALTER TABLE `company_infos`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `enquiries`
--
ALTER TABLE `enquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Chỉ mục cho bảng `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Chỉ mục cho bảng `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `stripe_checkout_session_id` (`stripe_checkout_session_id`),
  ADD KEY `stripe_payment_intent_id` (`stripe_payment_intent_id`),
  ADD KEY `checkout_group` (`checkout_group`),
  ADD KEY `stripe_session_id` (`stripe_session_id`);

--
-- Chỉ mục cho bảng `phinxlog`
--
ALTER TABLE `phinxlog`
  ADD PRIMARY KEY (`version`);

--
-- Chỉ mục cho bảng `slot_status_history`
--
ALTER TABLE `slot_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slot_id` (`slot_id`,`created_at`),
  ADD KEY `changed_by` (`changed_by`);

--
-- Chỉ mục cho bảng `student_attendance_summary`
--
ALTER TABLE `student_attendance_summary`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_student_teacher` (`student_id`,`teacher_id`),
  ADD KEY `idx_student` (`student_id`),
  ADD KEY `idx_teacher` (`teacher_id`);

--
-- Chỉ mục cho bảng `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `teacher_availability`
--
ALTER TABLE `teacher_availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`,`day_of_week`);

--
-- Chỉ mục cho bảng `teacher_availability_slots`
--
ALTER TABLE `teacher_availability_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`,`session_date`),
  ADD KEY `teacher_id_2` (`teacher_id`,`session_date`,`status`),
  ADD KEY `lesson_id` (`lesson_id`,`status`),
  ADD KEY `status` (`status`,`session_date`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `attendance_records`
--
ALTER TABLE `attendance_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT cho bảng `company_infos`
--
ALTER TABLE `company_infos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `enquiries`
--
ALTER TABLE `enquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `materials`
--
ALTER TABLE `materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT cho bảng `slot_status_history`
--
ALTER TABLE `slot_status_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `student_attendance_summary`
--
ALTER TABLE `student_attendance_summary`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `teacher_availability`
--
ALTER TABLE `teacher_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT cho bảng `teacher_availability_slots`
--
ALTER TABLE `teacher_availability_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ràng buộc đối với các bảng kết xuất
--

--
-- Ràng buộc cho bảng `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `announcements_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Ràng buộc cho bảng `attendance_records`
--
ALTER TABLE `attendance_records`
  ADD CONSTRAINT `attendance_records_ibfk_1` FOREIGN KEY (`slot_id`) REFERENCES `teacher_availability_slots` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `attendance_records_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `attendance_records_ibfk_3` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `attendance_records_ibfk_4` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `attendance_records_ibfk_5` FOREIGN KEY (`marked_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ràng buộc cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`slot_id`) REFERENCES `teacher_availability_slots` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ràng buộc cho bảng `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ràng buộc cho bảng `enquiries`
--
ALTER TABLE `enquiries`
  ADD CONSTRAINT `enquiries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `materials`
--
ALTER TABLE `materials`
  ADD CONSTRAINT `materials_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `slot_status_history`
--
ALTER TABLE `slot_status_history`
  ADD CONSTRAINT `slot_status_history_ibfk_1` FOREIGN KEY (`slot_id`) REFERENCES `teacher_availability_slots` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `slot_status_history_ibfk_2` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ràng buộc cho bảng `teacher_availability`
--
ALTER TABLE `teacher_availability`
  ADD CONSTRAINT `teacher_availability_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ràng buộc cho bảng `teacher_availability_slots`
--
ALTER TABLE `teacher_availability_slots`
  ADD CONSTRAINT `teacher_availability_slots_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `teacher_availability_slots_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
