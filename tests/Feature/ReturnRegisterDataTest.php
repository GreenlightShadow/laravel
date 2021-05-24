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
        $response = $this->post('/api/auth/users', $data);
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
        $response = $this->post('/api/auth/login', $data);
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
        ];
        Mail::fake();
        $response = $this->post('/api/auth/reset', $data);
        Mail::to($user->email)->send(new ResetMail('hrenkjfle3ilhnl43423gblb423', $data['email']));
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
        $response = $this->post('/api/auth/update', $data);
        $response->assertStatus(200);
    }
}
