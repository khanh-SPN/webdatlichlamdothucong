<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\ForbiddenException;
use Cake\Log\Log;
use Cake\ORM\Exception\RecordNotFoundException;

class AdminController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Authentication.Authentication');
    }

    // Require admin role
    private function checkAdmin()
    {
        $user = $this->request->getAttribute('identity');

        if (!$user || $user->role !== 'admin') {
            Log::error('Unauthorized access to admin');
            throw new ForbiddenException('Chỉ dành cho Quản trị viên');
        }
    }

    // ================= DASHBOARD =================
    public function index()
    {
        $this->checkAdmin();

        $this->viewBuilder()->setTemplatePath('Pages/Admin');
        $this->viewBuilder()->setTemplate('index');

        $usersTable     = $this->fetchTable('Users');
        $enquiriesTable = $this->fetchTable('Enquiries');
        $teachersTable  = $this->fetchTable('Teachers');
        $workshopsTable = $this->fetchTable('Workshops');
        $bookingsTable  = $this->fetchTable('Bookings');

        $company = $this->fetchTable('CompanyInfos')->find()->first() ?? $this->fetchTable('CompanyInfos')->newEmptyEntity();

        $this->set([
            'totalUsers'       => $usersTable->find()->count(),
            'totalEnquiries'   => $enquiriesTable->find()->count(),
            'pending'          => $enquiriesTable->find()->where(['status' => 'pending'])->count(),
            'totalTeachers'    => $teachersTable->find()->count(),
            'totalWorkshops'   => $workshopsTable->find()->count(),
            'pendingBookings'  => $bookingsTable->find()->where(['status' => 'pending'])->count(),
            'pendingEnquiries' => $enquiriesTable->find()->where(['status' => 'pending'])->count(),
            'company'          => $company,
        ]);
    }

    // ================= ENQUIRIES =================
    public function enquiries()
    {
        $this->checkAdmin();

        $this->viewBuilder()->setTemplatePath('Pages/Admin');
        $this->viewBuilder()->setTemplate('enquiries');

        $enquiriesTable = $this->fetchTable('Enquiries');

        $enquiries = $enquiriesTable->find()
            ->orderBy(['created' => 'DESC'])
            ->all();

        $this->set(compact('enquiries'));
    }

    public function deleteEnquiry($id)
    {
        $this->checkAdmin();

        $enquiries = $this->fetchTable('Enquiries');
        $e = $enquiries->get($id);

        if ($enquiries->delete($e)) {
            $this->Flash->success('Đã xóa yêu cầu thành công');
            Log::debug("Deleted enquiry ID: $id");
        } else {
            $this->Flash->error('Xóa thất bại');
            Log::error("Delete enquiry failed: $id");
        }

        return $this->redirect('/admin/enquiries');
    }

    // ================= USERS =================
    public function users()
    {
        $this->checkAdmin();

        $this->viewBuilder()->setTemplatePath('Pages/Admin');
        $this->viewBuilder()->setTemplate('users');

        $usersTable = $this->fetchTable('Users');

        $users = $usersTable->find()
            ->contain(['Customers'])
            ->orderBy(['Users.id' => 'DESC'])
            ->all();

        $this->set(compact('users'));
    }

    public function addUser()
    {
        $this->checkAdmin();

        $this->viewBuilder()->setTemplatePath('Pages/Admin');
        $this->viewBuilder()->setTemplate('user_form');

        $usersTable = $this->fetchTable('Users');
        $customersTable = $this->fetchTable('Customers');
        $user = $usersTable->newEmptyEntity();
        $user->role = 'customer';
        $customer = $customersTable->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $roleInput = (string) ($data['role'] ?? 'customer');
            $role = in_array($roleInput, ['admin', 'teacher', 'customer'], true) ? $roleInput : 'customer';

            if (($data['password'] ?? '') !== ($data['confirm_password'] ?? '')) {
                $this->Flash->error('Mật khẩu không khớp.');
                $user = $usersTable->patchEntity($user, $data, [
                    'fields' => ['email', 'password'],
                    'validate' => false,
                ]);
                $user->role = $role;
                $fullName = trim((string) ($data['full_name'] ?? ''));
                $phone = trim((string) ($data['phone'] ?? ''));
                $address = trim((string) ($data['address'] ?? ''));
                $customer = $customersTable->patchEntity($customer, [
                    'name' => $fullName,
                    'phone' => $phone,
                    'address' => $address !== '' ? $address : null,
                ], ['validate' => false]);
                $this->set(compact('user', 'customer'));

                return;
            }

            $user = $usersTable->patchEntity($user, $data, [
                'fields' => ['email', 'password'],
                'validate' => 'register',
            ]);
            $user->role = $role;

            if ($user->hasErrors()) {
                $this->Flash->error('Vui lòng sửa các lỗi bên dưới và thử lại.');
                $fullName = trim((string) ($data['full_name'] ?? ''));
                $phone = trim((string) ($data['phone'] ?? ''));
                $address = trim((string) ($data['address'] ?? ''));
                $customer = $customersTable->patchEntity($customersTable->newEmptyEntity(), [
                    'name' => $fullName,
                    'phone' => $phone,
                    'address' => $address !== '' ? $address : null,
                ], ['validate' => false]);
                $this->set(compact('user', 'customer'));

                return;
            }

            if ($role === 'customer') {
                $fullName = trim((string) ($data['full_name'] ?? ''));
                $phone = trim((string) ($data['phone'] ?? ''));
                $address = trim((string) ($data['address'] ?? ''));

                $customer = $customersTable->patchEntity($customersTable->newEmptyEntity(), [
                    'name' => $fullName,
                    'phone' => $phone,
                    'address' => $address !== '' ? $address : null,
                ], [
                    'validate' => 'register',
                ]);

                if ($customer->hasErrors()) {
                    $this->Flash->error('Vui lòng kiểm tra chi tiết liên hệ cho tài khoản khách hàng.');
                    $this->set(compact('user', 'customer'));

                    return;
                }

                $connection = $usersTable->getConnection();
                $saved = $connection->transactional(function () use ($usersTable, $customersTable, $user, $customer) {
                    if (!$usersTable->save($user)) {
                        return false;
                    }
                    $customer->user_id = $user->id;

                    return (bool) $customersTable->save($customer);
                });

                if ($saved) {
                    $this->Flash->success('Đã tạo người dùng thành công.');
                    Log::debug('Admin created user ID: ' . $user->id);

                    return $this->redirect(['action' => 'users']);
                }

                $this->Flash->error('Không thể tạo người dùng. Vui lòng thử lại.');
                Log::error('Admin addUser save failed: ' . json_encode($user->getErrors()) . json_encode($customer->getErrors()));
            } elseif (in_array($role, ['admin', 'teacher'], true)) {
                if ($usersTable->save($user)) {
                    $this->Flash->success('Đã tạo người dùng thành công.');
                    Log::debug('Admin created ' . $role . ' user ID: ' . $user->id);

                    return $this->redirect(['action' => 'users']);
                }

                $this->Flash->error('Không thể tạo người dùng. Vui lòng thử lại.');
                Log::error('Admin addUser staff save failed: ' . json_encode($user->getErrors()));
            }
        }

        $this->set(compact('user', 'customer'));
    }

    public function deleteUser($id)
    {
        $this->checkAdmin();

        $id = (int) $id;
        $users = $this->fetchTable('Users');
        $customersTable = $this->fetchTable('Customers');
        $connection = $users->getConnection();

        $deleted = $connection->transactional(function () use ($users, $customersTable, $id) {
            $customersTable->deleteAll(['user_id' => $id]);
            $user = $users->get($id);

            return $users->delete($user);
        });

        if ($deleted) {
            $this->Flash->success('Đã xóa người dùng');
            Log::debug("Deleted user ID: $id");
        } else {
            $this->Flash->error('Xóa thất bại');
            Log::error("Delete user failed: $id");
        }

        return $this->redirect('/admin/users');
    }
    public function unlockUser($id)
    {
        $this->checkAdmin();

        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->get($id);

        $user->failed_login_attempts = 0;
        $user->last_failed_login = null;

        if ($usersTable->save($user)) {
            $this->Flash->success('Tài khoản người dùng đã được mở khóa thành công.');
            Log::info("Unlocked user ID: {$id}");
        } else {
            $this->Flash->error('Không thể mở khóa tài khoản người dùng.');
            Log::error("Unlock user failed: ID {$id}");
        }

        return $this->redirect('/admin/users');
    }

    public function markReplied($id)
    {
        $this->request->allowMethod(['post']);

        $enquiriesTable = $this->fetchTable('Enquiries');

        $enquiry = $enquiriesTable->get($id);
        $enquiry->status = 'replied';

        if ($enquiriesTable->save($enquiry)) {
            $this->Flash->success('Đã đánh dấu là đã trả lời');
        } else {
            $this->Flash->error('Failed');
        }

        return $this->redirect(['action' => 'enquiries']);
    }
    // ================= TEACHERS =================

