<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Customers\Auth\AuthController;
use App\Http\Controllers\Api\Customers\Auth\FacebookController;
use App\Http\Controllers\Api\Customers\Auth\GoogleController;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/verify/{token}', [AuthController::class, 'verifyEmail']);

Route::get('/login/facebook', [FacebookController::class, 'redirectToFacebook']);
Route::get('/login/facebook/callback', [FacebookController::class, 'handleFacebookCallback']);

Route::get('/login/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('/login/google/callback', [GoogleController::class, 'handleGoogleCallback']);

