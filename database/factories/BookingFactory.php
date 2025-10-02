<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Pitch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $start = Carbon::tomorrow()->setTime(10,0);
        return [
            'pitch_id' => Pitch::factory(),
            'customer_name' => $this->faker->name,
            'customer_phone' => $this->faker->phoneNumber,
            'start_at' => $start,
            'end_at' => $start->copy()->addHour(),
        ];
    }
}
