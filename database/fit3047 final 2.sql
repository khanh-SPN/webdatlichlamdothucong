-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 25, 2026 lúc 06:48 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `fit3047`
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
  `modified` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `lesson_id`, `booking_date`, `status`, `created`, `modified`) VALUES
(2, 1, 3, '2026-04-02', 'confirmed', '2026-04-01 12:47:51', '2026-04-01 13:11:20'),
(3, 1, 5, '2026-04-03', 'pending', '2026-04-01 13:05:33', '2026-04-01 13:05:33'),
(4, 8, 4, '2026-04-23', 'pending', '2026-04-23 04:09:53', '2026-04-23 04:09:53');

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
(1, 'CandleCraft Academy', 'CandleCraftAcademy@gmail.com', '+61 412 678 990', 'Studio 3, 27 Innovation Walk Clayton VIC 3800 Australia', 'CandleCraft Academy is a creative workshop space offering hands-on experiences in candle making, pottery, knitting, and other crafts. We aim to inspire creativity and relaxation through guided sessions led by experienced instructors.', '2026-03-23 08:56:13', '2026-04-25 16:46:10');

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
(3, 4, '', '', '', '2026-03-23 09:44:17', '2026-03-23 09:44:17'),
(4, 5, '', '', '', '2026-03-23 10:18:26', '2026-03-23 10:18:26'),
(5, 6, '', '', '', '2026-03-23 13:45:36', '2026-03-23 13:45:36'),
(6, 7, 'ngu da cha', '0999881111', '2\r\nappt', '2026-04-22 08:42:11', '2026-04-22 08:42:11'),
(7, 8, 'ngu da cha', '0321321321', '2\r\nappt', '2026-04-23 04:09:36', '2026-04-23 04:09:36');

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
(2, 'a', 'a', 'a@gmail.com', '0999881111', 'a', 'a', 'pending', NULL, '2026-03-25 11:31:28', '2026-03-25 11:31:28');

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
(8, 'Do I need prior experience?', 'No prior experience is required. All workshops are beginner-friendly.', 'general', 7, '2026-04-01 19:20:27', '2026-04-01 19:20:27'),
(9, 'What should I bring to the workshop?', 'All materials are provided. Just bring yourself and your creativity!', 'general', 8, '2026-04-01 19:20:27', '2026-04-01 19:20:27'),
(10, 'Are your workshops suitable for children?', 'Yes, we offer sessions suitable for children aged 8 and above.', 'general', 9, '2026-04-01 19:20:27', '2026-04-01 19:20:27'),
(11, 'What is your refund policy?', 'Refunds are available for cancellations made at least 24 hours in advance.', 'policy', 10, '2026-04-01 19:20:27', '2026-04-01 19:20:27'),
(12, 'Do you offer group discounts?', 'Yes, we provide special pricing for group bookings. Please contact us for details.', 'policy', 11, '2026-04-01 19:20:27', '2026-04-01 19:20:27'),
(13, 'Can I book a private workshop?', 'Yes, private sessions are available upon request for individuals or groups.', 'policy', 12, '2026-04-01 19:20:27', '2026-04-01 19:20:27'),
(14, 'Are workshops suitable for children?', 'Our workshops are generally suitable for participants aged 12 and above. Some sessions may involve hot materials or tools, so we recommend checking the workshop description before booking.', 'General', 1, '2026-04-25 16:40:58', '2026-04-25 16:40:58'),
(15, 'Is there a minimum age requirement?', 'The recommended minimum age for most workshops is 12 years old. This ensures participants can safely follow instructions and handle materials.', 'General', 5, '2026-04-25 16:41:19', '2026-04-25 16:41:19'),
(16, 'Do children need to be accompanied by an adult?', 'Yes, children under 16 must be accompanied by an adult during the workshop to ensure safety and support.', 'General', 3, '2026-04-25 16:41:36', '2026-04-25 16:41:36');

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
(5, 'Ceramic Design Masterclass', 'Masterclass', 'Advanced ceramic crafting techniques.', 80.00, 2, '2026-04-01 19:22:31', '2026-04-01 19:22:31', NULL),
(6, 'Knitting Basics', 'Workshop', 'Learn knitting techniques for beginners.', 40.00, 3, '2026-04-01 19:22:31', '2026-04-01 19:22:31', NULL),
(7, 'Creative DIY Crafts', 'Workshop', 'Explore multiple crafting techniques.', 55.00, 4, '2026-04-01 19:22:31', '2026-04-01 19:22:31', NULL),
(8, 'Clay Sculpture Workshop', 'Workshop', 'Create sculptures using clay.', 60.00, 6, '2026-04-01 19:22:31', '2026-04-01 19:22:31', NULL);

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
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `payments`
--

INSERT INTO `payments` (`id`, `booking_id`, `payment_method`, `payment_status`, `created`, `modified`) VALUES
(2, 2, 'bank', 'paid', '2026-04-01 12:47:51', '2026-04-01 13:11:20'),
(3, 3, 'stripe', 'unpaid', '2026-04-01 13:05:33', '2026-04-01 13:05:33'),
(4, 4, 'stripe', 'unpaid', '2026-04-23 04:09:53', '2026-04-23 04:09:53');

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
(20260402120000, 'CreateTeacherAvailabilitySlots', '2026-04-09 10:15:30', '2026-04-09 10:15:30', 0),
(20260420120000, 'SeedSampleTeacherUser', '2026-04-25 16:31:26', '2026-04-25 16:31:26', 0),
(20260420140000, 'EnsureSampleTeacherUser', '2026-04-25 16:31:26', '2026-04-25 16:31:26', 0),
(20260424120000, 'TeacherPortalExpansion', '2026-04-25 16:31:26', '2026-04-25 16:31:26', 0);

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
(2, 'Emma Wilson', 'emma.wilson@candlecraft.com', '+61 401 234 567', 'Candle Making Specialist', '2026-04-01 19:22:16', '2026-04-01 19:22:16', NULL, NULL),
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `modified` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `teacher_availability_slots`
--

