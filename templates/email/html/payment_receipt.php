<?php
/**
 * Payment receipt email template
 * @var $userName
 * @var $bookingDetails
 * @var $totalPrice
 * @var $discountAmount
 * @var $discountPercent
 * @var $finalPrice
 * @var $paymentMethod
 * @var $paymentDate
 * @var $receiptId
 */
?>

<h2 style="color: #752f3f; font-family: Georgia, serif; font-size: 24px; margin: 0 0 10px 0;">
    Thanh toán đã được xác nhận – Cảm ơn bạn!
</h2>

<p style="color: #888; font-size: 13px; margin: 0 0 20px 0;">
    Mã biên lai: <strong><?= h($receiptId) ?></strong>
</p>

<p style="color: #555; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">
    Dear <?= h($userName) ?>,
</p>

<p style="color: #555; font-size: 16px; line-height: 1.6; margin: 0 0 15px 0;">
    <strong>Cảm ơn bạn đã chọn Hội Nghệ Thuật Nến!</strong> Chúng tôi rất vui mừng xác nhận rằng thanh toán của bạn đã được xử lý thành công. Đặt chỗ của bạn hiện đã được xác nhận và chúng tôi mong chờ được chào đón bạn tại các hội thảo làm nến của chúng tôi.
</p>

<div style="background: #e8f5e9; border-left: 4px solid #4caf50; padding: 15px; margin: 20px 0;">
    <p style="color: #2e7d32; font-size: 15px; margin: 0;">
        <strong>✓ Thanh toán thành công!</strong> Đặt chỗ của bạn đã được xác nhận. Chúng tôi đã giữ chỗ cho bạn – hẹn gặp lại bạn sớm!
    </p>
</div>

<h3 style="color: #333; font-size: 18px; margin: 25px 0 15px 0;">
    Hội thảo đã Đặt
</h3>

<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
    <thead>
        <tr style="background: #f5f5f5; border-bottom: 2px solid #ddd;">
            <th style="padding: 12px; text-align: left; color: #666; font-weight: bold;">Hội thảo</th>
            <th style="padding: 12px; text-align: center; color: #666; font-weight: bold;">Ngày</th>
            <th style="padding: 12px; text-align: center; color: #666; font-weight: bold;">Số lượng</th>
            <th style="padding: 12px; text-align: right; color: #666; font-weight: bold;">Giá</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($bookingDetails as $detail): ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 12px; color: #333;">
                    <strong><?= h($detail['workshop_name'] ?? 'Workshop') ?></strong>
                </td>
                <td style="padding: 12px; text-align: center; color: #666;">
                    <?= isset($detail['booking_date']) ? h($detail['booking_date']) : '—' ?>
                </td>
                <td style="padding: 12px; text-align: center; color: #666;">
                    <?= (int)($detail['quantity'] ?? 1) ?>
                </td>
                <td style="padding: 12px; text-align: right; color: #752f3f; font-weight: bold;">
                    $<?= number_format((float)($detail['price'] ?? 0), 2) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div style="background: #f9f7f4; padding: 15px; margin-bottom: 20px; border-radius: 8px;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 8px 0; color: #666; font-size: 15px;">Tổng phụ:</td>
            <td style="padding: 8px 0; color: #666; font-size: 15px; text-align: right;">
                $<?= number_format($totalPrice, 2) ?>
            </td>
        </tr>
        <?php if ($discountAmount > 0): ?>
            <tr>
                <td style="padding: 8px 0; color: #27AE60; font-size: 15px;">
                    <strong>Giảm giá (<?= $discountPercent ?>%):</strong>
                </td>
                <td style="padding: 8px 0; color: #27AE60; font-size: 15px; text-align: right;">
                    <strong>-$<?= number_format($discountAmount, 2) ?></strong>
                </td>
            </tr>
        <?php endif; ?>
        <tr style="border-top: 2px solid #ddd;">
            <td style="padding: 12px 0; color: #752f3f; font-size: 16px; font-weight: bold;">Tổng đã thanh toán:</td>
            <td style="padding: 12px 0; color: #752f3f; font-size: 16px; font-weight: bold; text-align: right;">
                $<?= number_format($finalPrice, 2) ?>
            </td>
        </tr>
    </table>
</div>

<h3 style="color: #333; font-size: 16px; margin: 20px 0 15px 0;">
    Chi tiết Thanh toán
</h3>

<p style="color: #555; font-size: 15px; line-height: 1.6; margin: 0 0 8px 0;">
    <strong>Phương thức thanh toán:</strong> <?= h($paymentMethod) ?>
</p>
<p style="color: #555; font-size: 15px; line-height: 1.6; margin: 0;">
    <strong>Ngày:</strong> <?= h($paymentDate) ?>
</p>

<p style="color: #555; font-size: 15px; line-height: 1.6; margin: 25px 0 15px 0;">
    <strong>Tiếp theo là gì?</strong><br>
    • Vui lòng đến trước 10 phút khi hội thảo của bạn bắt đầu<br>
    • Mang theo biên lai của bạn (dạng kỹ thuật số hoặc in)<br>
    • Tất cả vật liệu sẽ được cung cấp<br>
    • Mặc quần áo thoải mái mà bạn không ngại bị dính sáp
</p>

<p style="color: #555; font-size: 15px; line-height: 1.6; margin: 20px 0 15px 0;">
    <strong>Cần thay đổi?</strong> Nếu bạn cần đổi lịch hoặc có bất kỳ câu hỏi nào, chỉ cần trả lời email này hoặc liên hệ với chúng tôi. Chúng tôi rất vui được giúp đỡ!
</p>

<div style="background: #f0f9ff; border-left: 4px solid #1976d2; padding: 15px; margin: 20px 0;">
    <p style="color: #1976d2; font-size: 14px; margin: 0;">
        <strong>✓ Đặt chỗ đã được xác nhận!</strong> Chúng tôi không thể chờ đợi để tạo ra những chiếc nến đẹp cùng với bạn!
    </p>
</div>

<p style="color: #888; font-size: 13px; line-height: 1.6; margin: 25px 0 0 0; border-top: 1px solid #ddd; padding-top: 15px;">
    Với trân trọng,<br>
    <strong>Đội ngũ Hội Nghệ Thuật Nến</strong><br>
    <em>Tạo ra những trải nghiệm đáng nhớ, từng chiếc nến một.</em>
</p>
