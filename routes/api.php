<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

Route::middleware('auth:sanctum', 'throttle:60,1')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return response()->json([
        'message' => 'Тек авторизацияланған қолданушылар көре алады!',
        'user' => auth()->user(),
    ]);
});
Route::middleware(['auth:api'])->group(function () {
    Route::post('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store']);
    Route::delete('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy']);
});


Route::get('/test', function () {
    return response()->json([
        'message' => 'API жұмыс істеп тұр!'
    ]);
});

