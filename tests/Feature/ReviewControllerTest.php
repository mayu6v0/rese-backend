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
    public function test_index_review()
    {
        // ユーザーが認証されている
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

    public function test_index_review_Not_authenticated()
    {
        // ユーザーが認証されていない
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $reservation = Reservation::factory()->for($restaurant)->for($user)->create();
        $review = Review::factory()->for($restaurant)->for($reservation)->for($user)->create();
        $response = $this->get('/api/review');
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    public function test_store_review()
    {
        // ユーザーが認証されている
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

    public function test_store_review_Not_authenticated()
    {
        // ユーザーが認証されていない
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
        $response = $this->post('/api/review', $data);
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }
}
