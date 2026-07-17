<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class ContactControllerTest extends TestCase
{
    use IntegrationTestTrait;

    public function testSubmitSuccess(): void
    {
        $this->enableCsrfToken();
        $this->post('/contact', [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '0400000000',
            'subject' => 'Classes',
            'message' => 'Hello',
        ]);
        $this->assertResponseSuccess();
        $this->assertFlashMessageContains('Thank you for your message');
    }

    public function testSubmitValidationEmpty(): void
    {
        $this->enableCsrfToken();
        $this->post('/contact', [
            'full_name' => '',
            'email' => 'test@example.com',
            'phone' => '1',
            'subject' => 'x',
            'message' => 'y',
        ]);
        $this->assertResponseSuccess();
        $this->assertFlashMessageContains('Please fill in all fields');
    }
}
