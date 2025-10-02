<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stadium;
use App\Models\Pitch;
use App\Models\Booking;
use Carbon\Carbon;

class StadiumPitchSeeder extends Seeder
{
    public function run(): void
    {
        // Create 2 stadiums, each with 3 pitches
        Stadium::factory()->count(2)->create()->each(function($stadium) {
            for ($i=1; $i<=3; $i++) {
                $pitch = Pitch::create([
                    'stadium_id' => $stadium->id,
                    'name' => "Pitch {$i}",
                ]);

                // optionally create a sample booking for tomorrow at 10:00-11:00 on pitch 1
                if ($i === 1) {
                    Booking::create([
                        'pitch_id' => $pitch->id,
                        'customer_name' => 'Sample Customer',
                        'customer_phone' => '0123456789',
                        'start_at' => Carbon::tomorrow()->setTime(10,0),
                        'end_at' => Carbon::tomorrow()->setTime(11,0),
                    ]);
                }
            }
        });
    }
}
