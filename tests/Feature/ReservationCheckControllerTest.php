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
    public function test_reservation_check_authorized()
    {
        // owner権限のあるuserが認証されている
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

    public function test_reservation_check_unauthorized()
    {
        // ユーザー認証されているがowner権限がない
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

    public function test_reservation_check_Not_authenticated()
    {
        // ユーザー認証されていない
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
}