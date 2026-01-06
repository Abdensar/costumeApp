<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CostumeController;
use App\Http\Controllers\API\RentalController;
use App\Http\Controllers\API\CostumeImageController;
use App\Http\Controllers\API\SizeController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public category and costume browsing
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::get('/costumes', [CostumeController::class, 'index']);
Route::get('/costumes/{costume}', [CostumeController::class, 'show']);
Route::get('/costumes/{costume}/images', [CostumeImageController::class, 'index']);
Route::get('/costumes/{costume}/sizes', [SizeController::class, 'index']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // User profile
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);

    // Customer rentals
    Route::get('/my-rentals', [RentalController::class, 'myRentals']);
    Route::post('/rentals', [RentalController::class, 'store']);

    // Admin only routes
    Route::middleware('admin')->group(function () {
        // Categories management
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

        // Costumes management
        Route::post('/costumes', [CostumeController::class, 'store']);
        Route::put('/costumes/{costume}', [CostumeController::class, 'update']);
        Route::delete('/costumes/{costume}', [CostumeController::class, 'destroy']);
        Route::post('/costumes/{costume}/images', [CostumeImageController::class, 'store']);
        Route::post('/costumes/{costume}/sizes', [SizeController::class, 'store']);

        // Rentals management
        Route::get('/rentals', [RentalController::class, 'index']);
        Route::put('/rentals/{rental}/status', [RentalController::class, 'updateStatus']);
        Route::get('/rentals/statistics', [RentalController::class, 'statistics']);

        // Users management
        Route::get('/users', [UserController::class, 'index']);
    });
});
