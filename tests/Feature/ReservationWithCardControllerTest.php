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

class ReservationWithCardControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    //ユーザーが認証されていない場合アクセスできない
    public function test_store_reservation_with_card_not_authenticated_users_can_not_access()
    {
        $response = $this->post('/api/reservation-card');
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    //ユーザーがメール認証されていない場合アクセスできない
    public function test_store_reservation_with_card_not_email_verified_users_can_not_access()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->post('/api/reservation-card');
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    //ユーザーがメール認証されている場合のメソッドの確認

    //store
    public function test_store_reservation_with_card()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $data = [
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
            'datetime' => now()->addMinutes(60)->format('Y-m-d H:i'),
            'number' => 10
        ];
        $response = $this->actingAs($user)->post('/api/reservation-card', $data);
        $this->assertDatabaseHas('reservations', $data);
    }

    //store
    //バリデーションが通らない場合
    public function test_store_reservation_failed_validation()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $data = [
            'user_id' => '', //未入力
            'restaurant_id' => $restaurant->id,
            'datetime' => now()->addMinutes(60)->format('Y-m-d H:i:s'), //フォーマット違い
            'number' => 10
        ];
        $response = $this->actingAs($user)->post('/api/reservation', $data);
        $response->assertStatus(302);
    }
}
