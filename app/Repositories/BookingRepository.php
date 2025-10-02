<?php

namespace App\Repositories;

use App\Models\Booking;

class BookingRepository
{
    public function getBookingsForPitchByDate(int $pitchId, string $date)
    {
         $startOfDay = \Carbon\Carbon::parse($date)->startOfDay();
    $endOfDay   = \Carbon\Carbon::parse($date)->endOfDay();
        return Booking::where('pitch_id', $pitchId)
        ->where(function ($q) use ($startOfDay, $endOfDay) {
            $q->where('start_at', '<', $endOfDay)
              ->where('end_at', '>', $startOfDay);
        })
        ->get();
    }

    public function createBooking(array $data): Booking
    {
        return Booking::create($data);
    }

    public function isSlotAvailable(int $pitchId, string $date, string $startTime, string $endTime): bool
    {
        // return !Booking::where('pitch_id', $pitchId)
        //     // ->whereDate('start_at', '<', $endTime)
        //     // ->whereDate('end_at', '>', $startTime)
        //     ->where(function ($q) use ($startTime, $endTime) {
        //         $q->whereBetween('start_at', [$startTime, $endTime])
        //           ->orWhereBetween('end_at', [$startTime, $endTime]);
        //     })->exists();

             return !Booking::where('pitch_id', $pitchId)
        ->where('start_at', '<', $endTime)
        ->where('end_at', '>', $startTime)
        ->exists();
    }
}
