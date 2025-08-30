/**
 * ExpiredVehicles - Class for managing expired vehicles
 * Extends VehicleBase with expired-specific functionality
 */

import { VehicleBase } from './VehicleBase.js';

class ExpiredVehicles extends VehicleBase {
    constructor() {
        super('Expired Vehicles');
    }

    /**
     * Initialize expired vehicles page
     */
    init() {
        super.init();
        this.setupExpiredSpecificFeatures();
        console.log('Expired Vehicles page fully initialized');
    }

    /**
     * Setup expired-specific features
     */
    setupExpiredSpecificFeatures() {
        this.setupReturnYard();
        this.setupExtendTimer();
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
     * Setup extend timer functionality
     */
    setupExtendTimer() {
        const extendButtons = document.querySelectorAll('[data-action="extend-timer"]');
        extendButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const vehicleId = e.target.dataset.vehicleId;
                if (vehicleId) {
                    this.extendTimer(vehicleId, e.target);
                }
            });
        });
    }

    /**
     * Extend timer for expired vehicle
     */
    async extendTimer(vehicleId, button) {
        const duration = prompt('Nhập thời gian mở rộng (phút):');
        if (!duration || isNaN(duration) || duration <= 0 || duration > 120) {
            this.showNotification('Thời gian không hợp lệ (1-120 phút)', 'error');
            return;
        }

        try {
            this.showButtonLoading(button, 'Đang mở rộng...');
            
            const response = await this.makeApiCall('/api/vehicles/extend-timer', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId],
                    duration: parseInt(duration)
                })
            });

            if (response.success) {
                this.showNotification('Mở rộng thời gian thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error extending timer:', error);
            this.showNotification('Có lỗi xảy ra khi mở rộng thời gian', 'error');
        } finally {
            this.restoreButtonState(button);
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Expired Vehicles page loaded');
    
    // Create and initialize ExpiredVehicles instance
    const expiredVehicles = new ExpiredVehicles();
    expiredVehicles.init();
    
    // Make it available globally for debugging
    window.expiredVehicles = expiredVehicles;
});

// Export for ES6 modules
export default ExpiredVehicles;
