<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Models\Task;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
})->middleware(RedirectIfAuthenticated::class)->name('home');

Route::get('/dashboard', function () {
    $tasks = Task::with('project')
        ->where('assigned_to', Auth::id())
        ->latest()
        ->paginate(10);

    return view('dashboard', compact('tasks'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('users', UserController::class);
    // Projects and Tasks
    Route::resource('projects', ProjectController::class);
    Route::resource('tasks', TaskController::class)->only(['store','edit','update','destroy']);
    // Activity logs (admin only)
    Route::get('activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])
        ->name('activity_logs.index')
        ->middleware('admin');
});

require __DIR__.'/auth.php';
