<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\User;
use Cake\Datasource\EntityInterface;
use Cake\Http\Response;
use Cake\Routing\Router;
use Cake\Utility\Text;
use Cake\I18n\FrozenTime;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Log\Log;
use Authentication\PasswordHasher\DefaultPasswordHasher;

class UsersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Authentication.Authentication');

        $this->Authentication->allowUnauthenticated([
            'login',
            'register',
            'forgotPassword',
            'resetPassword'
        ]);
    }

    // ================= LOGIN =================
    public function login()
    {
        $this->viewBuilder()->setTemplatePath('Pages');
        $this->viewBuilder()->setTemplate('login');

        $this->request->allowMethod(['get', 'post']);

        if ($this->request->is('post')) {

            $data = $this->request->getData();
            $email = trim($data['email'] ?? '');

            // ================= STEP 1: FIND USER =================
            $user = $this->Users->find()
                ->where(['email' => $email])
                ->first();

            if (!$user) {
                $this->Flash->error('Email hoặc mật khẩu không hợp lệ.');
                return;
            }

            // ================= STEP 2: CHECK ACCOUNT LOCK =================
            if ($this->isAccountLocked($user)) {

                $unlockTime = $user->last_failed_login->addMinutes(30);
                $minutesLeft = max(1, $unlockTime->diffInMinutes(FrozenTime::now()));

                $this->Flash->error("Tài khoản đã bị khóa. Thử lại sau {$minutesLeft} phút.");
                return;
            }

            // ================= STEP 3: AUTHENTICATION =================
            $result = $this->Authentication->getResult();
            $authenticated = $result->isValid();

            // Fallback when the form authenticator did not run (e.g. login URL mismatch) but credentials are correct.
            if (!$authenticated) {
                $plain = (string) ($data['password'] ?? '');
                if ($plain !== '' && (new DefaultPasswordHasher())->check($plain, $user->password)) {
                    $this->Authentication->setIdentity($user);
                    $authenticated = true;
                }
            }

            if ($authenticated) {

                $identity = $result->isValid() ? $result->getData() : $user;

                // ================= STEP 4: CHECK BUSINESS LOGIC (BEHAVIOR) =================
                if (!$this->Users->getBehavior('CanAuthenticate')->canLogin($identity)) {
                    $this->Authentication->logout();
                    $this->Flash->error('Tài khoản này không được phép đăng nhập.');
                    return;
                }

                // ================= STEP 5: RESET FAILED LOGIN ATTEMPTS =================
                $user->failed_login_attempts = 0;
                $user->last_failed_login = null;
                $this->Users->save($user);

                // ================= STEP 6: REDIRECT =================
                if ($user->role === 'admin') {
                    return $this->redirect('/admin');
                }

                if ($user->role === 'teacher') {
                    return $this->redirect('/teacher');
                }

                return $this->redirect(
                    $this->Authentication->getLoginRedirect() ?? '/'
                );
            }

            // ================= STEP 7: HANDLE FAILED LOGIN =================
            $this->handleFailedLogin($user);

            $this->Flash->error('Email hoặc mật khẩu không hợp lệ.');
        }
    }

    // ================= REGISTER =================
    public function register()
    {
        $this->viewBuilder()->setTemplatePath('Pages');
        $this->viewBuilder()->setTemplate('register');

        $redirectQuery = $this->request->getQuery('redirect');
        $this->set('redirectTarget', is_string($redirectQuery) ? $redirectQuery : '');

        $customersTable = $this->fetchTable('Customers');
        $user = $this->Users->newEmptyEntity();
        $customer = $customersTable->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // Never log raw registration payloads as they may contain passwords.
            Log::debug('REGISTER ATTEMPT: ' . json_encode([
                'email' => $data['email'] ?? null,
                'has_redirect' => isset($data['redirect']) || $this->request->getQuery('redirect') !== null,
            ]));

            $afterLoginPath = $this->safeRedirectPath(
                $data['redirect'] ?? $this->request->getQuery('redirect'),
                '/'
            );

            $fullName = trim((string) ($data['full_name'] ?? ''));
            $phone = trim((string) ($data['phone'] ?? ''));
            $address = trim((string) ($data['address'] ?? ''));

            $customer = $customersTable->patchEntity($customer, [
                'name' => $fullName,
                'phone' => $phone,
                'address' => $address !== '' ? $address : null,
            ], ['validate' => false]);

            if (($data['password'] ?? '') !== ($data['confirm_password'] ?? '')) {
                if ($this->wantsJsonResponse()) {
                    return $this->jsonAuthResponse(['success' => false, 'message' => 'Mật khẩu không khớp.']);
                }
                $this->Flash->error('Mật khẩu không khớp.');
                $user = $this->Users->patchEntity($user, $data, [
                    'fields' => ['email', 'password'],
                    'validate' => false,
                ]);
                $this->set(compact('user', 'customer'));

                return null;
            }

            $user = $this->Users->patchEntity($user, $data, [
                'fields' => ['email', 'password'],
                'validate' => 'register',
            ]);
            $user->role = 'customer';

            if ($user->hasErrors()) {
                Log::error('REGISTER USER VALIDATION: ' . json_encode($user->getErrors()));
                if ($this->wantsJsonResponse()) {
                    return $this->jsonAuthResponse([
                        'success' => false,
                        'message' => $this->firstEntityErrorMessage($user),
                    ]);
                }
                $this->Flash->error('Vui lòng sửa các lỗi bên dưới và thử lại.');
                $customer = $customersTable->patchEntity($customersTable->newEmptyEntity(), [
                    'name' => $fullName,
                    'phone' => $phone,
                    'address' => $address !== '' ? $address : null,
                ], ['validate' => false]);
                $this->set(compact('user', 'customer'));

                return null;
            }

            $customer = $customersTable->patchEntity($customersTable->newEmptyEntity(), [
                'name' => $fullName,
                'phone' => $phone,
                'address' => $address !== '' ? $address : null,
            ], [
                'validate' => 'register',
            ]);

            if ($customer->hasErrors()) {
                if ($this->wantsJsonResponse()) {
                    return $this->jsonAuthResponse([
                        'success' => false,
                        'message' => $this->firstEntityErrorMessage($customer),
                    ]);
                }
                $this->Flash->error('Vui lòng kiểm tra chi tiết liên hệ của bạn.');
                $this->set(compact('user', 'customer'));

                return null;
            }

            $connection = $this->Users->getConnection();
            $saved = $connection->transactional(function () use ($user, $customersTable, $customer) {
                if (!$this->Users->save($user)) {
                    return false;
                }
                $customer->user_id = $user->id;

                return (bool) $customersTable->save($customer);
            });

            if ($saved) {
                if ($this->wantsJsonResponse()) {
                    return $this->jsonAuthResponse([
                        'success' => true,
                        'message' => 'Đăng ký thành công. Đăng nhập bên dưới.',
                    ]);
                }
                $this->Flash->success('Đăng ký thành công. Bạn có thể đăng nhập ngay.');

                return $this->redirect([
                    'controller' => 'Users',
                    'action' => 'login',
                    '?' => ['redirect' => $afterLoginPath],
                ]);
            }

            Log::error('REGISTER FAILED');
            Log::error(json_encode($user->getErrors()));
            Log::error(json_encode($customer->getErrors()));
            if ($this->wantsJsonResponse()) {
                return $this->jsonAuthResponse([
                    'success' => false,
                    'message' => 'Không thể hoàn tất đăng ký. Vui lòng thử lại.',
                ]);
            }
            $this->Flash->error('Không thể hoàn tất đăng ký. Vui lòng thử lại.');
        }

        $this->set(compact('user', 'customer'));

        return null;
    }

    // ================= LOGOUT =================
    public function logout()
    {
        Log::debug('USER LOGOUT');
        $this->Authentication->logout();
        return $this->redirect(['action' => 'login']);
    }

    // ================= FORGOT PASSWORD =================
    public function forgotPassword()
    {
        $this->viewBuilder()->setTemplatePath('Pages');
        $this->viewBuilder()->setTemplate('forgot_password');

        if ($this->request->is('post')) {
            $email = $this->request->getData('email');

            Log::debug('FORGOT PASSWORD EMAIL: ' . $email);

            $user = $this->Users->findByEmail($email)->first();

            if ($user) {
                $user->nonce = Text::uuid();
                $user->nonce_expiry = FrozenTime::now()->addHours(1);

                $this->Users->save($user);

                Log::debug('RESET TOKEN GENERATED: ' . $user->nonce);

                $this->Flash->success('Liên kết đặt lại (demo): /users/resetPassword/' . $user->nonce);
            } else {
                Log::error('EMAIL NOT FOUND: ' . $email);
                $this->Flash->error('Không tìm thấy email');
            }
        }
    }

    // ================= RESET PASSWORD =================
    public function resetPassword($token = null)
    {
        $this->viewBuilder()->setTemplatePath('Pages');
        $this->viewBuilder()->setTemplate('reset_password');

        Log::debug('RESET TOKEN: ' . $token);

        $user = $this->Users->find()
            ->where([
                'nonce' => $token,
                'nonce_expiry >' => FrozenTime::now()
            ])
            ->first();

        if (!$user) {
            Log::error('INVALID TOKEN');
            throw new NotFoundException('Mã không hợp lệ hoặc đã hết hạn');
        }

        if ($this->request->is(['post', 'put'])) {
            $data = $this->request->getData();

            if ($data['password'] !== ($data['confirm_password'] ?? null)) {
                $this->Flash->error('Mật khẩu không khớp');
                return;
            }

            $user = $this->Users->patchEntity($user, $data);

            $user->nonce = null;
            $user->nonce_expiry = null;

            if ($this->Users->save($user)) {
                Log::debug('PASSWORD RESET SUCCESS');
                $this->Flash->success('Cập nhật mật khẩu thành công');
                return $this->redirect(['action' => 'login']);
            }

            Log::error('PASSWORD RESET FAILED');
            $this->Flash->error('Đã xảy ra lỗi');
        }

        $this->set(compact('user'));
    }

    // ================= ADMIN CRUD =================
    public function index()
    {
        $this->checkAdmin();
        $users = $this->paginate($this->Users);
        $this->set(compact('users'));
    }

    public function view($id = null)
    {
        $this->checkAdmin();
        $user = $this->Users->get($id, contain: ['Enquiries']);
        $this->set(compact('user'));
    }

    public function add()
    {
        $this->checkAdmin();
        $user = $this->Users->newEmptyEntity();

        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());

            if ($this->Users->save($user)) {
                $this->Flash->success('Đã lưu người dùng');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Không thể lưu người dùng');
        }
        $this->set(compact('user'));
    }

    public function edit($id = null)
    {
        $this->checkAdmin();
        $user = $this->Users->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());

            if ($this->Users->save($user)) {
                $this->Flash->success('Đã cập nhật người dùng');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Cập nhật thất bại');
        }
        $this->set(compact('user'));
    }

    public function delete($id = null)
    {
        $this->checkAdmin();
        $this->request->allowMethod(['post', 'delete']);

        $user = $this->Users->get($id);

        if ($this->Users->delete($user)) {
            $this->Flash->success('User deleted');
        } else {
            $this->Flash->error('Delete failed');
        }

        return $this->redirect(['action' => 'index']);
    }

    // ================= PROFILE =================
    public function profile()
    {
        $user = $this->request->getAttribute('identity');

        if ($user && $user->role === 'teacher') {
            return $this->redirect(['controller' => 'Teacher', 'action' => 'profile']);
        }

        $customersTable = $this->fetchTable('Customers');

        $customer = $customersTable->find()
            ->where(['user_id' => $user->id])
            ->first();

        if ($customer === null) {
            $customer = $customersTable->newEmptyEntity();
            $customer->user_id = $user->id;
        }

        // Get bookings grouped by checkout_group for display
        $rawBookings = $this->fetchTable('Bookings')->find()
            ->where(['Bookings.user_id' => $user->id])
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

        // Convert to array for template
        $bookingGroups = array_values($bookingGroups);
        $bookings = $rawBookings; // Keep for backward compatibility

        $announcementsForCustomer = [];
        $teacherIds = [];
        $userWorkshopIds = [];
        $allUserBookings = $this->fetchTable('Bookings')->find()
            ->contain(['Workshops'])
            ->where(['Bookings.user_id' => $user->id])
            ->all();
        foreach ($allUserBookings as $b) {
            if ($b->workshop) {
                $userWorkshopIds[$b->workshop_id] = true;
                $teacherIds[(int) $b->workshop->teacher_id] = true;
            }
        }
        if ($teacherIds !== []) {
            $rawAnnouncements = $this->fetchTable('Announcements')->find()
                ->contain(['Workshops', 'Teachers'])
                ->where(['Announcements.teacher_id IN' => array_keys($teacherIds)])
                ->orderBy(['Announcements.sent_at' => 'DESC'])
                ->limit(30)
                ->all();
            foreach ($rawAnnouncements as $a) {
                if ($a->workshop_id === null || isset($userWorkshopIds[$a->workshop_id])) {
                    $announcementsForCustomer[] = $a;
                }
            }
        }

        if ($this->request->is(['post', 'put'])) {
            $customer = $customersTable->patchEntity($customer, $this->request->getData());

            if ($customersTable->save($customer)) {
                $this->Flash->success('Đã cập nhật hồ sơ');
            } else {
                $this->Flash->error('Cập nhật thất bại');
            }
        }

        $this->set(compact('customer', 'bookings', 'bookingGroups', 'announcementsForCustomer'));
    }

    // ================= SECURITY HELPERS =================
    private function isAccountLocked(User $user): bool
    {
        if ($user->failed_login_attempts < 5) {
            return false;
        }

        if (!$user->last_failed_login) {
            return false;
        }

        $lockUntil = $user->last_failed_login->addMinutes(30);
        return FrozenTime::now()->lessThan($lockUntil);
    }

    private function handleFailedLogin(User $user): void
    {
        $current = (int) ($user->failed_login_attempts ?? 0);
        
        $user->failed_login_attempts = $current + 1;
        $user->last_failed_login     = FrozenTime::now();

        $this->Users->save($user);
    }
    private function checkAdmin()
    {
        $user = $this->request->getAttribute('identity');

        if (!$user || $user->role !== 'admin') {
            Log::error('UNAUTHORIZED ACCESS');
            throw new ForbiddenException('Truy cập bị từ chối');
        }
    }

    private function wantsJsonResponse(): bool
    {
        $accept = $this->request->getHeaderLine('Accept');
        if (str_contains($accept, 'application/json')) {
            return true;
        }

        return $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function jsonAuthResponse(array $payload, int $status = 200): Response
    {
        return $this->response
            ->withStatus($status)
            ->withType('application/json')
            ->withStringBody((string)json_encode($payload));
    }

    private function safeRedirectPath(?string $candidate, string $default = '/'): string
    {
        if ($candidate === null || $candidate === '') {
            return $default;
        }
        $candidate = trim(rawurldecode($candidate));
        if (!str_starts_with($candidate, '/') || str_starts_with($candidate, '//')) {
            return $default;
        }
        if (strlen($candidate) > 512 || str_contains($candidate, "\0")) {
            return $default;
        }

        return $candidate;
    }

    private function firstEntityErrorMessage(EntityInterface $entity): string
    {
        foreach ($entity->getErrors() as $msgs) {
            foreach ((array)$msgs as $m) {
                if (is_string($m)) {
                    return $m;
                }
                if (is_array($m)) {
                    $inner = reset($m);
                    if (is_string($inner)) {
                        return $inner;
                    }
                }
            }
        }

        return 'Vui lòng kiểm tra đầu vào của bạn và thử lại.';
    }
}