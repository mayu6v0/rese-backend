<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_register()
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

    //バリデーションを通らない場合
    public function test_register_failed_validation()
    {
        $data = [
            'name' => '',//未入力
            'email' => 'test@example',//email
            'password' => 'passwor',//文字数制限
            'authority' => 'user'
        ];
        $response = $this->post('/api/auth/register', $data);
        $response->assertStatus(302);
    }

    //ownerもしくはadmin権限のあるユーザーを作成する
    //admin権限がある
    public function test_owner_and_admin_register()
    {
        $admin = User::factory()->create([
            'authority' => 'admin',
        ]);

        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'authority' => 'owner'
        ];

        $response = $this->actingAs($admin)->post('/api/auth/register', $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => null,
            // 'password' => Hash::make($data->password),
            'remember_token' => null,
            'authority' => 'owner',
            'restaurant_id' => null
        ]);
        $response->assertJsonFragment([
            'message' => 'Successfully user create'
        ]);
    }

    //ownerもしくはadmin権限のあるユーザーを作成する
    //admin権限がない
    public function test_owner_and_admin_register_unauthorized()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'authority' => 'owner'
        ];

        $response = $this->actingAs($user)->post('/api/auth/register', $data);
        $response->assertStatus(403);
        $response->assertJsonFragment([
            'message' => '権限がありません'
        ]);
    }
}
