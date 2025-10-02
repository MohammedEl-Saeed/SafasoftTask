<?php

namespace Database\Seeders;

use App\Models\Pitch;
use App\Models\Stadium;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
       Stadium::factory()
        ->has(Pitch::factory()->count(3))
        ->count(2)
        ->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call(StadiumPitchSeeder::class);
    }
}
