<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Tests\TestCase;

class userServiceTest extends TestCase
{
    protected $userService;

    public function setUp(): void
    {
        parent::setUp();
        $this->userService = $this->app->make(UserService::class);

    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $data = ['email' => 'qwert11111y@gmail.com', 'password' => 'Qwerty1235', 'confirm_password' => 'Qwerty1235'];
        $createdUser = $this->userService->createUser($data);
        $this->assertInstanceOf(User::class, $createdUser);

    }
}
