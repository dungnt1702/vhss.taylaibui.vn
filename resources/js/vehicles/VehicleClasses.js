/**
 * Vehicle Classes Entry Point - VehicleClasses.js
 * This file ensures proper loading order of all vehicle classes
 * 
 * IMPORTANT: This file imports VehicleBase class first, then all vehicle-specific classes
 * to ensure proper inheritance and dependency management.
 */

// Import VehicleBase class first (from the actual class file)
import { VehicleBase } from './VehicleBase.js';

// Then import all vehicle-specific classes
import ReadyVehicles from './ReadyVehicles.js';
import WaitingVehicles from './WaitingVehicles.js';
import RunningVehicles from './RunningVehicles.js';
import PausedVehicles from './PausedVehicles.js';
import ExpiredVehicles from './ExpiredVehicles.js';
import WorkshopVehicles from './WorkshopVehicles.js';
import AttributesList from './AttributesList.js';
import MaintainingVehicles from './MaintainingVehicles.js';
import RepairingVehicles from './RepairingVehicles.js';
import Vehicles from './Vehicles.js';
import VehicleForms from './VehicleForms.js';
import VehicleOperations from './VehicleOperations.js';
import VehicleWrappers from './VehicleWrappers.js';
import VehiclesList from './VehiclesList.js';

console.log('All vehicle classes loaded successfully from VehicleClasses.js entry point');

// Make VehicleBase available globally for backward compatibility
if (typeof window !== 'undefined') {
    window.VehicleBase = VehicleBase;
    window.VehiclesList = VehiclesList;
    
    // Create VehicleBase instance for global use
    window.vehicleBase = new VehicleBase('Global Vehicle Base');
}
