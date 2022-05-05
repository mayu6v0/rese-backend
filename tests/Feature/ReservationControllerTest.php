<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Restaurant;
use App\Models\Area;
use App\Models\Genre;
use App\Models\User;
use App\Models\Reservation;

class ReservationControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    //ユーザーが認証されていない
    public function test_index_reservation_not_authenticated()
    {
        $response = $this->get('/api/reservation');
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    //ユーザーがメール認証されていない
    public function test_index_reservation_not_email_verified()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get('/api/reservation');
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }


    //ユーザーがメール認証されている場合の各メソッドの確認

    //index
    public function test_index_reservation()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $item = Reservation::factory()->for($restaurant)->for($user)->create();
        $response = $this->actingAs($user)->get('/api/reservation');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'user_id' => $item->user_id,
            'restaurant_id' => $item->restaurant_id,
            'datetime' => $item->datetime.':00',
            'number' => $item->number
        ]);
    }

    //store
    public function test_store_reservation()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $data = [
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
            'datetime' => now()->addMinutes(60)->format('Y-m-d H:i'),
            'number' => 10
        ];
        $response = $this->actingAs($user)->post('/api/reservation', $data);
        $response->assertStatus(201);
        $response->assertJsonFragment($data);
        $this->assertDatabaseHas('reservations', $data);
    }

    //store
    //バリデーションが通らない場合
    public function test_store_reservation_failed_validation()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $data = [
            'user_id' => '',//未入力
            'restaurant_id' => $restaurant->id,
            'datetime' => now()->addMinutes(60)->format('Y-m-d H:i:s'),//フォーマット違い
            'number' => 10
        ];
        $response = $this->actingAs($user)->post('/api/reservation', $data);
        $response->assertStatus(302);
    }


    //update
    public function test_update_reservation()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $item = Reservation::factory()->for($restaurant)->for($user)->create();

        $data = [
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
            'datetime' => now()->addMinutes(60)->format('Y-m-d H:i'),
            'number' => 10
        ];

        $response = $this->actingAs($user)->put('/api/reservation/' . $item->id, $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('reservations', $data);
        $response->assertJsonFragment([
            'message' => 'Updated successfully'
        ]);
    }

    //update
    //バリデーションが通らない場合
    public function test_update_failed_validation()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $item = Reservation::factory()->for($restaurant)->for($user)->create();

        $data = [
            'user_id' => $user->id,
            'restaurant_id' => '',//未入力
            'datetime' => now()->addMinutes(60)->format('Y-m-d H:i'),
            'number' => 'not_integer'//整数でない
        ];

        $response = $this->actingAs($user)->put('/api/reservation/' . $item->id, $data);
        $response->assertStatus(302);
    }

    //update
    //パラメータに一致するデータがない場合
    public function test_update_no_data()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $item = Reservation::factory()->for($restaurant)->for($user)->create();

        $data = [
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
            'datetime' => now()->addMinutes(60)->format('Y-m-d H:i'),
            'number' => 10
        ];

        $response = $this->actingAs($user)->put('/api/reservation/1', $data);
        $response->assertStatus(404);
    }

    //destroy
    public function test_destroy_reservation()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $item = Reservation::factory()->for($restaurant)->for($user)->create();
        $response = $this->actingAs($user)->delete('/api/reservation/' . $item->id);
        $response->assertStatus(200);
        $this->assertDeleted($item);
        $response->assertJsonFragment([
            'message' => 'Deleted successfully'
        ]);
    }

    //destory
    //パラメータに一致するデータがない場合
    public function test_destroy_reservation_no_data()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete('/api/reservation/1');
        $response->assertStatus(404);
    }
}
