/**
 * RunningVehicles - Class for managing running vehicles
 * Extends VehicleBase with running-specific functionality
 */

import { VehicleBase } from './VehicleBase.js';

class RunningVehicles extends VehicleBase {
    constructor() {
        super('Running Vehicles');
    }

    /**
     * Initialize running vehicles page
     */
    init() {
        super.init();
        this.setupRunningSpecificFeatures();
        console.log('Running Vehicles page fully initialized');
    }

    /**
     * Setup running-specific features
     */
    setupRunningSpecificFeatures() {
        this.setupPauseTimer();
        this.setupAddTime();
        console.log('Running Vehicles: Pause and add time features initialized');
    }

    /**
     * Setup pause timer functionality
     */
    setupPauseTimer() {
        const pauseButtons = document.querySelectorAll('[data-action="pause-vehicle"]');
        pauseButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const vehicleId = e.target.dataset.vehicleId;
                if (vehicleId) {
                    this.pauseTimer(vehicleId, e.target);
                }
            });
        });
    }

    /**
     * Setup add time functionality
     */
    setupAddTime() {
        const addTimeButtons = document.querySelectorAll('[data-action="add-time"]');
        addTimeButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const vehicleId = e.target.dataset.vehicleId;
                const duration = parseInt(e.target.dataset.duration);
                if (vehicleId && duration) {
                    this.addTime(vehicleId, duration, e.target);
                }
            });
        });
    }

    /**
     * Pause timer for a vehicle
     */
    async pauseTimer(vehicleId, button) {
        try {
            this.showButtonLoading(button, 'Đang tạm dừng...');
            
            const response = await this.makeApiCall(`/api/vehicles/${vehicleId}/pause`, {
                method: 'PATCH',
                body: JSON.stringify({})
            });

            if (response.success) {
                this.showNotification('Tạm dừng thành công!', 'success');
                
                // Clear countdown interval trước khi ẩn card
                this.clearCountdownInterval(vehicleId);
                
                // Ẩn vehicle card thay vì reload page
                this.hideVehicleCard(vehicleId);
                
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error pausing timer:', error);
            this.showNotification('Có lỗi xảy ra khi tạm dừng', 'error');
        } finally {
            this.restoreButtonState(button);
        }
    }

    /**
     * Add time to vehicle timer
     */
    async addTime(vehicleId, duration, button) {
        try {
            this.showButtonLoading(button, `Đang thêm ${duration} phút...`);
            
            const response = await this.makeApiCall('/api/vehicles/add-time', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId],
                    duration: duration
                })
            });

            if (response.success) {
                this.showNotification(`Đã thêm ${duration} phút thành công!`, 'success');
                
                // Refresh countdown timer với thời gian mới
                const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
                if (vehicleCard) {
                    // Clear interval cũ
                    this.clearCountdownInterval(vehicleId);
                    
                    // Cập nhật end time từ response nếu có
                    if (response.new_end_time) {
                        vehicleCard.dataset.endTime = response.new_end_time;
                    }
                    
                    // Restart countdown timer
                    this.startCountdownTimer(vehicleCard);
                }
                
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error adding time:', error);
            this.showNotification('Có lỗi xảy ra khi thêm thời gian', 'error');
        } finally {
            this.restoreButtonState(button);
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Running Vehicles page loaded');
    
    // Create and initialize RunningVehicles instance
    const runningVehicles = new RunningVehicles();
    runningVehicles.init();
    
    // Make it available globally for debugging
    window.runningVehicles = runningVehicles;
});

// Export for ES6 modules
export default RunningVehicles;
