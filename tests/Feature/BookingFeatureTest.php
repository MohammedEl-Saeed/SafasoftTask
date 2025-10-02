<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Stadium;
use App\Models\Pitch;
use Carbon\Carbon;

class BookingFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_booking()
    {
        $stadium = Stadium::factory()->create();
        $pitch = Pitch::factory()->create(['stadium_id'=>$stadium->id]);

        $start = Carbon::tomorrow()->setTime(10,0)->format('H:i');
        $end = Carbon::tomorrow()->setTime(11,0)->format('H:i');

        $date = Carbon::tomorrow()->toDateString();
        $resp = $this->postJson('/api/bookings', [
            'pitch_id' => $pitch->id,
            'customer_name' => 'Test User',
            'date' => $date,
            'start_time' => $start,
            'end_time' => $end,
        ]);

        $slotStart = Carbon::parse("{$date} {$start}");
        $slotEnd = Carbon::parse("{$date} {$end}");

        $resp->assertStatus(201);
        $this->assertDatabaseHas('bookings', ['pitch_id'=>$pitch->id,'start_at'=>$slotStart]);
    }
}
