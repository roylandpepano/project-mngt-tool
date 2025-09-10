<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectApiController;
use App\Http\Controllers\Api\TaskApiController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('projects', ProjectApiController::class);
    Route::apiResource('tasks', TaskApiController::class)->only(['store','show','update','destroy']);
    Route::get('/user', function (Request $request) { return $request->user(); });
});
