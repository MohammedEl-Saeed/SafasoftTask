<?php

namespace Database\Factories;

use App\Models\Pitch;
use App\Models\Stadium;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pitch>
 */
class PitchFactory extends Factory
{
     protected $model = Pitch::class;

    public function definition(): array
    {
        return [
            'stadium_id' => Stadium::factory(),
            'name' => 'Pitch ' . $this->faker->unique()->numberBetween(1,10),
            'notes' => $this->faker->optional()->sentence,
        ];
    }
}
