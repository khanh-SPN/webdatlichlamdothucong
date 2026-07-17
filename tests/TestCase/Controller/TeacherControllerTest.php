<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * Instructor portal (TeacherController) integration tests.
 */
class TeacherControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Users',
        'app.Teachers',
        'app.Workshops',
        'app.Bookings',
        'app.Announcements',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->enableCsrfToken();
    }

    /**
     * Session identity for the Authentication Session authenticator (key: Auth).
     *
     * @param array<string, mixed> $extra
     * @return void
     */
    private function loginAsTeacher(array $extra = []): void
    {
        $this->session([
            'Auth' => array_merge([
                'id' => 2,
                'email' => 'teacher@example.com',
                'role' => 'teacher',
            ], $extra),
        ]);
    }

    public function testDashboardRedirectsWhenNotLoggedIn(): void
    {
        $this->get('/teacher');
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/pages/login');
    }

    public function testDashboardOkForTeacher(): void
    {
        $this->loginAsTeacher();
        $this->get('/teacher');
        $this->assertResponseOk();
        $this->assertResponseContains('Instructor hub');
    }

    public function testClassManagementList(): void
    {
        $this->loginAsTeacher();
        $this->get('/teacher/workshops');
        $this->assertResponseOk();
        $this->assertResponseContains('Your workshops');
        $this->assertResponseContains('Create workshop');
    }

    public function testCreateClassForm(): void
    {
        $this->loginAsTeacher();
        $this->get('/teacher/workshops/add');
        $this->assertResponseOk();
        $this->assertResponseContains('Create workshop');
    }

    public function testCreateClassPost(): void
    {
        $this->loginAsTeacher();
        $this->post('/teacher/workshops/add', [
            'workshop_name' => 'Wheel Throwing 101',
            'workshop_type' => 'Pottery',
            'description' => 'Intro workshop',
            'price' => '55.00',
            'capacity' => '8',
        ]);
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/teacher/workshops');

        $workshops = $this->getTableLocator()->get('Workshops');
        $created = $workshops->find()->where(['workshop_name' => 'Wheel Throwing 101'])->first();
        $this->assertNotNull($created);
        $this->assertSame(1, (int) $created->teacher_id);
    }

    public function testStudentProgressList(): void
    {
        $this->loginAsTeacher();
        $this->get('/teacher/students');
        $this->assertResponseOk();
        $this->assertResponseContains('Student progress');
        $this->assertResponseContains('customer@example.com');
    }

    public function testStudentDetail(): void
    {
        $this->loginAsTeacher();
        $this->get('/teacher/students/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('customer@example.com');
        $this->assertResponseContains('Total bookings');
    }

    public function testStudentDetailDeniedWhenNoSharedBookings(): void
    {
        $this->loginAsTeacher();
        $this->get('/teacher/students/view/2');
        $this->assertResponseCode(403);
    }

    public function testAttendancePageLoads(): void
    {
        $this->loginAsTeacher();
        $this->get('/teacher/attendance');
        $this->assertResponseOk();
        $this->assertResponseContains('Class attendance');
    }

    public function testAttendanceMarkPresent(): void
    {
        $this->loginAsTeacher();
        $this->post('/teacher/attendance/save', [
            'lesson_id' => '1',
            'session_date' => '2026-06-01',
            'attendance' => [
                '1' => 'present',
            ],
        ]);
        $this->assertResponseCode(302);

        $bookings = $this->getTableLocator()->get('Bookings');
        $b = $bookings->get(1);
        $this->assertSame('present', $b->attendance_status);
    }

    public function testSendAnnouncement(): void
    {
        $this->loginAsTeacher();
        $this->post('/teacher/messages/send', [
            'body' => 'Studio closed on Sunday.',
            'lesson_id' => '',
        ]);
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/teacher/messages');

        $ann = $this->getTableLocator()->get('Announcements');
        $row = $ann->find()->where(['body' => 'Studio closed on Sunday.'])->first();
        $this->assertNotNull($row);
        $this->assertSame(1, (int) $row->teacher_id);
    }
}
