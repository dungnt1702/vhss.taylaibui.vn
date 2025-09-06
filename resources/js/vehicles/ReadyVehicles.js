/**
 * ReadyVehicles - Class for managing ready vehicles
 * Extends VehicleBase with ready-specific functionality
 */

import { VehicleBase } from './VehicleBase.js';

class ReadyVehicles extends VehicleBase {
    constructor() {
        super('Ready Vehicles');
        this.specificActions = ['open-workshop-modal'];
    }

    /**
     * Initialize ready vehicles page
     */
    init() {
        super.init();
        this.setupReadySpecificFeatures();
        console.log('Ready Vehicles page fully initialized');
    }

    /**
     * Setup ready-specific features
     */
    setupReadySpecificFeatures() {
        // Add any ready-specific initialization here
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
    
    // Create and initialize ReadyVehicles instance
    const waitingVehicles = new ReadyVehicles();
    waitingVehicles.init();
    
    // Make it available globally for debugging
    window.waitingVehicles = waitingVehicles;
});

// Export for ES6 modules
export default ReadyVehicles;
