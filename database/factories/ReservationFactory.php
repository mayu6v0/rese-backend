<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Reservation;


class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'datetime' => $this->faker->dateTimeBetween($startDate = 'now', $endDate = '+1 year')->format('Y-m-d H:i'),
            'number' => $this->faker->numberBetween(1, 10),
        ];
    }
}
