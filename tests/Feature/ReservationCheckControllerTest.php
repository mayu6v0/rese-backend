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
use Illuminate\Support\Facades\URL;


class ReservationCheckControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    // ユーザー認証されていない
    public function test_reservation_check_not_authenticated()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $reservation = Reservation::factory()->for($restaurant)->for($user)->create();
        $signed_url = URL::signedRoute('reservation.check', ['reservation_id' => $reservation->id]);
        $response = $this->get($signed_url);
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    // ユーザー認証されているがowner権限がない
    public function test_reservation_check_unauthorized()
    {
        $user_not_owner = User::factory()->create();
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $reservation = Reservation::factory()->for($restaurant)->for($user)->create();
        $signed_url = URL::signedRoute('reservation.check', ['reservation_id' => $reservation->id]);
        $response = $this->actingAs($user_not_owner)->get($signed_url);
        $response->assertStatus(403);
        $response->assertJsonFragment([
            'message' => '権限がありません'
        ]);
    }

    // owner権限のあるuserが認証されている
    public function test_reservation_check_authorized()
    {
        $owner = User::factory()->create([
            'authority' => 'owner',
        ]);
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $reservation = Reservation::factory()->for($restaurant)->for($user)->create();
        $signed_url = URL::signedRoute('reservation.check', ['reservation_id' => $reservation->id]);
        $response = $this->actingAs($owner)->get($signed_url);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'user_id' => $reservation->user_id,
            'restaurant_id' => $reservation->restaurant_id,
            'datetime' => $reservation->datetime . ':00',
            'number' => $reservation->number
        ]);
    }

    // owner権限のあるuserが認証されている
    // 照合URLが異なる
    public function test_reservation_check_authorized_with_wrong_URL()
    {
        $owner = User::factory()->create([
            'authority' => 'owner',
        ]);
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $reservation = Reservation::factory()->for($restaurant)->for($user)->create();
        $signed_url = URL::signedRoute('reservation.check', ['reservation_id' => 'wrong-id']);
        $response = $this->actingAs($owner)->get($signed_url);
        $response->assertStatus(404);
        $response->assertJsonFragment([
            'message' => 'Not found'
        ]);
        // $response->assertJsonFragment([
        //     'user_id' => $reservation->user_id,
        //     'restaurant_id' => $reservation->restaurant_id,
        //     'datetime' => $reservation->datetime . ':00',
        //     'number' => $reservation->number
        // ]);
    }
}