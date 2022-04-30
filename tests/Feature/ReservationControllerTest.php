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
    public function test_index_reservation()
    {
        // ユーザーが認証されている
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

    public function test_index_reservation_unauthorized()
    {
        // ユーザーが認証されていない
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $item = Reservation::factory()->for($restaurant)->for($user)->create();
        $response = $this->get('/api/reservation');
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    public function test_store_reservation()
    {
        //ユーザーが認証されている
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

    public function test_store_reservation_Not_authenticated()
    {
        //ユーザーが認証されていない
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $data = [
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
            'datetime' => now()->addMinutes(60)->format('Y-m-d H:i'),
            'number' => 10
        ];
        $response = $this->post('/api/reservation', $data);
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    public function test_destroy_reservation()
    {
        // ユーザーが認証されている
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $item = Reservation::factory()->for($restaurant)->for($user)->create();
        $response = $this->actingAs($user)->delete('/api/reservation/' . $item->id);
        $response->assertStatus(200);
        $this->assertDeleted($item);
    }

    public function test_destroy_reservation_Not_authenticated()
    {
        // ユーザーが認証されていない
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $item = Reservation::factory()->for($restaurant)->for($user)->create();
        $response = $this->delete('/api/reservation/' . $item->id);
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }
}