INSERT INTO `teacher_availability_slots` (`id`, `teacher_id`, `lesson_id`, `session_date`, `time_label`, `notes`, `created`, `modified`) VALUES
(1, 2, 4, '2026-04-05', '10:00 to 13:00', 'Pottery for Beginners', '2026-04-09 10:15:30', '2026-04-09 10:15:30'),
(2, 2, 5, '2026-04-12', '14:00 to 17:00', 'Ceramic Design Masterclass', '2026-04-09 10:15:30', '2026-04-09 10:15:30'),
(3, 3, 6, '2026-04-06', '09:30 to 12:00', NULL, '2026-04-09 10:15:30', '2026-04-09 10:15:30'),
(4, 5, 3, '2026-04-08', '11:00 to 14:00', NULL, '2026-04-09 10:15:30', '2026-04-09 10:15:30'),
(5, 4, 7, '2026-04-10', '10:00 to 13:30', NULL, '2026-04-09 10:15:30', '2026-04-09 10:15:30'),
(6, 6, 8, '2026-04-15', '13:00 to 16:00', NULL, '2026-04-09 10:15:30', '2026-04-09 10:15:30');

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
(1, 'admin@gmail.com', '$2y$10$ElhgNxHPxIdXG9wq/AsXAuE94cs1DffWZ4QseMJLe/ATc1gdoQRC6', 'admin', NULL, NULL, '2026-03-23 06:06:09', '2026-03-23 13:06:19', 0, NULL),
(2, 'a@gmail.com', '$2y$10$Qur0XKJYwx4HuPtIahBrVeEsQCSIpfXpovkfoEpSqSS/ISgtaTjGS', 'customer', NULL, NULL, '2026-03-23 08:16:49', '2026-04-23 04:08:26', 2, '2026-04-23 04:08:26'),
(3, 'a1@gmail.com', '$2y$10$k8bAFqWKEemWWQ1trLS5RO8rlH7M4Qp4EM5rHzcY92LCp3ru4DGJC', 'customer', NULL, NULL, '2026-03-23 09:43:00', '2026-04-23 04:09:04', 2, '2026-04-23 04:09:04'),
(4, 'a2@gmail.com', '$2y$10$zPOFmEqBB/GPRcJS7JficuYKaq6K2G0CKWZgtX1JJ5ruqhMy7v7iy', 'customer', NULL, NULL, '2026-03-23 09:44:17', '2026-03-23 09:44:17', 0, NULL),
(5, 'a3@gmail.com', '$2y$10$UKrAJnfly9AKO40/0jV2zOZGEF4bQAF6.w/TsEo07rM0XLYlF5Kq.', 'customer', NULL, NULL, '2026-03-23 10:18:26', '2026-03-23 10:18:26', 0, NULL),
(6, 'admin1@gmail.com', '$2y$10$2zdtROZaXRjuzA37V5vqset0cLDi06Nj5WLDZDJkEGAInNN9lOcx2', 'admin', NULL, NULL, '2026-03-23 13:45:36', '2026-03-23 20:45:52', 0, NULL),
(7, 'a4@gmail.com', '$2y$10$4S0XFjCyrzcs9ScDaKBXlOloiZv5QVDxGXWun3Jnhqx19yFrMn8r.', 'customer', NULL, NULL, '2026-04-22 08:42:11', '2026-04-22 08:42:11', 0, NULL),
(8, 'a5@gmail.com', '$2y$10$XCNETBoo4qZLJJFycRSSqu8tK.ejFcqR4nqStB..kHGx.cMr1aA5K', 'customer', NULL, NULL, '2026-04-23 04:09:36', '2026-04-23 04:09:36', 0, NULL),
(9, 'emma.wilson@candlecraft.com', '$2y$12$/eTcTF5CkCngf4zj8ZcCt.4SxWCgtHzkM2vye6hEysfVf1BmImHRa', 'teacher', NULL, NULL, '2026-04-25 16:31:26', '2026-04-25 16:31:26', 0, NULL);

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
-- Chỉ mục cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `lesson_id` (`lesson_id`);

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
  ADD KEY `booking_id` (`booking_id`);

--
-- Chỉ mục cho bảng `phinxlog`
--
ALTER TABLE `phinxlog`
  ADD PRIMARY KEY (`version`);

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
  ADD KEY `lesson_id` (`lesson_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `company_infos`
--
ALTER TABLE `company_infos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `enquiries`
--
ALTER TABLE `enquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `materials`
--
ALTER TABLE `materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `teacher_availability`
--
ALTER TABLE `teacher_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `teacher_availability_slots`
--
ALTER TABLE `teacher_availability_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `announcements_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `enquiries`
--
ALTER TABLE `enquiries`
  ADD CONSTRAINT `enquiries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `materials`
--
ALTER TABLE `materials`
  ADD CONSTRAINT `materials_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `teacher_availability`
--
ALTER TABLE `teacher_availability`
  ADD CONSTRAINT `teacher_availability_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `teacher_availability_slots`
--
ALTER TABLE `teacher_availability_slots`
  ADD CONSTRAINT `teacher_availability_slots_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `teacher_availability_slots_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
