<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('tasks/list', [TaskController::class, 'getTaskPage'])->middleware(['auth'])->name('tasks.list');

Route::prefix('api')->group(function () {
    Route::resource('tasks', TaskController::class);
});



require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
