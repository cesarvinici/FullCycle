<?php

namespace Tests\Unit\Domain\Notification;

use Core\Domain\Notification\Notification;
use PHPUnit\Framework\TestCase;

class NotificationUnitTest extends TestCase
{
    public function testGetErrors()
    {
        $notification = new Notification();
        $errors = $notification->getErrors();

        $this->assertIsArray($errors);
    }

    public function testAddError()
    {
        $notification = new Notification();
        $notification->addError([
            'context' => 'video',
            'message' => 'Error message'
        ]);

        $errors = $notification->getErrors();

        $this->assertCount(1, $errors);
        $this->assertEquals('Error message', $errors[0]['message']);
    }

    public function testHasErrors()
    {
        $notification = new Notification();
        $this->assertFalse($notification->hasErrors());
    }

    public function testHasErrorsTrue()
    {
        $notification = new Notification();
        $notification->addError([
            'context' => 'video',
            'message' => 'Error message'
        ]);

        $this->assertTrue($notification->hasErrors());
    }

    public function testMessages()
    {
        $notification = new Notification();

        $notification->addError([
            'context' => 'video',
            'message' => 'Error message'
        ]);

        $notification->addError([
            'context' => 'video',
            'message' => 'Error message 2'
        ]);

        $message = $notification->messages();

        $this->assertIsString($message);
        $this->assertEquals("video: Error message\nvideo: Error message 2\n", $message);
    }

    public function testMessagesByContext()
    {
        $notification = new Notification();

        $notification->addError([
            'context' => 'video',
            'message' => 'Error message'
        ]);

        $notification->addError([
            'context' => 'category',
            'message' => 'Error message 2'
        ]);

        $message = $notification->messages('category');
        $this->assertCount(2, $notification->getErrors());
        $this->assertEquals("category: Error message 2\n", $message);
    }
}
