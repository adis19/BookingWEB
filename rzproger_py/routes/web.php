<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\ExtraServiceController;

/*
|--------------------------------------------------------------------------
| Веб-маршруты
|--------------------------------------------------------------------------
|
| Здесь вы можете зарегистрировать веб-маршруты для вашего приложения. Эти
| маршруты загружаются RouteServiceProvider в группе, содержащей
| middleware группу "web". Теперь создайте что-то великолепное!
|
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Room Routes
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{roomType}', [RoomController::class, 'show'])->name('rooms.show');
Route::post('/rooms/search', [RoomController::class, 'search'])->name('rooms.search');

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Booking Routes (Auth required)
Route::middleware(['auth'])->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Room Types Management
    Route::get('/room-types', [AdminRoomController::class, 'indexRoomTypes'])->name('room-types.index');
    Route::get('/room-types/create', [AdminRoomController::class, 'createRoomType'])->name('room-types.create');
    Route::post('/room-types', [AdminRoomController::class, 'storeRoomType'])->name('room-types.store');
    Route::get('/room-types/{roomType}/edit', [AdminRoomController::class, 'editRoomType'])->name('room-types.edit');
    Route::put('/room-types/{roomType}', [AdminRoomController::class, 'updateRoomType'])->name('room-types.update');
    Route::delete('/room-types/{roomType}', [AdminRoomController::class, 'destroyRoomType'])->name('room-types.destroy');

    // Rooms Management
    Route::get('/rooms', [AdminRoomController::class, 'indexRooms'])->name('rooms.index');
    Route::get('/rooms/create', [AdminRoomController::class, 'createRoom'])->name('rooms.create');
    Route::post('/rooms', [AdminRoomController::class, 'storeRoom'])->name('rooms.store');
    Route::get('/rooms/{room}/edit', [AdminRoomController::class, 'editRoom'])->name('rooms.edit');
    Route::put('/rooms/{room}', [AdminRoomController::class, 'updateRoom'])->name('rooms.update');
    Route::delete('/rooms/{room}', [AdminRoomController::class, 'destroyRoom'])->name('rooms.destroy');

    // Bookings Management
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/edit', [AdminBookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [AdminBookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');
    Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status');

    // Extra Services Management
    Route::get('/extra-services', [ExtraServiceController::class, 'index'])->name('extra-services.index');
    Route::get('/extra-services/create', [ExtraServiceController::class, 'create'])->name('extra-services.create');
    Route::post('/extra-services', [ExtraServiceController::class, 'store'])->name('extra-services.store');
    Route::get('/extra-services/{extraService}/edit', [ExtraServiceController::class, 'edit'])->name('extra-services.edit');
    Route::put('/extra-services/{extraService}', [ExtraServiceController::class, 'update'])->name('extra-services.update');
    Route::delete('/extra-services/{extraService}', [ExtraServiceController::class, 'destroy'])->name('extra-services.destroy');
});

// Debug routes
Route::get('/debug/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    return "Application cache cleared";
});

// Debug route to check room availability
Route::get('/debug/check-availability', [App\Http\Controllers\DebugController::class, 'checkAvailability']);

// Debug route for form submission
Route::any('/debug/request', [App\Http\Controllers\DebugController::class, 'debugRequest']);

