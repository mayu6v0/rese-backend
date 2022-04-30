<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Restaurant;
use App\Models\Area;
use App\Models\Genre;
use App\Models\User;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Contracts\Auth\Authenticatable;

class RestaurantControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_index_restaurant()
    {
        $item = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $response = $this->get('/api/restaurant');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $item->name,
            'area_id' => $item->area_id,
            'genre_id' => $item->genre_id,
            'overview' => $item->overview,
            'image_url' => $item->image_url,
        ]);
    }

    public function test_show_restaurant()
    {
        $item = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $response = $this->get('/api/restaurant/' . $item->id);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $item->name,
            'area_id' => $item->area_id,
            'genre_id' => $item->genre_id,
            'overview' => $item->overview,
            'image_url' => $item->image_url,
        ]);
    }

    public function test_store_restaurant_authorized()
    {
        // owner権限のあるuserが認証されている
        $owner = User::factory()->create([
            'authority' => 'owner',
        ]);
        $area = Area::factory()->create();
        $genre = Genre::factory()->create();
        $data = [
            'name' => '新規店舗',
            'area_id' => $area->id,
            'genre_id' => $genre->id,
            'overview' => '店舗の概要',
            'image_url' => 'http://example.com',
        ];
        $response = $this->actingAs($owner)->post('/api/restaurant', $data);
        $response->assertStatus(201);
        $response->assertJsonFragment($data);
        $this->assertDatabaseHas('restaurants', $data);
    }

    public function test_store_restaurant_unauthorized()
    {
        //ユーザー認証されているがowner権限がない
        $user = User::factory()->create();
        $area = Area::factory()->create();
        $genre = Genre::factory()->create();
        $data = [
            'name' => '新規店舗',
            'area_id' => $area->id,
            'genre_id' => $genre->id,
            'overview' => '店舗の概要',
            'image_url' => 'http://example.com',
        ];
        $response = $this->actingAs($user)->post('/api/restaurant', $data);
        $response->assertStatus(403);
        $response->assertJsonFragment([
            'message' => '権限がありません'
        ]);
    }

    public function test_store_restaurant_Not_authenticated()
    {
        //ユーザーが認証されていない
        $area = Area::factory()->create();
        $genre = Genre::factory()->create();
        $data = [
            'name' => '新規店舗',
            'area_id' => $area->id,
            'genre_id' => $genre->id,
            'overview' => '店舗の概要',
            'image_url' => 'http://example.com',
        ];
        $response = $this->post('/api/restaurant', $data);
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

}
