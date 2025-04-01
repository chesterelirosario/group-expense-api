<?php

use Illuminate\Support\Facades\Route;
use Modules\Group\Http\Controllers\GroupController;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('groups')->group(function () {
        Route::get('/', [GroupController::class, 'index'])->name('groups.index');
        Route::post('/', [GroupController::class, 'store'])->name('groups.store');
        Route::put('{group}', [GroupController::class, 'update'])->name('groups.update');
        Route::delete('{group}', [GroupController::class, 'destroy'])->name('groups.destroy');
    });
});