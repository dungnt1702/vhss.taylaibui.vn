/**
 * RunningVehicles - Class for managing running vehicles
 * Extends VehicleBase with running-specific functionality
 */

import { VehicleBase } from './VehicleBase.js';

class RunningVehicles extends VehicleBase {
    constructor() {
        super('Running Vehicles');
    }

    /**
     * Initialize running vehicles page
     */
    init() {
        super.init();
        this.setupRunningSpecificFeatures();
        console.log('Running Vehicles page fully initialized');
    }

    /**
     * Setup running-specific features
     */
    setupRunningSpecificFeatures() {
        // Tất cả actions đã được xử lý bởi VehicleBase
        // Không cần setup riêng nữa
        console.log('Running Vehicles: All actions handled by VehicleBase');
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Running Vehicles page loaded');
    
    // Create and initialize RunningVehicles instance
    const runningVehicles = new RunningVehicles();
    runningVehicles.init();
    
    // Make it available globally for debugging
    window.runningVehicles = runningVehicles;
});

// Export for ES6 modules
export default RunningVehicles;
