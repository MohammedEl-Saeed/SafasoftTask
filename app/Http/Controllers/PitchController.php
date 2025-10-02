<?php

namespace App\Http\Controllers;

use App\Models\Pitch;
use Illuminate\Http\Request;
use App\Services\SlotService;

class PitchController extends Controller
{
    private $slotService;

    public function __construct(SlotService $slotService)
    {
        $this->slotService = $slotService;
    }

    public function availableSlots(Pitch $pitch, Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'duration' => 'nullable|in:60,90',
        ]);

        $duration = (int)($data['duration'] ?? 60);
        $slots = $this->slotService->getAvailableSlots($pitch, $data['date'], $duration);

        // Return data directly as array so frontend can consume: [{start_time,end_time},...]
        return response()->json($slots);
    }
}
