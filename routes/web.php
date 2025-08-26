<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\vehicles\ActiveVehiclesController;
use App\Http\Controllers\vehicles\VehiclesListController;
use App\Http\Controllers\vehicles\GridDisplayController;
use App\Http\Controllers\vehicles\VehicleAttributesController;
use App\Http\Controllers\vehicles\VehicleManagementController;

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
    // Main vehicle management
    Route::get('/vehicles', [VehicleManagementController::class, 'index'])->name('vehicles.index');
    Route::get('/vehicles/create', [VehicleManagementController::class, 'create'])->name('vehicles.create');
    Route::post('/vehicles', [VehicleManagementController::class, 'store'])->name('vehicles.store');
    Route::get('/vehicles/{vehicle}', [VehicleManagementController::class, 'show'])->name('vehicles.show');
    Route::get('/vehicles/{vehicle}/edit', [VehicleManagementController::class, 'edit'])->name('vehicles.edit');
    Route::put('/vehicles/{vehicle}', [VehicleManagementController::class, 'update'])->name('vehicles.update');
    Route::delete('/vehicles/{vehicle}', [VehicleManagementController::class, 'destroy'])->name('vehicles.destroy');
    
    // Vehicle Status and Route Management
    Route::patch('/vehicles/{vehicle}/status', [VehicleManagementController::class, 'updateStatus'])->name('vehicles.updateStatus');
    Route::patch('/vehicles/{vehicle}/route', [VehicleManagementController::class, 'updateRoute'])->name('vehicles.updateRoute');
    Route::patch('/vehicles/{vehicle}/workshop', [VehicleManagementController::class, 'moveToWorkshop'])->name('vehicles.moveToWorkshop');
    
    // Vehicle Attributes
    Route::get('/vehicles/attributes', [VehicleAttributesController::class, 'index'])->name('vehicles.attributes');
    
    // Vehicles List
    Route::get('/vehicles-list', [VehiclesListController::class, 'index'])->name('vehicles.list');
    
    // Grid Display
    Route::get('/grid-display', [GridDisplayController::class, 'index'])->name('vehicles.grid');
    
    // Active Vehicles
    Route::get('/active-vehicles', [ActiveVehiclesController::class, 'index'])->name('active-vehicles.index');
    
    // API Routes
    Route::get('/api/vehicles', [VehiclesListController::class, 'getVehicles'])->name('api.vehicles.get');
    Route::get('/api/vehicles/grid', [GridDisplayController::class, 'getVehiclesForGrid'])->name('api.vehicles.grid');
    Route::get('/api/vehicles/attributes', [VehicleAttributesController::class, 'getAllAttributes'])->name('api.vehicles.attributes');
    Route::get('/api/vehicles/attributes/{type}', [VehicleAttributesController::class, 'getAttributesByType'])->name('api.vehicles.attributes.type');
    Route::get('/api/vehicles/{id}/data', [VehicleManagementController::class, 'getVehicleData'])->name('api.vehicles.data');
    
    // Active Vehicles API
    Route::post('/api/active-vehicles/start-timer', [ActiveVehiclesController::class, 'startTimer'])->name('api.active-vehicles.start-timer');
    Route::post('/api/active-vehicles/assign-route', [ActiveVehiclesController::class, 'assignRoute'])->name('api.active-vehicles.assign-route');
    Route::post('/api/active-vehicles/return-yard', [ActiveVehiclesController::class, 'returnToYard'])->name('api.active-vehicles.return-yard');
    Route::patch('/api/active-vehicles/{vehicle}/pause', [ActiveVehiclesController::class, 'pause'])->name('api.active-vehicles.pause');
    Route::patch('/api/active-vehicles/{vehicle}/resume', [ActiveVehiclesController::class, 'resume'])->name('api.active-vehicles.resume');
    Route::get('/api/active-vehicles/by-status', [ActiveVehiclesController::class, 'getVehiclesByStatus'])->name('api.active-vehicles.by-status');
});

require __DIR__.'/auth.php';
