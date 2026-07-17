<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Teacher;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\I18n\DateTime;
use Cake\I18n\FrozenDate;
use Cake\Utility\Text;
use Cake\Log\Log;

/**
 * Instructor-facing area (separate from admin site management).
 */
class TeacherController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        $this->Authentication->allowUnauthenticated([]);
    }

    public function beforeRender(\Cake\Event\EventInterface $event): void
    {
        parent::beforeRender($event);
        if ($this->request->getParam('action') === 'downloadReport') {
            $this->viewBuilder()->disableAutoLayout();
            return;
        }
        $this->viewBuilder()->setLayout('landing');
    }

    private function teacherGate(): ?Response
    {
        $user = $this->request->getAttribute('identity');
        if ($user === null) {
            return null;
        }
        if ($user->role === 'admin') {
            return $this->redirect('/admin');
        }
        if ($user->role !== 'teacher') {
            throw new ForbiddenException('Chỉ dành cho Giảng viên.');
        }

        return null;
    }

    private function getInstructorByEmail(): ?Teacher
    {
        $user = $this->request->getAttribute('identity');
        if ($user === null) {
            return null;
        }

        return $this->fetchTable('Teachers')->find()
            ->where(['email' => $user->email])
            ->first();
    }

    public function index(): ?Response
    {
        if (($r = $this->teacherGate()) !== null) {
            return $r;
        }

        $this->set('title', 'Trung tâm Giảng viên');

        $teachersTable = $this->fetchTable('Teachers');
        $workshopsTable = $this->fetchTable('Workshops');
        $bookingsTable = $this->fetchTable('Bookings');

        $instructor = $teachersTable->find()
            ->where(['email' => $this->request->getAttribute('identity')->email])
            ->first();

        $workshopIds = [];
        $workshops = [];
        $totalStudents = 0;

        if ($instructor !== null) {
            $workshops = $workshopsTable->find()
                ->where(['teacher_id' => $instructor->id])
                ->orderBy(['workshop_name' => 'ASC'])
                ->all();

            foreach ($workshops as $workshop) {
                $workshopIds[] = $workshop->id;
            }

            if ($workshopIds !== []) {
                $totalStudents = $bookingsTable->find()
                    ->where(['workshop_id IN' => $workshopIds])
                    ->count();
            }
        }

        $recentBookings = [];
        if ($workshopIds !== []) {
            $recentBookings = $bookingsTable->find()
                ->contain(['Users', 'Workshops'])
                ->where(['Bookings.workshop_id IN' => $workshopIds])
                ->orderBy(['Bookings.created' => 'DESC'])
                ->limit(12)
                ->all();
        }

        $announcementFeed = [];
        if ($instructor !== null) {
            $announcementFeed = $this->fetchTable('Announcements')->find()
                ->where(['teacher_id' => $instructor->id])
                ->orderBy(['sent_at' => 'DESC'])
                ->limit(5)
                ->all();
        }

        $this->set(compact('instructor', 'workshops', 'totalStudents', 'recentBookings', 'announcementFeed'));

        return null;
    }

    public function profile(): ?Response
    {
        Log::info('PROFILE: === ENTERING PROFILE METHOD ===');

        if (($r = $this->teacherGate()) !== null) {
            Log::info('PROFILE: Blocked by teacherGate');
            return $r;
        }

        $this->set('title', 'Hồ sơ Giáo viên');
        $teachersTable = $this->fetchTable('Teachers');
        $instructor = $this->getInstructorByEmail();

        Log::info('PROFILE: Got instructor: ' . ($instructor ? 'id=' . $instructor->id : 'NULL'));

        if ($instructor === null) {
            Log::warning('PROFILE: No instructor found, redirecting');
            $this->Flash->error('Chưa có hồ sơ giảng viên nào được liên kết với email tài khoản của bạn.');
            return $this->redirect(['action' => 'index']);
        }

        $isPost = $this->request->is(['post', 'put']);
        Log::info('PROFILE: isPost/put=' . ($isPost ? 'true' : 'false') . ', method=' . $this->request->getMethod());

        if ($isPost) {
            Log::info('PROFILE: === PROCESSING POST REQUEST ===');
            $data = $this->request->getData();
            Log::info('PROFILE: Raw POST data keys: ' . implode(', ', array_keys($data)));
            $photoPath = $instructor->photo;
            $isAjax = $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';
            Log::info('PROFILE: isAjax=' . ($isAjax ? 'true' : 'false'));
            $error = null;

            $upload = $this->request->getUploadedFile('photo_file');
            Log::info('PROFILE: Upload file check: ' . ($upload ? 'has file, name=' . $upload->getClientFilename() : 'no file'));

            if ($upload !== null && $upload->getClientFilename() !== '') {
                $err = $upload->getError();
                Log::info('PROFILE: Upload error code: ' . $err);

                if ($err !== UPLOAD_ERR_OK) {
                    $error = 'Photo upload failed. Please try again.';
                    Log::error('PROFILE: Upload error: ' . $err);
                } else {
                    $mime = (string) $upload->getClientMediaType();
                    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
                    Log::info('PROFILE: Upload mime: ' . $mime);

                    if (!isset($allowed[$mime])) {
                        $error = 'Photo must be JPEG, PNG, or WebP.';
                        Log::error('PROFILE: Invalid mime type: ' . $mime);
                    } else {
                        $dir = WWW_ROOT . 'uploads' . DS . 'teachers';
                        Log::info('PROFILE: Upload dir: ' . $dir . ', exists=' . (is_dir($dir) ? 'yes' : 'no'));

                        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
                            $error = 'Could not create upload directory.';
                            Log::error('PROFILE: Failed to create dir: ' . $dir);
                        } else {
                            $basename = Text::uuid() . '.' . $allowed[$mime];
                            $target = $dir . DS . $basename;
                            Log::info('PROFILE: Moving file to: ' . $target);

                            try {
                                $upload->moveTo($target);
                                $photoPath = 'uploads/teachers/' . $basename;
                                Log::info('PROFILE: File uploaded successfully: ' . $photoPath);
                            } catch (\Exception $e) {
                                $error = 'Failed to save uploaded file.';
                                Log::error('PROFILE: moveTo failed: ' . $e->getMessage());
                            }
                        }
                    }
                }
            }

            // Handle upload/validation errors
            if ($error !== null) {
                Log::error('PROFILE: Error occurred before save: ' . $error);
                if ($isAjax) {
                    return $this->response
                        ->withType('application/json')
                        ->withStatus(400)
                        ->withStringBody(json_encode(['status' => 'error', 'message' => $error]));
                }
                $this->Flash->error($error);
            } elseif ($error === null) {
                // Form data is NOT scoped - fields are: specialization, bio, photo_file, remove_photo
                if (!empty($data['remove_photo'])) {
                    $photoPath = null;
                }

                // Log incoming data
                Log::info('PROFILE: Raw data: ' . json_encode($data));
                Log::info('PROFILE: Update data received: bio=' . ($data['bio'] ?? 'null') . ', specialization=' . ($data['specialization'] ?? 'null') . ', photo=' . ($photoPath ?? 'null'));

                $patchData = [
                    'bio' => $data['bio'] ?? null,
                    'specialization' => $data['specialization'] ?? null,
                    'photo' => $photoPath,
                ];

                $instructor = $teachersTable->patchEntity($instructor, $patchData, [
                    'fields' => ['bio', 'specialization', 'photo'],
                ]);

                Log::info('PROFILE: Entity after patch - bio=' . ($instructor->bio ?? 'null') . ', photo=' . ($instructor->photo ?? 'null') . ', dirty=' . json_encode($instructor->getDirty()));

                // Check if entity has errors before saving
                if ($instructor->hasErrors()) {
                    $errors = $instructor->getErrors();
                    Log::warning('PROFILE: Entity has validation errors: ' . json_encode($errors));
                    $error = 'Validation failed: ' . json_encode($errors);
                    if ($isAjax) {
                        return $this->response
                            ->withType('application/json')
                            ->withStatus(400)
                            ->withStringBody(json_encode(['status' => 'error', 'message' => $error, 'errors' => $errors]));
                    }
                    $this->Flash->error($error);
                } else {
                    try {
                        // Try save with exception to catch any silent failures
                        $saveResult = $teachersTable->save($instructor);

                        if ($saveResult === false) {
                            Log::error('PROFILE: Save returned FALSE for teacher id=' . $instructor->id);
                            $error = 'Database save failed - save() returned false';
                            if ($isAjax) {
                                return $this->response
                                    ->withType('application/json')
                                    ->withStatus(500)
                                    ->withStringBody(json_encode(['status' => 'error', 'message' => $error]));
                            }
                            $this->Flash->error($error);
                        } else {
                            Log::info('PROFILE: Save returned object, checking database...');

                            // Direct SQL check to verify save
                            $connection = $teachersTable->getConnection();
                            $result = $connection->execute('SELECT bio, photo FROM teachers WHERE id = ?', [$instructor->id])->fetch('assoc');
                            Log::info('PROFILE: Direct DB query result: ' . json_encode($result));

                            // Force reload from database
                            $freshInstructor = $teachersTable->get($instructor->id);
                            Log::info('PROFILE: Entity after reload - bio=' . ($freshInstructor->bio ?? 'null') . ', photo=' . ($freshInstructor->photo ?? 'null'));

                            $instructor = $freshInstructor;

                            if ($isAjax) {
                                $payload = [
                                    'status' => 'ok',
                                    'instructor' => [
                                        'bio' => $instructor->bio,
                                        'specialization' => $instructor->specialization,
                                        'photo' => $instructor->photo,
                                    ],
                                ];
                                return $this->response
                                    ->withType('application/json')
                                    ->withStringBody(json_encode($payload));
                            }
                            $this->Flash->success('Đã cập nhật hồ sơ.');
                            return $this->redirect(['action' => 'profile']);
                        }
                    } catch (\Exception $e) {
                        Log::error('PROFILE: Exception during save: ' . $e->getMessage());
                        $error = 'Exception: ' . $e->getMessage();
                        if ($isAjax) {
                            return $this->response
                                ->withType('application/json')
                                ->withStatus(500)
                                ->withStringBody(json_encode(['status' => 'error', 'message' => $error]));
                        }
                        $this->Flash->error($error);
                    }
                }
            }
        }

        $this->set(compact('instructor'));

        return null;
    }

    public function availability(): ?Response
    {
        if (($r = $this->teacherGate()) !== null) {
            return $r;
        }

        $this->set('title', 'Khả dụng hàng tuần');
        $instructor = $this->getInstructorByEmail();
        if ($instructor === null) {
            $this->Flash->error('Chưa có hồ sơ giảng viên nào được liên kết với email tài khoản của bạn.');
            return $this->redirect(['action' => 'index']);
        }

        $slotsTable = $this->fetchTable('TeacherAvailability');
        $slots = $slotsTable->find()
            ->where(['teacher_id' => $instructor->id])
            ->orderBy(['day_of_week' => 'ASC', 'start_time' => 'ASC'])
            ->all();

        $this->set(compact('instructor', 'slots'));

        return null;
    }

    public function saveAvailability(): ?Response
    {
        if (($r = $this->teacherGate()) !== null) {
            return $r;
        }

        $this->request->allowMethod(['post']);
        $instructor = $this->getInstructorByEmail();
        if ($instructor === null) {
            $this->Flash->error('Chưa có hồ sơ giảng viên nào được liên kết với email tài khoản của bạn.');
            return $this->redirect(['action' => 'index']);
        }

        $slotsTable = $this->fetchTable('TeacherAvailability');
        $rows = (array) $this->request->getData('slots', []);
        $entities = [];

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $day = isset($row['day_of_week']) ? (int) $row['day_of_week'] : -1;
            $start = trim((string) ($row['start_time'] ?? ''));
            $end = trim((string) ($row['end_time'] ?? ''));
            $active = !empty($row['is_active']);
            if ($day < 0 || $day > 6 || $start === '' || $end === '') {
                continue;
            }
            if (
                !preg_match('/^(?:[01]\d|2[0-3]):(?:00|30)$/', $start)
                || !preg_match('/^(?:[01]\d|2[0-3]):(?:00|30)$/', $end)
                || $end <= $start
            ) {
                $this->Flash->error('Thời gian khả dụng phải sử dụng khoảng 30 phút và thời gian kết thúc phải sau thời gian bắt đầu.');
                return $this->redirect(['action' => 'availability']);
            }
            $entities[] = $slotsTable->newEntity([
                'teacher_id' => $instructor->id,
                'day_of_week' => $day,
                'start_time' => $start,
                'end_time' => $end,
                'is_active' => $active,
            ]);
        }

        $connection = $slotsTable->getConnection();
        $ok = $connection->transactional(function () use ($slotsTable, $instructor, $entities) {
            $slotsTable->deleteAll(['teacher_id' => $instructor->id]);
            foreach ($entities as $e) {
                if ($e->hasErrors() || !$slotsTable->save($e)) {
                    return false;
                }
            }

            return true;
        });

        if ($ok) {
            $this->Flash->success('Đã lưu khả dụng.');
        } else {
            $this->Flash->error('Không thể lưu khả dụng. Kiểm tra thời gian (bắt đầu trước khi kết thúc) và thử lại.');
        }

        return $this->redirect(['action' => 'availability']);
    }

    public function workshops(): ?Response
    {
        if (($r = $this->teacherGate()) !== null) {
            return $r;
        }

        $this->set('title', 'Hội thảo của bạn');
        $instructor = $this->getInstructorByEmail();
        if ($instructor === null) {
            $this->Flash->error('Chưa có hồ sơ giảng viên nào được liên kết với email tài khoản của bạn.');
            return $this->redirect(['action' => 'index']);
        }

        $workshops = $this->fetchTable('Workshops')->find()
            ->where(['teacher_id' => $instructor->id])
            ->orderBy(['workshop_name' => 'ASC'])
            ->all();

        // Build workshop data with slot statistics
        $workshopsData = [];
        foreach ($workshops as $workshop) {
            $slots = $this->fetchTable('TeacherAvailabilitySlots')->find()
                ->where(['workshop_id' => $workshop->id])
                ->all();

            $totalCapacity = 0;
            $totalBooked = 0;
            foreach ($slots as $slot) {
                $capacity = $slot->capacity ?? $workshop->capacity ?? 0;
                $totalCapacity += $capacity;
                $totalBooked += (int)($slot->seats_booked ?? 0);
            }

            $workshopsData[] = [
                'workshop' => $workshop,
                'totalSlots' => count($slots),
                'totalCapacity' => $totalCapacity,
                'totalBooked' => $totalBooked,
                'available' => max(0, $totalCapacity - $totalBooked),
            ];
        }

        $this->set(compact('instructor', 'workshops', 'workshopsData'));

        return null;
    }

    public function editWorkshop(?string $id = null): ?Response
    {
        if (($r = $this->teacherGate()) !== null) {
            return $r;
        }

        $instructor = $this->getInstructorByEmail();
        if ($instructor === null) {
            $this->Flash->error('Chưa có hồ sơ giảng viên nào được liên kết với email tài khoản của bạn.');
            return $this->redirect(['action' => 'index']);
        }

        $workshopsTable = $this->fetchTable('Workshops');
        $workshop = $workshopsTable->get((int) $id, contain: ['Teachers']);
        if ((int) $workshop->teacher_id !== (int) $instructor->id) {
            throw new ForbiddenException('You cannot edit this workshop.');
        }

        $this->set('title', 'Chỉnh sửa hội thảo');

        if ($this->request->is(['post', 'put', 'patch'])) {
            $data = $this->request->getData();
            if (array_key_exists('capacity', $data) && $data['capacity'] === '') {
                $data['capacity'] = null;
            }
            $workshop = $workshopsTable->patchEntity($workshop, $data, [
                'fields' => ['workshop_name', 'workshop_type', 'description', 'price', 'capacity'],
            ]);
            $workshop->teacher_id = $instructor->id;

            if ($workshopsTable->save($workshop)) {
                $this->Flash->success('Đã cập nhật hội thảo.');
                return $this->redirect(['action' => 'workshops']);
            }
            $this->Flash->error('Please fix the errors below.');
        }

        $this->set(compact('instructor', 'workshop'));

        return null;
    }

    public function messages(): ?Response
    {
        if (($r = $this->teacherGate()) !== null) {
            return $r;
        }

        $this->set('title', 'Nhắn tin');
        $instructor = $this->getInstructorByEmail();
        if ($instructor === null) {
            $this->Flash->error('Chưa có hồ sơ giảng viên nào được liên kết với email tài khoản của bạn.');
            return $this->redirect(['action' => 'index']);
        }

        $announcementsTable = $this->fetchTable('Announcements');
        $announcements = $announcementsTable->find()
            ->contain(['Workshops'])
            ->where(['Announcements.teacher_id' => $instructor->id])
            ->orderBy(['Announcements.sent_at' => 'DESC'])
            ->limit(50)
            ->all();

        $workshops = $this->fetchTable('Workshops')->find()
            ->where(['teacher_id' => $instructor->id])
            ->orderBy(['workshop_name' => 'ASC'])
            ->all();

        $this->set(compact('instructor', 'announcements', 'workshops'));

        return null;
    }

    public function sendMessage(): ?Response
    {
        if (($r = $this->teacherGate()) !== null) {
            return $r;
        }

        $this->request->allowMethod(['post']);
        $instructor = $this->getInstructorByEmail();

        if ($instructor === null) {
            $this->Flash->error('Chưa có hồ sơ giảng viên nào được liên kết với email tài khoản của bạn.');
            return $this->redirect(['action' => 'index']);
        }

        // Get & clean input
        $body = trim((string) $this->request->getData('body'));
        $subject = trim((string) $this->request->getData('subject'));
        $body = strip_tags($body); // basic sanitize
        $subject = strip_tags($subject);

        $workshopIdRaw = $this->request->getData('workshop_id');
        $workshopId = $workshopIdRaw === '' || $workshopIdRaw === null ? null : (int) $workshopIdRaw;

        // Empty check
        if ($body === '') {
            $this->Flash->error('Tin nhắn không thể để trống.');
            return $this->redirect(['action' => 'messages']);
        }

        if ($subject === '') {
            $this->Flash->error('Chủ đề không thể để trống.');
            return $this->redirect(['action' => 'messages']);
        }

        // Length limit
        if (mb_strlen($body) > 500) {
            $this->Flash->error('Tin nhắn quá dài (tối đa 500 ký tự).');
            return $this->redirect(['action' => 'messages']);
        }

        if (mb_strlen($subject) > 100) {
            $this->Flash->error('Chủ đề quá dài (tối đa 100 ký tự).');
            return $this->redirect(['action' => 'messages']);
        }

        // Basic spam pattern (aaaaaa...)
        if (preg_match('/^(.)\1{10,}$/', $body)) {
            $this->Flash->error('Tin nhắn trông giống spam.');
            return $this->redirect(['action' => 'messages']);
        }

        // Rate limit (10 seconds)
        $session = $this->request->getSession();
        $lastSent = $session->read('last_message_time');

        if ($lastSent && time() - $lastSent < 10) {
            $this->Flash->error('Vui lòng đợi vài giây trước khi gửi tin nhắn khác.');
            return $this->redirect(['action' => 'messages']);
        }

        $session->write('last_message_time', time());

        $workshop = null;
        // Validate workshop ownership
        if ($workshopId !== null) {
            $workshop = $this->fetchTable('Workshops')->find()
                ->where([
                    'id' => $workshopId,
                    'teacher_id' => $instructor->id
                ])
                ->first();

            if ($workshop === null) {
                throw new ForbiddenException('Invalid workshop for this announcement.');
            }
        }

        // Save message
        $announcementsTable = $this->fetchTable('Announcements');
        $entity = $announcementsTable->newEntity([
            'teacher_id' => $instructor->id,
            'workshop_id' => $workshopId,
            'body' => $body,
            'subject' => $subject,
            'sent_at' => DateTime::now(),
        ]);

        if ($announcementsTable->save($entity)) {
            if ($workshop !== null) {
                $this->sendAnnouncementEmails(
                    $instructor->name ?? 'Instructor',
                    $workshop->workshop_name ?? 'Workshop',
                    $subject,
                    $body,
                    $workshopId,
                    null
                );
                $this->Flash->success('Đã lưu tin nhắn và gửi email cho học viên đã đặt hội thảo này.');
            } else {
                $this->sendAnnouncementEmails(
                    $instructor->name ?? 'Instructor',
                    'All my workshops',
                    $subject,
                    $body,
                    null,
                    $instructor->id
                );
                $this->Flash->success('Đã lưu tin nhắn và gửi email cho tất cả học viên của bạn.');
            }
        } else {
            $this->Flash->error('Không thể lưu tin nhắn.');
        }

        return $this->redirect(['action' => 'messages']);
    }

    /**
     * Helper method: Send announcement emails to students
     *
     * @param string $teacherName Teacher's name
     * @param string $workshopName Workshop name
     * @param string $subject Announcement subject
     * @param string $message Announcement message
     * @param int|null $workshopId Workshop ID (null = all students who booked any of this teacher's workshops)
     * @param int|null $teacherId Teacher ID (required when workshopId is null)
     * @return void
     */
    private function sendAnnouncementEmails(
        string $teacherName,
        string $workshopName,
        string $subject,
        string $message,
        ?int $workshopId = null,
        ?int $teacherId = null
    ): void {
        try {
            $bookingsTable = $this->fetchTable('Bookings');

            if ($workshopId !== null) {
                // Get students who booked this specific workshop
                $bookings = $bookingsTable->find()
                    ->select(['user_id'])
                    ->where([
                        'workshop_id' => $workshopId,
                        'status !=' => 'cancelled'
                    ])
                    ->distinct(['user_id'])
                    ->all();
            } elseif ($teacherId !== null) {
                // Get all students who booked ANY of this teacher's workshops
                // First get all workshop IDs for this teacher
                $workshopIds = $this->fetchTable('Workshops')->find()
                    ->select(['id'])
                    ->where(['teacher_id' => $teacherId])
                    ->all()
                    ->extract('id')
                    ->toList();

                if (empty($workshopIds)) {
                    Log::info("No workshops found for teacher {$teacherId}, skipping announcement emails");
                    return;
                }

                $bookings = $bookingsTable->find()
                    ->select(['user_id'])
                    ->where([
                        'workshop_id IN' => $workshopIds,
                        'status !=' => 'cancelled'
                    ])
                    ->distinct(['user_id'])
                    ->all();
            } else {
                Log::warning("Cannot send announcement emails: both workshopId and teacherId are null");
                return;
            }

            $userIds = array_map(fn($b) => $b->user_id, $bookings->toArray());

            if (empty($userIds)) {
                Log::info("No students found to send announcement to");
                return;
            }

            // Get student emails
            $usersTable = $this->fetchTable('Users');
            $users = $usersTable->find()
                ->select(['email'])
                ->where(['id IN' => $userIds])
                ->all();

            $emails = array_map(fn($u) => $u->email, $users->toArray());

            if (empty($emails)) {
                Log::info("No email addresses found for students");
                return;
            }

            Log::info("Sending announcement to " . count($emails) . " students for teacher {$teacherName}, workshop=" . ($workshopName ?: 'All Workshops'));

            // Send emails
            $emailService = new \App\Service\EmailService();
            $sentCount = $emailService->sendTeacherAnnouncement(
                $emails,
                $teacherName,
                $workshopName,
                $subject,
                $message
            );

            Log::info("Successfully sent {$sentCount} announcement emails");
        } catch (\Exception $e) {
            \Cake\Log\Log::error("Failed to send announcement emails: {$e->getMessage()}");
        }
    }

    public function earnings(): ?Response
    {
        if (($r = $this->teacherGate()) !== null) {
            return $r;
        }

        $this->set('title', 'Thu nhập');
        $instructor = $this->getInstructorByEmail();
        if ($instructor === null) {
            $this->Flash->error('Chưa có hồ sơ giảng viên nào được liên kết với email tài khoản của bạn.');
            return $this->redirect(['action' => 'index']);
        }

        $period = (string) $this->request->getQuery('period', 'month');
        if (!in_array($period, ['month', 'quarter', 'all'], true)) {
            $period = 'month';
        }

        [$startDate, $endDate] = $this->earningsDateRange($period);

        $workshopIds = $this->fetchTable('Workshops')->find()
            ->select(['id'])
            ->where(['teacher_id' => $instructor->id])
            ->all()
            ->extract('id')
            ->toList();

        $bookingsTable = $this->fetchTable('Bookings');
        $query = $bookingsTable->find()
            ->contain(['Workshops'])
            ->where([
                'Bookings.status' => 'confirmed',
                'Bookings.workshop_id IN' => $workshopIds !== [] ? $workshopIds : [-1],
            ]);

        if ($startDate !== null) {
            $query->where(['Bookings.booking_date >=' => $startDate]);
        }
        if ($endDate !== null) {
            $query->where(['Bookings.booking_date <=' => $endDate]);
        }

        $rows = $workshopIds === [] ? [] : $query->all();
        $totalRevenue = 0.0;
        $byMonth = [];
        $byWorkshop = [];

        foreach ($rows as $b) {
            $price = (float) ($b->workshop->price ?? 0);
            $totalRevenue += $price;
            if ($b->booking_date) {
                $ym = $b->booking_date->format('Y-m');
                $byMonth[$ym] = ($byMonth[$ym] ?? 0) + $price;
            }
            $wid = (int) ($b->workshop_id ?? 0);
            $wname = (string) ($b->workshop->workshop_name ?? 'Workshop');
            if (!isset($byWorkshop[$wid])) {
                $byWorkshop[$wid] = ['name' => $wname, 'total' => 0.0, 'count' => 0];
            }
            $byWorkshop[$wid]['total'] += $price;
            $byWorkshop[$wid]['count']++;
        }

        ksort($byMonth);
        $maxMonth = $byMonth !== [] ? max($byMonth) : 0.0;

        $this->set(compact(
            'instructor',
            'period',
            'totalRevenue',
            'byMonth',
            'byWorkshop',
            'maxMonth',
            'startDate',
            'endDate'
        ));

        return null;
    }

    /**
     * @return array{0: ?FrozenDate, 1: ?FrozenDate}
     */
    private function earningsDateRange(string $period): array
    {
        $today = FrozenDate::now();
        if ($period === 'all') {
            return [null, null];
        }
        if ($period === 'quarter') {
            $start = $today->subMonths(3);

            return [$start, $today];
        }

        $start = $today->startOfMonth();

        return [$start, $today];
    }

    public function addWorkshop(): ?Response
    {
        if (($r = $this->teacherGate()) !== null) {
            return $r;
        }

        $instructor = $this->getInstructorByEmail();
        if ($instructor === null) {
            $this->Flash->error('Chưa có hồ sơ giảng viên nào được liên kết với email tài khoản của bạn.');
            return $this->redirect(['action' => 'index']);
        }

        $this->set('title', 'Hội thảo mới');
        $workshopsTable = $this->fetchTable('Workshops');
        $workshop = $workshopsTable->newEmptyEntity();

        if ($this->request->is(['post', 'put', 'patch'])) {
            $data = $this->request->getData();
            if (array_key_exists('capacity', $data) && $data['capacity'] === '') {
                $data['capacity'] = null;
            }
            $workshop = $workshopsTable->patchEntity($workshop, $data, [
                'fields' => ['workshop_name', 'workshop_type', 'description', 'price', 'capacity'],
            ]);
            $workshop->teacher_id = $instructor->id;

            if ($workshopsTable->save($workshop)) {
                $this->Flash->success('Workshop created.');
                return $this->redirect(['action' => 'workshops']);
            }
            $this->Flash->error('Please fix the errors below.');
        }

        $this->set(compact('instructor', 'workshop'));

        return null;
    }

    public function students(): ?Response
    {
        if (($r = $this->teacherGate()) !== null) {
            return $r;
        }

        $this->set('title', 'Tiến độ Học viên');
        $instructor = $this->getInstructorByEmail();
        if ($instructor === null) {
            $this->Flash->error('Chưa có hồ sơ giảng viên nào được liên kết với email tài khoản của bạn.');
            return $this->redirect(['action' => 'index']);
        }

        $workshopIds = $this->fetchTable('Workshops')->find()
            ->select(['id'])
            ->where(['teacher_id' => $instructor->id])
            ->all()
            ->extract('id')
            ->toList();

        $rows = $workshopIds === [] ? [] : $this->fetchTable('Bookings')->find()
            ->contain(['Users', 'Workshops'])
            ->where(['Bookings.workshop_id IN' => $workshopIds])
            ->orderBy(['Bookings.booking_date' => 'DESC'])
            ->all();

        $byUser = [];
        foreach ($rows as $b) {
            $uid = (int) $b->user_id;
            if (!isset($byUser[$uid])) {
                $byUser[$uid] = [
                    'user' => $b->user,
                    'bookings' => 0,
                    'confirmed' => 0,
                    'workshops' => [],
                    'attendance_marked' => 0,
                    'attendance_present' => 0,
                ];
            }
            $byUser[$uid]['bookings']++;
            if (($b->status ?? '') === 'confirmed') {
                $byUser[$uid]['confirmed']++;
            }
            $wid = (int) $b->workshop_id;
            $byUser[$uid]['workshops'][$wid] = true;
            $st = $b->attendance_status ?? '';
            if ($st !== '') {
                $byUser[$uid]['attendance_marked']++;
                if (in_array($st, ['present', 'late'], true)) {
                    $byUser[$uid]['attendance_present']++;
                }
            }
        }

        $studentRows = [];
        foreach ($byUser as $uid => $info) {
            $marked = $info['attendance_marked'];
            $studentRows[] = [
                'user' => $info['user'],
                'booking_count' => $info['bookings'],
                'confirmed_count' => $info['confirmed'],
                'distinct_workshops' => count($info['workshops']),
                'attendance_rate' => $marked > 0 ? round(100 * $info['attendance_present'] / $marked) : null,
            ];
        }

        usort($studentRows, static fn ($a, $b) => strcmp(
            (string) ($a['user']->email ?? ''),
            (string) ($b['user']->email ?? '')
        ));

        $this->set(compact('instructor', 'studentRows'));

        return null;
    }

    public function viewStudent(?string $id = null): ?Response
    {
        if (($r = $this->teacherGate()) !== null) {
            return $r;
        }

        $instructor = $this->getInstructorByEmail();
        if ($instructor === null) {
            $this->Flash->error('Chưa có hồ sơ giảng viên nào được liên kết với email tài khoản của bạn.');
            return $this->redirect(['action' => 'index']);
        }

        $workshopIds = $this->fetchTable('Workshops')->find()
            ->select(['id'])
            ->where(['teacher_id' => $instructor->id])
            ->all()
            ->extract('id')
            ->toList();

        $userId = (int) $id;
        $user = $this->fetchTable('Users')->get($userId);

        $bookings = $workshopIds === [] ? [] : $this->fetchTable('Bookings')->find()
            ->contain(['Workshops'])
            ->where([
                'Bookings.user_id' => $userId,
                'Bookings.workshop_id IN' => $workshopIds,
            ])
            ->orderBy(['Bookings.booking_date' => 'DESC'])
            ->all();

        if (count($bookings) === 0) {
            throw new ForbiddenException('You do not have access to this student record.');
        }

        $attendanceMarked = 0;
        $attendancePresent = 0;
        foreach ($bookings as $b) {
            $st = $b->attendance_status ?? '';
            if ($st !== '') {
                $attendanceMarked++;
                if (in_array($st, ['present', 'late'], true)) {
                    $attendancePresent++;
                }
            }
        }

        $this->set('title', 'Student: ' . ($user->email ?? ''));
        $confirmedCount = 0;
        $workshopKey = [];
        foreach ($bookings as $xb) {
            if (($xb->status ?? '') === 'confirmed') {
                $confirmedCount++;
            }
            $wid = (int) $xb->workshop_id;
            $workshopKey[$wid] = true;
        }
        $metrics = [
            'total_bookings' => count($bookings),
            'confirmed' => $confirmedCount,
            'distinct_workshops' => count($workshopKey),
            'attendance_marked' => $attendanceMarked,
            'attendance_present' => $attendancePresent,
            'attendance_rate' => $attendanceMarked > 0 ? round(100 * $attendancePresent / $attendanceMarked) : null,
        ];

        $this->set(compact('instructor', 'user', 'bookings', 'metrics'));

        return null;
    }

    private function legacyAttendance(): ?Response
    {
        if (($r = $this->teacherGate()) !== null) {
            return $r;
        }

        $this->set('title', 'Điểm danh');
        $instructor = $this->getInstructorByEmail();
        if ($instructor === null) {
            $this->Flash->error('Chưa có hồ sơ giảng viên nào được liên kết với email tài khoản của bạn.');
            return $this->redirect(['action' => 'index']);
        }

        $workshops = $this->fetchTable('Workshops')->find()
            ->where(['teacher_id' => $instructor->id])
            ->orderBy(['workshop_name' => 'ASC'])
            ->all();

        $workshopId = $this->request->getQuery('workshop_id');
        $sessionDateRaw = $this->request->getQuery('session_date');

        $sessionBookings = [];
        $workshopEntity = null;
        $sessionDate = null;

        if ($workshopId !== null && $workshopId !== '' && $sessionDateRaw !== null && $sessionDateRaw !== '') {
            $wid = (int) $workshopId;
            $workshopEntity = $this->fetchTable('Workshops')->find()
                ->where(['id' => $wid, 'teacher_id' => $instructor->id])
                ->first();

            $sessionDate = FrozenDate::createFromFormat('Y-m-d', (string) $sessionDateRaw);

            if ($sessionDate && !$workshopEntity) {
                $this->Flash->error('Select one of your workshops.');
            }
            if ($workshopEntity && $sessionDate) {
                $sessionBookings = $this->fetchTable('Bookings')->find()
                    ->contain(['Users'])
                    ->where([
                        'Bookings.workshop_id' => $wid,
                        'Bookings.booking_date' => $sessionDate,
                    ])
                    ->orderBy(['Users.email' => 'ASC'])
                    ->all();
            } elseif (!$sessionDate && ($sessionDateRaw !== null && $sessionDateRaw !== '')) {
                $this->Flash->error('Use a valid session date (YYYY-MM-DD).');
            }
        }

        $statusOptions = self::attendanceStatusOptions();
        $this->set(compact('instructor', 'workshops', 'workshopEntity', 'sessionDate', 'sessionBookings', 'statusOptions'));

        return null;
    }

    public function saveAttendance(): ?Response
    {
        if (($r = $this->teacherGate()) !== null) {
            return $r;
        }

        $this->request->allowMethod(['post']);
        $instructor = $this->getInstructorByEmail();

        if ($instructor === null) {
            $this->Flash->error('Chưa có hồ sơ giảng viên nào được liên kết với email tài khoản của bạn.');
            return $this->redirect(['action' => 'index']);
        }

        $workshopId = (int) $this->request->getData('workshop_id');
        $sessionDateRaw = (string) $this->request->getData('session_date');
        $sessionDate = FrozenDate::createFromFormat('Y-m-d', $sessionDateRaw);
        if (!$sessionDate) {
            $this->Flash->error('Invalid session date.');
            return $this->redirect(['action' => 'attendance']);
        }

        $workshop = $this->fetchTable('Workshops')->find()
            ->where(['id' => $workshopId, 'teacher_id' => $instructor->id])
            ->first();
        if ($workshop === null) {
            throw new ForbiddenException('Invalid workshop.');
        }

        $marks = (array) $this->request->getData('attendance', []);
        $bookingsTable = $this->fetchTable('Bookings');

        $saved = $bookingsTable->getConnection()->transactional(function () use ($bookingsTable, $marks, $workshopId, $sessionDate, $instructor) {
            foreach ($marks as $bookingIdRaw => $statusRaw) {
                $bookingId = (int) $bookingIdRaw;
                if ($bookingId < 1) {
                    continue;
                }
                $booking = $bookingsTable->get($bookingId, contain: ['Workshops']);
                if ((int) $booking->workshop_id !== $workshopId || (int) $booking->workshop->teacher_id !== (int) $instructor->id) {
                    throw new ForbiddenException('Invalid booking.');
                }
                $bd = $booking->booking_date;
                if (!$bd || $bd->format('Y-m-d') !== $sessionDate->format('Y-m-d')) {
                    throw new ForbiddenException('Booking does not match this session.');
                }

                $status = $this->normalizeAttendanceStatus((string) $statusRaw);
                $booking = $bookingsTable->patchEntity($booking, [
                    'attendance_status' => $status,
                    'attendance_updated' => DateTime::now(),
                ], ['fields' => ['attendance_status', 'attendance_updated']]);

                if (!$bookingsTable->save($booking)) {
                    return false;
                }
            }

            return true;
        });

        if ($saved) {
            $this->Flash->success('Attendance saved.');
        } else {
            $this->Flash->error('Could not save attendance.');
        }

        $q = [
            'workshop_id' => $workshopId,
            'session_date' => $sessionDateRaw,
        ];

        return $this->redirect(['action' => 'attendance', '?' => $q]);
    }

    /**
     * @return array<string, string>
     */
    private static function attendanceStatusOptions(): array
    {
        return [
            '' => '— Not recorded —',
            'present' => 'Present',
            'late' => 'Late',
            'absent' => 'Absent',
            'excused' => 'Excused',
        ];
    }

    private function normalizeAttendanceStatus(string $raw): ?string
    {
        $raw = strtolower(trim($raw));
        if ($raw === '' || $raw === 'unrecorded') {
            return null;
        }
        $allowed = ['present', 'late', 'absent', 'excused'];

        return in_array($raw, $allowed, true) ? $raw : null;
    }

    public function downloadReport(): Response
    {
        $gate = $this->teacherGate();
        if ($gate !== null) {
            return $gate;
        }

        $instructor = $this->getInstructorByEmail();
        if ($instructor === null) {
            throw new NotFoundException('Instructor profile not found.');
        }

        $this->request->allowMethod(['get']);
        $type = (string) $this->request->getQuery('type', 'bookings');
        if ($type !== 'bookings') {
            throw new NotFoundException('Unknown report type.');
        }

        $fromRaw = $this->request->getQuery('from');
        $toRaw = $this->request->getQuery('to');
        $from = $fromRaw ? FrozenDate::createFromFormat('Y-m-d', (string) $fromRaw) : null;
        $to = $toRaw ? FrozenDate::createFromFormat('Y-m-d', (string) $toRaw) : null;
        if ($fromRaw && !$from) {
            throw new NotFoundException('Invalid from date.');
        }
        if ($toRaw && !$to) {
            throw new NotFoundException('Invalid to date.');
        }

        $workshopIds = $this->fetchTable('Workshops')->find()
            ->select(['id'])
            ->where(['teacher_id' => $instructor->id])
            ->all()
            ->extract('id')
            ->toList();

        $bookingsTable = $this->fetchTable('Bookings');
        $query = $bookingsTable->find()
            ->contain(['Users', 'Workshops'])
            ->where(['Bookings.workshop_id IN' => $workshopIds !== [] ? $workshopIds : [-1]])
            ->orderBy(['Bookings.booking_date' => 'DESC', 'Bookings.id' => 'DESC']);

        if ($from) {
            $query->where(['Bookings.booking_date >=' => $from]);
        }
        if ($to) {
            $query->where(['Bookings.booking_date <=' => $to]);
        }

        $filename = 'bookings-report-' . date('Y-m-d') . '.csv';
        $response = $this->response->withType('text/csv')
            ->withHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['customer_email', 'workshop', 'booking_date', 'status', 'price']);
        $bookings = $workshopIds === [] ? [] : $query->all();
        foreach ($bookings as $b) {
            fputcsv($handle, [
                (string) ($b->user->email ?? ''),
                (string) ($b->workshop->workshop_name ?? ''),
                $b->booking_date ? $b->booking_date->format('Y-m-d') : '',
                (string) ($b->status ?? ''),
                (string) ($b->workshop->price ?? ''),
            ]);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        $this->autoRender = false;

        return $response->withStringBody($csv);
    }

    /**
     * Slot Management - List all slots for the teacher
     */
    public function slots(): ?Response
    {
        if (($r = $this->teacherGate()) !== null) {
            return $r;
        }

        $instructor = $this->getInstructorByEmail();
        if (!$instructor) {
            $this->Flash->warning('No instructor profile linked. Please contact admin.');
            return $this->redirect(['action' => 'index']);
        }

        $slotsTable = $this->fetchTable('TeacherAvailabilitySlots');
        $workshopsTable = $this->fetchTable('Workshops');

        // Get filter parameters
        $status = $this->request->getQuery('status');
        $fromDate = $this->request->getQuery('from_date');
        $toDate = $this->request->getQuery('to_date');
        $workshopId = $this->request->getQuery('workshop_id');

        $slots = $slotsTable->find('forTeacher',
            teacher_id: $instructor->id,
            status: $status ?: null,
            from_date: $fromDate ?: null,
            to_date: $toDate ?: null,
            workshop_id: $workshopId ? (int)$workshopId : null,
        )->all();

        // Get teacher's workshops for filter dropdown
        $workshops = $workshopsTable->find()
            ->where(['teacher_id' => $instructor->id])
            ->orderBy(['workshop_name' => 'ASC'])
            ->all();

        // Get slot statistics
        $stats = $slotsTable->getTeacherStats($instructor->id, $fromDate, $toDate);

        $this->set(compact('slots', 'workshops', 'stats', 'status', 'fromDate', 'toDate', 'workshopId', 'instructor'));
        $this->set('title', 'Manage Slots');

        return null;
    }

    /**
     * Create a new slot
     */
    public function createSlot(): ?Response
    {
        if (($r = $this->teacherGate()) !== null) {
            return $r;
        }

        $instructor = $this->getInstructorByEmail();
        if (!$instructor) {
            $this->Flash->warning('No instructor profile linked.');
            return $this->redirect(['action' => 'index']);
        }

        $slotsTable = $this->fetchTable('TeacherAvailabilitySlots');
        $workshopsTable = $this->fetchTable('Workshops');

        // Get teacher's workshops for dropdown
        $workshops = $workshopsTable->find()
            ->where(['teacher_id' => $instructor->id])
            ->orderBy(['workshop_name' => 'ASC'])
            ->all();

        if ($workshops->isEmpty()) {
            $this->Flash->warning('You need to have a class assigned before creating slots. Please contact admin.');
            return $this->redirect(['action' => 'slots']);
        }

        // Get company address for default location
        $companyAddress = null;
        try {
            $company = $this->fetchTable('CompanyInfos')->find()->first();
            $companyAddress = $company?->address ?? null;
        } catch (\Throwable $e) {
            $companyAddress = null;
        }

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            // Use company address as default if location not provided
            $location = $data['location'] ?? null;
            if (empty($location) && $companyAddress) {
                $location = $companyAddress;
            }
            
            $startTime = $data['start_time'] ?? null;
            $endTime   = $data['end_time']   ?? null;
            $timeLabel = ($startTime && $endTime) ? "{$startTime} to {$endTime}" : null;

            $slot = $slotsTable->newEntity([
                'teacher_id' => $instructor->id,
                'workshop_id' => $data['workshop_id'] ?? null,
                'session_date' => $data['session_date'] ?? null,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'time_label' => $timeLabel,
                'capacity' => $data['capacity'] ?? 10,
                'location' => $location,
                'notes' => $data['notes'] ?? null,
                'status' => 'available',
                'seats_booked' => 0,
                'is_active' => true,
            ]);

            if ($slotsTable->save($slot)) {
                $this->Flash->success('Slot created successfully.');
                return $this->redirect(['action' => 'slots']);
            } else {
                $errors = $slot->getErrors();
                $errorMsg = 'Failed to create slot. ';
                foreach ($errors as $field => $errs) {
                    $errorMsg .= implode(' ', $errs) . ' ';
                }
                $this->Flash->error(trim($errorMsg));
            }
        }

        $this->set(compact('workshops', 'instructor'));
        $this->set('title', 'Create Slot');

        return null;
    }

    /**
     * Edit a slot
     */
    public function editSlot(int $id): ?Response
    {
        if (($r = $this->teacherGate()) !== null) {
            return $r;
        }

        $instructor = $this->getInstructorByEmail();
        if (!$instructor) {
            $this->Flash->warning('No instructor profile linked.');
            return $this->redirect(['action' => 'index']);
        }

        $slotsTable = $this->fetchTable('TeacherAvailabilitySlots');
        
        try {
            $slot = $slotsTable->get($id, contain: ['Workshops', 'Bookings']);
        } catch (\Exception $e) {
            throw new NotFoundException('Slot not found.');
        }

        // Verify ownership
        if ($slot->teacher_id !== $instructor->id) {
            throw new ForbiddenException('You can only edit your own slots.');
        }

        // Check if slot can be edited (not cancelled, not in past with bookings)
        if ($slot->status === 'cancelled') {
            $this->Flash->error('Cannot edit cancelled slots.');
            return $this->redirect(['action' => 'slots']);
        }

        $workshopsTable = $this->fetchTable('Workshops');
        $workshops = $workshopsTable->find()
            ->where(['teacher_id' => $instructor->id])
            ->orderBy(['workshop_name' => 'ASC'])
            ->all();

        if ($this->request->is(['post', 'put', 'patch'])) {
            $data = $this->request->getData();
            
            // Don't allow editing if slot has confirmed bookings and changing critical fields
            $hasBookings = !empty(array_filter($slot->bookings ?? [], function($b) {
                return $b->status === 'confirmed';
            }));

            if ($hasBookings) {
                // Only allow editing notes and location if has bookings
                $allowedFields = ['notes', 'location'];
                foreach ($data as $field => $value) {
                    if (!in_array($field, $allowedFields)) {
                        unset($data[$field]);
                    }
                }
                $this->Flash->warning('Slot has bookings. Only notes and location can be edited.');
            }

            // Auto-generate time_label from start/end times
            if (!$hasBookings && !empty($data['start_time']) && !empty($data['end_time'])) {
                $data['time_label'] = $data['start_time'] . ' to ' . $data['end_time'];
            }

            $slotsTable->patchEntity($slot, $data);

            if ($slotsTable->save($slot)) {
                $this->Flash->success('Slot updated successfully.');
                return $this->redirect(['action' => 'slots']);
            } else {
                $errors = $slot->getErrors();
                $errorMsg = 'Failed to update slot. ';
                foreach ($errors as $field => $errs) {
                    $errorMsg .= implode(' ', $errs) . ' ';
                }
                $this->Flash->error(trim($errorMsg));
            }
        }

        $this->set(compact('slot', 'workshops', 'instructor'));
        $this->set('title', 'Edit Slot');

        return null;
    }

    /**
     * Cancel/Delete a slot
     */
    public function cancelSlot(int $id): Response
    {
        if (($r = $this->teacherGate()) !== null) {
            return $r;
        }

        $instructor = $this->getInstructorByEmail();
        if (!$instructor) {
            $this->Flash->warning('No instructor profile linked.');
            return $this->redirect(['action' => 'index']);
        }

        $slotsTable = $this->fetchTable('TeacherAvailabilitySlots');
        
        try {
            $slot = $slotsTable->get($id, contain: ['Bookings']);
        } catch (\Exception $e) {
            throw new NotFoundException('Slot not found.');
        }

        // Verify ownership
        if ($slot->teacher_id !== $instructor->id) {
            throw new ForbiddenException('You can only cancel your own slots.');
        }

        // Check if can cancel
        $canCancel = $slotsTable->canCancel($id);
        if (!$canCancel['can_cancel']) {
            $this->Flash->error($canCancel['reason']);
            return $this->redirect(['action' => 'slots']);
        }

        $reason = $this->request->getData('cancellation_reason');
        $user = $this->request->getAttribute('identity');

        if ($slotsTable->cancelSlot($id, $reason, $user->id)) {
            $this->Flash->success('Slot cancelled successfully.');
        } else {
            $this->Flash->error('Failed to cancel slot.');
        }

        return $this->redirect(['action' => 'slots']);
    }

    /**
     * Calendar view of slots
     */
    public function calendar(): ?Response
    {
        if (($r = $this->teacherGate()) !== null) {
            return $r;
        }

        $instructor = $this->getInstructorByEmail();
        if (!$instructor) {
            $this->Flash->warning('No instructor profile linked.');
            return $this->redirect(['action' => 'index']);
        }

        $slotsTable = $this->fetchTable('TeacherAvailabilitySlots');
        $workshopsTable = $this->fetchTable('Workshops');

        // Get month/year from query or default to current
        $month = (int)($this->request->getQuery('month') ?? date('m'));
        $year = (int)($this->request->getQuery('year') ?? date('Y'));

        // Calculate date range
        $firstDay = sprintf('%04d-%02d-01', $year, $month);
        $lastDay = date('Y-m-t', strtotime($firstDay));

        // Get slots for the month
        $slots = $slotsTable->find('forTeacher',
            teacher_id: $instructor->id,
            from_date: $firstDay,
            to_date: $lastDay,
        )->all();

        // Get workshops for color coding
        $workshops = $workshopsTable->find()
            ->where(['teacher_id' => $instructor->id])
            ->all();

        // Build calendar data
        $calendarData = [];
        foreach ($slots as $slot) {
            $date = $slot->session_date->format('Y-m-d');
            if (!isset($calendarData[$date])) {
                $calendarData[$date] = [];
            }
            $calendarData[$date][] = $slot;
        }

        $this->set(compact('slots', 'workshops', 'calendarData', 'month', 'year', 'firstDay', 'lastDay', 'instructor'));
        $this->set('title', 'Calendar');

        return null;
    }

    /**
     * Mark attendance for a slot
     */
    public function attendance(?int $slotId = null): ?Response
    {
        if ($slotId === null) {
            return $this->legacyAttendance();
        }

        if (($r = $this->teacherGate()) !== null) {
            return $r;
        }

        $instructor = $this->getInstructorByEmail();
        if (!$instructor) {
            $this->Flash->warning('No instructor profile linked.');
            return $this->redirect(['action' => 'index']);
        }

        $slotsTable = $this->fetchTable('TeacherAvailabilitySlots');
        $bookingsTable = $this->fetchTable('Bookings');
        $attendanceTable = $this->fetchTable('AttendanceRecords');

        try {
            $slot = $slotsTable->get($slotId, contain: ['Workshops']);
        } catch (\Exception $e) {
            throw new NotFoundException('Slot not found.');
        }

        // Verify ownership
        if ($slot->teacher_id !== $instructor->id) {
            throw new ForbiddenException('You can only manage attendance for your own slots.');
        }

        // Get confirmed bookings for this slot
        $bookings = $bookingsTable->find()
            ->contain(['Users'])
            ->where([
                'slot_id' => $slotId,
                'status' => 'confirmed',
            ])
            ->all();

        // Get existing attendance records
        $existingAttendance = collection($attendanceTable->find()
            ->where(['slot_id' => $slotId])
            ->all())
            ->indexBy('student_id')
            ->toArray();

        // Check slot timing - only allow attendance marking during or after session
        $now = new DateTime();
        $slotStart = new DateTime($slot->session_date->format('Y-m-d') . ' ' . $slot->start_time->format('H:i:s'));
        $slotEnd = new DateTime($slot->session_date->format('Y-m-d') . ' ' . $slot->end_time->format('H:i:s'));
        
        $slotNotStarted = $now < $slotStart;

        if ($this->request->is('post')) {
            // Block attendance marking if slot hasn't started
            if ($slotNotStarted) {
                $this->Flash->warning('Attendance marking is only available when the session has started or ended.');
                return $this->redirect(['action' => 'attendance', $slotId]);
            }
            
            $data = $this->request->getData('attendance');
            $user = $this->request->getAttribute('identity');

            $saved = 0;
            $errors = [];

            foreach ($data as $studentId => $attendanceData) {
                $bookingId = $attendanceData['booking_id'] ?? null;
                $status = $attendanceData['status'] ?? 'present';
                $notes = $attendanceData['notes'] ?? null;

                // Check if record exists
                if (isset($existingAttendance[$studentId])) {
                    $record = $existingAttendance[$studentId];
                    // Skip if locked
                    if ($record->is_locked) {
                        continue;
                    }
                    $record->status = $status;
                    $record->notes = $notes;
                } else {
                    $record = $attendanceTable->newEntity([
                        'slot_id' => $slotId,
                        'booking_id' => $bookingId,
                        'student_id' => $studentId,
                        'teacher_id' => $instructor->id,
                        'status' => $status,
                        'marked_at' => $now,
                        'marked_by' => $user->id,
                        'notes' => $notes,
                        'is_locked' => false,
                    ]);
                }

                if ($attendanceTable->save($record)) {
                    $saved++;
                } else {
                    $errors[] = "Failed to save attendance for student {$studentId}";
                }
            }

            if (empty($errors)) {
                $this->Flash->success("Attendance saved for {$saved} students.");
            } else {
                $this->Flash->warning("Saved {$saved} records, but had errors: " . implode(', ', $errors));
            }

            return $this->redirect(['action' => 'attendance', $slotId]);
        }

        $this->set(compact('slot', 'bookings', 'existingAttendance', 'instructor'));
        $this->set('title', 'Mark Attendance');

        return null;
    }

    /**
     * Lock attendance for a slot (prevent further edits)
     */
    public function lockAttendance(int $slotId): Response
    {
        if (($r = $this->teacherGate()) !== null) {
            return $r;
        }

        $instructor = $this->getInstructorByEmail();
        if (!$instructor) {
            $this->Flash->warning('No instructor profile linked.');
            return $this->redirect(['action' => 'index']);
        }

        $slotsTable = $this->fetchTable('TeacherAvailabilitySlots');
        $attendanceTable = $this->fetchTable('AttendanceRecords');

        try {
            $slot = $slotsTable->get($slotId);
        } catch (\Exception $e) {
            throw new NotFoundException('Slot not found.');
        }

        if ($slot->teacher_id !== $instructor->id) {
            throw new ForbiddenException('You can only lock attendance for your own slots.');
        }

        $locked = $attendanceTable->lockAttendanceForSlot($slotId);
        $this->Flash->success("Attendance locked for {$locked} records.");

        return $this->redirect(['action' => 'attendance', $slotId]);
    }

}
