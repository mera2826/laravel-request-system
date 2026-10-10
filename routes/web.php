<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Service request routes
    Route::get('/requests', [ServiceRequestController::class, 'index'])
        ->name('requests.index');

    Route::get('/requests/create', [ServiceRequestController::class, 'create'])
        ->name('requests.create');

    Route::post('/requests', [ServiceRequestController::class, 'store'])
        ->name('requests.store');

    Route::get('/requests/{serviceRequest}', [ServiceRequestController::class, 'show'])
        ->name('requests.show');

    Route::patch('/requests/{serviceRequest}/status', [ServiceRequestController::class, 'updateStatus'])
        ->name('requests.updateStatus');
});

require __DIR__.'/auth.php';