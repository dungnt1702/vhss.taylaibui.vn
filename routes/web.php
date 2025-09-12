<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// ActiveVehiclesController đã được migration sang VehicleOperationsController
use App\Http\Controllers\vehicles\ActiveVehiclesController;
use App\Http\Controllers\vehicles\ReadyVehiclesController;
use App\Http\Controllers\vehicles\RunningVehiclesController;
use App\Http\Controllers\vehicles\PausedVehiclesController;
use App\Http\Controllers\vehicles\ExpiredVehiclesController;
use App\Http\Controllers\vehicles\WorkshopVehiclesController;
use App\Http\Controllers\vehicles\RepairingVehiclesController;
use App\Http\Controllers\vehicles\VehiclesListController;
use App\Http\Controllers\vehicles\VehicleOperationsController;

use App\Http\Controllers\vehicles\AttributesListController;
use App\Http\Controllers\vehicles\VehicleManagementController;
use App\Http\Controllers\RoleManagementController;
use App\Http\Controllers\UserManagementController;

Route::get('/', function () {
    return view('welcome');
})->middleware(['auth', 'verified'])->name('home');

// Test route for role management (temporary)
Route::get('/test-roles', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->name('test-roles');

// Test route for role management with session (temporary)
Route::get('/test-roles-session', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->name('test-roles-session');

// Test route for role management with full session (temporary)
Route::get('/test-roles-full', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->name('test-roles-full');

// Test route for role management without middleware (temporary)
Route::get('/test-roles-no-auth', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->name('test-roles-no-auth');

// Test route for role management with auth middleware only (temporary)
Route::get('/test-roles-auth-only', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-auth-only');

// Test route for role management with full session and auth middleware (temporary)
Route::get('/test-roles-full-auth', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-full-auth');

// Test route for role management with full session and auth middleware but no verified (temporary)
Route::get('/test-roles-full-auth-no-verified', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-full-auth-no-verified');

// Test route for role management with full session and auth middleware but no verified (temporary)
Route::get('/test-roles-full-auth-no-verified-2', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-full-auth-no-verified-2');

// Test route for role management with full session and auth middleware but no verified (temporary)
Route::get('/test-roles-full-auth-no-verified-3', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-full-auth-no-verified-3');

// Test route for role management with full session and auth middleware but no verified (temporary)
Route::get('/test-roles-full-auth-no-verified-4', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-full-auth-no-verified-4');

// Test route for role management with full session and auth middleware but no verified (temporary)
Route::get('/test-roles-full-auth-no-verified-5', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-full-auth-no-verified-5');

// Test route for role management with full session and auth middleware but no verified (temporary)
Route::get('/test-roles-full-auth-no-verified-6', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-full-auth-no-verified-6');

// Test route for role management with full session and auth middleware but no verified (temporary)
Route::get('/test-roles-full-auth-no-verified-7', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-full-auth-no-verified-7');

// Test route for role management with full session and auth middleware but no verified (temporary)
Route::get('/test-roles-full-auth-no-verified-8', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-full-auth-no-verified-8');

// Test route for role management with full session and auth middleware but no verified (temporary)
Route::get('/test-roles-full-auth-no-verified-9', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-full-auth-no-verified-9');

// Test route for role management with full session and auth middleware but no verified (temporary)
Route::get('/test-roles-full-auth-no-verified-10', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-full-auth-no-verified-10');

// Test route for role management with full session and auth middleware but no verified (temporary)
Route::get('/test-roles-full-auth-no-verified-11', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-full-auth-no-verified-11');

// Test route for role management with full session and auth middleware but no verified (temporary)
Route::get('/test-roles-full-auth-no-verified-12', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-full-auth-no-verified-12');

// Test route for role management with full session and auth middleware but no verified (temporary)
Route::get('/test-roles-full-auth-no-verified-13', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-full-auth-no-verified-13');

// Test route for role management with full session and auth middleware but no verified (temporary)
Route::get('/test-roles-full-auth-no-verified-14', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-full-auth-no-verified-14');

// Test route for role management with full session and auth middleware but no verified (temporary)
Route::get('/test-roles-full-auth-no-verified-15', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-full-auth-no-verified-15');

// Test route for role management with full session and auth middleware but no verified (temporary)
Route::get('/test-roles-full-auth-no-verified-16', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-full-auth-no-verified-16');

// Test route for role management with full session and auth middleware but no verified (temporary)
Route::get('/test-roles-full-auth-no-verified-17', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-full-auth-no-verified-17');

// Test route for role management with full session and auth middleware but no verified (temporary)
Route::get('/test-roles-full-auth-no-verified-18', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-full-auth-no-verified-18');

// Test route for role management with full session and auth middleware but no verified (temporary)
Route::get('/test-roles-full-auth-no-verified-19', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-full-auth-no-verified-19');

// Test route for role management with full session and auth middleware but no verified (temporary)
Route::get('/test-roles-full-auth-no-verified-20', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-full-auth-no-verified-20');

// Test route for role management with full session but no middleware (temporary)
Route::get('/test-roles-full-no-middleware', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->name('test-roles-full-no-middleware');

// Test route for role management with auth middleware but no session (temporary)
Route::get('/test-roles-auth-no-session', function () {
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-auth-no-session');

// Test route for role management with auth middleware and full session (temporary)
Route::get('/test-roles-auth-full-session', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->regenerate();
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-auth-full-session');

// Test route for role management with auth middleware and full session but no regenerate (temporary)
Route::get('/test-roles-auth-full-session-no-regenerate', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    session()->put('auth.password_confirmed_at', time());
    session()->put('auth.user', $user->id);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-auth-full-session-no-regenerate');

// Test route for role management with auth middleware and full session but no session put (temporary)
Route::get('/test-roles-auth-full-session-no-put', function () {
    $user = App\Models\User::first();
    Auth::login($user);
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-auth-full-session-no-put');

// Test route for role management with auth middleware and full session but no Auth::login (temporary)
Route::get('/test-roles-auth-full-session-no-login', function () {
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-auth-full-session-no-login');

// Test route for role management with auth middleware and full session but no Auth::login (temporary)
Route::get('/test-roles-auth-full-session-no-login-2', function () {
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-auth-full-session-no-login-2');

// Test route for role management with auth middleware and full session but no Auth::login (temporary)
Route::get('/test-roles-auth-full-session-no-login-3', function () {
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-auth-full-session-no-login-3');

// Test route for role management with auth middleware and full session but no Auth::login (temporary)
Route::get('/test-roles-auth-full-session-no-login-4', function () {
    $roles = App\Models\Role::with('permissions')->paginate(10);
    return view('roles.index', compact('roles'));
})->middleware('auth')->name('test-roles-auth-full-session-no-login-4');













Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Role Management Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('roles', RoleManagementController::class);
    Route::get('/roles/{role}/permissions', [RoleManagementController::class, 'permissions'])->name('roles.permissions');
    Route::post('/roles/{role}/permissions', [RoleManagementController::class, 'updatePermissions'])->name('roles.permissions.update');
    Route::get('/roles/{role}/users', [RoleManagementController::class, 'users'])->name('roles.users');
    Route::post('/users/{user}/assign-role', [RoleManagementController::class, 'assignRole'])->name('users.assign-role');
});

// User Management Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserManagementController::class);
    Route::post('/users/{user}/assign-role', [UserManagementController::class, 'assignRole'])->name('users.assign-role');
});

Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->middleware(['auth', 'verified'])->name('profile.edit');

// Profile routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Vehicle Management Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Main vehicle management
    Route::get('/vehicles', [VehicleManagementController::class, 'index'])->name('vehicles.index');
    
    // RESTful vehicle status routes
    Route::get('/vehicles/active', [VehicleManagementController::class, 'index'])->name('vehicles.active');
    Route::get('/vehicles/active/page/{page}', [VehicleManagementController::class, 'index'])->name('vehicles.active.page');
    Route::get('/vehicles/ready', [VehicleManagementController::class, 'index'])->name('vehicles.ready');
    Route::get('/vehicles/ready/page/{page}', [VehicleManagementController::class, 'index'])->name('vehicles.ready.page');
    Route::get('/vehicles/running', [VehicleManagementController::class, 'index'])->name('vehicles.running');
    Route::get('/vehicles/running/page/{page}', [VehicleManagementController::class, 'index'])->name('vehicles.running.page');
    Route::get('/vehicles/paused', [VehicleManagementController::class, 'index'])->name('vehicles.paused');
    Route::get('/vehicles/paused/page/{page}', [VehicleManagementController::class, 'index'])->name('vehicles.paused.page');
    Route::get('/vehicles/expired', [VehicleManagementController::class, 'index'])->name('vehicles.expired');
    Route::get('/vehicles/expired/page/{page}', [VehicleManagementController::class, 'index'])->name('vehicles.expired.page');
    Route::get('/vehicles/workshop', [VehicleManagementController::class, 'index'])->name('vehicles.workshop');
    Route::get('/vehicles/workshop/page/{page}', [VehicleManagementController::class, 'index'])->name('vehicles.workshop.page');
    Route::get('/vehicles/repairing', [VehicleManagementController::class, 'index'])->name('vehicles.repairing');
    Route::get('/vehicles/repairing/page/{page}', [VehicleManagementController::class, 'index'])->name('vehicles.repairing.page');
    Route::get('/vehicles/repairing/vehicle_id/{vehicle_id}', [VehicleManagementController::class, 'index'])->name('vehicles.repairing.vehicle');
    Route::get('/vehicles/attributes', [VehicleManagementController::class, 'index'])->name('vehicles.attributes');
    Route::get('/vehicles/attributes/page/{page}', [VehicleManagementController::class, 'index'])->name('vehicles.attributes.page');
    Route::get('/vehicles/list', [VehicleManagementController::class, 'index'])->name('vehicles.list');
    Route::get('/vehicles/list/page/{page}', [VehicleManagementController::class, 'index'])->name('vehicles.list.page');
    Route::get('/vehicles/create', [VehicleManagementController::class, 'create'])->name('vehicles.create');
    Route::post('/vehicles', [VehicleManagementController::class, 'store'])->name('vehicles.store');
    Route::get('/vehicles/{vehicle}', [VehicleManagementController::class, 'show'])->name('vehicles.show');
    Route::get('/vehicles/{vehicle}/edit', [VehicleManagementController::class, 'edit'])->name('vehicles.edit');
    Route::put('/vehicles/{vehicle}', [VehicleManagementController::class, 'update'])->name('vehicles.update');
    Route::put('/vehicles/{id}/notes', [VehicleManagementController::class, 'updateNotes'])->name('vehicles.updateNotes');
    Route::delete('/vehicles/{vehicle}', [VehicleManagementController::class, 'destroy'])->name('vehicles.destroy');
    
    // Vehicle Status and Route Management
    Route::patch('/vehicles/{vehicle}/status', [VehicleManagementController::class, 'updateStatus'])->name('vehicles.updateStatus');
    Route::patch('/vehicles/{vehicle}/route', [VehicleManagementController::class, 'updateRoute'])->name('vehicles.updateRoute');
    Route::patch('/vehicles/{vehicle}/workshop', [VehicleManagementController::class, 'moveToWorkshop'])->name('vehicles.moveToWorkshop');
    
    // Active Vehicles (Xe hoạt động)
    Route::get('/active-vehicles', [ActiveVehiclesController::class, 'index'])->name('active-vehicles.index');
    Route::post('/active-vehicles/start-timer', [ActiveVehiclesController::class, 'startTimer'])->name('active-vehicles.start-timer');
    Route::post('/active-vehicles/assign-route', [ActiveVehiclesController::class, 'assignRoute'])->name('active-vehicles.assign-route');
    Route::post('/active-vehicles/move-workshop', [ActiveVehiclesController::class, 'moveToWorkshop'])->name('active-vehicles.move-workshop');
    
    // Ready Vehicles (Xe sẵn sàng)
    Route::get('/ready-vehicles', [ReadyVehiclesController::class, 'index'])->name('ready-vehicles.index');
    
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
    
    // Vehicle Attributes
    Route::get('/attributes-list', [AttributesListController::class, 'index'])->name('attributes-list.index');
    
    // Vehicles List (legacy route - redirect to new RESTful route)
    Route::get('/vehicles-list', function() {
        return redirect()->route('vehicles.list');
    });
    

    

    
    // API Routes
    Route::get('/api/vehicles', [VehiclesListController::class, 'getVehicles'])->name('api.vehicles.get');

    Route::get('/api/attributes', [AttributesListController::class, 'getAllAttributes'])->name('api.attributes.get');
    Route::get('/api/vehicles/{id}/data', [VehicleManagementController::class, 'getVehicleData'])->name('api.vehicles.data');
    
    // Vehicle Operations API (Centralized)
    Route::post('/api/vehicles/start-timer', [VehicleOperationsController::class, 'startTimer'])->name('api.vehicles.start-timer');
    Route::post('/api/vehicles/assign-route', [VehicleOperationsController::class, 'assignRoute'])->name('api.vehicles.assign-route');
    Route::post('/api/vehicles/return-yard', [VehicleOperationsController::class, 'returnToYard'])->name('api.vehicles.return-yard');
    Route::post('/api/vehicles/return-yard-with-notes', [VehicleOperationsController::class, 'returnToYardWithNotes'])->name('api.vehicles.return-yard-with-notes');
    Route::post('/api/vehicles/technical-update', [VehicleOperationsController::class, 'technicalUpdate'])->name('api.vehicles.technical-update');
    Route::post('/api/technical-issues/{issue}/process', [VehicleOperationsController::class, 'processIssue'])->name('api.technical-issues.process');
Route::post('/api/technical-issues/{issue}/edit', [VehicleOperationsController::class, 'editIssue'])->name('api.technical-issues.edit');

// Maintenance Schedule Routes
Route::prefix('maintenance')->name('maintenance.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\MaintenanceScheduleController::class, 'dashboard'])->name('dashboard');
    Route::get('/schedules', [App\Http\Controllers\MaintenanceScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/calendar', [App\Http\Controllers\MaintenanceScheduleController::class, 'calendar'])->name('schedules.calendar');
    Route::get('/schedules/create', [App\Http\Controllers\MaintenanceScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/schedules', [App\Http\Controllers\MaintenanceScheduleController::class, 'store'])->name('schedules.store');
    Route::get('/schedules/{schedule}', [App\Http\Controllers\MaintenanceScheduleController::class, 'show'])->name('schedules.show');
    Route::get('/schedules/{schedule}/perform', [App\Http\Controllers\MaintenanceScheduleController::class, 'perform'])->name('schedules.perform');
    Route::post('/schedules/{schedule}/record', [App\Http\Controllers\MaintenanceScheduleController::class, 'storeRecord'])->name('schedules.record');
    Route::post('/schedules/mark-overdue', [App\Http\Controllers\MaintenanceScheduleController::class, 'markOverdue'])->name('schedules.mark-overdue');
});
    Route::patch('/api/vehicles/{vehicle}/pause', [VehicleOperationsController::class, 'pause'])->name('api.vehicles.pause');
    Route::patch('/api/vehicles/{vehicle}/resume', [VehicleOperationsController::class, 'resume'])->name('api.vehicles.resume');
    Route::post('/api/vehicles/add-time', [VehicleOperationsController::class, 'addTime'])->name('api.vehicles.add-time');
    Route::post('/api/vehicles/move-workshop', [VehicleOperationsController::class, 'moveToWorkshop'])->name('api.vehicles.move-workshop');
    Route::put('/api/vehicles/{vehicle}/update-notes', [VehicleOperationsController::class, 'updateNotes'])->name('api.vehicles.update-notes');
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
