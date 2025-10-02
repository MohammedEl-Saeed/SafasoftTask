<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Stadium;
use App\Models\Pitch;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingConcurrencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_concurrent_booking_prevented()
    {
        $stadium = Stadium::factory()->create();
        $pitch = Pitch::factory()->create(['stadium_id'=>$stadium->id]);

        $start = Carbon::tomorrow()->setTime(10,0)->format("H:i");
        $end = Carbon::tomorrow()->setTime(11,0)->format("H:i");

        // Simulate two requests: create one booking, then second will conflict
        $resp1 = $this->postJson('/api/bookings', [
            'pitch_id' => $pitch->id,
            'customer_name' => 'A',
            'date' => Carbon::tomorrow()->toDateString(),
            'start_time'=>$start,
            'end_time'=>$end
        ]);
        $resp1->assertStatus(201);

        $resp2 = $this->postJson('/api/bookings', [
            'pitch_id' => $pitch->id,
            'customer_name' => 'B',
            'date' => Carbon::tomorrow()->toDateString(),
            'start_time'=>$start,
            'end_time'=>$end
        ]);
        $this->assertTrue(in_array($resp2->status(), [409,422]));
    }
}
