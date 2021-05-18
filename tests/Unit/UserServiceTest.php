<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;
    protected $userService;

    public function setUp(): void
    {
        parent::setUp();
        $this->userService = $this->app->make(UserService::class);

    }

    public function createNewUser()
    {
        $data = ['email' => 'qwert11111y@gmail.com', 'password' => 'Qwerty1235', 'confirm_password' => 'Qwerty1235'];
        $createdUser = $this->userService->createUser($data);
        $this->assertInstanceOf(User::class, $createdUser);

    }
}
