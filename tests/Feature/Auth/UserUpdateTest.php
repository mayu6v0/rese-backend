<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Area;
use App\Models\Genre;


class UserUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    //usersテーブルにrestaurant_idを挿入する
    public function test_user_update()
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $update = [
            'restaurant_id' => $restaurant->id,
        ];

        $response = $this->actingAs($user)->put('/api/auth/update', $update);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'Updated successfully',
        ]);
    }
}
