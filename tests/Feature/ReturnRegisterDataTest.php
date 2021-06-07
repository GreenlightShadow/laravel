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
        $response = $this->post('/api/register', $data);
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
        $reset = ResetPassword::factory()->create();
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
            'name' => 'Qwertyqweqwe',
            'email' => 'qwerty2@gmail.com',
        ]);
        $data = [
            'name' => 'Qrreewert',
            'email' => 'qwerty112@gmail.com',
        ];
        $this->actingAs($user, 'api');
        $response = $this->put('/api/auth/update/'.$user->id, $data);
        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
        $user->refresh();
        $this->assertEquals($user->name, $data['name']);
        $this->assertEquals($user->email, $data['email']);
    }
    /** @test */
    public function getUsersTest()
    {
        $count = 5;
        $users = User::factory()->count($count)->create();
        $user = $users->first();
        $this->actingAs($user, 'api');
        $response = $this->get('/api/auth/users');
        $response->assertStatus(200);
        $response->assertJsonStructure(['users']);
        $this->assertCount($count, $users);
    }
    /** @test */
    public function getUserDataTest()
    {
        $user = User::factory()->create([
            'name' => 'Qwertyqweqwe',
            'email' => 'qwerty2@gmail.com',
        ]);
        $this->actingAs($user, 'api');
        $response = $this->get('/api/auth/users/'.$user->id);
        $response->assertStatus(200);
        $response->assertJsonStructure(['user']);
    }
    /** @test */
    public function rejectGetUserData(){
        $user = User::factory()->create([
            'name' => 'Qwertyqweqwe',
            'email' => 'qwerty2@gmail.com',
        ]);
        $anotherUser = User::factory()->create([
            'name' => 'Qwertyqw33321we',
            'email' => 'q1112werty2@gmail.com',
        ]);
        $this->actingAs($anotherUser, 'api');
        $response = $this->get('/api/auth/users/'.$user->id);
        $response->assertStatus(403);
    }

    /** @test */
    public function sendMessageTest()
    {
        $user = User::factory()->create([
            'name' => 'Qwertyqweqwe',
            'email' => 'qwerty2@gmail.com',
        ]);
        $anotherUser = User::factory()->create([
            'name' => 'Qwertyqw33321we',
            'email' => 'q1112werty2@gmail.com',
        ]);
        $data = [
            'email' => $anotherUser->email,
            'message' => 'Hello world',
        ];
        $this->actingAs($user, 'api');
        $response = $this->post('/api/auth/messages/', $data);
        $response->assertStatus(201);
    }
}
