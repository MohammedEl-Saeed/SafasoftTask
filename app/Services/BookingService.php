<?php

namespace App\Services;

use App\Repositories\BookingRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class BookingService
{
    protected BookingRepository $repo;

    public function __construct(BookingRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAvailableSlots(int $pitchId, string $date, int $duration): Collection
    {
        // Fetch bookings already sorted by start time from the repository.
        $bookings = $this->repo->getBookingsForPitchByDate($pitchId, $date)
            ->sortBy('start_at');

        $slots = collect();

        // Start with the first available time, which is the opening time.
        $currentTime = Carbon::parse("{$date} 08:00:00");
        $closing = Carbon::parse("{$date} 22:00:00");

        // Add a "dummy" booking at the end of the day to simplify the loop.
        // This ensures the time from the last real booking until closing is also processed.
        $bookings->push((object)[
            'start_at' => $closing->copy(),
            'end_at' => $closing->copy(),
        ]);

        foreach ($bookings as $booking) {
            // The "free block" is the time between our current time and the start of the next booking.
            $freeBlockEnd = Carbon::parse($booking->start_at);

            // Generate all possible slots within this free block.
            while ($currentTime->copy()->addMinutes($duration)->lte($freeBlockEnd)) {
                $slots->push([
                    'start_time' => $currentTime->format('H:i'),
                    'end_time'   => $currentTime->copy()->addMinutes($duration)->format('H:i'),
                ]);
                $currentTime->addMinutes($duration);
            }

            // After a booking, the next possible start time is the end of that booking.
            // We take the max to handle cases of overlapping or back-to-back bookings.
            $nextAvailableTime = Carbon::parse($booking->end_at);
            $currentTime = $currentTime->max($nextAvailableTime);
        }

        return $slots;
    }

    public function bookSlot(int $pitchId, string $date, string $startTime, string $endTime)
    {
        $slotStart = Carbon::parse("{$date} {$startTime}")->format('Y-m-d H:i:s');
        $slotEnd = Carbon::parse("{$date} {$endTime}")->format('Y-m-d H:i:s');
        
        if (!$this->repo->isSlotAvailable($pitchId, $date, $slotStart, $slotEnd)) {
            throw new \Exception("Slot already booked");
        }
        return $this->repo->createBooking([
            'pitch_id' => $pitchId,
            'start_at' => $slotStart,
            'end_at' => $slotEnd,
        ]);
    }
}
