<?php

use App\Http\Controllers\TaskController;
use App\Models\Task;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    $total = Task::all()->count();
    $completed = Task::where('status', 'completed')->count();
    $in_progress = Task::where('status', 'in_progress')->count();
    $pending = Task::where('status', 'pending')->count();

    return Inertia::render('Dashboard', [
        'total' => $total,
        'completed' => $completed,
        'in_progress' => $in_progress,
        'pending' => $pending,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('tasks/list', [TaskController::class, 'getTaskPage'])->middleware(['auth'])->name('tasks.list');

Route::prefix('api')->group(function () {
    Route::resource('tasks', TaskController::class);
});



require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
