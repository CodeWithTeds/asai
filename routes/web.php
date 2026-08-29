<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobPostingController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');
Route::inertia('/events', 'Events/Public')->name('events');
Route::inertia('/careers', 'JobPostings/Public')->name('careers');

Route::post('job-postings/{jobPosting}/apply', [JobApplicationController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('job-postings.apply');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::resource('events/manage', EventController::class)
        ->except(['show', 'create', 'edit'])
        ->names('events.manage')
        ->parameters(['manage' => 'event']);

    Route::resource('job-postings', JobPostingController::class)
        ->except(['show', 'create', 'edit']);

    Route::get('job-applications', [JobApplicationController::class, 'index'])->name('job-applications.index');
    Route::get('job-applications/{jobApplication}', [JobApplicationController::class, 'show'])->name('job-applications.show');
    Route::get('/job-applications/{jobApplication}/resume', [JobApplicationController::class, 'resume'])
        ->name('job-applications.resume');
});

// Direct storage file server fallback for shared hosting environments
Route::get('/storage/{path}', function (string $path) {
    $publicFilePath = public_path('storage/' . $path);
    if (file_exists($publicFilePath) && is_file($publicFilePath)) {
        return response()->file($publicFilePath);
    }

    $storageFilePath = storage_path('app/public/' . $path);
    if (file_exists($storageFilePath) && is_file($storageFilePath)) {
        return response()->file($storageFilePath);
    }

    abort(404);
})->where('path', '.*')->name('storage.serve');

require __DIR__ . '/settings.php';
