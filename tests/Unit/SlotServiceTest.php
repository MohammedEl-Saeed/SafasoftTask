<?php
namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\SlotService;
use App\Models\Stadium;
use App\Models\Pitch;
use Carbon\Carbon;

class SlotServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_slot_generation_excludes_existing_bookings()
    {
        $stadium = Stadium::factory()->create(['open_at'=>'08:00:00','close_at'=>'12:00:00']);
        $pitch = Pitch::factory()->create(['stadium_id' => $stadium->id]);
        // create booking at 09:00 - 10:00
        \App\Models\Booking::create([
            'pitch_id' => $pitch->id,
            'customer_name' => 'x',
            'start_at' => Carbon::today()->setTime(9,0),
            'end_at' => Carbon::today()->setTime(10,0),
        ]);

        $svc = $this->app->make(SlotService::class);
        $slots = $svc->listAvailableSlots($pitch->id, Carbon::today()->toDateString(), 60);
        // slots: 08-09 (available), 09-10 (booked), 10-11 (available), 11-12 (available) => 3 available
        $this->assertCount(3, $slots);
    }
}
