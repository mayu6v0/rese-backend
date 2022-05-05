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

    //ユーザーが認証されていない
    public function test_owner_reservation_not_authenticated()
    {
        $response = $this->get('/api/owner/reservation');
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    //ユーザーがメール認証されていない
    public function test_owner_reservation_not_email_verified()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        $response = $this->actingAs($user)->get('/api/owner/reservation');
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    //ユーザー認証されているがowner権限がない
    public function test_index_owner_reservation_unauthorized()
    {
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

    // owner権限のあるuserが認証されている
    public function test_index_owner_reservation()
    {
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
}


