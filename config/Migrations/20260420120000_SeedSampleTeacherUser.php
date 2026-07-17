<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

/**
 * Đảm bảo tài khoản giáo viên mẫu tồn tại để /pages/login hoạt động sau khi migrate.
 * Email: emma.wilson@hoinghethuatnen.com  Mật khẩu: TeacherDemo123
 */
class SeedSampleTeacherUser extends AbstractMigration
{
    public function up(): void
    {
        // fetchRow() returns false when no row exists (not null).
        $row = $this->fetchRow(
            "SELECT id FROM users WHERE email = 'emma.wilson@hoinghethuatnen.com' LIMIT 1",
        );
        if ($row !== false) {
            return;
        }

        $this->execute(
            "INSERT INTO users (email, password, role, failed_login_attempts, created, modified) VALUES ("
            . "'emma.wilson@hoinghethuatnen.com', "
            . "'\$2y\$12\$/eTcTF5CkCngf4zj8ZcCt.4SxWCgtHzkM2vye6hEysfVf1BmImHRa', "
            . "'teacher', 0, NOW(), NOW())",
        );
    }

    public function down(): void
    {
        $this->execute(
            "DELETE FROM users WHERE email = 'emma.wilson@hoinghethuatnen.com' AND role = 'teacher'",
        );
    }
}
