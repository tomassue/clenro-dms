<?php

use App\Livewire\Dashboard;
use App\Livewire\Incoming\Requests;
use App\Livewire\Settings\Category;
use App\Livewire\Settings\SubCategory;
use App\Livewire\Settings\UserManagement;
use App\Livewire\Settings\Venue;
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
    Route::get('/settings/category', Category::class)->name('category');
    Route::get('/settings/sub-category', SubCategory::class)->name('sub-category');
    Route::get('/settings/venue', Venue::class)->name('venue');
});
