<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;


class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'authority' => 'user'
        ];
        $response = $this->post('/api/auth/register', $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users',[
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => null,
            // 'password' => Hash::make($data->password),
            'remember_token' => null,
            'authority' => 'user',
            'restaurant_id' => null
        ]);
        $response->assertJsonFragment([
            'message' => 'Successfully user create'
        ]);
    }
}
