# Vehicle JavaScript Architecture

## Overview
This directory contains the refactored JavaScript code for the VHSS vehicle management system, organized using ES6 classes and inheritance for better maintainability and code reuse.

## File Structure

### Core Classes
- **`VehicleBase.js`** - Base class containing common functionality for all vehicle statuses
- **`VehicleClasses.js`** - Entry point that imports and manages all vehicle classes

### Vehicle Status Classes
- **`ReadyVehicles.js`** - Manages ready vehicles (extends VehicleBase)
- **`WaitingVehicles.js`** - Manages waiting vehicles (extends VehicleBase)
- **`RunningVehicles.js`** - Manages running vehicles (extends VehicleBase)
- **`PausedVehicles.js`** - Manages paused vehicles (extends VehicleBase)
- **`ExpiredVehicles.js`** - Manages expired vehicles (extends VehicleBase)

### Legacy Files (Still in use)
- **`vehicle-forms.js`** - Vehicle form handling and modals
- **`vehicle-operations.js`** - Core vehicle operations and API calls
- **`vehicle-wrappers.js`** - UI wrapper functions and utilities
- **`vehicles-list.js`** - Vehicle list management
- **`vehicles.js`** - General vehicle utilities
- **`workshop-vehicles.js`** - Workshop-specific functionality
- **`repairing-vehicles.js`** - Repair-specific functionality
- **`maintaining-vehicles.js`** - Maintenance-specific functionality
- **`attributes-list.js`** - Vehicle attributes management

## Architecture Benefits

### 1. Code Reuse
- Common functionality is centralized in `VehicleBase`
- Each status-specific class inherits from `VehicleBase`
- Reduces code duplication significantly

### 2. Maintainability
- Clear separation of concerns
- Easy to add new vehicle statuses
- Consistent API across all vehicle types

### 3. Extensibility
- New features can be added to base class for all vehicles
- Status-specific features can be added to individual classes
- Easy to override base functionality when needed

## Usage

### In Views
```blade
@push('scripts')
    @vite(['resources/js/vehicles/VehicleClasses.js'])
@endpush
```

### In JavaScript
```javascript
// VehicleBase is available globally
const vehicle = new VehicleBase('Page Name');
vehicle.init();

// Or use specific classes
const readyVehicles = new ReadyVehicles();
readyVehicles.init();
```

## Key Features

### VehicleBase Class
- **Event Handling**: Automatic setup of common action listeners
- **Countdown Timers**: Built-in countdown functionality
- **API Calls**: Standardized API communication with error handling
- **Notifications**: User feedback system
- **Button States**: Loading states and restoration
- **Vehicle Selection**: Checkbox management and bulk actions

### Status-Specific Classes
- **ReadyVehicles**: Duration selection, route assignment, workshop transfer
- **WaitingVehicles**: Timer start, route assignment, workshop transfer
- **RunningVehicles**: Timer pause/resume, return to yard
- **PausedVehicles**: Timer resume, return to yard
- **ExpiredVehicles**: Timer extension, return to yard

## Migration Notes

### What Was Changed
- Replaced old procedural JavaScript files with class-based architecture
- Centralized common functionality in `VehicleBase`
- Updated all views to use `VehicleClasses.js` entry point
- Maintained backward compatibility through global exports

### What Was Removed
- `ready-vehicles.js` (old)
- `waiting-vehicles.js` (old)
- `running-vehicles.js` (old)
- `paused-vehicles.js` (old)
- `expired-vehicles.js` (old)

### What Was Kept
- All CSS files remain unchanged
- Non-vehicle-status JavaScript files remain unchanged
- All existing functionality is preserved

## Future Enhancements

### Potential Improvements
1. **TypeScript**: Convert to TypeScript for better type safety
2. **State Management**: Implement centralized state management
3. **Event System**: Add custom event system for better decoupling
4. **Testing**: Add unit tests for each class
5. **Documentation**: Add JSDoc comments for all methods

### Adding New Vehicle Statuses
1. Create new class extending `VehicleBase`
2. Implement status-specific functionality
3. Add to `VehicleClasses.js` entry point
4. Update views to use new class
5. Add any necessary CSS styling

## Troubleshooting

### Common Issues
1. **VehicleBase not defined**: Ensure `VehicleClasses.js` is loaded before other scripts
2. **Import errors**: Check that all files use ES6 import/export syntax
3. **Build failures**: Verify Vite configuration includes all necessary files

### Debug Mode
All classes log initialization and key operations to console for debugging:
```javascript
console.log('Ready Vehicles page loaded');
console.log('Ready Vehicles page fully initialized');
```
