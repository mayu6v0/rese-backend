<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;


class SendEmailControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    //ユーザーが認証されていない
    public function test_sendmail_not_authenticated()
    {
        $response = $this->post('/api/sendmail');
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    //ユーザーがメール認証されていない
    public function test_sendmail_not_email_verified()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        $response = $this->actingAs($user)->post('/api/sendmail');
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    //ユーザー認証されているがadmin権限がない
    public function test_sendmail_unauthorized()
    {
        $user = User::factory()->create();
        //メール送信先となるユーザーを作成
        $users = User::factory()->count(5)->create();
        $data = [
            'mailTitle' => 'メールのタイトル',
            'mailText' => 'メールの本文',
        ];
        $response = $this->actingAs($user)->post('/api/sendmail', $data);
        $response->assertStatus(403);
        $response->assertJsonFragment([
            'message' => '権限がありません'
        ]);
    }

    // admin権限のあるユーザーが認証されている

    //メール送信先が'user'
    public function test_sendmail_authorized_to_user()
    {
        $admin = User::factory()->create([
            'authority' => 'admin',
        ]);
        //メール送信先となるユーザーを作成
        $users = User::factory()->count(5)->create(
        );
        $data = [
            'mailTitle' => 'メールのタイトル',
            'mailText' => 'メールの本文',
            'mailTo' => 'user'
        ];
        $response = $this->actingAs($admin)->post('/api/sendmail', $data);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message'=> 'メール送信に成功しました'
        ]);
    }

    //メール送信先が'owner'
    public function test_sendmail_authorized_to_owner()
    {
        $admin = User::factory()->create([
            'authority' => 'admin',
        ]);
        //メール送信先となるユーザーを作成
        $users = User::factory()->count(5)->create([
            'authority' => 'owner'
        ]);
        $data = [
            'mailTitle' => 'メールのタイトル',
            'mailText' => 'メールの本文',
            'mailTo' => 'owner'
        ];
        $response = $this->actingAs($admin)->post('/api/sendmail', $data);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'メール送信に成功しました'
        ]);
    }

    //メール送信先が'admin'
    public function test_sendmail_authorized_to_admin()
    {
        $admin = User::factory()->create([
            'authority' => 'admin',
        ]);
        //メール送信先となるユーザーを作成
        $users = User::factory()->count(5)->create([
            'authority' => 'admin'
        ]);
        $data = [
            'mailTitle' => 'メールのタイトル',
            'mailText' => 'メールの本文',
            'mailTo' => 'admin'
        ];
        $response = $this->actingAs($admin)->post('/api/sendmail', $data);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'メール送信に成功しました'
        ]);
    }
}

