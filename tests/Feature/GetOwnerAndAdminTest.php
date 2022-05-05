<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class GetOwnerAndAdminTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    //admin権限のあるユーザー
    //get_owner
    public function test_get_owner_authenticated()
    {
        $user = User::factory()->create([
            'authority' => 'admin'
        ]);
        $owner = User::factory()->create([
            'authority' => 'owner'
        ]);
        $response = $this->actingAs($user)->get('/api/auth/owner');
        $response->assertStatus(200);
        // $response->assertJsonFragment([
        //     'data' => $owner
        // ]);
    }

    //get_admin
    public function test_get_admin_authenticated()
    {
        $user = User::factory()->create([
            'authority' => 'admin'
        ]);
        $admin = User::factory()->create([
            'authority' => 'admin'
        ]);
        $response = $this->actingAs($user)->get('/api/auth/admin');
        $response->assertStatus(200);
        // $response->assertJsonFragment([
        //     'data' => $admin
        // ]);
    }
}