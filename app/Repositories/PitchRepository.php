<?php
namespace App\Repositories;

use App\Models\Pitch;
use Carbon\Carbon;

class PitchRepository
{
    public function find(int $id): ?Pitch
    {
        return Pitch::with('stadium')->find($id);
    }

    public function getByStadium(int $stadiumId)
    {
        return Pitch::where('stadium_id', $stadiumId)->get();
    }

    /**
     * Return bookings for a pitch on a date
     * @param int $pitchId
     * @param string $date Y-m-d
     */
    public function bookingsForDate(int $pitchId, string $date)
    {
        $startOfDay = Carbon::parse($date)->startOfDay();
        $endOfDay = Carbon::parse($date)->endOfDay();

        return \App\Models\Booking::where('pitch_id', $pitchId)
                ->whereBetween('start_at', [$startOfDay, $endOfDay])
                ->orderBy('start_at')
                ->get();
    }
}
