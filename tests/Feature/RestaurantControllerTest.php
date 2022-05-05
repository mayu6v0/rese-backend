<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Restaurant;
use App\Models\Area;
use App\Models\Genre;
use App\Models\User;

class RestaurantControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */


     //index
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

    //show
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

    //show
    //パラメータに一致するデータがない場合
    public function test_show_restaurant_no_data()
    {
        $item = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $response = $this->get('/api/restaurant/1');
        $response->assertStatus(404);
    }

    //store
    //ユーザーが認証されていない
    public function test_store_restaurant_not_authenticated()
    {
        $response = $this->post('/api/restaurant');
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    //store
    //ユーザーがメール認証されていない
    public function test_store_restaurant_not_email_verified()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->post('/api/restaurant');
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    //store
    //ユーザー認証されているがowner権限がない
    public function test_store_restaurant_unauthorized()
    {
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

    //owner権限のあるuserが認証されている
    //store
    public function test_store_restaurant_authorized()
    {
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

    //store
    //バリデーションが通らない場合
    public function test_store_restaurant_failed_validation()
    {
        $owner = User::factory()->create([
            'authority' => 'owner',
        ]);
        $area = Area::factory()->create();
        $genre = Genre::factory()->create();
        $data = [
            'name' => '',//未入力
            'area_id' => $area->id,
            'genre_id' => $genre->id,
            'overview' => '店舗の概要',
            'image_url' => 'http://example.com',
        ];
        $response = $this->actingAs($owner)->post('/api/restaurant', $data);
        $response->assertStatus(302);
    }

    //update
    //ユーザーが認証されていない
    public function test_update_restaurant_not_authenticated()
    {
        $item = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();

        $data = [
            'name' => 'update',
            'area_id' => $item->area_id,
            'genre_id' => $item->genre_id,
            'overview' => 'update',
            'image_url' => 'http://example.com-update',
        ];

        $response = $this->put('/api/restaurant/'. $item->id, $data );
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    //update
    //ユーザーがメール認証されていない
    public function test_update_restaurant_not_email_verified()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $item = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();

        $data = [
            'name' => 'update',
            'area_id' => $item->area_id,
            'genre_id' => $item->genre_id,
            'overview' => 'update',
            'image_url' => 'http://example.com-update',
        ];

        $response = $this->actingAs($user)->put('/api/restaurant/' . $item->id, $data);
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    // update
    // ユーザー認証されているがowner権限がない
    public function test_update_restaurant_unauthorized()
    {
        $user = User::factory()->create();

        $item = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();

        $data = [
            'name' => 'update',
            'area_id' => $item->area_id,
            'genre_id' => $item->genre_id,
            'overview' => 'update',
            'image_url' => 'http://example.com-update',
        ];
        $response = $this->actingAs($user)->put('/api/restaurant/' . $item->id, $data);
        $response->assertStatus(403);
        $response->assertJsonFragment([
            'message' => '権限がありません'
        ]);
    }

    //owner権限のあるuserが認証されている
    //update
    public function test_update_restaurant_authorized()
    {
        $owner = User::factory()->create([
            'authority' => 'owner',
        ]);
        $item = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();

        $data = [
            'name' => 'update',
            'area_id' => $item->area_id,
            'genre_id' => $item->genre_id,
            'overview' => 'update',
            'image_url' => 'http://example.com-update',
        ];
        $response = $this->actingAs($owner)->put('/api/restaurant/'.$item->id, $data);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message' => 'Updated successfully',
        ]);
        $this->assertDatabaseHas('restaurants', $data);
    }

    // update
    // バリデーションが通らない場合
    public function test_update_restaurant_failed_validation()
    {
        $owner = User::factory()->create([
            'authority' => 'owner',
        ]);
        $item = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();

        $data = [
            'name' => '',//未入力
            'area_id' => $item->area_id,
            'genre_id' => $item->genre_id,
            'overview' => 'update',
            'image_url' => 'http://example.com-update',
        ];
        $response = $this->actingAs($owner)->put('/api/restaurant/' . $item->id, $data);
        $response->assertStatus(302);
    }

    //update
    //パラメータに一致するデータがない場合
    public function test_update_restaurant_no_data()
    {
        $owner = User::factory()->create([
            'authority' => 'owner',
        ]);
        $item = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();

        $data = [
            'name' => 'update',
            'area_id' => $item->area_id,
            'genre_id' => $item->genre_id,
            'overview' => 'update',
            'image_url' => 'http://example.com-update',
        ];
        $response = $this->actingAs($owner)->put('/api/restaurant/1', $data);
        $response->assertStatus(404);
    }
}
