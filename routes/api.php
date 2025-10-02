<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\PitchController;
use App\Http\Controllers\StadiumController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Book a pitch
Route::post('/bookings', [BookingController::class, 'store']);

Route::get('/stadiums', [StadiumController::class, 'index']);
Route::get('/stadiums/{stadium}/pitches', [StadiumController::class, 'pitches']);
Route::get('/pitches/{pitch}/slots', [PitchController::class, 'availableSlots']);
Route::post('/bookings', [BookingController::class, 'store']);


Route::prefix('bookings')->group(function () {
    Route::get('slots', [BookingController::class, 'getAvailableSlots']);
    Route::post('/', [BookingController::class, 'bookSlot']);
});