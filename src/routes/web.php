<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reset-password/{token}', function ($token) {
    return response()->json([
        'message' => 'Token reçu',
        'token' => $token
    ]);
})->name('password.reset');

Route::get('/debug-cloudinary', function () {
    return response()->json([
        'config_cloudinary' => config('cloudinary'),
        'env_cloud_name'    => env('CLOUDINARY_CLOUD_NAME'),
        'env_url'           => env('CLOUDINARY_URL'),
    ]);
});
