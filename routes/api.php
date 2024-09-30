<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'here';
});
Route::post('auth', AuthController::class)->name('auth');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('statistics', StatisticsController::class)->name('statistics');
    Route::resource('tasks', TaskController::class)->except(['create', 'edit']);
    Route::resource('projects', ProjectController::class)->except(['create', 'edit']);
});

