<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\JobPostingController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    Route::resource('announcements', AnnouncementController::class)
        ->except(['show', 'create', 'edit']);

    Route::resource('job-postings', JobPostingController::class)
        ->except(['show', 'create', 'edit']);
});

require __DIR__.'/settings.php';
