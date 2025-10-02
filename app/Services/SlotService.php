<?php
namespace App\Services;

use App\Repositories\PitchRepository;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class SlotService
{
    protected PitchRepository $pitchRepo;

    public function __construct(PitchRepository $pitchRepo)
    {
        $this->pitchRepo = $pitchRepo;
    }

    /**
     * Generate available slots for a pitch on a date for a given duration
     * @param int $pitchId
     * @param string $date (Y-m-d)
     * @param int $minutes (60 or 90)
     * @return array of ['start' => ISO, 'end' => ISO]
     */
    public function listAvailableSlots(int $pitchId, string $date, int $minutes): array
    {
        $pitch = $this->pitchRepo->find($pitchId);
        if (!$pitch) return [];

        $stadium = $pitch->stadium;
        $openAt = Carbon::parse($date . ' ' . $stadium->open_at);
        $closeAt = Carbon::parse($date . ' ' . $stadium->close_at);

        $slots = [];
        $current = $openAt->copy();

        $interval = CarbonInterval::minutes($minutes);

        // collect existing bookings
        $bookings = $this->pitchRepo->bookingsForDate($pitchId, $date)
                     ->map(function($b){ return ['start'=>Carbon::parse($b->start_at),'end'=>Carbon::parse($b->end_at)]; })
                     ->toArray();

        while ($current->addMinutes(0)->lte($closeAt->copy()->subMinutes($minutes))) {
            $start = $current->copy();
            $end = $start->copy()->addMinutes($minutes);

            $overlaps = false;
            foreach ($bookings as $bk) {
                if ($this->intervalsOverlap($start, $end, $bk['start'], $bk['end'])) {
                    $overlaps = true;
                    break;
                }
            }

            if (!$overlaps) {
                $slots[] = [
                    'start' => $start->toDateTimeString(),
                    'end' => $end->toDateTimeString(),
                ];
            }

            // move to next slot (no gaps) — contiguous slots
            $current->addMinutes($minutes);
        }

        return $slots;
    }

    public function getAvailableSlots($pitchOrPitchId, string $date, int $minutes): array
    {
        $pitchId = is_object($pitchOrPitchId) ? $pitchOrPitchId->id : (int)$pitchOrPitchId;
        $raw = $this->listAvailableSlots($pitchId, $date, $minutes);

        return array_map(fn($s) => [
            'start_time' => $s['start'],
            'end_time' => $s['end'],
        ], $raw);
    }
    
    protected function intervalsOverlap($aStart, $aEnd, $bStart, $bEnd): bool
    {
        return $aStart < $bEnd && $bStart < $aEnd;
    }
}
