/**
 * VehicleBase - Base class for all vehicle-related functionality
 * Contains common methods and utilities used across all vehicle statuses
 */

export class VehicleBase {
    constructor(pageName) {
        this.pageName = pageName;
        this.vehicleCards = [];
        this.actionButtons = [];
        this.initialized = false;
        console.log(`${this.pageName} class initialized`);
    }

    /**
     * Initialize the vehicle page
     */
    init() {
        if (this.initialized) return;
        
        this.initializeElements();
        this.initializeEventListeners();
        this.initializeCountdownTimers();
        this.initialized = true;
        console.log(`${this.pageName} page initialized`);
    }

    /**
     * Initialize DOM elements
     */
    initializeElements() {
        this.vehicleCards = document.querySelectorAll('.vehicle-card');
        this.actionButtons = document.querySelectorAll('[data-action]');
        
        if (this.vehicleCards.length === 0) {
            console.log(`${this.pageName}: No vehicle cards found`);
            return;
        }
        
        console.log(`${this.pageName}: Found ${this.vehicleCards.length} vehicle cards`);
    }

    /**
     * Initialize event listeners for common actions
     */
    initializeEventListeners() {
        if (this.actionButtons.length === 0) return;
        
        this.actionButtons.forEach(button => {
            const action = button.dataset.action;
            if (action) {
                this.setupActionListener(button, action);
            }
        });
    }

    /**
     * Setup action listener for a specific button
     */
    setupActionListener(button, action) {
        switch (action) {
            case 'start-timer':
                button.addEventListener('click', (e) => this.handleStartTimer(e));
                break;
            case 'pause-timer':
                button.addEventListener('click', (e) => this.handlePauseTimer(e));
                break;
            case 'resume-timer':
                button.addEventListener('click', (e) => this.handleResumeTimer(e));
                break;
            case 'return-yard':
                button.addEventListener('click', (e) => this.handleReturnYard(e));
                break;
            case 'move-workshop':
                button.addEventListener('click', (e) => this.handleMoveWorkshop(e));
                break;
            case 'assign-route':
                button.addEventListener('click', (e) => this.handleAssignRoute(e));
                break;
            case 'close-notification':
                button.addEventListener('click', (e) => this.closeNotification(e));
                break;
        }
    }

    /**
     * Initialize countdown timers for all vehicles
     */
    initializeCountdownTimers() {
        if (this.vehicleCards.length === 0) return;
        
        this.vehicleCards.forEach(card => {
            this.startCountdownTimer(card);
        });
    }

    /**
     * Start countdown timer for a specific vehicle card
     */
    startCountdownTimer(card) {
        const timerElement = card.querySelector('.countdown-timer');
        if (!timerElement) return;

        const endTime = timerElement.dataset.endTime;
        if (!endTime) return;

        this.updateCountdown(timerElement, endTime);
        
        // Update every second
        setInterval(() => {
            this.updateCountdown(timerElement, endTime);
        }, 1000);
    }

    /**
     * Update countdown display
     */
    updateCountdown(timerElement, endTime) {
        const now = new Date().getTime();
        const end = new Date(endTime).getTime();
        const distance = end - now;

        if (distance < 0) {
            timerElement.textContent = 'Hết giờ';
            timerElement.classList.add('text-red-500');
            return;
        }

        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        timerElement.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }

    /**
     * Handle start timer action
     */
    handleStartTimer(e) {
        const vehicleId = e.target.dataset.vehicleId;
        const duration = parseInt(e.target.dataset.duration);
        
        if (!vehicleId || !duration) {
            console.error('Missing vehicle ID or duration');
            return;
        }

        if (confirm(`Bạn có chắc muốn bắt đầu bấm giờ cho xe ${vehicleId} trong ${duration} phút?`)) {
            this.startTimer(vehicleId, duration, e.target);
        }
    }

    /**
     * Handle pause timer action
     */
    handlePauseTimer(e) {
        const vehicleId = e.target.dataset.vehicleId;
        if (vehicleId) {
            this.pauseTimer(vehicleId, e.target);
        }
    }

    /**
     * Handle resume timer action
     */
    handleResumeTimer(e) {
        const vehicleId = e.target.dataset.vehicleId;
        if (vehicleId) {
            this.resumeTimer(vehicleId, e.target);
        }
    }

    /**
     * Handle return yard action
     */
    handleReturnYard(e) {
        const vehicleId = e.target.dataset.vehicleId;
        if (vehicleId) {
            this.returnToYard(vehicleId, e.target);
        }
    }

    /**
     * Handle move workshop action
     */
    handleMoveWorkshop(e) {
        const vehicleId = e.target.dataset.vehicleId;
        if (vehicleId) {
            this.moveToWorkshop(vehicleId, e.target);
        }
    }

    /**
     * Handle assign route action
     */
    handleAssignRoute(e) {
        const vehicleId = e.target.dataset.vehicleId;
        if (vehicleId) {
            this.assignRoute(vehicleId, e.target);
        }
    }

