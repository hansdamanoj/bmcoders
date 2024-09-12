<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReelController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['auth:api'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
// Authenticated routes for reel management
Route::middleware(['auth:api'])->group(function () {
    Route::post('/reels/upload', [ReelController::class, 'uploadReel']);  // Upload a reel
    Route::get('/reels', [ReelController::class, 'getReels']);            // Get all reels
    Route::get('/reels/{id}', [ReelController::class, 'getReel']);        // Get a specific reel
    Route::post('/reels/save-last-second', [ReelController::class, 'saveLastSecond']);
    Route::get('/reels/{reel}/user/{user}/get-last-second', [ReelController::class, 'getLastSecond']);
});
