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

     //ユーザーが認証されていない場合アクセスできない
    public function test_favorite_not_authenticated_users_can_not_access()
    {
        $response = $this->get('/api/favorite');
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    //ユーザーがメール認証されていない場合アクセスできない
    public function test_favorite_not_email_verified_users_can_not_access()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get('/api/favorite');
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }


    //ユーザーがメール認証されている場合の各メソッドの確認

    //index
    public function test_index_favorite()
    {
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

    //store
    public function test_store_favorite()
    {
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


    //store
    //バリデーションが通らない場合
    public function test_store_favorite_failed_validation()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $data = [
            'user_id' => '',//未入力
            'restaurant_id' => 'not_integer',// 整数でない
        ];
        $response = $this->actingAs($user)->post('/api/favorite', $data);
        $response->assertStatus(302);
    }

    //destroy
    public function test_destroy_favorite()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $item = Favorite::factory()->for($restaurant)->for($user)->create();

        $response = $this->actingAs($user)->delete('/api/favorite/' . $item->id);

        $response->assertStatus(200);
        $this->assertDeleted($item);
        $response->assertJsonFragment([
            'message' => 'Deleted successfully'
        ]);
    }

    //destroy
    //パラメータに一致するデータがない場合
    public function test_destroy_favorite_no_data()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete('/api/favorite/' . 1);

        $response->assertStatus(404);
    }
}