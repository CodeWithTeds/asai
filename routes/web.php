<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobPostingController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::post('/job-postings/{jobPosting}/apply', [JobApplicationController::class, 'store'])
    ->name('job-postings.apply');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    Route::resource('announcements', AnnouncementController::class)
        ->except(['show', 'create', 'edit']);

    Route::resource('job-postings', JobPostingController::class)
        ->except(['show', 'create', 'edit']);

    Route::get('job-applications', [JobApplicationController::class, 'index'])->name('job-applications.index');
    Route::get('job-applications/{jobApplication}', [JobApplicationController::class, 'show'])->name('job-applications.show');
    Route::get('/job-applications/{jobApplication}/resume', [JobApplicationController::class, 'resume'])
        ->name('job-applications.resume');
});

require __DIR__.'/settings.php';