    /**
     * Start timer for a vehicle
     */
    async startTimer(vehicleId, duration, button) {
        try {
            this.showButtonLoading(button, 'Đang bấm giờ...');
            
            const response = await this.makeApiCall('/api/vehicles/start-timer', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId],
                    duration: duration
                })
            });

            if (response.success) {
                this.showNotification('Bấm giờ thành công!', 'success');
                // Reload page to show updated status
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error starting timer:', error);
            this.showNotification('Có lỗi xảy ra khi bấm giờ', 'error');
        } finally {
            this.restoreButtonState(button);
        }
    }

    /**
     * Pause timer for a vehicle
     */
    async pauseTimer(vehicleId, button) {
        try {
            this.showButtonLoading(button, 'Đang tạm dừng...');
            
            const response = await this.makeApiCall('/api/vehicles/pause-timer', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId]
                })
            });

            if (response.success) {
                this.showNotification('Tạm dừng thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
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
     * Resume timer for a vehicle
     */
    async resumeTimer(vehicleId, button) {
        try {
            this.showButtonLoading(button, 'Đang tiếp tục...');
            
            const response = await this.makeApiCall('/api/vehicles/resume-timer', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId]
                })
            });

            if (response.success) {
                this.showNotification('Tiếp tục thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
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

    /**
     * Return vehicle to yard
     */
    async returnToYard(vehicleId, button) {
        try {
            // Simple loading state
            if (button) {
                const originalText = button.textContent;
                button.textContent = 'Đang xử lý...';
                button.disabled = true;
            }
            
            const response = await this.makeApiCall('/api/vehicles/return-yard', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId]
                })
            });

            if (response.success) {
                // Simple success - just reload page
                window.location.reload();
            } else {
                // Show error if needed
                console.error('Return to yard failed:', response.message);
                if (button) {
                    button.textContent = 'Lỗi - Thử lại';
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.disabled = false;
                    }, 2000);
                }
            }
        } catch (error) {
            console.error('Error returning to yard:', error);
            if (button) {
                button.textContent = 'Lỗi - Thử lại';
                setTimeout(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                }, 2000);
            }
        }
    }

    /**
     * Move vehicle to workshop
     */
    async moveToWorkshop(vehicleId, button) {
        try {
            this.showButtonLoading(button, 'Đang chuyển xưởng...');
            
            const response = await this.makeApiCall('/api/vehicles/move-workshop', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId]
                })
            });

            if (response.success) {
                this.showNotification('Chuyển xưởng thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error moving to workshop:', error);
            this.showNotification('Có lỗi xảy ra khi chuyển xưởng', 'error');
        } finally {
            this.restoreButtonState(button);
        }
    }

    /**
     * Assign route to vehicle
     */
    async assignRoute(vehicleId, button) {
        try {
            this.showButtonLoading(button, 'Đang phân tuyến...');
            
            const response = await this.makeApiCall('/api/vehicles/assign-route', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId]
                })
            });

            if (response.success) {
                this.showNotification('Phân tuyến thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error assigning route:', error);
            this.showNotification('Có lỗi xảy ra khi phân tuyến', 'error');
        } finally {
            this.restoreButtonState(button);
        }
    }

    /**
     * Make API call with error handling
     */
    async makeApiCall(url, options = {}) {
        try {
            const response = await fetch(url, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    ...options.headers
                },
                ...options
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('API call failed:', error);
            throw error;
        }
    }

    /**
     * Show notification to user
     */
    showNotification(message, type = 'info') {
        // Try to use browser notifications if available
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification(message);
        } else {
            // Fallback to alert
            alert(message);
        }
    }

    /**
     * Show loading state on button
     */
    showButtonLoading(button, text) {
        button.dataset.originalText = button.textContent;
        button.textContent = text;
        button.disabled = true;
    }

    /**
     * Restore button to original state
     */
    restoreButtonState(button) {
        if (button.dataset.originalText) {
            button.textContent = button.dataset.originalText;
            delete button.dataset.originalText;
        }
        button.disabled = false;
    }

    /**
     * Close notification
     */
    closeNotification(event) {
        const notification = event.target.closest('.notification');
        if (notification) {
            notification.remove();
        }
    }

    /**
     * Get selected vehicles
     */
    getSelectedVehicles() {
        const checkboxes = document.querySelectorAll('.vehicle-checkbox:checked');
        return Array.from(checkboxes).map(cb => cb.value);
    }

    /**
     * Setup select all functionality
     */
    setupSelectAll(containerId, checkboxClass) {
        const selectAll = document.getElementById(`select-all-${containerId}`);
        if (!selectAll) return;

        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll(`.${checkboxClass}`);
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
}

// Make VehicleBase available globally for backward compatibility
if (typeof window !== 'undefined') {
    window.VehicleBase = VehicleBase;
}
