<?php

use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('user/register', [UserAuthController::class, 'register']);
Route::post('user/login', [UserAuthController::class, 'login'])->name('login');
Route::delete('user', [UserAuthController::class, 'delete'])->name('user.delete');
Route::get('users', [UserAuthController::class, 'list'])->name('users.show');

Route::get('/room/{room}/bookings', [RoomController::class, 'bookings']); //->middleware('auth:api');
Route::apiResource('/room', RoomController::class);
Route::apiResource('/room_type', RoomTypeController::class)->middleware('auth:api');
Route::apiResource('/booking', BookingController::class)->middleware('auth:api');
