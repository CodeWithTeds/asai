<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobPostingController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');
Route::inertia('/news', 'Announcements/Public')->name('news');

Route::post('job-postings/{jobPosting}/apply', [JobApplicationController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('job-postings.apply');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

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
