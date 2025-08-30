<?php

use Illuminate\Support\Facades\Route;
// ActiveVehiclesController đã được migration sang VehicleOperationsController
use App\Http\Controllers\vehicles\ReadyVehiclesController;
use App\Http\Controllers\vehicles\WaitingVehiclesController;
use App\Http\Controllers\vehicles\RunningVehiclesController;
use App\Http\Controllers\vehicles\PausedVehiclesController;
use App\Http\Controllers\vehicles\ExpiredVehiclesController;
use App\Http\Controllers\vehicles\WorkshopVehiclesController;
use App\Http\Controllers\vehicles\RepairingVehiclesController;
use App\Http\Controllers\vehicles\MaintainingVehiclesController;
use App\Http\Controllers\vehicles\VehiclesListController;
use App\Http\Controllers\vehicles\VehicleOperationsController;

use App\Http\Controllers\vehicles\AttributesListController;
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
    
    // Ready Vehicles (Xe sẵn sàng chạy)
    Route::get('/ready-vehicles', [ReadyVehiclesController::class, 'index'])->name('ready-vehicles.index');
    Route::post('/ready-vehicles/start-timer', [ReadyVehiclesController::class, 'startTimer'])->name('ready-vehicles.start-timer');
    Route::post('/ready-vehicles/assign-route', [ReadyVehiclesController::class, 'assignRoute'])->name('ready-vehicles.assign-route');
    Route::post('/ready-vehicles/move-workshop', [ReadyVehiclesController::class, 'moveToWorkshop'])->name('ready-vehicles.move-workshop');
    
    // Waiting Vehicles (Xe đang chờ)
    Route::get('/waiting-vehicles', [WaitingVehiclesController::class, 'index'])->name('waiting-vehicles.index');
    
    // Running Vehicles (Xe đang chạy)
    Route::get('/running-vehicles', [RunningVehiclesController::class, 'index'])
        ->middleware('update.expired.vehicles')
        ->name('running-vehicles.index');
    
    // Paused Vehicles (Xe tạm dừng)
    Route::get('/paused-vehicles', [PausedVehiclesController::class, 'index'])->name('paused-vehicles.index');
    
    // Expired Vehicles (Xe hết giờ)
    Route::get('/expired-vehicles', [ExpiredVehiclesController::class, 'index'])->name('expired-vehicles.index');
    
    // Workshop Vehicles (Xe trong xưởng)
    Route::get('/workshop-vehicles', [WorkshopVehiclesController::class, 'index'])->name('workshop-vehicles.index');
    
    // Repairing Vehicles (Xe đang sửa chữa)
    Route::get('/repairing-vehicles', [RepairingVehiclesController::class, 'index'])->name('repairing-vehicles.index');
    
    // Maintaining Vehicles (Xe đang bảo trì)
    Route::get('/maintaining-vehicles', [MaintainingVehiclesController::class, 'index'])->name('maintaining-vehicles.index');
    
    // Vehicle Attributes
    Route::get('/attributes-list', [AttributesListController::class, 'index'])->name('attributes-list.index');
    
    // Vehicles List
    Route::get('/vehicles-list', [VehiclesListController::class, 'index'])->name('vehicles.list');
    

    

    
    // API Routes
    Route::get('/api/vehicles', [VehiclesListController::class, 'getVehicles'])->name('api.vehicles.get');

    Route::get('/api/attributes', [AttributesListController::class, 'getAllAttributes'])->name('api.attributes.get');
    Route::get('/api/vehicles/{id}/data', [VehicleManagementController::class, 'getVehicleData'])->name('api.vehicles.data');
    
    // Vehicle Operations API (Centralized)
    Route::post('/api/vehicles/start-timer', [VehicleOperationsController::class, 'startTimer'])->name('api.vehicles.start-timer');
    Route::post('/api/vehicles/assign-route', [VehicleOperationsController::class, 'assignRoute'])->name('api.vehicles.assign-route');
    Route::post('/api/vehicles/return-yard', [VehicleOperationsController::class, 'returnToYard'])->name('api.vehicles.return-yard');
    Route::patch('/api/vehicles/{vehicle}/pause', [VehicleOperationsController::class, 'pause'])->name('api.vehicles.pause');
    Route::patch('/api/vehicles/{vehicle}/resume', [VehicleOperationsController::class, 'resume'])->name('api.vehicles.resume');
    Route::post('/api/vehicles/add-time', [VehicleOperationsController::class, 'addTime'])->name('api.vehicles.add-time');
    Route::post('/api/vehicles/move-workshop', [VehicleOperationsController::class, 'moveToWorkshop'])->name('api.vehicles.move-workshop');
    Route::get('/api/vehicles/by-status', [VehicleOperationsController::class, 'getVehiclesByStatus'])->name('api.vehicles.by-status');
    
    // Legacy API Routes (redirected to VehicleOperationsController for backward compatibility)
    Route::post('/api/active-vehicles/start-timer', [VehicleOperationsController::class, 'startTimer'])->name('api.active-vehicles.start-timer');
    Route::post('/api/active-vehicles/assign-route', [VehicleOperationsController::class, 'assignRoute'])->name('api.active-vehicles.assign-route');
    Route::post('/api/active-vehicles/return-yard', [VehicleOperationsController::class, 'returnToYard'])->name('api.active-vehicles.return-yard');
    Route::patch('/api/active-vehicles/{vehicle}/pause', [VehicleOperationsController::class, 'pause'])->name('api.active-vehicles.pause');
    Route::patch('/api/active-vehicles/{vehicle}/resume', [VehicleOperationsController::class, 'resume'])->name('api.active-vehicles.resume');
    Route::get('/api/active-vehicles/by-status', [VehicleOperationsController::class, 'getVehiclesByStatus'])->name('api.active-vehicles.by-status');
});

require __DIR__.'/auth.php';
