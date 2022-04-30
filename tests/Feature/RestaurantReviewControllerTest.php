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


class RestaurantReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_index_restaurantreview()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();
        $reservation = Reservation::factory()->for($restaurant)->for($user)->create();
        $review = Review::factory()->for($restaurant)->for($reservation)->for($user)->create();
        $response = $this->get('/api/restaurantreview?restaurant_id=' . $review->restaurant_id);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'user_id' => $review->user_id,
            'restaurant_id' => $review->restaurant_id,
            'reservation_id' => $review->reservation_id,
            'rating' => $review->rating,
            'title' => $review->title,
            'review' => $review->review,
        ]);
    }
}
