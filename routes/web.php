<?php

use App\Http\Controllers\FileController;
use App\Livewire\Accomplishments;
use App\Livewire\AccountSettings\ChangePassword;
use App\Livewire\Calendar;
use App\Livewire\Dashboard;
use App\Livewire\Incoming\Documents;
use App\Livewire\Incoming\Requests;
use App\Livewire\Outgoing;
use App\Livewire\Settings\AccomplishmentCategory;
use App\Livewire\Settings\Category;
use App\Livewire\Settings\Divisions;
use App\Livewire\Settings\IncomingDocumentCategory;
use App\Livewire\Settings\IncomingRequestCategory;
use App\Livewire\Settings\OutgoingCategory;
use App\Livewire\Settings\UserManagement;
use App\Livewire\Settings\UserPermissions;
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

    // Accomplishments
    Route::get('accomplishments/', Accomplishments::class)->name('accomplishments');

    // Reference
    //// Route::get('/reference/category', Category::class)->name('category');
    Route::get('/reference/user-management', UserManagement::class)->name('user-management');
    Route::get('/reference/incoming-request-category', IncomingRequestCategory::class)->name('incoming-request-category');
    Route::get('/reference/incoming-document-category', IncomingDocumentCategory::class)->name('incoming-document-category');
    Route::get('/references/outgoing-category', OutgoingCategory::class)->name('outgoing-category');
    Route::get('/references/divisions', Divisions::class)->name('divisions');
    Route::get('/references/accomplishment-category', AccomplishmentCategory::class)->name('accomplishment-category');

    // Super-admin
    Route::get('/reference/user-permissions', UserPermissions::class)->name('user-permissions');
});

Route::middleware(['auth'])->group(function () {
    // AccountSettings
    Route::get('/account-settings/change-password', ChangePassword::class)->name('change-password');
});

/* -------------------------------------------------------------------------- */

Livewire::setScriptRoute(function ($handle) {
    return Route::get('/clenroms/livewire/livewire.js', $handle);
});
Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/clenroms/livewire/update', $handle);
});
