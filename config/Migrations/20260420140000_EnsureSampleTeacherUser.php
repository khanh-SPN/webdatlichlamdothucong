<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

/**
 * Fixes SeedSampleTeacherUser which skipped INSERT because fetchRow() returns false
 * (not null) when no row exists — the demo teacher was never created.
 *
 * Email: emma.wilson@candlecraft.com  Password: TeacherDemo123
 */
class EnsureSampleTeacherUser extends AbstractMigration
{
    public function up(): void
    {
        $row = $this->fetchRow(
            "SELECT id FROM users WHERE email = 'emma.wilson@candlecraft.com' LIMIT 1",
        );
        if ($row !== false) {
            return;
        }

        $this->execute(
            "INSERT INTO users (email, password, role, failed_login_attempts, created, modified) VALUES ("
            . "'emma.wilson@candlecraft.com', "
            . "'\$2y\$12\$/eTcTF5CkCngf4zj8ZcCt.4SxWCgtHzkM2vye6hEysfVf1BmImHRa', "
            . "'teacher', 0, NOW(), NOW())",
        );
    }

    public function down(): void
    {
        $this->execute(
            "DELETE FROM users WHERE email = 'emma.wilson@candlecraft.com' AND role = 'teacher'",
        );
    }
}
