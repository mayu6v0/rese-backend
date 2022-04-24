<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController; //追加
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RestaurantReviewController;
use App\Http\Controllers\OwnerReservationController;
use App\Http\Controllers\SendEmailController;
use App\Http\Controllers\ReservationCheckController;




Route::group([
    'middleware' => ['auth:api'],
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [AuthController::class, 'register'])->withoutMiddleware(['auth:api']);
    Route::post('login', [AuthController::class, 'login'])->withoutMiddleware(['auth:api']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('user', [AuthController::class, 'me']);
    Route::get('owner', [AuthController::class, 'owner'])->withoutMiddleware(['auth:api']);
    Route::get('admin', [AuthController::class, 'admin'])->withoutMiddleware(['auth:api']);
    Route::get('verify-email/{id}/{hash}', [VerificationController::class, 'verify'])
    ->withoutMiddleware(['auth:api'])
    ->name('verification.verify')->middleware('signed');
    Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend')->withoutMiddleware(['auth:api']);
});


Route::apiResources([
    '/restaurant' => RestaurantController::class,
    '/reservation' => ReservationController::class,
    '/favorite' => FavoriteController::class,
    '/review' => ReviewController::class,
    '/restaurantreview' => RestaurantReviewController::class,
    '/owner/reservation' => OwnerReservationController::class,
]);

Route::post('/sendmail', [SendEmailController::class, 'sendmail']);
Route::get('/owner/reservation-check', [ReservationCheckController::class, 'reservationCheck'])->name('reservation.check')->middleware('signed');