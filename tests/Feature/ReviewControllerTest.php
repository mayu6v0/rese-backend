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
use App\Models\Review;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    //ユーザーが認証されていない場合アクセスできない
    public function test_review_not_authenticated_users_can_not_access()
    {
        $response = $this->get('/api/review');
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    //ユーザーがメール認証されていない場合アクセスできない
    public function test_review_not_email_verified_users_can_not_access()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        $response = $this->actingAs($user)->get('/api/review');
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    //ユーザーがメール認証されている場合の各メソッドの確認

    //index
    public function test_index_review()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $reservation = Reservation::factory()->for($restaurant)->for($user)->create();
        $review = Review::factory()->for($restaurant)->for($reservation)->for($user)->create();
        $response = $this->actingAs($user)->get('/api/review');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'user_id' => $review->user_id,
            'restaurant_id' => $review->restaurant_id,
            'reservation_id' => $review->reservation_id,
            'rating' => $review->rating,
            'title' => $review->title,
            'review'=> $review->review,
        ]);
    }


    //store
    public function test_store_review()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $reservation = Reservation::factory()->for($restaurant)->for($user)->create();
        $data = [
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
            'reservation_id' => $reservation->id,
            'rating' => 5,
            'title' => 'レビューのタイトル',
            'review' => 'レビューの本文',
        ];
        $response = $this->actingAs($user)->post('/api/review', $data);
        $response->assertStatus(201);
        $response->assertJsonFragment($data);
        $this->assertDatabaseHas('reviews', $data);
    }


    //store
    //バリデーションが通らない場合
    public function test_store_review_failed_validation()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $reservation = Reservation::factory()->for($restaurant)->for($user)->create();
        $data = [
            'user_id' => '',//未入力
            'restaurant_id' => $restaurant->id,
            'reservation_id' => $reservation->id,
            'rating' => 'not_integer',//整数でない
            'title' => 'レビューのタイトル',
            'review' => 'レビューの本文',
        ];
        $response = $this->actingAs($user)->post('/api/review', $data);
        $response->assertStatus(302);
    }
}
