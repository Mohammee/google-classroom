<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//don't need route name (no route redirect)
//Route::apiResource('/classrooms', \App\Http\Controllers\Api\V1\ClassroomsController::class);

//Route::apiResource('classrooms.classworks', \App\Http\Controllers\Api\V1\ClassworksController::class);
//    ->shallow();

// api use sanctum or oauth
// use more than one token for user to support multi-device
Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('auth/access-tokens', [\App\Http\Controllers\Api\V1\AccessTokenController::class, 'index']);
        Route::delete('auth/access-tokens/{id?}', [\App\Http\Controllers\Api\V1\AccessTokenController::class, 'destroy']
        );

        Route::post('devices', [\App\Http\Controllers\Api\V1\DeviceController::class, 'store']);

        Route::apiResource('/classrooms', \App\Http\Controllers\Api\V1\ClassroomsController::class);
        Route::apiResource('classrooms.classworks', \App\Http\Controllers\Api\V1\ClassworksController::class);

        //this for chat
        Route::apiResource('classrooms.messages', \App\Http\Controllers\Api\V1\ClassroomMessagesController::class);
    });

    Route::middleware('guest:sanctum')->group(function () {
        Route::post('auth/access-tokens', [\App\Http\Controllers\Api\V1\AccessTokenController::class, 'store']);
    });
});
