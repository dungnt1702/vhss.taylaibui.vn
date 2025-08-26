import './bootstrap';

import Alpine from 'alpinejs';

// Immediately define critical functions to prevent "not defined" errors
// These functions must be available before any HTML onclick events can trigger them
// Helper function to manage vehicle toggle using specific IDs
function toggleVehicleById(vehicleId) {
    const contentId = `content-${vehicleId}`;
    const iconId = `icon-${vehicleId}`;
    
    const content = document.getElementById(contentId);
    const icon = document.getElementById(iconId);
    
    if (!content || !icon) {
        console.warn(`Vehicle elements not found: contentId=${contentId}, iconId=${iconId}`);
        return;
    }
    
    const isHidden = content.classList.contains('hidden');
    
    if (isHidden) {
        // Close all other vehicle contents first using their specific IDs
        closeAllOtherVehicles(contentId);
        
        // Open the clicked vehicle using its specific ID
        openVehicle(content, icon);
    } else {
        // Close the clicked vehicle using its specific ID
        closeVehicle(content, icon);
    }
}

// Helper function to close all other vehicles
function closeAllOtherVehicles(currentContentId) {
    // Only close other vehicles, not the current one
    const allContents = document.querySelectorAll('.vehicle-content');
    
    allContents.forEach((contentEl) => {
        const contentId = contentEl.id;
        if (contentId !== currentContentId) {
            // Close this content
            contentEl.classList.add('hidden');
            
            // Find and reset the corresponding icon
            const vehicleId = contentId.replace('content-', '');
            const icon = document.getElementById(`icon-${vehicleId}`);
            if (icon) {
                icon.style.transform = 'rotate(0deg)';
            }
        }
    });
}

// Helper function to open a specific vehicle
function openVehicle(content, icon) {
    content.classList.remove('hidden');
    icon.style.transform = 'rotate(180deg)';
}

// Helper function to close a specific vehicle
function closeVehicle(content, icon) {
    content.classList.add('hidden');
    icon.style.transform = 'rotate(0deg)';
}

window.toggleVehicle = function(vehicleId) {
    try {
        // Use specific IDs to toggle the exact vehicle clicked
        toggleVehicleById(vehicleId);
    } catch (error) {
        // Silent error handling
        console.error('Error in toggleVehicle:', error);
    }
};

// Simple toggle function that only affects one vehicle - NO ACCORDION BEHAVIOR
window.toggleVehicleSimple = function(vehicleId) {
    try {
        console.log(`Simple toggleVehicle called with vehicleId: ${vehicleId}`);
        
        // Find the specific vehicle card and its elements
        const contentId = `content-${vehicleId}`;
        const iconId = `icon-${vehicleId}`;
        
        const content = document.getElementById(contentId);
        const icon = document.getElementById(iconId);
        
        if (!content || !icon) {
            console.warn(`Vehicle elements not found: contentId=${contentId}, iconId=${iconId}`);
            return;
        }
        
        const isHidden = content.classList.contains('hidden');
        
        if (isHidden) {
            console.log(`Opening ONLY vehicle ${vehicleId}`);
            // Open this specific vehicle ONLY
            content.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            console.log(`Closing ONLY vehicle ${vehicleId}`);
            // Close this specific vehicle ONLY
            content.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    } catch (error) {
        console.error('Error in toggleVehicleSimple:', error);
    }
};

// Function to auto-expand all vehicle cards for running filter
window.autoExpandRunningVehicles = function() {
    try {
        console.log(`Auto-expanding all running vehicles...`);
        
        const allVehicleCards = document.querySelectorAll('.vehicle-card');
        let expandedCount = 0;
        
        allVehicleCards.forEach(function(card) {
            const vehicleId = card.dataset.vehicleId;
            const content = document.getElementById(`content-${vehicleId}`);
            const icon = document.getElementById(`icon-${vehicleId}`);
            
            if (content && icon) {
                // Remove hidden class and rotate icon
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
                expandedCount++;
                
                console.log(`Auto-expanded vehicle ${vehicleId}`);
            }
        });
        
        console.log(`Successfully auto-expanded ${expandedCount} vehicle cards`);
        return expandedCount;
    } catch (error) {
        console.error('Error in autoExpandRunningVehicles:', error);
        return 0;
    }
};

// Test function to check all vehicle cards and their IDs
window.checkAllVehicleCards = function() {
    try {
        console.log(`=== CHECKING ALL VEHICLE CARDS ===`);
        
        const allCards = document.querySelectorAll('.vehicle-card');
        console.log(`Found ${allCards.length} vehicle cards`);
        
        allCards.forEach((card, index) => {
            const vehicleId = card.dataset.vehicleId;
            const contentId = `content-${vehicleId}`;
            const iconId = `icon-${vehicleId}`;
            
            const content = document.getElementById(contentId);
            const icon = document.getElementById(iconId);
            
            console.log(`Card ${index + 1}:`);
            console.log(`  - Vehicle ID: ${vehicleId}`);
            console.log(`  - Content ID: ${contentId}`);
            console.log(`  - Icon ID: ${iconId}`);
            console.log(`  - Content found: ${!!content}`);
            console.log(`  - Icon found: ${!!icon}`);
            console.log(`  - Content hidden: ${content ? content.classList.contains('hidden') : 'N/A'}`);
            console.log(`  - Icon transform: ${icon ? icon.style.transform : 'N/A'}`);
            console.log(`  ---`);
        });
        
        console.log(`=== END CHECK ===`);
    } catch (error) {
        console.error('Error in checkAllVehicleCards:', error);
    }
};

// Test function to check if there are duplicate IDs
window.checkForDuplicateIds = function() {
    try {
        console.log(`=== CHECKING FOR DUPLICATE IDs ===`);
        
        const allContents = document.querySelectorAll('.vehicle-content');
        const allIcons = document.querySelectorAll('.vehicle-header svg');
        
        const contentIds = Array.from(allContents).map(el => el.id);
        const iconIds = Array.from(allIcons).map(el => el.id);
        
        console.log(`Content IDs:`, contentIds);
        console.log(`Icon IDs:`, iconIds);
        
        // Check for duplicates
        const contentDuplicates = contentIds.filter((id, index) => contentIds.indexOf(id) !== index);
        const iconDuplicates = iconIds.filter((id, index) => iconIds.indexOf(id) !== index);
        
        if (contentDuplicates.length > 0) {
            console.warn(`Duplicate content IDs found:`, contentDuplicates);
        } else {
            console.log(`No duplicate content IDs found`);
        }
        
        if (iconDuplicates.length > 0) {
            console.warn(`Duplicate icon IDs found:`, iconDuplicates);
        } else {
            console.log(`No duplicate icon IDs found`);
        }
        
        console.log(`=== END DUPLICATE CHECK ===`);
    } catch (error) {
        console.error('Error in checkForDuplicateIds:', error);
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
// vehicle-wrappers.js will be loaded after vehicleOperations is initialized

// Import active vehicles specific functionality
import './active-vehicles';

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
        
        // Now that vehicleOperations is ready, load vehicle-wrappers.js
        if (!window.vehicleWrappersLoaded) {
            import('./vehicle-wrappers').then(() => {
                window.vehicleWrappersLoaded = true;
                
                // Initialize countdown timers after everything is loaded and DOM is ready
                if (document.readyState === 'complete') {
                    window.vehicleOperations.initializeCountdownTimers();
                } else {
                    window.addEventListener('load', () => {
                        window.vehicleOperations.initializeCountdownTimers();
                    });
                }
            });
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
