<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('login', [AuthController::class, 'login'])
    ->middleware(['throttle:6,1']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])
    ->middleware(['auth:sanctum']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user', fn (Request $request) => $request->user());

    Route::apiResource('comments', CommentController::class);
});
