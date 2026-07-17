<?php
/**
 * Contact form confirmation email template
 * @var $fullName
 * @var $subject
 * @var $message
 * @var $receivedAt
 */
?>

<h2 style="color: #752f3f; font-family: Georgia, serif; font-size: 24px; margin: 0 0 20px 0;">
    Chúng tôi đã nhận được tin nhắn của bạn
</h2>

<p style="color: #555; font-size: 16px; line-height: 1.6; margin: 0 0 15px 0;">
    Hi <strong><?= h($fullName) ?></strong>,
</p>

<p style="color: #555; font-size: 16px; line-height: 1.6; margin: 0 0 15px 0;">
    Cảm ơn bạn đã liên hệ với Hội Nghệ Thuật Nến! Chúng tôi đã nhận được tin nhắn của bạn và sẽ xem xét kỹ lưỡng.
</p>

<div style="background: #f9f7f4; border-left: 4px solid #752f3f; padding: 15px; margin: 20px 0;">
    <p style="color: #666; font-size: 14px; margin: 0 0 10px 0;">
        <strong>Chủ đề Tin nhắn:</strong> <?= h($subject) ?>
    </p>
    <p style="color: #666; font-size: 14px; margin: 0;">
        <strong>Đã nhận lúc:</strong> <?= h($receivedAt) ?>
    </p>
</div>

<p style="color: #555; font-size: 16px; line-height: 1.6; margin: 0 0 15px 0;">
    Chúng tôi thường phản hồi các yêu cầu trong vòng <strong>5 ngày làm việc</strong>. Vui lòng kiểm tra email của bạn (bao gồm thư mục spam) để nhận phản hồi của chúng tôi.
</p>

<p style="color: #555; font-size: 16px; line-height: 1.6; margin: 0 0 15px 0;">
    Nếu vấn đề của bạn khẩn cấp, vui lòng gọi cho chúng tôi trực tiếp hoặc trả lời email này.
</p>

<p style="color: #888; font-size: 14px; line-height: 1.6; margin: 20px 0 0 0; border-top: 1px solid #ddd; padding-top: 15px;">
    Trân trọng,<br>
    <strong>Đội ngũ Hội Nghệ Thuật Nến</strong>
</p>
