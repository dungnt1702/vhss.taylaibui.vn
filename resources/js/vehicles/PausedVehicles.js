/**
 * PausedVehicles - Class for managing paused vehicles
 * Extends VehicleBase with paused-specific functionality
 */


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
        const resumeButtons = document.querySelectorAll('[data-action="resume-vehicle"]');
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

    /**
     * Resume timer for a vehicle
     */
    async resumeTimer(vehicleId, button) {
        try {
            this.showButtonLoading(button, 'Đang tiếp tục...');
            
            const response = await this.makeApiCall(`/api/vehicles/${vehicleId}/resume`, {
                method: 'PATCH',
                body: JSON.stringify({})
            });

            if (response.success) {
                this.showNotification('Tiếp tục thành công!', 'success');
                
                // Clear countdown interval trước khi ẩn card
                this.clearCountdownInterval(vehicleId);
                
                // Ẩn vehicle card thay vì reload page
                // (Xe sẽ xuất hiện lại ở màn hình running)
                this.hideVehicleCard(vehicleId);
                
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error resuming timer:', error);
            this.showNotification('Có lỗi xảy ra khi tiếp tục', 'error');
        } finally {
            this.restoreButtonState(button);
        }
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

// Make PausedVehicles available globally
window.PausedVehicles = PausedVehicles;
