<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MedicineApiController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes
Route::prefix('v1')->group(function () {
    // Medicines API
    Route::prefix('medicines')->group(function () {
        Route::get('/', [MedicineApiController::class, 'index']);
        Route::get('/{id}', [MedicineApiController::class, 'show']);
        Route::get('/available/search', [MedicineApiController::class, 'available']);
        Route::get('/low-stock', [MedicineApiController::class, 'lowStock']);
        Route::get('/expiring', [MedicineApiController::class, 'expiring']);
    });

    // Manufacturers API
    Route::prefix('manufacturers')->group(function () {
        Route::get('/', [App\Http\Controllers\ManufacturerController::class, 'index']);
        Route::get('/{id}', [App\Http\Controllers\ManufacturerController::class, 'show']);
    });

    // Units API
    Route::prefix('units')->group(function () {
        Route::get('/', [App\Http\Controllers\UnitController::class, 'index']);
        Route::get('/{id}', [App\Http\Controllers\UnitController::class, 'show']);
    });
});

// Protected API routes (require authentication)
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Users API
    Route::prefix('users')->group(function () {
        Route::get('/', [App\Http\Controllers\UserController::class, 'apiIndex']);
        Route::get('/{user}', [App\Http\Controllers\UserController::class, 'apiShow']);
        Route::post('/', [App\Http\Controllers\UserController::class, 'apiStore']);
        Route::put('/{user}', [App\Http\Controllers\UserController::class, 'apiUpdate']);
        Route::delete('/{user}', [App\Http\Controllers\UserController::class, 'apiDestroy']);
    });

    // Manufacturers API (Protected)
    Route::prefix('manufacturers')->group(function () {
        Route::post('/', [App\Http\Controllers\ManufacturerController::class, 'store']);
        Route::put('/{id}', [App\Http\Controllers\ManufacturerController::class, 'update']);
        Route::delete('/{id}', [App\Http\Controllers\ManufacturerController::class, 'destroy']);
    });

    // Units API (Protected)
    Route::prefix('units')->group(function () {
        Route::post('/', [App\Http\Controllers\UnitController::class, 'store']);
        Route::put('/{id}', [App\Http\Controllers\UnitController::class, 'update']);
        Route::delete('/{id}', [App\Http\Controllers\UnitController::class, 'destroy']);
    });
});
