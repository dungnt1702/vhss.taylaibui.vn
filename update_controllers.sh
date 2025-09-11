#!/bin/bash

# Update WaitingVehiclesController
cat > app/Http/Controllers/vehicles/WaitingVehiclesController.php << 'WAITING_EOF'
<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class WaitingVehiclesController extends Controller
{
    /**
     * Display waiting vehicles (xe đang chờ)
     */
    public function index()
    {
        $vehicles = Vehicle::waiting()->latest()->get();
        return view('vehicles.waiting_vehicles', compact('vehicles'));
    }

    /**
     * Get waiting vehicles for API
     */
    public function getWaitingVehicles()
    {
        $vehicles = Vehicle::waiting()->latest()->get();
        return response()->json(['success' => true, 'vehicles' => $vehicles]);
    }
}
WAITING_EOF

# Update RunningVehiclesController
cat > app/Http/Controllers/vehicles/RunningVehiclesController.php << 'RUNNING_EOF'
<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class RunningVehiclesController extends Controller
{
    /**
     * Display running vehicles (xe đang chạy)
     */
    public function index()
    {
        $vehicles = Vehicle::running()->latest()->get();
        return view('vehicles.running_vehicles', compact('vehicles'));
    }

    /**
     * Get running vehicles for API
     */
    public function getRunningVehicles()
    {
        $vehicles = Vehicle::running()->latest()->get();
        return response()->json(['success' => true, 'vehicles' => $vehicles]);
    }
}
RUNNING_EOF

# Update PausedVehiclesController
cat > app/Http/Controllers/vehicles/PausedVehiclesController.php << 'PAUSED_EOF'
<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class PausedVehiclesController extends Controller
{
    /**
     * Display paused vehicles (xe tạm dừng)
     */
    public function index()
    {
        $vehicles = Vehicle::paused()->latest()->get();
        return view('vehicles.paused_vehicles', compact('vehicles'));
    }

    /**
     * Get paused vehicles for API
     */
    public function getPausedVehicles()
    {
        $vehicles = Vehicle::paused()->latest()->get();
        return response()->json(['success' => true, 'vehicles' => $vehicles]);
    }
}
PAUSED_EOF

# Update ExpiredVehiclesController
cat > app/Http/Controllers/vehicles/ExpiredVehiclesController.php << 'EXPIRED_EOF'
<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class ExpiredVehiclesController extends Controller
{
    /**
     * Display expired vehicles (xe hết giờ)
     */
    public function index()
    {
        $vehicles = Vehicle::expired()->latest()->get();
        return view('vehicles.expired_vehicles', compact('vehicles'));
    }

    /**
     * Get expired vehicles for API
     */
    public function getExpiredVehicles()
    {
        $vehicles = Vehicle::expired()->latest()->get();
        return response()->json(['success' => true, 'vehicles' => $vehicles]);
    }
}
EXPIRED_EOF

# Update WorkshopVehiclesController
cat > app/Http/Controllers/vehicles/WorkshopVehiclesController.php << 'WORKSHOP_EOF'
<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class WorkshopVehiclesController extends Controller
{
    /**
     * Display workshop vehicles (xe trong xưởng)
     */
    public function index()
    {
        $vehicles = Vehicle::inactive()->latest()->get();
        return view('vehicles.workshop_vehicles', compact('vehicles'));
    }

    /**
     * Get workshop vehicles for API
     */
    public function getWorkshopVehicles()
    {
        $vehicles = Vehicle::inactive()->latest()->get();
        return response()->json(['success' => true, 'vehicles' => $vehicles]);
    }
}
WORKSHOP_EOF

# Update RepairingVehiclesController
cat > app/Http/Controllers/vehicles/RepairingVehiclesController.php << 'REPAIRING_EOF'
<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class RepairingVehiclesController extends Controller
{
    /**
     * Display repairing vehicles (xe đang sửa chữa)
     */
    public function index()
    {
        $vehicles = Vehicle::where('status', 'repairing')->latest()->get();
        return view('vehicles.repairing_vehicles', compact('vehicles'));
    }

    /**
     * Get repairing vehicles for API
     */
    public function getRepairingVehicles()
    {
        $vehicles = Vehicle::where('status', 'repairing')->latest()->get();
        return response()->json(['success' => true, 'vehicles' => $vehicles]);
    }
}
REPAIRING_EOF

# Update MaintainingVehiclesController
cat > app/Http/Controllers/vehicles/MaintainingVehiclesController.php << 'MAINTAINING_EOF'
<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class MaintainingVehiclesController extends Controller
{
    /**
     * Display maintaining vehicles (xe đang bảo trì)
     */
    public function index()
    {
        $vehicles = Vehicle::where('status', 'maintaining')->latest()->get();
        return view('vehicles.maintaining_vehicles', compact('vehicles'));
    }

    /**
     * Get maintaining vehicles for API
     */
    public function getMaintainingVehicles()
    {
        $vehicles = Vehicle::where('status', 'maintaining')->latest()->get();
        return response()->json(['success' => true, 'vehicles' => $vehicles]);
    }
}
MAINTAINING_EOF

# Update AttributesListController
cat > app/Http/Controllers/vehicles/AttributesListController.php << 'ATTRIBUTES_EOF'
<?php

namespace App\Http\Controllers\vehicles;

use App\Http\Controllers\Controller;
use App\Models\VehicleAttribute;
use Illuminate\Http\Request;

class AttributesListController extends Controller
{
    /**
     * Display vehicle attributes (thuộc tính xe)
     */
    public function index()
    {
        $colors = VehicleAttribute::getColors();
        $seats = VehicleAttribute::getSeats();
        $powerOptions = VehicleAttribute::getPowerOptions();
        $wheelSizes = VehicleAttribute::getWheelSizes();

        return view('vehicles.attributes_list', compact(
            'colors', 
            'seats', 
            'powerOptions', 
            'wheelSizes'
        ));
    }

    /**
     * Get all attributes for API
     */
    public function getAllAttributes()
    {
        $attributes = [
            'colors' => VehicleAttribute::getColors(),
            'seats' => VehicleAttribute::getSeats(),
            'power_options' => VehicleAttribute::getPowerOptions(),
            'wheel_sizes' => VehicleAttribute::getWheelSizes()
        ];

        return response()->json([
            'success' => true,
            'attributes' => $attributes
        ]);
    }
}
ATTRIBUTES_EOF

echo "All controllers updated successfully!"