// ================= TEACHERS LIST =================
    public function teachers()
    {
        $this->checkAdmin();

        $this->viewBuilder()->setTemplatePath('Pages/Admin');
        $this->viewBuilder()->setTemplate('teachers');

        $teachersTable = $this->fetchTable('Teachers');

        $teachers = $this->paginate($teachersTable);

        $this->set(compact('teachers'));
    }

    // ================= ADD =================
    public function addTeacher()
    {
        $this->checkAdmin();

        $teachersTable = $this->fetchTable('Teachers');
        $teacher = $teachersTable->newEmptyEntity();

        if ($this->request->is('post')) {
            $teacher = $teachersTable->patchEntity($teacher, $this->request->getData());

            if ($teachersTable->save($teacher)) {
                $this->Flash->success('Đã tạo giáo viên thành công');
            } else {
                $errors = $teacher->getErrors();
                $errorMsg = 'Không thể tạo giáo viên';
                if (!empty($errors)) {
                    $firstError = reset($errors);
                    if (is_array($firstError)) {
                        $firstError = reset($firstError);
                    }
                    $errorMsg .= ': ' . $firstError;
                }
                $this->Flash->error($errorMsg);
                Log::error('Add teacher failed: ' . json_encode($errors));
            }

            return $this->redirect(['action' => 'teachers']);
        }

        // GET request - redirect to teachers list since add is handled via modal
        return $this->redirect(['action' => 'teachers']);
    }

    // ================= EDIT =================
    public function editTeacher($id = null)
    {
        $this->checkAdmin();

        $teachersTable = $this->fetchTable('Teachers');

        try {
            $teacher = $teachersTable->get($id);
        } catch (RecordNotFoundException $e) {
            $this->Flash->error('Không tìm thấy giáo viên');
            return $this->redirect(['action' => 'teachers']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $teacher = $teachersTable->patchEntity($teacher, $this->request->getData());

            if ($teachersTable->save($teacher)) {
                $this->Flash->success('Đã cập nhật giáo viên thành công');
            } else {
                $errors = $teacher->getErrors();
                $errorMsg = 'Không thể cập nhật giáo viên';
                if (!empty($errors)) {
                    $firstError = reset($errors);
                    if (is_array($firstError)) {
                        $firstError = reset($firstError);
                    }
                    $errorMsg .= ': ' . $firstError;
                }
                $this->Flash->error($errorMsg);
                Log::error('Edit teacher failed for ID ' . $id . ': ' . json_encode($errors));
            }

            return $this->redirect(['action' => 'teachers']);
        }

        // GET request - redirect to teachers list since edit is handled via modal
        return $this->redirect(['action' => 'teachers']);
    }

    // ================= DELETE =================
    public function deleteTeacher($id = null)
    {
        $this->checkAdmin();

        $this->request->allowMethod(['post', 'delete']);

        $teachersTable = $this->fetchTable('Teachers');

        try {
            $teacher = $teachersTable->get($id);
        } catch (RecordNotFoundException $e) {
            $this->Flash->error('Không tìm thấy giáo viên');
            return $this->redirect(['action' => 'teachers']);
        }

        if ($teachersTable->delete($teacher)) {
            $this->Flash->success('Đã xóa giáo viên thành công');
        } else {
            $this->Flash->error('Không thể xóa giáo viên');
        }

        return $this->redirect(['action' => 'teachers']);
    }
    // ================= WORKSHOPS =================

    public function workshops()
    {
        $this->checkAdmin();

        $workshopsTable = $this->fetchTable('Workshops');

        $workshops = $this->paginate(
            $workshopsTable->find()->contain(['Teachers'])
        );

        
        $teachers = $this->fetchTable('Teachers')
            ->find('list', keyField: 'id', valueField: 'name')
            ->toArray();

        $this->set(compact('workshops','teachers'));

        $this->viewBuilder()->setTemplatePath('Pages/Admin');
        $this->viewBuilder()->setTemplate('workshops');
    }
    public function addWorkshop()
    {
        $this->checkAdmin();

        $workshopsTable = $this->fetchTable('Workshops');
        $workshop = $workshopsTable->newEmptyEntity();

        if ($this->request->is('post')) {
            $workshop = $workshopsTable->patchEntity($workshop, $this->request->getData());

            if ($workshopsTable->save($workshop)) {
                $this->Flash->success('Workshop created successfully');
            } else {
                $errors = $workshop->getErrors();
                $errorMsg = 'Failed to create workshop';
                if (!empty($errors)) {
                    $firstError = reset($errors);
                    if (is_array($firstError)) {
                        $firstError = reset($firstError);
                    }
                    $errorMsg .= ': ' . $firstError;
                }
                $this->Flash->error($errorMsg);
                Log::error('Add workshop failed: ' . json_encode($errors));
            }

            return $this->redirect(['action'=>'workshops']);
        }

        // GET request - redirect to workshops list since add is handled via modal
        return $this->redirect(['action'=>'workshops']);
    }
    public function editWorkshop($id)
    {
        $this->checkAdmin();

        $workshopsTable = $this->fetchTable('Workshops');

        try {
            $workshop = $workshopsTable->get($id);
        } catch (RecordNotFoundException $e) {
            $this->Flash->error('Workshop not found');
            return $this->redirect(['action'=>'workshops']);
        }

        if ($this->request->is(['post','put','patch'])) {
            $workshop = $workshopsTable->patchEntity($workshop, $this->request->getData());

            if ($workshopsTable->save($workshop)) {
                $this->Flash->success('Workshop updated successfully');
            } else {
                $errors = $workshop->getErrors();
                $errorMsg = 'Failed to update workshop';
                if (!empty($errors)) {
                    $firstError = reset($errors);
                    if (is_array($firstError)) {
                        $firstError = reset($firstError);
                    }
                    $errorMsg .= ': ' . $firstError;
                }
                $this->Flash->error($errorMsg);
                Log::error('Edit workshop failed for ID ' . $id . ': ' . json_encode($errors));
            }

            return $this->redirect(['action'=>'workshops']);
        }

        // GET request - redirect to workshops list since edit is handled via modal
        return $this->redirect(['action'=>'workshops']);
    }
    public function deleteWorkshop($id)
    {
        $this->checkAdmin();

        $this->request->allowMethod(['post','delete']);

        $workshopsTable = $this->fetchTable('Workshops');
        $workshop = $workshopsTable->get($id);

        if ($workshopsTable->delete($workshop)) {
            $this->Flash->success('Deleted');
        } else {
            $this->Flash->error('Failed');
        }

        return $this->redirect(['action'=>'workshops']);
    }

    // ================= WORKSHOP SLOTS MANAGER =================
    // NOTE: Slot management moved to teacher portal - teachers manage their own slots
    public function manageWorkshopSlots($workshopId)
    {
        $this->checkAdmin();
        
        $this->Flash->info('Slot management has been moved to the teacher portal. Teachers can now create and manage their own session slots directly from their dashboard.');
        return $this->redirect(['action' => 'workshops']);
    }

    // NOTE: Slot management moved to teacher portal
    public function saveWorkshopSlots($workshopId)
    {
        $this->checkAdmin();
        $this->request->allowMethod(['post']);
        
        $this->Flash->info('Slot management has been moved to the teacher portal. Teachers can now create and manage their own session slots directly from their dashboard.');
        return $this->redirect(['action' => 'workshops']);
    }

    // NOTE: Slot management moved to teacher portal
    public function deleteWorkshopSlot($slotId)
    {
        $this->checkAdmin();
        $this->request->allowMethod(['post', 'delete']);
        
        $this->Flash->info('Slot management has been moved to the teacher portal. Teachers can now create and manage their own session slots directly from their dashboard.');
        return $this->redirect(['action' => 'workshops']);
    }

 // ================= MATERIAL =================
    public function materials()
    {
        $this->checkAdmin();

        $materialsTable = $this->fetchTable('Materials');

        $materials = $this->paginate(
            $materialsTable->find()->contain(['Workshops'])
        );

        $workshops = $this->fetchTable('Workshops')
            ->find('list', keyField: 'id', valueField: 'workshop_name')
            ->toArray();

        $this->set(compact('materials','workshops'));

        $this->viewBuilder()->setTemplatePath('Pages/Admin');
        $this->viewBuilder()->setTemplate('materials');
    }

    public function addMaterial()
    {
        $this->checkAdmin();

        $table = $this->fetchTable('Materials');
        $entity = $table->newEmptyEntity();

        if ($this->request->is('post')) {
            $entity = $table->patchEntity($entity, $this->request->getData());

            if ($table->save($entity)) {
                $this->Flash->success('Material created');
            } else {
                $this->Flash->error('Create failed');
            }
        }

        return $this->redirect(['action'=>'materials']);
    }

    public function editMaterial($id)
    {
        $this->checkAdmin();

        $table = $this->fetchTable('Materials');
        $entity = $table->get($id);

        if ($this->request->is(['post','put','patch'])) {
            $entity = $table->patchEntity($entity, $this->request->getData());

            if ($table->save($entity)) {
                $this->Flash->success('Updated');
            } else {
                $this->Flash->error('Update failed');
            }
        }

        return $this->redirect(['action'=>'materials']);
    }

    public function deleteMaterial($id)
    {
        $this->checkAdmin();

        $this->request->allowMethod(['post','delete']);

        $table = $this->fetchTable('Materials');
        $entity = $table->get($id);

        if ($table->delete($entity)) {
            $this->Flash->success('Deleted');
        } else {
            $this->Flash->error('Xóa thất bại');
        }

        return $this->redirect(['action'=>'materials']);
    }
    public function company()
    {
        $this->checkAdmin();

        $table = $this->fetchTable('CompanyInfos');

        
        $company = $table->find()->first();

        if (!$company) {
            $company = $table->newEmptyEntity();
        }

        if ($this->request->is(['post','put'])) {
            $company = $table->patchEntity($company, $this->request->getData());

            if ($table->save($company)) {
                $this->Flash->success('Company info saved successfully');
            } else {
                $this->Flash->error('Failed to save');
            }
        }

        $this->set(compact('company'));

        $this->viewBuilder()->setTemplatePath('Pages/Admin');
        $this->viewBuilder()->setTemplate('company');
    }
    public function faqs()
    {
        $this->checkAdmin();

        $table = $this->fetchTable('Faqs');

        $faqs = $this->paginate(
            $table->find()->orderBy(['display_order'=>'ASC'])
        );

        $this->set(compact('faqs'));

        $this->viewBuilder()->setTemplatePath('Pages/Admin');
        $this->viewBuilder()->setTemplate('faqs');
    }

    public function addFaq()
    {
        $this->checkAdmin();

        $table = $this->fetchTable('Faqs');
        $entity = $table->newEmptyEntity();

        if ($this->request->is('post')) {
            $entity = $table->patchEntity($entity, $this->request->getData());

            if ($table->save($entity)) {
                $this->Flash->success('FAQ added successfully');
            } else {
                $errors = $entity->getErrors();
                $errorMsg = 'Failed to add FAQ';
                if (!empty($errors)) {
                    $firstError = reset($errors);
                    if (is_array($firstError)) {
                        $firstError = reset($firstError);
                    }
                    $errorMsg .= ': ' . $firstError;
                }
                $this->Flash->error($errorMsg);
                Log::error('Add FAQ failed: ' . json_encode($errors));
            }

            return $this->redirect(['action'=>'faqs']);
        }

        // GET request - redirect to faqs list since add is handled via modal
        return $this->redirect(['action'=>'faqs']);
    }

    public function editFaq($id)
    {
        $this->checkAdmin();

        $table = $this->fetchTable('Faqs');

        try {
            $entity = $table->get($id);
        } catch (RecordNotFoundException $e) {
            $this->Flash->error('FAQ not found');
            return $this->redirect(['action'=>'faqs']);
        }

        if ($this->request->is(['post','put','patch'])) {
            $entity = $table->patchEntity($entity, $this->request->getData());

            if ($table->save($entity)) {
                $this->Flash->success('FAQ updated successfully');
            } else {
                $errors = $entity->getErrors();
                $errorMsg = 'Failed to update FAQ';
                if (!empty($errors)) {
                    $firstError = reset($errors);
                    if (is_array($firstError)) {
                        $firstError = reset($firstError);
                    }
                    $errorMsg .= ': ' . $firstError;
                }
                $this->Flash->error($errorMsg);
                Log::error('Edit FAQ failed for ID ' . $id . ': ' . json_encode($errors));
            }

            return $this->redirect(['action'=>'faqs']);
        }

        // GET request - redirect to faqs list since edit is handled via modal
        return $this->redirect(['action'=>'faqs']);
    }

    public function deleteFaq($id)
    {
        $this->checkAdmin();

        $this->request->allowMethod(['post','delete']);

        $table = $this->fetchTable('Faqs');
        $entity = $table->get($id);

        if ($table->delete($entity)) {
            $this->Flash->success('Deleted');
        } else {
            $this->Flash->error('Xóa thất bại');
        }

        return $this->redirect(['action'=>'faqs']);
    }
    public function bookings()
    {
        $this->checkAdmin();

        $bookings = $this->paginate(
            $this->fetchTable('Bookings')
                ->find()
                ->contain(['Users','Workshops','Payments'])
        );

        $this->set(compact('bookings'));

        $this->viewBuilder()->setTemplatePath('Pages/Admin');
        $this->viewBuilder()->setTemplate('bookings');
    }

    public function updateBooking($id, $status)
    {
        $this->checkAdmin();

        // Validate status values
        $validStatuses = ['pending', 'confirmed', 'cancelled'];
        if (!in_array($status, $validStatuses, true)) {
            $this->Flash->error('Invalid status value');
            return $this->redirect(['action' => 'bookings']);
        }

        $table = $this->fetchTable('Bookings');
        $booking = $table->get($id);

        $oldStatus = $booking->status;
        $booking->status = $status;

        if ($table->save($booking)) {
            $this->Flash->success("Booking status updated from {$oldStatus} to {$status}");
            Log::info("Admin updated booking {$id} status from {$oldStatus} to {$status}");
        } else {
            $this->Flash->error('Failed to update booking status');
            Log::error("Failed to update booking {$id} status");
        }

        return $this->redirect(['action' => 'bookings']);
    }

    // NOTE: Slot management moved to teacher portal
    public function teacherAvailabilitySlots()
    {
        $this->checkAdmin();
        
        $this->Flash->info('Slot management has been moved to the teacher portal. Teachers can now create and manage their own session slots directly from their dashboard.');
        return $this->redirect(['action' => 'workshops']);
    }

    // NOTE: Slot management moved to teacher portal
    public function deleteTeacherAvailabilitySlot(?string $id = null)
    {
        $this->checkAdmin();
        $this->request->allowMethod(['post', 'delete']);
        
        $this->Flash->info('Slot management has been moved to the teacher portal. Teachers can now create and manage their own session slots directly from their dashboard.');
        return $this->redirect(['action' => 'workshops']);
    }
}
