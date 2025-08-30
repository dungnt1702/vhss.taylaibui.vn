/**
 * VehicleWrappers - Vehicle wrapper functions and utilities class
 * Extends VehicleBase with wrapper-specific functionality
 */

import { VehicleBase } from './VehicleBase.js';

class VehicleWrappers extends VehicleBase {
    constructor() {
        super('Vehicle Wrappers');
    }

    /**
     * Initialize vehicle wrappers page
     */
    init() {
        super.init();
        this.setupWrapperSpecificFeatures();
        console.log('Vehicle Wrappers page fully initialized');
    }

    /**
     * Setup wrapper-specific features
     */
    setupWrapperSpecificFeatures() {
        this.setupGlobalFunctions();
        this.setupEventDelegation();
    }

    /**
     * Setup global functions for backward compatibility
     */
    setupGlobalFunctions() {
        // Make wrapper functions available globally
        window.toggleVehicleById = (vehicleId) => this.toggleVehicleById(vehicleId);
        window.closeAllOtherVehiclesSimple = (currentContentId) => this.closeAllOtherVehiclesSimple(currentContentId);
        window.testToggleVehicle = (vehicleId) => this.testToggleVehicle(vehicleId);
        window.toggleVehicle = (vehicleId) => this.toggleVehicle(vehicleId);
        window.closeAllOtherVehicles = (currentContentId) => this.closeAllOtherVehicles(currentContentId);
        window.openVehicle = (content, icon) => this.openVehicle(content, icon);
        window.closeVehicle = (content, icon) => this.closeVehicle(content, icon);
    }

    /**
     * Setup event delegation for dynamic content
     */
    setupEventDelegation() {
        // Use event delegation for vehicle toggle functionality
        document.addEventListener('click', (event) => {
            const toggleButton = event.target.closest('[data-toggle-vehicle]');
            if (toggleButton) {
                const vehicleId = toggleButton.dataset.toggleVehicle;
                if (vehicleId) {
                    this.toggleVehicleById(vehicleId);
                }
            }
        });
    }

    /**
     * Toggle vehicle by ID (simple version)
     */
    toggleVehicleById(vehicleId) {
        const contentId = `content-${vehicleId}`;
        const iconId = `icon-${vehicleId}`;
        
        const content = document.getElementById(contentId);
        const icon = document.getElementById(iconId);
        
        if (!content || !icon) {
            console.warn(`Vehicle elements not found: contentId=${contentId}, iconId=${iconId}`);
            return;
        }
        
        console.log(`Toggling vehicle ${vehicleId}: contentId=${contentId}, iconId=${iconId}`);
        
        const isHidden = content.classList.contains('hidden');
        
        if (isHidden) {
            console.log(`Opening vehicle ${vehicleId}`);
            // Close all other vehicles first
            this.closeAllOtherVehiclesSimple(contentId);
            
            // Open the clicked vehicle
            content.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            console.log(`Closing vehicle ${vehicleId}`);
            // Close the clicked vehicle
            content.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }

    /**
     * Close all other vehicles (simple version)
     */
    closeAllOtherVehiclesSimple(currentContentId) {
        console.log(`Closing all other vehicles except: ${currentContentId}`);
        
        // Get all vehicle content elements
        const allContents = document.querySelectorAll('.vehicle-content');
        console.log(`Found ${allContents.length} vehicle contents`);
        
        allContents.forEach((contentEl) => {
            const contentId = contentEl.id;
            
            if (contentId !== currentContentId) {
                console.log(`Closing: ${contentId}`);
                // Close this specific content
                contentEl.classList.add('hidden');
                
                // Get the vehicle ID from content ID and reset its icon
                const vehicleId = contentId.replace('content-', '');
                const icon = document.getElementById(`icon-${vehicleId}`);
                
                if (icon) {
                    icon.style.transform = 'rotate(0deg)';
                    console.log(`Reset icon for vehicle: ${vehicleId}`);
                }
            } else {
                console.log(`Keeping open: ${contentId}`);
            }
        });
    }

    /**
     * Test function to debug toggle vehicle (simple version)
     */
    testToggleVehicle(vehicleId) {
        console.log(`=== TESTING SIMPLE TOGGLE VEHICLE ${vehicleId} ===`);
        
        const contentId = `content-${vehicleId}`;
        const iconId = `icon-${vehicleId}`;
        
        console.log(`Looking for elements:`);
        console.log(`- Content ID: ${contentId}`);
        console.log(`- Icon ID: ${iconId}`);
        
        const content = document.getElementById(contentId);
        const icon = document.getElementById(iconId);
        
        console.log(`Found elements:`);
        console.log(`- Content:`, content);
        console.log(`- Icon:`, icon);
        
        if (content && icon) {
            const isHidden = content.classList.contains('hidden');
            console.log(`Content is hidden: ${isHidden}`);
            
            if (isHidden) {
                console.log(`Opening vehicle ${vehicleId}`);
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                console.log(`Closing vehicle ${vehicleId}`);
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        } else {
            console.error(`Missing elements for vehicle ${vehicleId}`);
        }
    }

    /**
     * Toggle vehicle (advanced version)
     */
    toggleVehicle(vehicleId) {
        // Use specific IDs to toggle the exact vehicle clicked
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
            this.closeAllOtherVehicles(contentId);
            
            // Open the clicked vehicle using its specific ID
            this.openVehicle(content, icon);
        } else {
            // Close the clicked vehicle using its specific ID
            this.closeVehicle(content, icon);
        }
    }

    /**
     * Close all other vehicles (advanced version)
     */
    closeAllOtherVehicles(currentContentId) {
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

    /**
     * Open a specific vehicle
     */
    openVehicle(content, icon) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    }

    /**
     * Close a specific vehicle
     */
    closeVehicle(content, icon) {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }

    /**
     * Toggle vehicle expansion with animation
     */
    toggleVehicleWithAnimation(vehicleId) {
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
            // Close all other vehicles first
            this.closeAllOtherVehiclesSimple(contentId);
            
            // Open with animation
            this.openVehicleWithAnimation(content, icon);
        } else {
            // Close with animation
            this.closeVehicleWithAnimation(content, icon);
        }
    }

