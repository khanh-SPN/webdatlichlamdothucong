<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Log\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Cake\Routing\Router;
use Cake\Utility\Text;

class BookingsController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        // Allow public access to booking page, read-only APIs, and Stripe redirect-back URLs
        $this->Authentication->allowUnauthenticated([
            'add',
            'getAvailableSlots',
            'getAvailableSeats',
            'success',
            'cancelPayment',
        ]);
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        // For JSON API endpoints: disable CSRF, auto-render, and force JSON response on errors
        $action = $this->request->getParam('action');
        if (in_array($action, ['getAvailableSlots', 'getAvailableSeats'], true)) {
            $this->autoRender = false;
            $this->viewBuilder()->setClassName('Json');
            // Disable CSRF protection for read-only GET endpoints
            if ($this->components()->has('FormProtection')) {
                $this->FormProtection->setConfig('unlockedActions', [$action]);
            }
        }
    }

    /**
     * Helper to return JSON response consistently
     */
    private function jsonResponse(array $data, int $status = 200)
    {
        return $this->response
            ->withStatus($status)
            ->withType('application/json')
            ->withStringBody(json_encode($data));
    }

    public function add()
    {
        $this->viewBuilder()->setTemplatePath('Pages');
        $this->viewBuilder()->setTemplate('booking');

        $user = $this->request->getAttribute('identity');
        $workshops = $this->fetchTable('Workshops')->find()->all();

        if ($this->request->is('post')) {
            if ($user === null) {
                $this->Flash->warning('Đăng nhập để đặt hội thảo', [
                    'params' => [
                        'subtitle' => 'Đăng nhập hoặc tạo tài khoản, sau đó quay lại đặt chỗ để giữ chỗ của bạn.',
                        'actionUrl' => Router::url(['controller' => 'Bookings', 'action' => 'add']),
                        'actionLabel' => 'Quay lại đặt chỗ',
                    ],
                ]);

                return $this->redirect(['controller' => 'Bookings', 'action' => 'add']);
            }

            $data = $this->request->getData();
            $itemsRaw = $data['items'] ?? null;
            if (!is_array($itemsRaw) || $itemsRaw === []) {
                $itemsRaw = [[
                    'workshop_id' => $data['workshop_id'] ?? null,
                    'booking_date' => $data['booking_date'] ?? null,
                ]];
            }

            $items = [];
            foreach ($itemsRaw as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $workshopId = isset($row['workshop_id']) ? (int)$row['workshop_id'] : 0;
                $bookingDate = isset($row['booking_date']) ? (string)$row['booking_date'] : '';
                $slotId = isset($row['slot_id']) ? (int)$row['slot_id'] : null;
                $quantity = isset($row['quantity']) ? (int)$row['quantity'] : 1;
                if ($workshopId <= 0 || $quantity <= 0) {
                    continue;
                }
                if ($quantity > 10) {
                    $quantity = 10;
                }
                $items[] = [
                    'workshop_id' => $workshopId,
                    'booking_date' => $bookingDate,
                    'slot_id' => $slotId,
                    'quantity' => $quantity
                ];
            }

            if ($items === []) {
                $this->Flash->error('Vui lòng chọn ít nhất một hội thảo.');
                return $this->redirect(['controller' => 'Bookings', 'action' => 'add']);
            }
            if (count($items) > 6) {
                $this->Flash->error('Bạn có thể đặt tối đa 6 hội thảo khác nhau mỗi lần thanh toán.');
                return $this->redirect(['controller' => 'Bookings', 'action' => 'add']);
            }

            // Calculate total seats needed for discount
            $totalSeats = 0;
            foreach ($items as $it) {
                $totalSeats += $it['quantity'];
            }

            // Auto apply 20% discount for 2+ seats total
            $discountPercent = ($totalSeats >= 2) ? 20 : 0;

            $workshopsTable = $this->fetchTable('Workshops');
            $bookingsTable = $this->fetchTable('Bookings');
            $paymentsTable = $this->fetchTable('Payments');
            $slotsTable = $this->fetchTable('TeacherAvailabilitySlots');

            // NEW: Slot-based validation
            $aggregatedItems = [];
            foreach ($items as $it) {
                $workshop = $workshopsTable->get($it['workshop_id']);
                if ($workshop === null) {
                    $this->Flash->error('Vui lòng chọn một hội thảo hợp lệ.');
                    return $this->redirect(['controller' => 'Bookings', 'action' => 'add']);
                }

                $isPass = strtolower((string)$workshop->workshop_type) === 'pass'
                    || str_contains(strtolower((string)$workshop->workshop_name), 'pass');
                $bookingDate = $isPass ? null : (string)$it['booking_date'];

                // For non-pass workshops, MUST have a valid slot
                if (!$isPass) {
                    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $bookingDate)) {
                        $this->Flash->error('Vui lòng chọn ngày hợp lệ cho mỗi hội thảo.');
                        return $this->redirect(['controller' => 'Bookings', 'action' => 'add']);
                    }

                    // Find the slot for this workshop and date
                    $slot = $slotsTable->find()
                        ->select([
                            'id',
                            'teacher_id',
                            'workshop_id',
                            'session_date',
                            'time_label',
                            'capacity',
                            'is_active',
                            'seats_booked',
                        ])
                        ->where([
                            'workshop_id' => $workshop->id,
                            'session_date' => $bookingDate,
                            'is_active' => true,
                        ])
                        ->first();

                    if (!$slot) {
                        $this->Flash->error("Không có buổi nào có sẵn cho {$workshop->workshop_name} vào {$bookingDate}. Vui lòng chọn ngày khác.");
                        return $this->redirect(['controller' => 'Bookings', 'action' => 'add']);
                    }

                    // Store slot_id for later use
                    $it['slot_id'] = $slot->id;
                    $it['slot'] = $slot;
                }

                $key = $workshop->id . '_' . ($bookingDate ?? 'pass');
                if (!isset($aggregatedItems[$key])) {
                    $aggregatedItems[$key] = [
                        'workshop' => $workshop,
                        'booking_date' => $bookingDate,
                        'slot' => $it['slot'] ?? null,
                        'slot_id' => $it['slot_id'] ?? null,
                        'quantity' => 0,
                        'items' => [],
                    ];
                }
                $aggregatedItems[$key]['quantity'] += $it['quantity'];
                $aggregatedItems[$key]['items'][] = $it;
            }

            // Check slot-based capacity
            foreach ($aggregatedItems as $agg) {
                $workshop = $agg['workshop'];
                $slot = $agg['slot'];
                $totalQuantity = $agg['quantity'];

                // Pass-type workshops skip slot validation
                $isPass = strtolower((string)$workshop->workshop_type) === 'pass'
                    || str_contains(strtolower((string)$workshop->workshop_name), 'pass');

                if (!$isPass && $slot) {
                    // Use slot capacity if set, otherwise use workshop capacity
                    $capacity = $slot->capacity ?? $workshop->capacity ?? 0;
                    
                    // Get booked seats for this slot (using cached seats_booked)
                    $alreadyBooked = (int)$slot->seats_booked;
                    $available = max(0, $capacity - $alreadyBooked);

                    Log::info("Slot {$slot->id}: capacity={$capacity}, alreadyBooked={$alreadyBooked}, available={$available}, requested={$totalQuantity}");

                    if ($totalQuantity > $available) {
                        $this->Flash->error("Không đủ chỗ cho {$workshop->workshop_name} vào {$agg['booking_date']}. Chỉ còn {$available} chỗ, bạn đã yêu cầu {$totalQuantity}.");
                        return $this->redirect(['controller' => 'Bookings', 'action' => 'add']);
                    }
                }
            }

            $checkoutGroup = Text::uuid();
            $lineItems = [];
            $createdPayments = [];

            foreach ($items as $it) {
                $workshop = $workshopsTable->find()->where(['id' => (int)$it['workshop_id']])->first();
                $isPass = $workshop && (strtolower((string)$workshop->workshop_type) === 'pass'
                    || str_contains(strtolower((string)$workshop->workshop_name), 'pass'));
                $bookingDate = $isPass ? null : (string)$it['booking_date'];

                $booking = $bookingsTable->newEmptyEntity();
                $booking->user_id = $user->id;
                $booking->workshop_id = (int)$it['workshop_id'];
                $booking->slot_id = $it['slot_id'] ?? null; // NEW: Link to slot
                $booking->booking_date = $bookingDate;
                $booking->status = 'pending';
                $booking->checkout_group = $checkoutGroup;
                $booking->quantity = $it['quantity'];

                if (!$bookingsTable->save($booking)) {
                    $this->Flash->error('Không thể tạo đặt chỗ. Vui lòng thử lại.');
                    return $this->redirect(['controller' => 'Bookings', 'action' => 'add']);
                }

                $payment = $this->fetchTable('Payments')->newEmptyEntity();
                $payment->booking_id = $booking->id;
                $payment->payment_method = 'stripe';
                $payment->payment_status = 'unpaid';
                $payment->checkout_group = $checkoutGroup;
                $payment->currency = (string)env('STRIPE_CURRENCY', 'usd');
                $payment->amount_cents = (int)$workshop->price * 100;

                $this->fetchTable('Payments')->save($payment);
                $createdPayments[] = $payment;

                // Add line item with quantity
                $lineItems[] = [
                    'price_data' => [
                        'currency' => (string)env('STRIPE_CURRENCY', 'usd'),
                        'product_data' => [
                            'name' => (string)$workshop->workshop_name . ($it['quantity'] > 1 ? " (x{$it['quantity']})" : ''),
                        ],
                        'unit_amount' => (int)$workshop->price * 100,
                    ],
                    'quantity' => $it['quantity'],
                ];
            }

            // Stripe setup
            $stripeKey = (string)Configure::read('Stripe.secretKey', '');
            if ($stripeKey === '') {
                $this->Flash->error('Stripe chưa được cấu hình. Vui lòng liên hệ với studio hoặc thử lại sau.');
                return $this->redirect(['action' => 'add']);
            }
            Stripe::setApiKey($stripeKey);

            // Build Stripe session params
            $sessionParams = [
                'payment_method_types' => ['card'],
                'mode' => 'payment',
                'line_items' => $lineItems,
                'success_url' => Router::url(['action' => 'success', $checkoutGroup], true),
                'cancel_url' => Router::url(['action' => 'cancelPayment', $checkoutGroup], true),
                'metadata' => [
                    'checkout_group' => $checkoutGroup,
                    'user_id' => (string)$user->id,
                ],
            ];

            // Apply discount using Stripe Coupon (auto 20% for 2+ workshops)
            if ($discountPercent > 0) {
                $couponId = 'auto_20_percent_off';
                try {
                    \Stripe\Coupon::retrieve($couponId);
                } catch (\Exception $e) {
                    // Coupon doesn't exist, create it
                    try {
                        \Stripe\Coupon::create([
                            'id' => $couponId,
                            'percent_off' => 20,
                            'duration' => 'once',
                            'name' => '20% off (2+ workshops)',
                        ]);
                    } catch (\Exception $e) {
                        $couponId = null;
                    }
                }
                if ($couponId) {
                    $sessionParams['discounts'] = [['coupon' => $couponId]];
                }
            }

            $session = Session::create($sessionParams);

            foreach ($createdPayments as $p) {
                $p->stripe_session_id = $session->id;
                $paymentsTable->save($p);
            }

            // Calculate total price for email
            $totalPrice = 0;
            foreach ($items as $it) {
                $workshop = $workshopsTable->get($it['workshop_id']);
                $totalPrice += ($workshop->price ?? 0) * ($it['quantity'] ?? 1);
            }

            // Send booking confirmation email (pending payment)
            Log::info("BOOKING_EMAIL: Starting to send booking confirmation email for user_id={$user->id}, email={$user->email}, checkout_group={$checkoutGroup}");
            $emailResult = $this->sendBookingConfirmationEmail($user, $items, $totalPrice);
            Log::info("BOOKING_EMAIL: Email sending result for user_id={$user->id}: " . ($emailResult ? 'SUCCESS' : 'FAILED'));

            Log::info("BOOKING_STRIPE: Redirecting to Stripe checkout for user_id={$user->id}, session_id={$session->id}");
            return $this->redirect($session->url);
        }

        $bookings = [];
        if ($user !== null) {
            $rawBookings = $this->fetchTable('Bookings')->find()
                ->where([
                    'Bookings.user_id' => $user->id,
                    'Bookings.status !=' => 'cancelled',
                ])
                ->contain(['Workshops', 'Payments'])
                ->orderBy(['Bookings.created' => 'DESC'])
                ->all();

            // Group by checkout_group
            $bookingGroups = [];
            foreach ($rawBookings as $b) {
                $groupId = $b->checkout_group ?? 'single_' . $b->id;
                if (!isset($bookingGroups[$groupId])) {
                    $bookingGroups[$groupId] = [
                        'bookings' => [],
                        'totalPrice' => 0,
                        'discountPercent' => 0,
                        'discountAmount' => 0,
                        'finalPrice' => 0,
                        'paymentStatus' => 'unpaid',
                        'created' => $b->created,
                        'checkoutGroup' => $b->checkout_group,
                    ];
                }
                $qty = $b->quantity ?? 1;
                $bookingGroups[$groupId]['bookings'][] = $b;
                $bookingGroups[$groupId]['totalPrice'] += ($b->workshop->price ?? 0) * $qty;
                if ($b->payments) {
                    foreach ($b->payments as $p) {
                        if ($p->payment_status === 'paid') {
                            $bookingGroups[$groupId]['paymentStatus'] = 'paid';
                            break;
                        }
                    }
                }
            }

            // Calculate discount based on total seats, not number of bookings
            foreach ($bookingGroups as &$group) {
                $totalSeats = 0;
                foreach ($group['bookings'] as $b) {
                    $totalSeats += ($b->quantity ?? 1);
                }
                if ($totalSeats >= 2) {
                    $group['discountPercent'] = 20;
                    $group['discountAmount'] = (int) round($group['totalPrice'] * 0.2);
                }
                $group['finalPrice'] = $group['totalPrice'] - $group['discountAmount'];
            }
            unset($group);

            $bookingGroups = array_values($bookingGroups);
            $bookings = $rawBookings; // Keep for backward compatibility
        } else {
            $bookingGroups = [];
        }

        $prefillWorkshopId = $this->request->getQuery('workshop_id');
        $prefillBookingDate = $this->request->getQuery('booking_date');
        if ($prefillWorkshopId !== null && $prefillWorkshopId !== '' && ctype_digit((string)$prefillWorkshopId)) {
            $prefillWorkshopId = (int)$prefillWorkshopId;
            $validIds = [];
            foreach ($workshops as $l) {
                $validIds[] = (int)$l->id;
            }
            if (!in_array($prefillWorkshopId, $validIds, true)) {
                $prefillWorkshopId = null;
            }
        } else {
            $prefillWorkshopId = null;
        }
        if ($prefillBookingDate !== null && $prefillBookingDate !== '') {
            $bd = (string)$prefillBookingDate;
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $bd)) {
                $prefillBookingDate = null;
            } else {
                $prefillBookingDate = $bd;
            }
        } else {
            $prefillBookingDate = null;
        }

        $this->set(compact('workshops', 'bookings', 'bookingGroups', 'prefillWorkshopId', 'prefillBookingDate'));
    }

    public function success($checkoutGroup)
    {
        $paymentsTable = $this->fetchTable('Payments');
        $bookingsTable = $this->fetchTable('Bookings');
        $workshopsTable = $this->fetchTable('Workshops');

        $payments = $paymentsTable->find()
            ->where(['Payments.checkout_group' => $checkoutGroup])
            ->contain(['Bookings.Workshops'])
            ->all();

        Log::info("SUCCESS: Processing checkout_group={$checkoutGroup}, payments count=" . $payments->count());

        $bookingIds = [];
        foreach ($payments as $payment) {
            $payment->payment_status = 'paid';
            $paymentsTable->save($payment);

            $booking = $payment->booking;
            if ($booking) {
                $oldStatus = $booking->status;
                $booking->status = 'confirmed';
                $bookingsTable->save($booking);
                $bookingIds[] = $booking->id;
                Log::info("SUCCESS: Booking {$booking->id} status changed from {$oldStatus} to confirmed, quantity={$booking->quantity}, workshop_id={$booking->workshop_id}, date={$booking->booking_date}");
            }
        }

        // Send payment receipt email
        $this->sendPaymentReceiptEmail($bookingIds, $checkoutGroup);

        return $this->redirect(['action' => 'add']);
    }

    /**
     * Helper method: Send payment receipt email with booking details
     *
     * @param array<int> $bookingIds Array of booking IDs
     * @param string $checkoutGroup Checkout group UUID
     * @return void
     */
    private function sendPaymentReceiptEmail(array $bookingIds, string $checkoutGroup): void
    {
        try {
            // Get user from first booking
            $firstBooking = $this->fetchTable('Bookings')->get($bookingIds[0] ?? 0, contain: ['Workshops', 'Users']);
            if (!$firstBooking || !$firstBooking->user) {
                return;
            }

            $user = $firstBooking->user;
            $bookings = $this->fetchTable('Bookings')->find()
                ->where(['Bookings.id IN' => $bookingIds])
                ->contain(['Workshops', 'Users'])
                ->all();

            // Build booking details for email
            $bookingDetails = [];
            $totalPrice = 0;
            foreach ($bookings as $booking) {
                $price = ($booking->workshop->price ?? 0) * ($booking->quantity ?? 1);
                $bookingDetails[] = [
                    'workshop_name' => $booking->workshop->workshop_name ?? 'Workshop',
                    'booking_date' => $booking->booking_date ? $booking->booking_date->format('M d, Y') : 'Anytime',
                    'quantity' => $booking->quantity ?? 1,
                    'price' => $price,
                ];
                $totalPrice += $price;
            }

            // Calculate discount (20% for 2+ seats)
            $totalSeats = 0;
            foreach ($bookings as $b) {
                $totalSeats += ($b->quantity ?? 1);
            }
            $discountPercent = ($totalSeats >= 2) ? 20 : 0;
            $discountAmount = $discountPercent > 0 ? ($totalPrice * $discountPercent / 100) : 0;
            $finalPrice = $totalPrice - $discountAmount;

            // Send email
            $emailService = new \App\Service\EmailService();
            $emailService->sendPaymentReceipt(
                $user->email,
                $user->name ?? 'Student',
                $bookingDetails,
                $totalPrice,
                $discountAmount,
                $finalPrice,
                'Stripe'
            );
        } catch (\Exception $e) {
            Log::error("Failed to send payment receipt email: {$e->getMessage()}");
        }
    }

    /**
     * Helper method: Send booking confirmation email for pending bookings
     *
     * @param object $user User entity
     * @param array $items Booking items with workshop details
     * @param float $totalPrice Total price
     * @return bool True if email sent successfully
     */
    private function sendBookingConfirmationEmail($user, array $items, float $totalPrice): bool
    {
        Log::info("BOOKING_EMAIL_METHOD: Starting sendBookingConfirmationEmail for user=" . ($user->email ?? 'unknown'));

        try {
            $userEmail = $user->email ?? null;
            if (empty($userEmail)) {
                Log::warning("BOOKING_EMAIL_METHOD: FAILED - user email is empty");
                return false;
            }
            Log::info("BOOKING_EMAIL_METHOD: User email verified: {$userEmail}");

            $bookingDetails = [];
            $workshopsTable = $this->fetchTable('Workshops');
            Log::info("BOOKING_EMAIL_METHOD: Building booking details for " . count($items) . " items");

            foreach ($items as $idx => $it) {
                $workshopId = $it['workshop_id'] ?? null;
                Log::info("BOOKING_EMAIL_METHOD: Processing item {$idx}, workshop_id={$workshopId}");

                if (!$workshopId) {
                    Log::warning("BOOKING_EMAIL_METHOD: Skipping item {$idx} - no workshop_id");
                    continue;
                }

                try {
                    $workshop = $workshopsTable->get($workshopId);
                    Log::info("BOOKING_EMAIL_METHOD: Found workshop: " . ($workshop->workshop_name ?? 'unnamed'));
                } catch (\Exception $e) {
                    Log::error("BOOKING_EMAIL_METHOD: Failed to get workshop {$workshopId}: " . $e->getMessage());
                    continue;
                }

                $price = ($workshop->price ?? 0) * ($it['quantity'] ?? 1);
                $bookingDate = 'Anytime';
                if (!empty($it['booking_date']) && $it['booking_date'] !== 'Anytime') {
                    $ts = strtotime($it['booking_date']);
                    if ($ts !== false) {
                        $bookingDate = date('M d, Y', $ts);
                    }
                }

                $bookingDetails[] = [
                    'workshop_name' => $workshop->workshop_name ?? 'Workshop',
                    'booking_date' => $bookingDate,
                    'quantity' => $it['quantity'] ?? 1,
                    'price' => $price,
                ];
                Log::info("BOOKING_EMAIL_METHOD: Added booking detail: {$workshop->workshop_name}, qty={$it['quantity']}, price={$price}");
            }

            if (empty($bookingDetails)) {
                Log::warning("BOOKING_EMAIL_METHOD: FAILED - No booking details to send email");
                return false;
            }
            Log::info("BOOKING_EMAIL_METHOD: Prepared " . count($bookingDetails) . " booking details, totalPrice={$totalPrice}");

            Log::info("BOOKING_EMAIL_METHOD: Calling EmailService->sendBookingConfirmation...");
            $emailService = new \App\Service\EmailService();
            $result = $emailService->sendBookingConfirmation(
                $userEmail,
                $user->name ?? 'Student',
                $bookingDetails,
                $totalPrice
            );

            if ($result) {
                Log::info("BOOKING_EMAIL_METHOD: SUCCESS - Email sent to {$userEmail}");
            } else {
                Log::error("BOOKING_EMAIL_METHOD: FAILED - EmailService returned false for {$userEmail}");
            }
            return $result;

        } catch (\Exception $e) {
            Log::error("BOOKING_EMAIL_METHOD: EXCEPTION - " . $e->getMessage());
            Log::error("BOOKING_EMAIL_METHOD: Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    public function cancelPayment($checkoutGroup)
    {
        $this->Flash->warning('Thanh toán đã bị hủy. Đặt chỗ của bạn vẫn đang chờ và có thể thanh toán sau.');
        return $this->redirect(['action' => 'add']);
    }

    public function getAvailableSeats($workshopId = null, $bookingDate = null)
    {
        try {
            $this->request->allowMethod(['get']);

            // Support both route args and query params
            if (!$workshopId) {
                $workshopId = $this->request->getQuery('workshop_id');
            }
            if (!$bookingDate) {
                $bookingDate = $this->request->getQuery('date');
            }

            if (!$workshopId) {
                return $this->jsonResponse(['error' => 'ID Hội thảo được yêu cầu', 'available' => 0], 400);
            }

            $workshop = $this->fetchTable('Workshops')->find()->where(['id' => $workshopId])->first();
            if (!$workshop) {
                return $this->jsonResponse(['error' => 'Không tìm thấy hội thảo', 'available' => 0], 404);
            }

            // For pass type, unlimited (max 10 per booking for practical reasons)
            $isPass = strtolower((string)$workshop->workshop_type) === 'pass'
                || str_contains(strtolower((string)$workshop->workshop_name), 'pass');
            if ($isPass) {
                return $this->jsonResponse([
                    'available' => 10,
                    'capacity' => null,
                    'booked' => 0,
                    'type' => 'pass',
                    'message' => 'Không giới hạn (Pass)',
                ]);
            }

            // Check if slot exists for this date
            if ($bookingDate) {
                $slotsTable = $this->fetchTable('TeacherAvailabilitySlots');
                $slot = $slotsTable->find()
                    ->select([
                        'id', 'teacher_id', 'workshop_id', 'session_date',
                        'time_label', 'capacity', 'is_active', 'seats_booked',
                    ])
                    ->where([
                        'workshop_id' => $workshopId,
                        'session_date' => $bookingDate,
                        'is_active' => true,
                    ])
                    ->first();

                if (!$slot) {
                    return $this->jsonResponse([
                        'error' => 'Không có buổi nào có sẵn',
                        'available' => 0,
                        'capacity' => null,
                        'booked' => 0,
                        'type' => 'workshop',
                        'message' => 'Không có hội thảo nào được lên lịch vào ngày này',
                    ]);
                }

                $capacity = $slot->capacity ?? $workshop->capacity ?? 0;
                $bookedSeats = (int)$slot->seats_booked;
                $available = max(0, $capacity - $bookedSeats);

                Log::info("getAvailableSeats (slot-based): slot={$slot->id}, workshop={$workshopId}, date={$bookingDate}, capacity={$capacity}, booked={$bookedSeats}, available={$available}");

                return $this->jsonResponse([
                    'available' => min($available, 10),
                    'capacity' => $capacity,
                    'booked' => $bookedSeats,
                    'type' => 'workshop',
                    'slot_id' => $slot->id,
                    'time_label' => $slot->time_label,
                    'message' => $available > 0 ? $available . ' chỗ còn lại' : 'Đã đặt hết',
                ]);
            }

            // Fallback for workshop-based capacity (no date specified)
            $capacity = $workshop->capacity ?? 0;
            return $this->jsonResponse([
                'available' => $capacity > 0 ? min($capacity, 10) : 0,
                'capacity' => $capacity,
                'booked' => 0,
                'type' => 'workshop',
                'message' => 'Chọn ngày để kiểm tra khả dụng',
            ]);
        } catch (\Throwable $e) {
            Log::error('getAvailableSeats error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->jsonResponse([
                'error' => 'Server error: ' . $e->getMessage(),
                'available' => 0,
            ], 500);
        }
    }

    /**
     * API endpoint to get available slots for a workshop
     * Returns slots with availability info for the booking calendar
     */
    public function getAvailableSlots($workshopId = null)
    {
        try {
            $this->request->allowMethod(['get']);

            // Support both route arg and query param
            if (!$workshopId) {
                $workshopId = $this->request->getQuery('workshop_id');
            }

            if (!$workshopId) {
                return $this->jsonResponse(['error' => 'ID Hội thảo được yêu cầu', 'slots' => []], 400);
            }

            $workshop = $this->fetchTable('Workshops')->find()->where(['id' => $workshopId])->first();
            if (!$workshop) {
                return $this->jsonResponse(['error' => 'Không tìm thấy hội thảo', 'slots' => []], 404);
            }

            $isPass = strtolower((string)$workshop->workshop_type) === 'pass'
                || str_contains(strtolower((string)$workshop->workshop_name), 'pass');

            // Pass-type workshops don't use slots
            if ($isPass) {
                return $this->jsonResponse([
                    'type' => 'pass',
                    'slots' => [],
                    'message' => 'Hội thảo loại Pass - không cần ngày cụ thể',
                ]);
            }

            // Get active slots for this workshop
            $slotsTable = $this->fetchTable('TeacherAvailabilitySlots');
            $slots = $slotsTable->find()
                ->select([
                    'TeacherAvailabilitySlots.id',
                    'TeacherAvailabilitySlots.teacher_id',
                    'TeacherAvailabilitySlots.workshop_id',
                    'TeacherAvailabilitySlots.session_date',
                    'TeacherAvailabilitySlots.time_label',
                    'TeacherAvailabilitySlots.capacity',
                    'TeacherAvailabilitySlots.is_active',
                    'TeacherAvailabilitySlots.seats_booked',
                ])
                ->where([
                    'TeacherAvailabilitySlots.workshop_id' => $workshopId,
                    'TeacherAvailabilitySlots.is_active' => true,
                    'TeacherAvailabilitySlots.session_date >=' => date('Y-m-d'),
                ])
                ->contain(['Teachers'])
                ->orderBy(['TeacherAvailabilitySlots.session_date' => 'ASC'])
                ->all();

            $result = [];
            foreach ($slots as $slot) {
                $capacity = $slot->capacity ?? $workshop->capacity ?? 0;
                $available = max(0, $capacity - (int)$slot->seats_booked);

                if ($available > 0) {
                    $sessionDate = $slot->session_date;
                    $dateStr = is_object($sessionDate) ? $sessionDate->format('Y-m-d') : (string)$sessionDate;
                    $dayName = is_object($sessionDate) ? $sessionDate->format('l') : date('l', strtotime((string)$sessionDate));

                    $result[] = [
                        'id' => $slot->id,
                        'date' => $dateStr,
                        'time_label' => $slot->time_label,
                        'teacher_name' => $slot->teacher->name ?? 'Unknown',
                        'capacity' => $capacity,
                        'booked' => (int)$slot->seats_booked,
                        'available' => min($available, 10),
                        'day_name' => $dayName,
                    ];
                }
            }

            return $this->jsonResponse([
                'type' => 'workshop',
                'workshop_name' => $workshop->workshop_name,
                'slots' => $result,
                'count' => count($result),
            ]);
        } catch (\Throwable $e) {
            Log::error('getAvailableSlots error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->jsonResponse([
                'error' => 'Server error: ' . $e->getMessage(),
                'slots' => [],
            ], 500);
        }
    }

    public function payAgain($paymentId)
    {
        $paymentsTable = $this->fetchTable('Payments');
        $payment = $paymentsTable->get($paymentId, contain: ['Bookings' => ['Workshops']]);

        if ($payment->payment_status === 'paid') {
            return $this->redirect(['action' => 'add']);
        }

        $stripeKey = (string)Configure::read('Stripe.secretKey', '');
        if ($stripeKey === '') {
            $this->Flash->error('Stripe is not configured yet. Please contact the studio or try again later.');
            return $this->redirect(['action' => 'add']);
        }
        Stripe::setApiKey($stripeKey);

        // If this payment is part of a checkout group, pay for the whole group
        $checkoutGroup = $payment->checkout_group;
        if (!empty($checkoutGroup)) {
            $groupPayments = $paymentsTable->find()
                ->where(['Payments.checkout_group' => $checkoutGroup, 'Payments.payment_status' => 'unpaid'])
                ->contain(['Bookings' => ['Workshops']])
                ->all();

            if ($groupPayments->count() === 0) {
                $this->Flash->success('All items are already paid.');
                return $this->redirect(['action' => 'add']);
            }

            $lineItems = [];
            $currency = (string)env('STRIPE_CURRENCY', 'usd');
            foreach ($groupPayments as $p) {
                $workshop = $p->booking->workshop;
                $qty = $p->booking->quantity ?? 1;
                $lineItems[] = [
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => [
                            'name' => (string)$workshop->workshop_name . ($qty > 1 ? " (x{$qty})" : ''),
                        ],
                        'unit_amount' => (int)$workshop->price * 100,
                    ],
                    'quantity' => $qty,
                ];
            }

            $session = Session::create([
                'payment_method_types' => ['card'],
                'mode' => 'payment',
                'line_items' => $lineItems,
                'success_url' => Router::url(['action' => 'success', $checkoutGroup], true),
                'cancel_url' => Router::url(['action' => 'cancelPayment', $checkoutGroup], true),
                'metadata' => [
                    'checkout_group' => $checkoutGroup,
                    'user_id' => (string)$payment->booking->user_id,
                ],
            ]);

            foreach ($groupPayments as $p) {
                $p->stripe_session_id = $session->id;
                $paymentsTable->save($p);
            }

            return $this->redirect($session->url);
        }

        // Fallback for single payment without checkout group
        $workshop = $payment->booking->workshop;
        $qty = $payment->booking->quantity ?? 1;
        $currency = (string)env('STRIPE_CURRENCY', 'usd');
        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => (string)$workshop->workshop_name . ($qty > 1 ? " (x{$qty})" : ''),
                    ],
                    'unit_amount' => (int)$workshop->price * 100,
                ],
                'quantity' => $qty,
            ]],
            'metadata' => [
                'payment_ids' => json_encode([(int)$payment->id]),
                'booking_ids' => json_encode([(int)$payment->booking_id]),
            ],
            'success_url' => Router::url(['action' => 'success', $payment->id], true),
            'cancel_url' => Router::url(['action' => 'cancelPayment', $payment->id], true),
        ]);

        try {
            $payment->stripe_checkout_session_id = (string)$session->id;
            $this->fetchTable('Payments')->save($payment);
        } catch (\Throwable $e) {
        }

        return $this->redirect($session->url);
    }

    public function payGroup($checkoutGroup)
    {
        $paymentsTable = $this->fetchTable('Payments');
        $groupPayments = $paymentsTable->find()
            ->where(['Payments.checkout_group' => $checkoutGroup, 'Payments.payment_status' => 'unpaid'])
            ->contain(['Bookings' => ['Workshops']])
            ->all();

        if ($groupPayments->count() === 0) {
            $this->Flash->success('All items are already paid.');
            return $this->redirect(['action' => 'add']);
        }

        $stripeKey = (string)Configure::read('Stripe.secretKey', '');
        if ($stripeKey === '') {
            $this->Flash->error('Stripe is not configured yet. Please contact the studio or try again later.');
            return $this->redirect(['action' => 'add']);
        }
        Stripe::setApiKey($stripeKey);

        $lineItems = [];
        foreach ($groupPayments as $p) {
            $workshop = $p->booking->workshop;
            $qty = $p->booking->quantity ?? 1;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => (string)$workshop->workshop_name . ($qty > 1 ? " (x{$qty})" : ''),
                    ],
                    'unit_amount' => (int)$workshop->price * 100,
                ],
                'quantity' => $qty,
            ];
        }

        // Build Stripe session params
        $sessionParams = [
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => $lineItems,
            'success_url' => Router::url(['action' => 'success', $checkoutGroup], true),
            'cancel_url' => Router::url(['action' => 'cancelPayment', $checkoutGroup], true),
            'metadata' => [
                'checkout_group' => $checkoutGroup,
                'user_id' => (string)$groupPayments->first()->booking->user_id,
            ],
        ];

        // Apply discount using Stripe Coupon (auto 20% for 2+ workshops)
        if (count($lineItems) >= 2) {
            $couponId = 'auto_20_percent_off';
            try {
                \Stripe\Coupon::retrieve($couponId);
            } catch (\Exception $e) {
                try {
                    \Stripe\Coupon::create([
                        'id' => $couponId,
                        'percent_off' => 20,
                        'duration' => 'once',
                        'name' => '20% off (2+ workshops)',
                    ]);
                } catch (\Exception $e) {
                    $couponId = null;
                }
            }
            if ($couponId) {
                $sessionParams['discounts'] = [['coupon' => $couponId]];
            }
        }

        $session = Session::create($sessionParams);

        foreach ($groupPayments as $p) {
            $p->stripe_session_id = $session->id;
            $paymentsTable->save($p);
        }

        return $this->redirect($session->url);
    }

    public function cancel($id)
    {
        $this->request->allowMethod(['post']);

        $user = $this->request->getAttribute('identity');
        $booking = $this->fetchTable('Bookings')->get($id);

        if ($user === null || (int)$booking->user_id !== (int)$user->id) {
            $this->Flash->error('You cannot cancel this booking.');
            return $this->redirect(['action' => 'add']);
        }

        // Only allow cancel when status is pending
        if ($booking->status !== 'pending') {
            $this->Flash->error('Only pending bookings can be cancelled');
            return $this->redirect(['action' => 'add']);
        }

        $booking->status = 'cancelled';

        if ($this->fetchTable('Bookings')->save($booking)) {
            $this->Flash->success('Booking request removed from your list.');
        } else {
            $this->Flash->error('Could not cancel this booking. Please try again.');
        }

        return $this->redirect(['action' => 'add']);
    }
}
