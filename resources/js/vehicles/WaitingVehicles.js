/**
 * WaitingVehicles - Class for managing waiting vehicles
 * Extends VehicleBase with waiting-specific functionality
 */

import { VehicleBase } from './VehicleBase.js';

class WaitingVehicles extends VehicleBase {
    constructor() {
        super('Waiting Vehicles');
        this.specificActions = ['start-timer', 'assign-route', 'move-workshop'];
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
        this.setupDurationSelectors();
        this.setupRouteAssignment();
    }

    /**
     * Setup duration selectors for start timer
     */
    setupDurationSelectors() {
        const durationButtons = document.querySelectorAll('[data-duration]');
        durationButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const duration = parseInt(e.target.dataset.duration);
                const vehicleId = e.target.dataset.vehicleId;
                
                if (duration && vehicleId) {
                    this.startTimer(vehicleId, duration, e.target);
                }
            });
        });
    }

    /**
     * Setup route assignment functionality
     */
    setupRouteAssignment() {
        // This can be extended with specific route assignment logic
        console.log('Route assignment setup complete');
    }

    /**
     * Override handleCustomAction for waiting-specific actions
     */
    handleCustomAction(action, vehicleId, button) {
        switch (action) {
            case 'open-workshop-modal':
                this.openWorkshopModal(vehicleId);
                break;
            case 'quick-start':
                this.quickStartTimer(vehicleId, button);
                break;
            default:
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

    /**
     * Quick start timer with default duration
     */
    quickStartTimer(vehicleId, button) {
        const defaultDuration = 30; // 30 minutes default
        this.startTimer(vehicleId, defaultDuration, button);
    }

    /**
     * Override startTimer for waiting-specific logic
     */
    async startTimer(vehicleId, duration, button) {
        console.log(`Starting timer for waiting vehicle ${vehicleId} with duration ${duration} minutes`);
        
        // Call parent method
        await super.startTimer(vehicleId, duration, button);
        
        // Additional waiting-specific logic after timer starts
        this.onTimerStarted(vehicleId, duration);
    }

    /**
     * Called when timer starts for a waiting vehicle
     */
    onTimerStarted(vehicleId, duration) {
        // Update vehicle status in UI
        const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
        if (vehicleCard) {
            vehicleCard.classList.add('timer-started');
            vehicleCard.classList.remove('waiting');
            
            // Add visual feedback
            const statusElement = vehicleCard.querySelector('.vehicle-status');
            if (statusElement) {
                statusElement.textContent = 'Đang chạy';
                statusElement.className = 'vehicle-status text-green-600 font-bold';
            }
        }

        // Log the action
        console.log(`Vehicle ${vehicleId} moved from waiting to running with ${duration} minute timer`);
    }

    /**
     * Override setupCardSpecificListeners for waiting vehicles
     */
    setupCardSpecificListeners(card) {
        // Add waiting-specific card functionality
        this.setupWaitingCardFeatures(card);
    }

    /**
     * Setup waiting-specific card features
     */
    setupWaitingCardFeatures(card) {
        // Add hover effects for waiting vehicles
        card.addEventListener('mouseenter', () => {
            card.classList.add('hover:shadow-lg');
        });

        card.addEventListener('mouseleave', () => {
            card.classList.remove('hover:shadow-lg');
        });

        // Add click to expand functionality
        const expandButton = card.querySelector('.expand-button');
        if (expandButton) {
            expandButton.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleCardExpansion(card);
            });
        }
    }

    /**
     * Toggle card expansion
     */
    toggleCardExpansion(card) {
        const details = card.querySelector('.vehicle-details');
        const expandButton = card.querySelector('.expand-button');
        
        if (details && expandButton) {
            const isExpanded = details.style.display !== 'none';
            
            if (isExpanded) {
                details.style.display = 'none';
                expandButton.innerHTML = '<i class="fas fa-chevron-down"></i>';
                expandButton.title = 'Mở rộng';
            } else {
                details.style.display = 'block';
                expandButton.innerHTML = '<i class="fas fa-chevron-up"></i>';
                expandButton.title = 'Thu gọn';
            }
        }
    }

    /**
     * Get waiting vehicles statistics
     */
    getWaitingStats() {
        const waitingCount = this.vehicleCards.length;
        const readyCount = document.querySelectorAll('.vehicle-card.ready').length;
        const totalCount = waitingCount + readyCount;
        
        return {
            waiting: waitingCount,
            ready: readyCount,
            total: totalCount
        };
    }

    /**
     * Update waiting vehicles display
     */
    updateWaitingDisplay() {
        const stats = this.getWaitingStats();
        
        // Update stats display if it exists
        const statsElement = document.querySelector('.waiting-stats');
        if (statsElement) {
            statsElement.innerHTML = `
                <div class="text-sm text-gray-600">
                    <span class="font-semibold">${stats.waiting}</span> xe đang chờ | 
                    <span class="font-semibold">${stats.ready}</span> xe sẵn sàng | 
                    <span class="font-semibold">${stats.total}</span> tổng cộng
                </div>
            `;
        }
    }

    /**
     * Refresh waiting vehicles data
     */
    async refreshWaitingVehicles() {
        try {
            const response = await fetch('/api/vehicles/by-status?status=waiting');
            const data = await response.json();
            
            if (data.success) {
                // Update the page with new data
                this.updateVehiclesList(data.vehicles);
            }
        } catch (error) {
            console.error('Error refreshing waiting vehicles:', error);
        }
    }

    /**
     * Update vehicles list with new data
     */
    updateVehiclesList(vehicles) {
        // This would typically update the DOM with new vehicle data
        console.log('Updating vehicles list with', vehicles.length, 'vehicles');
        
        // Update stats
        this.updateWaitingDisplay();
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
