<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Restaurant;
use Illuminate\Support\Str;

class RestaurantFactory extends Factory
{
    protected $model = Restaurant::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => Str::random(10),
            'area_id'=> $this->faker->numberBetween(1, 3),
            'genre_id' => $this->faker->numberBetween(1, 5),
            'overview' => $this->faker->text(100),
            'image_url' => $this->faker->url,
        ];
    }
}
