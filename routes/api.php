<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RestaurantReviewController;
use App\Http\Controllers\OwnerReservationController;
use App\Http\Controllers\SendEmailController;
use App\Http\Controllers\ReservationCheckController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ReservationWithCardController;

Route::group([
    'middleware' => ['auth:api'],
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [AuthController::class, 'register'])->withoutMiddleware(['auth:api']);
    Route::post('login', [AuthController::class, 'login'])->withoutMiddleware(['auth:api']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('user', [AuthController::class, 'me']);
    Route::put('update', [AuthController::class, 'update']);
    Route::get('verify-email/{id}/{hash}', [VerificationController::class, 'verify'])
        ->withoutMiddleware(['auth:api'])
        ->name('verification.verify')->middleware('signed');
    Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend')->withoutMiddleware(['auth:api']);
});

Route::get('/restaurant', [RestaurantController::class, 'index']);
Route::get('/restaurant/{restaurant}', [RestaurantController::class, 'show']);
Route::get('/restaurantreview', [RestaurantReviewController::class, 'index']);

Route::middleware(['verified'])->group(function () {
    Route::apiResources([
        '/reservation' => ReservationController::class,
        'reservation-card' => ReservationWithCardController::class,
        '/favorite' => FavoriteController::class,
        '/review' => ReviewController::class,
    ]);
});

Route::middleware(['verified', 'admin'])->group(function() {
    Route::post('/sendmail', [SendEmailController::class, 'sendmail']);
    Route::get('/auth/owner', [AuthController::class, 'get_owner']);
    Route::get('/auth/admin', [AuthController::class, 'get_admin']);
});

Route::middleware(['verified', 'owner'])->group(function () {
    Route::post('/restaurant', [RestaurantController::class, 'store']);
    Route::put('restaurant/{restaurant}', [RestaurantController::class, 'update']);
    Route::get('/owner/reservation', [OwnerReservationController::class, 'index']);
    Route::get('/owner/reservation-check', [ReservationCheckController::class, 'reservationCheck'])->name('reservation.check')->middleware('signed');
    Route::post('/images', [ImageController::class, 'create']);
    Route::get('/images', [ImageController::class, 'index']);
});