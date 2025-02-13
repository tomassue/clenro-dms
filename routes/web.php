<?php

use App\Http\Controllers\FileController;
use App\Livewire\AccountSettings\ChangePassword;
use App\Livewire\Calendar;
use App\Livewire\Dashboard;
use App\Livewire\Incoming\Documents;
use App\Livewire\Incoming\Requests;
use App\Livewire\Outgoing;
use App\Livewire\Settings\Category;
use App\Livewire\Settings\SubCategory;
use App\Livewire\Settings\UserManagement;
use App\Livewire\Settings\Venue;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes(['register' => false]);

Route::middleware(['auth', 'default-password'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Controller
    Route::get('/file/view/{id}', [FileController::class, 'viewFile'])->name('file.view')->middleware('signed');

    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Incoming
    Route::get('incoming/requests', Requests::class)->name('incoming.requests');
    Route::get('incoming/documents', Documents::class)->name('incoming.documents');

    // Outgoing
    Route::get('outgoing/', Outgoing::class)->name('outgoing');

    // Calendar
    Route::get('calendar/', Calendar::class)->name('calendar');

    // Settings
    Route::get('/settings/user-management', UserManagement::class)->name('user-management');
    Route::get('/settings/category', Category::class)->name('category');
    Route::get('/settings/sub-category', SubCategory::class)->name('sub-category');
});

Route::middleware(['auth'])->group(function () {
    // AccountSettings
    Route::get('/account-settings/change-password', ChangePassword::class)->name('change-password');
});

/* -------------------------------------------------------------------------- */

Livewire::setScriptRoute(function ($handle) {
    return Route::get('/clenro-dts/livewire/livewire.js', $handle);
});
Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/clenro-dts/livewire/update', $handle);
});
