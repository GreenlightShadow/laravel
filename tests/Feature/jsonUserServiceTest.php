<?php

namespace Tests\Feature;

use Tests\TestCase;

class jsonUserServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $data = [
            'email' => 'qwerty2211@gmail.com',
            'password' => 'Qwerty1235',
            'confirm_password' => 'Qwerty1235'
        ];
        $response = $this->post('/api/users', $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(['token']);
    }
}
