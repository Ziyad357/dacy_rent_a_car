<?php

use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\ContractController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PenaltyController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('cars', CarController::class);
    Route::patch('cars/{car}/status', [CarController::class, 'updateStatus'])->name('cars.status');
    Route::post('cars/{car}/maintenance', [CarController::class, 'storeMaintenance'])->name('cars.maintenance.store');

    Route::resource('users', UserController::class);
    Route::patch('users/{user}/role', [UserController::class, 'updateRole'])->name('users.role');
    Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
    Route::patch('users/{user}/blacklist', [UserController::class, 'blacklist'])->name('users.blacklist');

    Route::resource('reservations', ReservationController::class);
    Route::patch('reservations/{reservation}/status', [ReservationController::class, 'updateStatus'])->name('reservations.status');

    Route::resource('contracts', ContractController::class)->only(['index', 'show']);
    Route::get('contracts/{contract}/pdf', [ContractController::class, 'pdf'])->name('contracts.pdf');
    Route::post('contracts/{contract}/close', [ContractController::class, 'close'])->name('contracts.close');

    Route::resource('payments', PaymentController::class)->only(['index', 'store']);

    Route::resource('penalties', PenaltyController::class)->only(['index', 'show']);
    Route::patch('penalties/{penalty}/pay', [PenaltyController::class, 'markPaid'])->name('penalties.pay');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
    Route::get('reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
    Route::get('reports/utilization', [ReportController::class, 'utilization'])->name('reports.utilization');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('support', [SupportController::class, 'index'])->name('support.index');
    Route::get('support/{user}', [SupportController::class, 'show'])->name('support.show');
    Route::post('support/{user}/reply', [SupportController::class, 'reply'])->name('support.reply');
});
