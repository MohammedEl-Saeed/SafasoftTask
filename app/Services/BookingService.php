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
        $bookings = $this->repo->getBookingsForPitchByDate($pitchId, $date);
        $slots = collect();

        $opening = Carbon::parse("{$date} 08:00:00");
        $closing = Carbon::parse("{$date} 22:00:00");

        while ($opening->lt($closing)) {
            $start = $opening->copy();
            $end   = $start->copy()->addMinutes($duration);

            if ($end->gt($closing)) break;

            // Check conflict
            $conflict = $bookings->first(function ($booking) use ($start, $end) {
                $bookingStart = Carbon::parse($booking->start_at);
                $bookingEnd   = Carbon::parse($booking->end_at);

                return $start->lt($bookingEnd) && $end->gt($bookingStart);
            });

            if (! $conflict) {
                $slots->push([
                    'start_time' => $start->format('H:i'),
                    'end_time'   => $end->format('H:i'),
                ]);
            }

            $opening->addMinutes($duration);
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
