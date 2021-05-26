<?php

namespace Tests\Feature;

use App\Mail\ResetMail;
use App\Models\ResetPassword;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
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
        $response = $this->post('/api/users', $data);
        $response->assertStatus(201);
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
        $response = $this->post('/api/login', $data);
        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }
    /** @test */
    public function resetPasswordTest()
    {
        $user = User::factory()->create([
            'email' => 'qwerty2@gmail.com',
        ]);
        $data = [
            'email' => 'qwerty2@gmail.com',
            'token' => 'hrenkjfle3ilhnl43423gblb423'
        ];
        Mail::fake();
        $response = $this->post('/api/reset', $data);
        Mail::to($user->email)->send(new ResetMail($data['token'], $data['email']));
        $response->assertStatus(200);
        Mail::assertSent(ResetMail::class);
    }
    /** @test */
    public function updatePasswordTest()
    {

        $user = User::factory()->create([
            'id' => '41',
            'email' => 'qwerty2@gmail.com',
            'password' => bcrypt($password = 'Qwerty1235')
        ]);
        $reset = ResetPassword::factory()->create([
            'user_id' => '41',
            'token' => 'hrenkjfle3ilhnl43423gblb423',
            'created_at' => '2021-05-24 11:35:27',
        ]);
        $data = [
            'token' => $reset->token,
            'password' => $password,
            ];
        $response = $this->post('/api/update', $data);
        $response->assertStatus(200);
    }
    /** @test */
    public function updateTest()
    {
        $user = User::factory()->create([
            'id' => 40,
            'name' => 'Qwertyqweqwe',
            'email' => 'qwerty2@gmail.com',
        ]);
        $data = [
            'name' => 'Qrreewert',
            'email' => 'qwerty112@gmail.com',
        ];
        $this->actingAs($user, 'api');
        $response = $this->put('/api/auth/users/40', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(['token']);
    }
}
