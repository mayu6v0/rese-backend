<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    //正常にログインできる
    public function test_login()
    {
        $user = User::factory()->create();

        $response = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $credentials = request(['email', 'password']);
        $this->assertAuthenticated();
        $response->assertStatus(200);
    }

    public function test_login_with_invalid_password()
    {
        //間違ったパスワードの場合ログインできないことを確認
        $user = User::factory()->create();

        $response = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertStatus(401);
        $response->assertJsonFragment([
            'error' => 'Unauthorized'
        ]);
    }
}
