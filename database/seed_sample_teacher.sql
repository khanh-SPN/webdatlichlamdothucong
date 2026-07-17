-- Đăng nhập giáo viên mẫu (role = teacher). Bảng điều khiển: http://localhost:8765/teacher
--
-- Ưu tiên: `bin/cake migrations migrate` — migration `20260420120000_SeedSampleTeacherUser`
-- chèn người dùng này nếu thiếu.
--
-- Hoặc nhập thủ công sau lược đồ chính của bạn (ví dụ: database/fit3047 final 2.sql).
--
-- Thông tin đăng nhập:
--   Email:    emma.wilson@hoinghethuatnen.com
--   Mật khẩu: TeacherDemo123
--
-- Email này khớp với hàng "Emma Wilson" hiện có trong `teachers` (id 2 trong bản sao đi kèm)
-- để trung tâm giảng viên có thể hiển thị các hội thảo và đặt chỗ liên quan của cô ấy.

INSERT INTO `users` (`email`, `password`, `role`, `nonce`, `nonce_expiry`, `created`, `modified`, `failed_login_attempts`, `last_failed_login`)
VALUES (
  'emma.wilson@hoinghethuatnen.com',
  '$2y$12$/eTcTF5CkCngf4zj8ZcCt.4SxWCgtHzkM2vye6hEysfVf1BmImHRa',
  'teacher',
  NULL,
  NULL,
  NOW(),
  NOW(),
  0,
  NULL
);
