<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetSlotsRequest;
use App\Http\Requests\BookSlotRequest;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    protected BookingService $service;

    public function __construct(BookingService $service)
    {
        $this->service = $service;
    }

    public function getAvailableSlots(GetSlotsRequest $request): JsonResponse
    {
        $data = $request->validated();

        $slots = $this->service->getAvailableSlots($data['pitch_id'], $data['date'], $data['duration']);

        return response()->json(['slots' => $slots]);
    }

    public function bookSlot(BookSlotRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $booking = $this->service->bookSlot($data['pitch_id'],$data['date'],$data['start_time'],$data['end_time']);

            return response()->json(['message' => 'Booking successful', 'booking' => $booking], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
