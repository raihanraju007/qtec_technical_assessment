<?php

use App\Http\Controllers\Web\TaskDashboardController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/tasks');

Route::controller(TaskDashboardController::class)
    ->prefix('tasks')
    ->name('task-dashboard.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/{task}', 'update')->name('update');
        Route::delete('/{task}', 'destroy')->name('destroy');
    });
