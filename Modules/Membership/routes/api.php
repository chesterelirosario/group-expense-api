<?php

use Illuminate\Support\Facades\Route;
use Modules\Membership\Http\Controllers\MembershipController;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('memberships')->group(function () {
        Route::get('/', [MembershipController::class, 'members'])->name('memberships.members');
        Route::post('join', [MembershipController::class, 'join'])->name('memberships.join');
        Route::put('promote', [MembershipController::class, 'promote'])->name('memberships.promote');
        Route::put('demote', [MembershipController::class, 'demote'])->name('memberships.demote');
        Route::delete('leave', [MembershipController::class, 'leave'])->name('memberships.leave');
    });
});