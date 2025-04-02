<?php

use Illuminate\Support\Facades\Route;
use Modules\Membership\Http\Controllers\MembershipController;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('api/memberships')->group(function () {
        Route::get('/', [MembershipController::class, 'members']);
        Route::post('join', [MembershipController::class, 'join']);
        Route::put('promote', [MembershipController::class, 'promote']);
        Route::put('demote', [MembershipController::class, 'demote']);
        Route::delete('leave', [MembershipController::class, 'leave']);
    });
});