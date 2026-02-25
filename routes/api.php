<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Test endpoint (no authentication required)
Route::get('/ping', function () {
	return response()->json(['message' => 'pong']);
});

// Protected user endpoint
Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:sanctum');
