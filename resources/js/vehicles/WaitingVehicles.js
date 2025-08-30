/**
 * WaitingVehicles - Class for managing waiting vehicles
 * Extends VehicleBase with waiting-specific functionality
 */

import { VehicleBase } from './VehicleBase.js';

class WaitingVehicles extends VehicleBase {
    constructor() {
        super('Waiting Vehicles');
        this.specificActions = ['open-workshop-modal'];
    }

    /**
     * Initialize waiting vehicles page
     */
    init() {
        super.init();
        this.setupWaitingSpecificFeatures();
        console.log('Waiting Vehicles page fully initialized');
    }

    /**
     * Setup waiting-specific features
     */
    setupWaitingSpecificFeatures() {
        // Add any waiting-specific initialization here
        this.setupWorkshopTransfer();
    }

    /**
     * Setup workshop transfer functionality
     */
    setupWorkshopTransfer() {
        const workshopButtons = document.querySelectorAll('[data-action="open-workshop-modal"]');
        workshopButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const vehicleId = e.target.dataset.vehicleId;
                if (vehicleId) {
                    this.openWorkshopModal(vehicleId);
                }
            });
        });
    }

    /**
     * Override handleCustomAction for waiting-specific actions
     */
    handleCustomAction(action, vehicleId, button) {
        switch (action) {
            case 'open-workshop-modal':
                this.openWorkshopModal(vehicleId);
                break;
            default:
                // Delegate all other actions to VehicleBase
                super.handleCustomAction(action, vehicleId, button);
        }
    }

    /**
     * Open workshop modal for vehicle
     */
    openWorkshopModal(vehicleId) {
        if (window.vehicleForms && window.vehicleForms.openWorkshopModal) {
            window.vehicleForms.openWorkshopModal(vehicleId);
        } else {
            // Fallback: direct workshop move
            this.moveToWorkshop(vehicleId);
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Waiting Vehicles page loaded');
    
    // Create and initialize WaitingVehicles instance
    const waitingVehicles = new WaitingVehicles();
    waitingVehicles.init();
    
    // Make it available globally for debugging
    window.waitingVehicles = waitingVehicles;
});

// Export for ES6 modules
export default WaitingVehicles;
