<?php
/**
 * Booking confirmation email template (for pending bookings)
 * @var $userName
 * @var $bookingDetails
 * @var $createdAt
 * @var $checkoutUrl Optional payment link
 * @var $totalPrice
 */
?>

<h2 style="color: #752f3f; font-family: Georgia, serif; font-size: 24px; margin: 0 0 10px 0;">
    Cảm ơn bạn đã đặt chỗ!
</h2>

<p style="color: #555; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">
    Dear <?= h($userName) ?>,
</p>

<p style="color: #555; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">
    Cảm ơn bạn đã chọn <strong>Hội Nghệ Thuật Nến</strong>! Chúng tôi rất vui mừng xác nhận rằng đặt chỗ của bạn đã được nhận thành công. Các hội thảo bạn đã chọn đã được giữ chỗ và đang chờ thanh toán.
</p>

<div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;">
    <p style="color: #856404; font-size: 15px; margin: 0;">
        <strong>⚠️ Cần thanh toán:</strong> Đặt chỗ của bạn hiện đang <strong>chờ thanh toán</strong>. Vui lòng hoàn tất thanh toán trong vòng 24 giờ để đảm bảo chỗ của bạn. Chỗ không được đảm bảo cho đến khi thanh toán được xác nhận.
    </p>
</div>

<h3 style="color: #333; font-size: 18px; margin: 25px 0 15px 0;">
    Chi tiết Đặt chỗ của bạn
</h3>

<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
    <thead>
        <tr style="background: #f5f5f5; border-bottom: 2px solid #ddd;">
            <th style="padding: 12px; text-align: left; color: #666; font-weight: bold;">Tên Lớp</th>
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
                    <?= isset($detail['booking_date']) ? h($detail['booking_date']) : 'Bất cứ lúc nào' ?>
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
            <td style="padding: 8px 0; color: #666; font-size: 15px;">Tổng số tiền:</td>
            <td style="padding: 8px 0; color: #752f3f; font-size: 18px; font-weight: bold; text-align: right;">
                $<?= number_format($totalPrice ?? 0, 2) ?>
            </td>
        </tr>
    </table>
</div>

<div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;">
    <p style="color: #856404; font-size: 15px; margin: 0;">
        <strong>⏰ Quan trọng:</strong> Vui lòng hoàn tất thanh toán trong vòng <strong>24 giờ</strong> để đảm bảo chỗ của bạn. Sau thời gian này, đặt chỗ của bạn có thể được giải phóng cho khách hàng khác.
    </p>
</div>

<div style="background: #f9f7f4; border-left: 4px solid #752f3f; padding: 15px; margin: 20px 0;">
    <p style="color: #333; font-size: 14px; margin: 0;">
        <strong>Cách hoàn tất thanh toán của bạn:</strong><br>
        1. Quay lại trang web của chúng tôi và đăng nhập vào tài khoản của bạn<br>
        2. Đi đến "Đặt chỗ của tôi" để xem đặt chỗ đang chờ của bạn<br>
        3. Nhấp "Thanh toán ngay" để hoàn tất thanh toán qua Stripe<br>
        4. Nhận xác nhận tức thì qua email sau khi thanh toán
    </p>
</div>

<p style="color: #555; font-size: 15px; line-height: 1.6; margin: 20px 0 15px 0;">
    <strong>Cần trợ giúp?</strong> Nếu bạn có bất kỳ câu hỏi nào hoặc cần trợ giúp với đặt chỗ của mình, vui lòng trả lời email này hoặc liên hệ trực tiếp với chúng tôi. Chúng tôi ở đây để giúp bạn!
</p>

<p style="color: #888; font-size: 13px; line-height: 1.6; margin: 20px 0 0 0; border-top: 1px solid #ddd; padding-top: 15px;">
    Trân trọng,<br>
    <strong>Đội ngũ Hội Nghệ Thuật Nến</strong><br>
    <em>Tạo ra những trải nghiệm đáng nhớ, từng chiếc nến một.</em>
</p>
