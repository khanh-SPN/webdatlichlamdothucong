<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

/**
 * Thêm sản phẩm "Vé nhiều lớp" dưới dạng hàng Lesson để thanh toán có thể
 * hỗ trợ mua gói mà không làm hỏng khóa ngoại hiện có.
 */
class SeedMultiClassPassLesson extends AbstractMigration
{
    public function up(): void
    {
        // Pick an existing teacher (lowest id) to satisfy FK.
        $t = $this->fetchRow("SELECT id FROM teachers ORDER BY id ASC LIMIT 1");
        if ($t === false || !isset($t['id'])) {
            return;
        }
        $teacherId = (int)$t['id'];

        $existing = $this->fetchRow("SELECT id FROM lessons WHERE lesson_type = 'Pass' OR lesson_name LIKE '%pass%' LIMIT 1");
        if ($existing !== false) {
            return;
        }

        $this->execute(
            "INSERT INTO lessons (lesson_name, lesson_type, description, price, teacher_id, created, modified) VALUES ("
            . "'Vé nhiều lớp (3 lớp)', "
            . "'Pass', "
            . "'Một gói linh hoạt cho ba lớp. Mua ngay, sau đó đặt ngày của bạn qua làm nến, gốm, và đan len qua nhiều lần thăm.', "
            . "135.00, "
            . $teacherId . ", "
            . "NOW(), NOW()"
            . ")"
        );
    }

    public function down(): void
    {
        $this->execute("DELETE FROM lessons WHERE lesson_type = 'Pass' OR lesson_name LIKE '%Multi class pass%'");
    }
}

