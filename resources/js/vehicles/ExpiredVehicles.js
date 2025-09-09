/**
 * ExpiredVehicles - Class for managing expired vehicles
 * Extends VehicleBase with expired-specific functionality
 */


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
        this.setupAddTime(); // Thêm xử lý add-time
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
     * Setup add time functionality for expired vehicles
     */
    setupAddTime() {
        const addTimeButtons = document.querySelectorAll('[data-action="add-time"]');
        addTimeButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                // Sử dụng e.currentTarget thay vì e.target để đảm bảo lấy đúng button
                const targetButton = e.currentTarget;
                const vehicleId = targetButton.dataset.vehicleId;
                const duration = parseInt(targetButton.dataset.duration);
                if (vehicleId && duration) {
                    this.handleAddTime(vehicleId, duration, targetButton);
                }
            });
        });
    }

    /**
     * Handle add time for expired vehicle
     */
    async handleAddTime(vehicleId, duration, button) {
        console.log('=== EXPIRED VEHICLE ADD TIME HANDLER ===');
        console.log('Vehicle ID:', vehicleId);
        console.log('Duration:', duration);
        console.log('Button:', button);
        
        // Safety check: đảm bảo button tồn tại
        if (!button || !button.dataset) {
            console.error('Button is undefined or invalid:', button);
            this.showNotificationModal('Lỗi', 'Button không hợp lệ', 'error');
            return;
        }
        
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
                
                // Debug response
                console.log('=== API RESPONSE DEBUG ===');
                console.log('Full response:', response);
                console.log('new_end_time:', response.new_end_time);
                console.log('expired_end_time:', response.expired_end_time);
                console.log('duration_added:', response.duration_added);
                
                // Xử lý xe expired: chuyển sang running và ẩn khỏi màn hình
                const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
                if (vehicleCard) {
                    console.log('Processing expired vehicle response');
                    this.processExpiredVehicleAddTime(vehicleCard, response);
                }
                
            } else {
                this.showNotificationModal('Lỗi', response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error adding time to expired vehicle:', error);
            this.showNotificationModal('Lỗi', 'Có lỗi xảy ra khi thêm thời gian', 'error');
        } finally {
            // Restore button state - thêm safety check
            if (button && button.dataset) {
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
            } else {
                console.error('Cannot restore button state - button is invalid:', button);
            }
        }
    }

    /**
     * Process expired vehicle after adding time
     * This is DIFFERENT from running vehicles - expired vehicles need special handling
     */
    processExpiredVehicleAddTime(vehicleCard, response) {
        console.log('=== EXPIRED VEHICLE ADD TIME PROCESSING ===');
        console.log('Vehicle ID:', vehicleCard.dataset.vehicleId);
        console.log('Response new_end_time:', response.new_end_time);
        console.log('Duration added (from response):', response.duration_added);

        // Cập nhật status thành running
        vehicleCard.dataset.status = 'running';

        // Cập nhật end time từ response - ưu tiên expired_end_time cho xe expired
        let newEndTime = null;
        if (response.expired_end_time) {
            // Xe expired: sử dụng expired_end_time
            newEndTime = response.expired_end_time;
            console.log('Using expired_end_time from response:', newEndTime);
        } else if (response.new_end_time) {
            // Fallback: sử dụng new_end_time
            newEndTime = response.new_end_time;
            console.log('Using new_end_time from response:', newEndTime);
        }
        
        if (newEndTime) {
            vehicleCard.dataset.endTime = newEndTime;

            // Convert to readable time for debugging
            const newDate = new Date(parseInt(newEndTime));
            console.log('New end time (readable):', newDate.toLocaleString('vi-VN'));
            console.log('Current time (readable):', new Date().toLocaleString('vi-VN'));
            console.log('Expected duration:', response.duration_added);
        } else {
            console.error('No end time found in response:', response);
        }

        // Thay đổi border color từ đỏ sang xanh
        vehicleCard.classList.remove('border-red-500');
        vehicleCard.classList.add('border-blue-500');

        // Cập nhật countdown display từ "HẾT GIỜ" sang timer
        const countdownDisplay = vehicleCard.querySelector('.countdown-display');
        if (countdownDisplay) {
            countdownDisplay.innerHTML = `
                <span class="countdown-minutes text-6xl font-black drop-shadow-lg">00</span>
                <span class="text-6xl font-black drop-shadow-lg">:</span>
                <span class="countdown-seconds text-6xl font-black drop-shadow-lg">00</span>
            `;
            countdownDisplay.classList.remove('text-red-600');
            countdownDisplay.classList.add('text-blue-600');
        }

        // Ẩn vehicle card khỏi màn hình expired NGAY LẬP TỨC
        // Vì xe này sẽ xuất hiện ở màn hình running
        console.log('Hiding expired vehicle card - converting to running');
        this.hideVehicleCard(vehicleCard.dataset.vehicleId);
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

// Make ExpiredVehicles available globally
window.ExpiredVehicles = ExpiredVehicles;
