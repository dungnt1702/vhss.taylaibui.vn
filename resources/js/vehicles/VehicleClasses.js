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

console.log('All vehicle classes loaded successfully from VehicleClasses.js entry point');

// Make VehicleBase available globally for backward compatibility
if (typeof window !== 'undefined') {
    window.VehicleBase = VehicleBase;
}
