/**
 * PausedVehicles - Class for managing paused vehicles
 * Extends VehicleBase with paused-specific functionality
 */

import { VehicleBase } from './VehicleBase.js';

class PausedVehicles extends VehicleBase {
    constructor() {
        super('Paused Vehicles');
    }

    /**
     * Initialize paused vehicles page
     */
    init() {
        super.init();
        this.setupPausedSpecificFeatures();
        console.log('Paused Vehicles page fully initialized');
    }

    /**
     * Setup paused-specific features
     */
    setupPausedSpecificFeatures() {
        this.setupResumeTimer();
        this.setupReturnYard();
    }

    /**
     * Setup resume timer functionality
     */
    setupResumeTimer() {
        const resumeButtons = document.querySelectorAll('[data-action="resume-timer"]');
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
                    // Use VehicleBase function for single vehicle
                    this.returnToYard(vehicleId, e.target);
                }
            });
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Paused Vehicles page loaded');
    
    // Create and initialize PausedVehicles instance
    const pausedVehicles = new PausedVehicles();
    pausedVehicles.init();
    
    // Make it available globally for debugging
    window.pausedVehicles = pausedVehicles;
});

// Export for ES6 modules
export default PausedVehicles;
