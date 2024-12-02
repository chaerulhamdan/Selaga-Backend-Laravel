<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\AuthmitraController;
use App\Http\Controllers\TimetableController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/booking', [BookingController::class, 'store']);
    Route::get('/logoutMitra', [AuthmitraController::class, 'logout']);
    Route::get('/profileMitra', [AuthmitraController::class, 'profile']);
    Route::post('/lapangan', [LapanganController::class, 'store']);
    Route::post('/venue', [VenueController::class, 'store']);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/loginMitra', [AuthmitraController::class, 'login']);
Route::post('/registerMitra', [AuthmitraController::class, 'register']);

Route::get('/lapangan', [LapanganController::class, 'index']);
Route::get('/lapangan/{id}', [LapanganController::class, 'show']);
Route::patch('/lapangan/{id}', [LapanganController::class, 'update']);
Route::delete('/lapangan/{id}', [LapanganController::class, 'destroy']);
Route::get('/booking', [BookingController::class, 'index']);
Route::get('/booking/{id}', [BookingController::class, 'show']);
Route::patch('/booking/{id}', [BookingController::class, 'update']);
Route::patch('/bookingratingstatus/{id}', [BookingController::class, 'updateratingstatus']);
Route::get('/timetable', [TimetableController::class, 'index']);
Route::get('/timetable/{id}', [TimetableController::class, 'show']);
Route::post('/timetable', [TimetableController::class, 'store']);
Route::patch('/timetable/{id}', [TimetableController::class, 'update']);
Route::delete('/timetable/{id}', [TimetableController::class, 'destroy']);

Route::get('/venue', [VenueController::class, 'index']);
Route::get('/venue/{id}', [VenueController::class, 'show']);
Route::patch('/venue/{id}', [VenueController::class, 'update']);
Route::delete('/venue/{id}', [VenueController::class, 'destroy']);
Route::patch('/venuerating/{id}', [VenueController::class, 'updaterating']);
Route::patch('/venuenoimage/{id}', [VenueController::class, 'updatenoimage']);