# Modal Components Documentation

## Overview
Modal components are organized by module to prevent conflicts and ensure proper isolation.

## Modal Structure
```
resources/views/
├── vehicles/partials/modals/
│   ├── active_modals.blade.php      # Active vehicles modals
│   ├── running_modals.blade.php     # Running vehicles modals
│   └── workshop_modals.blade.php    # Workshop vehicles modals
├── maintenance/partials/modals/
│   ├── schedule_modals.blade.php    # Maintenance schedule modals
│   └── record_modals.blade.php      # Maintenance record modals
└── common/partials/modals/
    ├── notification_modal.blade.php # Global notification modal
    └── confirmation_modal.blade.php # Global confirmation modal
```

## Modal Naming Convention
- Block: `.modal-{module}-{type}`
- Element: `.modal-{module}-{type}__header`, `.modal-{module}-{type}__content`
- Modifier: `.modal-{module}-{type}--large`, `.modal-{module}-{type}--small`

## Active Vehicles Modals

### Workshop Modal
- **ID**: `workshop-modal`
- **Class**: `.modal-vehicles-workshop`
- **Purpose**: Assign vehicles to workshop
- **Functions**: `openWorkshopModal(vehicleId)`, `closeWorkshopModal()`

### Status Modal
- **ID**: `status-modal`
- **Class**: `.modal-vehicles-status`
- **Purpose**: Update vehicle status
- **Functions**: `openStatusModal(vehicleId)`, `closeStatusModal()`

### Vehicle Detail Modal
- **ID**: `vehicle-detail-modal`
- **Class**: `.modal-vehicles-detail`
- **Purpose**: Show vehicle details
- **Functions**: `openVehicleDetailModal(vehicleId)`, `closeVehicleDetailModal()`

## Running Vehicles Modals

### Pause Modal
- **ID**: `pause-modal`
- **Class**: `.modal-vehicles-pause`
- **Purpose**: Pause running vehicles
- **Functions**: `openPauseModal(vehicleId)`, `closePauseModal()`

### Complete Modal
- **ID**: `complete-modal`
- **Class**: `.modal-vehicles-complete`
- **Purpose**: Mark vehicles as completed
- **Functions**: `openCompleteModal(vehicleId)`, `closeCompleteModal()`

## Workshop Vehicles Modals

### Technical Update Modal
- **ID**: `technical-update-modal`
- **Class**: `.modal-vehicles-technical`
- **Purpose**: Update technical information
- **Functions**: `openTechnicalUpdateModal(vehicleId)`, `closeTechnicalUpdateModal()`

### Workshop Assignment Modal
- **ID**: `workshop-assignment-modal`
- **Class**: `.modal-vehicles-workshop-assignment`
- **Purpose**: Assign vehicles to specific workshop
- **Functions**: `openWorkshopAssignmentModal(vehicleId)`, `closeWorkshopAssignmentModal()`

## Common Modals

### Notification Modal
- **ID**: `notification-modal`
- **Class**: `.modal-common-notification`
- **Purpose**: Show notifications to users
- **Functions**: `showNotificationModal(title, message, type)`, `closeNotificationModal()`

### Confirmation Modal
- **ID**: `confirmation-modal`
- **Class**: `.modal-common-confirmation`
- **Purpose**: Confirm user actions
- **Functions**: `showConfirmationModal(title, message, callback)`, `closeConfirmationModal()`

## Modal Loading Strategy

### Conditional Loading
Modals are loaded based on the current page/module:
```php
@if($filter === 'active')
    @include('vehicles.partials.modals.active_modals')
@elseif($filter === 'running')
    @include('vehicles.partials.modals.running_modals')
@elseif($filter === 'workshop')
    @include('vehicles.partials.modals.workshop_modals')
@endif
```

### JavaScript Initialization
Modals are initialized when the module loads:
```javascript
// In VehiclesModule.js
_setupPageSpecificFeatures() {
    if (this.pageType === 'active') {
        this._setupActiveModals();
    } else if (this.pageType === 'running') {
        this._setupRunningModals();
    }
}
```

## Modal CSS Classes

### Base Modal Classes
```css
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

.modal-content {
    position: relative;
    background-color: white;
    border-radius: 0.5rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    max-width: 90vw;
    max-height: 90vh;
    overflow: auto;
}
```

### Module-Specific Classes
```css
.modal-vehicles-workshop {
    /* Workshop modal specific styles */
}

.modal-vehicles-status {
    /* Status modal specific styles */
}

.modal-maintenance-schedule {
    /* Maintenance schedule modal specific styles */
}
```

## Best Practices

1. **Namespace Modals**: Always prefix modal classes with module name
2. **Conditional Loading**: Only load modals needed for current page
3. **Event Cleanup**: Remove event listeners when modals are closed
4. **Accessibility**: Ensure proper ARIA attributes and keyboard navigation
5. **Responsive Design**: Make modals work on all screen sizes
6. **Error Handling**: Handle modal loading and display errors gracefully
