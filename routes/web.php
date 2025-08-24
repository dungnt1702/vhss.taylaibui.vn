<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;

Route::get('/', function () {
    return view('welcome');
})->middleware(['auth', 'verified'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/profile', function () {
    return view('profile.edit');
})->middleware(['auth', 'verified'])->name('profile.edit');

// Vehicle Management Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/vehicles/create', [VehicleController::class, 'create'])->name('vehicles.create');
    Route::post('/vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
    Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');
    Route::get('/vehicles/{vehicle}/edit', [VehicleController::class, 'edit'])->name('vehicles.edit');
    Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update');
    Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');
    
    // Vehicle Status Management
    Route::patch('/vehicles/{vehicle}/status', [VehicleController::class, 'updateStatus'])->name('vehicles.updateStatus');
    Route::patch('/vehicles/{vehicle}/route', [VehicleController::class, 'updateRoute'])->name('vehicles.updateRoute');
    
    // Vehicle Attributes Management (Admin only)
    Route::get('/vehicles/attributes', [VehicleController::class, 'attributes'])->name('vehicles.attributes');
    
    // API Routes for AJAX
    Route::get('/api/vehicles', [VehicleController::class, 'getVehicles'])->name('api.vehicles.index');
});

require __DIR__.'/auth.php';
