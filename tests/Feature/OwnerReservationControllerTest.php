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

class OwnerReservationControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index_ownerreservation()
    {
        // owner権限のあるuserが認証されている
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $item = Reservation::factory()->for($restaurant)->for($user)->create();
        $owner = User::factory()->create([
            'authority' => 'owner',
            'restaurant_id' => $item->restaurant_id
        ]);
        $response = $this->actingAs($owner)->get('/api/owner/reservation?restaurant_id=' . $item->restaurant_id);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'user_id' => $item->user_id,
            'restaurant_id' => $item->restaurant_id,
            'datetime' => $item->datetime.':00',
            'number' => $item->number
        ]);
    }

    public function test_index_ownerreservation_unauthorized()
    {
        // ユーザー認証されているがowner権限がない
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $item = Reservation::factory()->for($restaurant)->for($user)->create();
        $owner = User::factory()->create([
            'authority' => 'owner',
            'restaurant_id' => $item->restaurant_id
        ]);
        $response = $this->actingAs($user)->get('/api/owner/reservation?restaurant_id=' . $item->restaurant_id);
        $response->assertStatus(403);
        $response->assertJsonFragment([
            'message' => '権限がありません'
        ]);
    }

    public function test_index_ownerreservation_Not_authenticated()
    {
        // ユーザー認証されていない
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $item = Reservation::factory()->for($restaurant)->for($user)->create();
        $owner = User::factory()->create([
            'authority' => 'owner',
            'restaurant_id' => $item->restaurant_id
        ]);
        $response = $this->get('/api/owner/reservation?restaurant_id=' . $item->restaurant_id);
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }
}


