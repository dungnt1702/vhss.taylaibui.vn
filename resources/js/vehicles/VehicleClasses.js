/**
 * Vehicle Classes Entry Point - VehicleClasses.js
 * This file intelligently loads only the JS needed for the current page
 * 
 * IMPORTANT: This file detects the current page type and loads only the corresponding JS
 * to avoid conflicts and improve performance.
 */

// Import VehicleBase class first (from the actual class file)
import { VehicleBase } from './VehicleBase.js';



/**
 * Detect current page type and load corresponding JS
 */
async function loadPageSpecificJS() {
    const pageElement = document.getElementById('vehicle-page');
    if (!pageElement) {
        console.log('No vehicle page detected, loading basic VehicleBase only');
        // Make VehicleBase available globally for backward compatibility
        if (typeof window !== 'undefined') {
            window.VehicleBase = VehicleBase;
            window.vehicleBase = new VehicleBase('Global Vehicle Base');
        }
        return;
    }

    const pageType = pageElement.dataset.pageType;

    if (!pageType) {
        // Make VehicleBase available globally for backward compatibility
        if (typeof window !== 'undefined') {
            window.VehicleBase = VehicleBase;
            window.vehicleBase = new VehicleBase('Global Vehicle Base');
        }
        return;
    }

    try {
        let VehicleClass;
        let className;

        // Load JS based on page type
        switch (pageType) {
            case 'expired':
                const ExpiredVehicles = (await import('./ExpiredVehicles.js')).default;
                VehicleClass = ExpiredVehicles;
                className = 'ExpiredVehicles';
                break;
            
            case 'paused':
                const PausedVehicles = (await import('./PausedVehicles.js')).default;
                VehicleClass = PausedVehicles;
                className = 'PausedVehicles';
                break;
            
            case 'active':
                const ActiveVehicles = (await import('./ActiveVehicles.js')).default;
                VehicleClass = ActiveVehicles;
                className = 'ActiveVehicles';
                break;
            
            case 'ready':
                const ReadyVehicles = (await import('./ReadyVehicles.js')).default;
                VehicleClass = ReadyVehicles;
                className = 'ReadyVehicles';
                break;
            
            case 'running':
                const RunningVehicles = (await import('./RunningVehicles.js')).default;
                VehicleClass = RunningVehicles;
                className = 'RunningVehicles';
                break;
            
            case 'waiting':
                const WaitingVehicles = (await import('./WaitingVehicles.js')).default;
                VehicleClass = WaitingVehicles;
                className = 'WaitingVehicles';
                break;
            
            case 'workshop':
                const WorkshopVehicles = (await import('./WorkshopVehicles.js')).default;
                VehicleClass = WorkshopVehicles;
                className = 'WorkshopVehicles';
                break;
            
            case 'maintaining':
                const MaintainingVehicles = (await import('./MaintainingVehicles.js')).default;
                VehicleClass = MaintainingVehicles;
                className = 'MaintainingVehicles';
                break;
            
            case 'repairing':
                const RepairingVehicles = (await import('./RepairingVehicles.js')).default;
                VehicleClass = RepairingVehicles;
                className = 'RepairingVehicles';
                break;
            
            case 'attributes':
                const AttributesList = (await import('./AttributesList.js')).default;
                VehicleClass = AttributesList;
                className = 'AttributesList';
                break;
            
            case 'list':
                const VehiclesList = (await import('./VehiclesList.js')).default;
                VehicleClass = VehiclesList;
                className = 'VehiclesList';
                break;
            
            default:
                // Make VehicleBase available globally for backward compatibility
                if (typeof window !== 'undefined') {
                    window.VehicleBase = VehicleBase;
                    window.vehicleBase = new VehicleBase('Global Vehicle Base');
                }
                return;
        }

        // Initialize the specific vehicle class
        if (VehicleClass) {
            const vehicleInstance = new VehicleClass();
            vehicleInstance.init();
            
            // Make it available globally for debugging
            window[pageType + 'Vehicles'] = vehicleInstance;
            
            // Also make VehicleBase available globally for backward compatibility
            if (typeof window !== 'undefined') {
                window.VehicleBase = VehicleBase;
                window.vehicleBase = new VehicleBase('Global Vehicle Base');
            }
        }

    } catch (error) {
        console.error(`Error loading JS for page type ${pageType}:`, error);
        // Fallback to basic VehicleBase
        if (typeof window !== 'undefined') {
            window.VehicleBase = VehicleBase;
            window.vehicleBase = new VehicleBase('Global Vehicle Base');
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    loadPageSpecificJS();
});

// Export for ES6 modules
export { loadPageSpecificJS };
export default loadPageSpecificJS;
