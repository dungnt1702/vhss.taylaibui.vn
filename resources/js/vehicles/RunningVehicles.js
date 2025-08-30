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
        this.setupPauseResume();
        this.setupReturnYard();
    }

    /**
     * Setup pause and resume functionality
     */
    setupPauseResume() {
        const pauseButtons = document.querySelectorAll('[data-action="pause-timer"]');
        const resumeButtons = document.querySelectorAll('[data-action="resume-timer"]');
        
        pauseButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const vehicleId = e.target.dataset.vehicleId;
                if (vehicleId) {
                    this.pauseTimer(vehicleId, e.target);
                }
            });
        });

        resumeButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const vehicleId = e.target.dataset.vehicleId;
                if (vehicleId) {
                    this.resumeTimer(vehicleId, e.target);
                }
            });
        });
    }

    /**
     * Setup return to yard functionality
     */
    setupReturnYard() {
        const returnButtons = document.querySelectorAll('[data-action="return-yard"]');
        returnButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const vehicleId = e.target.dataset.vehicleId;
                if (vehicleId) {
                    this.returnToYard(vehicleId, e.target);
                }
            });
        });
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
