<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RestaurantReviewController;
use App\Http\Controllers\OwnerReservationController;




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
});


Route::apiResources([
    '/restaurant' => RestaurantController::class,
    '/reservation' => ReservationController::class,
    '/favorite' => FavoriteController::class,
    '/review' => ReviewController::class,
    '/restaurantreview' => RestaurantReviewController::class,
    '/owner/reservation' => OwnerReservationController::class,
]);