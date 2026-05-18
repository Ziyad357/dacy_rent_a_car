<?php

use App\Http\Controllers\Agent\CarController;
use App\Http\Controllers\Agent\ContractController;
use App\Http\Controllers\Agent\CustomerController;
use App\Http\Controllers\Agent\DashboardController;
use App\Http\Controllers\Agent\ReservationController;
use Illuminate\Support\Facades\Route;

Route::prefix('agent')->name('agent.')->middleware(['auth', 'role:agent|admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('cars', [CarController::class, 'index'])->name('cars.index');
    Route::get('cars/{car}', [CarController::class, 'show'])->name('cars.show');
    Route::post('cars/check-availability', [CarController::class, 'checkAvailability'])->name('cars.availability');
    Route::patch('cars/{car}/status', [CarController::class, 'updateStatus'])->name('cars.status');

    Route::resource('customers', CustomerController::class)->except(['destroy']);

    Route::resource('reservations', ReservationController::class)->except(['destroy']);
    Route::patch('reservations/{reservation}/status', [ReservationController::class, 'updateStatus'])->name('reservations.status');
    Route::post('reservations/price-calculate', [ReservationController::class, 'calculatePrice'])->name('reservations.price');

    Route::get('contracts', [ContractController::class, 'index'])->name('contracts.index');
    Route::get('contracts/{contract}', [ContractController::class, 'show'])->name('contracts.show');
    Route::post('contracts', [ContractController::class, 'store'])->name('contracts.store');
    Route::post('contracts/{contract}/close', [ContractController::class, 'close'])->name('contracts.close');
    Route::get('contracts/{contract}/pdf', [ContractController::class, 'pdf'])->name('contracts.pdf');
});
