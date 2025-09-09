<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RecipeController;

Route::prefix('auth')->group(function() {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('recipes',           [RecipeController::class, 'index']);
    Route::post('recipes',          [RecipeController::class, 'store']);
    Route::get('recipes/{uuid}',    [RecipeController::class, 'show']);
    Route::put('recipes/{uuid}',    [RecipeController::class, 'update']);
    Route::delete('recipes/{uuid}', [RecipeController::class, 'destroy']);
});
Route::get('recipes/{uuid}/print', [RecipeController::class, 'print']);
