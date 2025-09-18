# Vehicles Module Structure

## Overview
The Vehicles module handles all vehicle-related functionality including active vehicles, running vehicles, workshop vehicles, and vehicle management.

## File Structure
```
resources/
├── css/vehicles/
│   ├── active-vehicles.css      # Active vehicles specific styles
│   ├── running-vehicles.css     # Running vehicles specific styles
│   ├── workshop-vehicles.css    # Workshop vehicles specific styles
│   └── vehicles-list.css        # General vehicle list styles
├── js/vehicles/
│   ├── VehicleBase.js           # Base class for all vehicle functionality
│   ├── ActiveVehicles.js        # Active vehicles functionality
│   ├── ReadyVehicles.js         # Ready vehicles functionality
│   ├── RunningVehicles.js       # Running vehicles functionality
│   ├── PausedVehicles.js        # Paused vehicles functionality
│   ├── ExpiredVehicles.js       # Expired vehicles functionality
│   ├── WorkshopVehicles.js      # Workshop vehicles functionality
│   ├── RepairingVehicles.js     # Repairing vehicles functionality
│   ├── AttributesList.js        # Vehicle attributes management
│   ├── VehiclesList.js          # General vehicle listing
│   └── GlobalModalFunctions.js  # Global modal utilities
└── views/vehicles/
    ├── active_vehicles.blade.php
    ├── running_vehicles.blade.php
    ├── workshop_vehicles.blade.php
    └── partials/modals/
        ├── active_modals.blade.php
        ├── running_modals.blade.php
        └── workshop_modals.blade.php
```

## CSS Naming Convention (BEM)
- Block: `.vehicles-active`, `.vehicles-running`, `.vehicles-workshop`
- Element: `.vehicles-active__table`, `.vehicles-active__section`
- Modifier: `.vehicles-active__section--collapsed`, `.vehicles-active__table--mobile`

## JavaScript Module System
- Base class: `VehiclesModule`
- Page-specific classes: `ActiveVehicles`, `RunningVehicles`, `WorkshopVehicles`
- Global functions: Namespaced under `window` object
- Event listeners: Tracked for automatic cleanup

## Dependencies
- Common CSS: `base.css`, `responsive.css`
- Common JS: `ModuleLoader.js`
- Laravel Blade templates
- Tailwind CSS (for utility classes)

## Integration Points
- **Controllers**: 
  - `VehicleManagementController` - Main CRUD operations
  - `AttributesListController` - Vehicle attributes management
  - `VehicleOperationsController` - Vehicle operations API
  - `ActiveVehiclesController` - Active vehicles specific
- **Routes**: `/vehicles/*`, `/api/vehicles/*`, `/api/attributes/*`
- **Database**: `vehicles`, `vehicle_attributes`, `roles`, `permissions` tables
- **Authentication**: Required for all vehicle operations
