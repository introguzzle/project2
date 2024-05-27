<?php

namespace Tests\Unit;

use App\Models\User\Profile;
use PHPUnit\Framework\TestCase;

class ProfileTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Мокаем модель User, чтобы она не взаимодействовала с реальной базой данных
        $this->user = $this->getMockBuilder(Profile::class)
            ->getMock();
    }

    public function test_get_id()
    {
        $this->user->method('getKey')->willReturn(1);

        $this->assertEquals(1, $this->user->getId());
    }

    public function test_magic_get()
    {
        $class = new class extends Profile {
            protected $attributes = [
                'created_at' => 'John',
                'updated_at' => 'Doe'
            ];
        };

        $profile = new $class();

        $this->assertEquals('John', $profile->createdAt);
        $this->assertEquals('Doe', $profile->updatedAt);
    }
}
