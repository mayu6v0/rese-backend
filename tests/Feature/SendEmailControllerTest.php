<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;


class SendEmailControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_sendmail_authorized()
    {
        //admin権限のあるユーザーが認証されている
        $admin = User::factory()->create([
            'authority' => 'admin',
        ]);
        //メール送信先となるユーザーを作成
        $users = User::factory()->count(5)->create();
        $data = [
            'mailTitle' => 'メールのタイトル',
            'mailText' => 'メールの本文',
        ];
        $response = $this->actingAs($admin)->post('/api/sendmail', $data);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message'=> 'メール送信に成功しました'
        ]);
    }

    public function test_sendmail_unauthorized()
    {
        //ユーザー認証されているがadmin権限がない
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
}

