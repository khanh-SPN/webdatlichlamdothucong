<?php
/**
 * Teacher announcement email template
 * @var $teacherName
 * @var $workshopName
 * @var $subject
 * @var $message
 * @var $sentAt
 */
?>

<h2 style="color: #752f3f; font-family: Georgia, serif; font-size: 24px; margin: 0 0 10px 0;">
    Thông báo từ <?= h($teacherName) ?>
</h2>

<p style="color: #666; font-size: 14px; margin: 0 0 20px 0;">
    <strong>Hội thảo:</strong> <?= h($workshopName) ?> • <strong>Đã gửi:</strong> <?= h($sentAt) ?>
</p>

<h3 style="color: #333; font-size: 18px; margin: 20px 0 15px 0;">
    <?= h($subject) ?>
</h3>

<div style="background: #f9f7f4; padding: 15px; margin: 20px 0; border-left: 4px solid #752f3f; border-radius: 4px;">
    <p style="color: #555; font-size: 15px; line-height: 1.8; margin: 0; white-space: pre-wrap;">
        <?= nl2br(h($message)) ?>
    </p>
</div>

<p style="color: #888; font-size: 13px; line-height: 1.6; margin: 20px 0 0 0; border-top: 1px solid #ddd; padding-top: 15px;">
    Đây là thông báo từ giảng viên của bạn. Nếu bạn có câu hỏi, vui lòng trả lời email này hoặc liên hệ với chúng tôi.<br>
    <strong>Hội Nghệ Thuật Nến</strong>
</p>
