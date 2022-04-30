<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Restaurant;
use App\Models\Area;
use App\Models\Genre;
use App\Models\User;
use App\Models\Favorite;


class FavoriteControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index_favorite()
    {
        //ユーザーが認証されている
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $item = Favorite::factory()->for($restaurant)->for($user)->create();
        $response = $this->actingAs($user)->get('/api/favorite');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'user_id' => $item->user_id,
            'restaurant_id' => $item->restaurant_id,
        ]);
    }

    public function test_index_favorite_unauthorized()
    {
        //ユーザーが認証されていない
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $item = Favorite::factory()->for($restaurant)->for($user)->create();
        $response = $this->get('/api/favorite');
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    public function test_store_favorite()
    {
        //ユーザーが認証されている
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $data = [
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
        ];
        $response = $this->actingAs($user)->post('/api/favorite', $data);
        $response->assertStatus(201);
        $response->assertJsonFragment($data);
        $this->assertDatabaseHas('favorites', $data);
    }

    public function test_store_favorite_unauthorized()
    {
        //ユーザーが認証されていない
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $data = [
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
        ];
        $response = $this->post('/api/favorite', $data);
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    public function test_destroy_favorite()
    {
        //ユーザーが認証されている
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $item = Favorite::factory()->for($restaurant)->for($user)->create();
        $response = $this->actingAs($user)->delete('/api/favorite/' . $item->id);
        $response->assertStatus(200);
        $this->assertDeleted($item);
    }

    public function test_destroy_favorite_unauthorized()
    {
        //ユーザーが認証されていない
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $item = Favorite::factory()->for($restaurant)->for($user)->create();
        $response = $this->delete('/api/favorite/' . $item->id);
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }
}


