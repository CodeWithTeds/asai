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

// Temporary debug route — REMOVE after fixing
Route::get('/storage-debug', function () {
    $storagePath = storage_path('app/public');
    $basePath = base_path();
    $publicPath = public_path();

    $eventsDir = storage_path('app/public/events/images');
    $files = is_dir($eventsDir) ? scandir($eventsDir) : ['DIR NOT FOUND'];

    // Check for orphaned backup
    $backups = glob(base_path('_storage_backup_*'));

    return response()->json([
        'base_path' => $basePath,
        'storage_path' => $storagePath,
        'public_path' => $publicPath,
        'storage_app_public_exists' => is_dir($storagePath),
        'events_images_dir_exists' => is_dir($eventsDir),
        'events_images_files' => $files,
        'orphaned_backups' => $backups,
        'public_storage_link_exists' => file_exists(public_path('storage')),
    ]);
});

// Serve storage files directly without requiring symlink
Route::get('/storage/{path}', function (string $path) {
    $filePath = storage_path('app/public/' . $path);
    if (! file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*')->name('storage.serve');

require __DIR__ . '/settings.php';
