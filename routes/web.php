<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
})->name('home');

Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole('admin')) {
        return redirect('/admin/dashboard');
    }
    if ($user->hasRole('agent')) {
        return redirect('/agent/dashboard');
    }

    return redirect('/customer/dashboard');
})->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/admin.php';
require __DIR__.'/agent.php';
require __DIR__.'/customer.php';
