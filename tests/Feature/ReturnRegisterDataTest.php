<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReturnRegisterDataTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
    }

    /** @test */
    public function registerTest()
    {
        $data = [
            'email' => 'qwerty2@gmail.com',
            'password' => 'Qwerty1235',
            'confirm_password' => 'Qwerty1235',
        ];
        $response = $this->post('/api/auth/users', $data);
        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    /** @test */
    public function loginTest()
    {
        $user = User::factory()->create([
            'email' => 'qwerty2@gmail.com',
            'password' => bcrypt($password = 'Qwerty1235')
        ]);
        $data = [
            'email' => $user->email,
            'password' => $password,
        ];
        $response = $this->post('/api/auth/login', $data);
        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }
}
