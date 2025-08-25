import './bootstrap';

import Alpine from 'alpinejs';

// Immediately define critical functions to prevent "not defined" errors
// These functions must be available before any HTML onclick events can trigger them
window.toggleVehicle = function(vehicleId) {
    try {
        const content = document.getElementById(`content-${vehicleId}`);
        const icon = document.getElementById(`icon-${vehicleId}`);
        
        if (!content) {
            return;
        }
        
        if (!icon) {
            return;
        }
        
        const isHidden = content.classList.contains('hidden');
        
        if (isHidden) {
            content.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            content.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    } catch (error) {
        // Silent error handling
    }
};

window.startVehicle = function(vehicleId, minutes = 30) {
    // Silent function
};

window.pauseVehicle = function(vehicleId) {
    // Silent function
};

window.resumeVehicle = function(vehicleId) {
    // Silent function
};

// Import vehicle management modules in correct order
// These will be loaded synchronously
import './vehicles';
import './vehicle-forms';
import './vehicle-operations';
import './vehicle-wrappers';

window.Alpine = Alpine;

// Function to initialize the vehicle management system
function initializeVehicleSystem() {
    // Check if all required modules are loaded
    const requiredModules = ['vehicleManager', 'vehicleForms', 'vehicleOperations'];
    const missingModules = requiredModules.filter(module => !window[module]);
    
    if (missingModules.length > 0) {
        return false;
    }
    
    // Check if all required functions are available
    const requiredFunctions = ['toggleVehicle', 'startVehicle', 'pauseVehicle', 'resumeVehicle'];
    const missingFunctions = requiredFunctions.filter(func => typeof window[func] === 'undefined');
    
    if (missingFunctions.length > 0) {
        return false;
    }
    
    // Enhance the basic functions with full functionality when modules are available
    if (window.vehicleManager && window.vehicleManager.toggleVehicle) {
        const originalToggleVehicle = window.toggleVehicle;
        window.toggleVehicle = function(vehicleId) {
            window.vehicleManager.toggleVehicle(vehicleId);
        };
    }
    
    if (window.vehicleOperations) {
        if (window.vehicleOperations.startVehicle) {
            window.startVehicle = function(vehicleId, minutes = 30) {
                window.vehicleOperations.startVehicle(vehicleId, minutes);
            };
        }
        if (window.vehicleOperations.pauseVehicle) {
            window.pauseVehicle = function(vehicleId) {
                window.vehicleOperations.pauseVehicle(vehicleId);
            };
        }
        if (window.vehicleOperations.resumeVehicle) {
            window.resumeVehicle = function(vehicleId) {
                window.vehicleOperations.resumeVehicle(vehicleId);
            };
        }
    }
    
    return true;
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Try to initialize immediately
    if (!initializeVehicleSystem()) {
        // If initialization fails, try again after a short delay
        setTimeout(() => {
            initializeVehicleSystem();
        }, 100);
    }
});

// Also try to initialize immediately if DOM is already loaded
if (document.readyState !== 'loading') {
    // Trigger the same initialization logic
    const event = new Event('DOMContentLoaded');
    document.dispatchEvent(event);
}

Alpine.start();
