<?php

use App\Livewire\Dashboard;
use App\Livewire\Settings\UserManagement;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Auth::routes(['register' => false]); //! NOT WORKING

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Settings
    Route::get('/settings/user-management', UserManagement::class)->name('user-management');
});
