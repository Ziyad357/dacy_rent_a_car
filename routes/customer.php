<?php

use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\PenaltyController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\ReservationController;
use App\Http\Controllers\Customer\SupportController;
use Illuminate\Support\Facades\Route;

Route::prefix('customer')->name('customer.')->middleware(['auth', 'role:customer', 'check.blacklist'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::patch('reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::get('reservations/{reservation}/contract/pdf', [ReservationController::class, 'contractPdf'])->name('reservations.contract.pdf');

    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('penalties', [PenaltyController::class, 'index'])->name('penalties.index');

    Route::get('support', [SupportController::class, 'index'])->name('support.index');
    Route::post('support', [SupportController::class, 'store'])->name('support.store');
});
