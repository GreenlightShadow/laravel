<?php

namespace Tests\Feature;

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

    public function tokenReturning()
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
}
