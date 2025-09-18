# Vehicles Module Functions

## Base Module Functions (VehiclesModule)

### Initialization
- `init()` - Initialize the module
- `destroy()` - Cleanup and destroy the module

### Global Functions
- `openWorkshopModal(vehicleId)` - Open workshop modal for specific vehicle
- `closeWorkshopModal()` - Close workshop modal
- `openStatusModal(vehicleId)` - Open status modal for specific vehicle
- `closeStatusModal()` - Close status modal
- `showNotificationModal(title, message, type)` - Show notification modal
- `closeNotificationModal()` - Close notification modal
- `openVehicleDetailModal(vehicleId)` - Open vehicle detail modal
- `closeVehicleDetailModal()` - Close vehicle detail modal

### Event Management
- `addEventListener(element, event, handler, options)` - Add tracked event listener
- `removeEventListener(element, event, handler)` - Remove event listener

## Active Vehicles Functions (ActiveVehicles)

### Table Management
- `updateTimerVehiclesTable()` - Update timer vehicles table
- `populateTimerTable(vehicles)` - Populate timer table with vehicle data
- `updateRunningVehiclesTable()` - Update running vehicles table
- `populateRunningTable(vehicles)` - Populate running table with vehicle data
- `updateRouteVehiclesTable()` - Update route vehicles table
- `populateRouteTable(vehicles)` - Populate route table with vehicle data

### Vehicle Operations
- `handleAssignTimer(vehicleIds)` - Assign timer to selected vehicles
- `handleAssignRoute(vehicleIds)` - Assign route to selected vehicles
- `returnToYardSilently(vehicleIds)` - Return vehicles to yard silently
- `setupBulkWorkshopTransfer(vehicleIds)` - Setup bulk workshop transfer

### UI Management
- `setupSectionsVisibility()` - Ensure all sections are visible
- `setupStatsUpdater()` - Setup stats updater for counters
- `setupMobileHeaders()` - Setup mobile-specific headers (removed)

## Running Vehicles Functions (RunningVehicles)

### Vehicle Management
- `pauseVehicle(vehicleId)` - Pause a running vehicle
- `resumeVehicle(vehicleId)` - Resume a paused vehicle
- `completeVehicle(vehicleId)` - Mark vehicle as completed

### Status Updates
- `updateVehicleStatus(vehicleId, status)` - Update vehicle status
- `refreshRunningTable()` - Refresh running vehicles table

## Workshop Vehicles Functions (WorkshopVehicles)

### Workshop Operations
- `assignToWorkshop(vehicleId, workshopType)` - Assign vehicle to workshop
- `updateWorkshopStatus(vehicleId, status)` - Update workshop status
- `completeWorkshopWork(vehicleId)` - Complete workshop work

### Technical Updates
- `setupTechnicalUpdateModal(vehicleId)` - Setup technical update modal
- `updateCategoryOptions(issueType)` - Update category options based on issue type
- `submitTechnicalUpdate(vehicleId, data)` - Submit technical update

## Common Utility Functions

### Data Fetching
- `fetchVehiclesByStatus(status)` - Fetch vehicles by status
- `fetchVehicleDetails(vehicleId)` - Fetch vehicle details
- `updateVehicleData(vehicleId, data)` - Update vehicle data

### UI Helpers
- `showLoading(element)` - Show loading state
- `hideLoading(element)` - Hide loading state
- `showError(message)` - Show error message
- `showSuccess(message)` - Show success message

### Validation
- `validateVehicleSelection(vehicleIds)` - Validate vehicle selection
- `validateWorkshopData(data)` - Validate workshop data
- `validateStatusUpdate(data)` - Validate status update data
