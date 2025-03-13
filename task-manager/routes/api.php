<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('users', UserController::class);
Route::apiResource('tasks', TaskController::class);
Route::get('/tasks/grouped', [TaskController::class, 'groupedTasks']);

Route::middleware('throttle:2,1')->post('/tasks', [TaskController::class, 'store']);