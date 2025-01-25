<?php

use App\Livewire\Dashboard;
use App\Livewire\Incoming\Requests;
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

    Route::get('incoming/requests', Requests::class)->name('incoming.requests');

    // Settings
    Route::get('/settings/user-management', UserManagement::class)->name('user-management');
});
