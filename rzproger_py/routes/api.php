<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\ExtraServiceController;
use App\Http\Controllers\API\AuthController;

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

// Публичные маршруты
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/rooms', [RoomController::class, 'index']);
Route::get('/rooms/{roomType}', [RoomController::class, 'show']);
Route::post('/rooms/search', [RoomController::class, 'search']);
Route::get('/extra-services', [ExtraServiceController::class, 'index']);

// Защищенные маршруты
Route::middleware('auth:sanctum')->group(function () {
    // Информация о пользователе
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Бронирование
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings/create', [BookingController::class, 'create']);
    Route::get('/bookings/{booking}', [BookingController::class, 'show']);
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);
});
