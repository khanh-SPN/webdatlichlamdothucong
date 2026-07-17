<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class CompanyInfosTableTest extends TestCase
{
    protected array $fixtures = [
        'app.CompanyInfos',
    ];

    public function testPhoneValidationAcceptsCommonFormats(): void
    {
        $table = TableRegistry::getTableLocator()->get('CompanyInfos');

        $ok = [
            '+61 412 345 678',
            '+61412345678',
            '0412 345 678',
            '(03) 9123 4567',
            '03 9123 4567',
        ];

        foreach ($ok as $phone) {
            $e = $table->newEntity([
                'name' => 'Test Co',
                'email' => 'test@example.com',
                'phone' => $phone,
            ]);
            $this->assertFalse($e->hasErrors(), 'Expected no validation errors for: ' . $phone);
        }
    }

    public function testPhoneValidationRejectsBadFormats(): void
    {
        $table = TableRegistry::getTableLocator()->get('CompanyInfos');

        $bad = [
            'abc-not-a-phone',
            '++61 412 345 678',
            '+61 41', // too short
            '1234567890123456789012345', // too long
        ];

        foreach ($bad as $phone) {
            $e = $table->newEntity([
                'name' => 'Test Co',
                'email' => 'test@example.com',
                'phone' => $phone,
            ]);
            $this->assertTrue($e->hasErrors(), 'Expected validation errors for: ' . $phone);
            $this->assertArrayHasKey('phone', $e->getErrors());
        }
    }
}