    /**
     * Open vehicle with animation
     */
    openVehicleWithAnimation(content, icon) {
        // Reset any previous animations
        content.style.transition = 'all 0.3s ease-in-out';
        content.style.maxHeight = '0';
        content.style.opacity = '0';
        content.style.overflow = 'hidden';
        
        // Force reflow
        content.offsetHeight;
        
        // Animate open
        content.classList.remove('hidden');
        content.style.maxHeight = content.scrollHeight + 'px';
        content.style.opacity = '1';
        
        // Rotate icon
        icon.style.transition = 'transform 0.3s ease-in-out';
        icon.style.transform = 'rotate(180deg)';
        
        // Clean up after animation
        setTimeout(() => {
            content.style.maxHeight = '';
            content.style.overflow = '';
        }, 300);
    }

    /**
     * Close vehicle with animation
     */
    closeVehicleWithAnimation(content, icon) {
        // Set initial state
        content.style.transition = 'all 0.3s ease-in-out';
        content.style.maxHeight = content.scrollHeight + 'px';
        content.style.overflow = 'hidden';
        
        // Force reflow
        content.offsetHeight;
        
        // Animate close
        content.style.maxHeight = '0';
        content.style.opacity = '0';
        
        // Rotate icon
        icon.style.transition = 'transform 0.3s ease-in-out';
        icon.style.transform = 'rotate(0deg)';
        
        // Hide after animation
        setTimeout(() => {
            content.classList.add('hidden');
            content.style.maxHeight = '';
            content.style.overflow = '';
        }, 300);
    }

    /**
     * Toggle multiple vehicles
     */
    toggleMultipleVehicles(vehicleIds, action = 'toggle') {
        vehicleIds.forEach(vehicleId => {
            const contentId = `content-${vehicleId}`;
            const iconId = `icon-${vehicleId}`;
            
            const content = document.getElementById(contentId);
            const icon = document.getElementById(iconId);
            
            if (content && icon) {
                switch (action) {
                    case 'open':
                        this.openVehicle(content, icon);
                        break;
                    case 'close':
                        this.closeVehicle(content, icon);
                        break;
                    case 'toggle':
                    default:
                        const isHidden = content.classList.contains('hidden');
                        if (isHidden) {
                            this.openVehicle(content, icon);
                        } else {
                            this.closeVehicle(content, icon);
                        }
                        break;
                }
            }
        });
    }

    /**
     * Close all vehicles
     */
    closeAllVehicles() {
        const allContents = document.querySelectorAll('.vehicle-content');
        const allIcons = document.querySelectorAll('[id^="icon-"]');
        
        allContents.forEach(content => {
            content.classList.add('hidden');
        });
        
        allIcons.forEach(icon => {
            icon.style.transform = 'rotate(0deg)';
        });
        
        console.log(`Closed ${allContents.length} vehicles`);
    }

    /**
     * Open all vehicles
     */
    openAllVehicles() {
        const allContents = document.querySelectorAll('.vehicle-content');
        const allIcons = document.querySelectorAll('[id^="icon-"]');
        
        allContents.forEach(content => {
            content.classList.remove('hidden');
        });
        
        allIcons.forEach(icon => {
            icon.style.transform = 'rotate(180deg)';
        });
        
        console.log(`Opened ${allContents.length} vehicles`);
    }

    /**
     * Get vehicle expansion state
     */
    getVehicleExpansionState(vehicleId) {
        const contentId = `content-${vehicleId}`;
        const content = document.getElementById(contentId);
        
        if (content) {
            return !content.classList.contains('hidden');
        }
        
        return false;
    }

    /**
     * Get all expanded vehicles
     */
    getAllExpandedVehicles() {
        const expandedVehicles = [];
        const allContents = document.querySelectorAll('.vehicle-content');
        
        allContents.forEach(content => {
            if (!content.classList.contains('hidden')) {
                const vehicleId = content.id.replace('content-', '');
                expandedVehicles.push(vehicleId);
            }
        });
        
        return expandedVehicles;
    }

    /**
     * Set vehicle expansion state
     */
    setVehicleExpansionState(vehicleId, expanded) {
        const contentId = `content-${vehicleId}`;
        const iconId = `icon-${vehicleId}`;
        
        const content = document.getElementById(contentId);
        const icon = document.getElementById(iconId);
        
        if (content && icon) {
            if (expanded) {
                this.openVehicle(content, icon);
            } else {
                this.closeVehicle(content, icon);
            }
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Vehicle Wrappers page loaded');
    
    // Create and initialize VehicleWrappers instance
    const vehicleWrappers = new VehicleWrappers();
    vehicleWrappers.init();
    
    // Make it available globally for debugging
    window.vehicleWrappers = vehicleWrappers;
});

// Export for ES6 modules
export default VehicleWrappers;
