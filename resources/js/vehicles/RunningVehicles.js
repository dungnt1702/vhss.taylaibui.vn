/**
 * RunningVehicles - Class for managing running vehicles
 * Extends VehicleBase with running-specific functionality
 */


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
            // Prevent duplicate requests
            if (button.disabled || button.dataset.processing === 'true') {
                console.log('Button already processing, skipping duplicate request');
                return;
            }
            
            // Mark button as processing
            button.dataset.processing = 'true';
            button.dataset.originalText = button.innerHTML;
            button.innerHTML = `Đang thêm ${duration} phút...`;
            button.disabled = true;
            
            console.log('Button state set:', {
                disabled: button.disabled,
                processing: button.dataset.processing,
                text: button.innerHTML
            });

            const response = await this.makeApiCall('/api/vehicles/add-time', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId],
                    duration: duration
                })
            });

            if (response.success) {
                this.showNotificationModal('Thành công', `Đã thêm ${duration} phút thành công!`, 'success');
                
                // Xử lý xe running: cập nhật timer và tiếp tục đếm ngược
                const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
                if (vehicleCard) {
                    console.log('Processing running vehicle response');
                    this.handleRunningVehicleAddTime(vehicleCard, response);
                }
                
            } else {
                this.showNotificationModal('Lỗi', response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error adding time to running vehicle:', error);
            this.showNotificationModal('Lỗi', 'Có lỗi xảy ra khi thêm thời gian', 'error');
        } finally {
            // Restore button state
            if (button.dataset.originalText) {
                button.innerHTML = button.dataset.originalText;
                delete button.dataset.originalText;
            }
            button.disabled = false;
            delete button.dataset.processing;
            
            console.log('Button state restored:', {
                disabled: button.disabled,
                processing: button.dataset.processing,
                text: button.innerHTML
            });
        }
    }

    /**
     * Handle add time response for running vehicles
     */
    handleRunningVehicleAddTime(vehicleCard, response) {
        console.log('=== RUNNING VEHICLE ADD TIME ===');
        console.log('Vehicle ID:', vehicleCard.dataset.vehicleId);
        console.log('Old end time (dataset):', vehicleCard.dataset.endTime);
        console.log('Response new_end_time:', response.new_end_time);
        console.log('Duration added (from response):', response.duration_added);
        
        // Clear interval cũ
        this.clearCountdownInterval(vehicleCard.dataset.vehicleId);
        
        // Cập nhật end time từ response nếu có
        if (response.new_end_time) {
            const oldEndTime = vehicleCard.dataset.endTime;
            vehicleCard.dataset.endTime = response.new_end_time;
            
            // Tính toán chính xác thời gian được thêm vào
            const timeDiffMs = response.new_end_time - oldEndTime;
            const timeDiffMinutes = timeDiffMs / (1000 * 60);
            
            console.log('=== TIME CALCULATION ===');
            console.log('Old end time (ms):', oldEndTime);
            console.log('New end time (ms):', response.new_end_time);
            console.log('Time difference (ms):', timeDiffMs);
            console.log('Time difference (minutes):', timeDiffMinutes);
            console.log('Expected duration:', response.duration_added);
            console.log('Is correct?', Math.abs(timeDiffMinutes - response.duration_added) < 0.1);
            
            // Convert to readable time for debugging
            const oldDate = new Date(parseInt(oldEndTime));
            const newDate = new Date(parseInt(response.new_end_time));
            console.log('Old end time (readable):', oldDate.toLocaleString('vi-VN'));
            console.log('New end time (readable):', newDate.toLocaleString('vi-VN'));
        }
        
        // Restart countdown timer với thời gian mới NGAY LẬP TỨC
        this.startCountdownTimer(vehicleCard);
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

// Make RunningVehicles available globally
window.RunningVehicles = RunningVehicles;
