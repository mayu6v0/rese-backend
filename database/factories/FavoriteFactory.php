<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Favorite;


class FavoriteFactory extends Factory
{
    protected $model = Favorite::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // 'user_id' => 'required',
            // 'restaurant_id' => 'required',
        ];
    }
}
