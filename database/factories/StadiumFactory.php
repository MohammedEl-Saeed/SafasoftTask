<?php

namespace Database\Factories;

use App\Models\Stadium;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stadium>
 */
class StadiumFactory extends Factory
{
    protected $model = Stadium::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Stadium',
            'location' => $this->faker->city,
            'open_at' => '08:00:00',
            'close_at' => '22:00:00',
        ];
    }
}
