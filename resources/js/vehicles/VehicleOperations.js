/**
 * VehicleOperations - Vehicle control operations class
 * Extends VehicleBase with operation-specific functionality
 */

import { VehicleBase } from './VehicleBase.js';

class VehicleOperations extends VehicleBase {
    constructor() {
        super('Vehicle Operations');
        this.vehicleTimers = {};
        this.currentFilter = '';
    }

    /**
     * Initialize vehicle operations page
     */
    init() {
        super.init();
        this.setupOperationSpecificFeatures();
        console.log('Vehicle Operations page fully initialized');
    }

    /**
     * Setup operation-specific features
     */
    setupOperationSpecificFeatures() {
        this.setupVehicleControl();
        this.setupCountdownTimers();
        this.setupGlobalFunctions();
    }

    /**
     * Setup vehicle control functionality
     */
    setupVehicleControl() {
        // Vehicle control buttons are handled by VehicleBase
        // Additional operation-specific setup can be added here
    }

    /**
     * Setup countdown timers
     */
    setupCountdownTimers() {
        // Countdown timers are handled by VehicleBase
        // Additional timer-specific setup can be added here
    }

    /**
     * Setup global functions for backward compatibility
     */
    setupGlobalFunctions() {
        // Make functions available globally for onclick handlers
        window.startVehicle = (vehicleId, minutes = 30) => this.startVehicle(vehicleId, minutes);
        window.pauseVehicle = (vehicleId) => this.pauseVehicle(vehicleId);
        window.resumeVehicle = (vehicleId) => this.resumeVehicle(vehicleId);
        window.returnToYard = (vehicleId) => this.returnToYard(vehicleId);
        window.addTime = (vehicleId, additionalMinutes) => this.addTime(vehicleId, additionalMinutes);
        window.moveToWorkshop = (vehicleId) => this.moveToWorkshop(vehicleId);
    }

    /**
     * Start vehicle with timer
     */
    async startVehicle(vehicleId, minutes = 30) {
        const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
        if (!vehicleCard) return;
        
        try {
            // Calculate start and end time using local timezone
            const startTimeMs = this.getCurrentTimeMs();
            const endTimeMs = startTimeMs + (minutes * 60 * 1000);
            
            // Update vehicle card
            vehicleCard.dataset.startTime = startTimeMs.toString();
            vehicleCard.dataset.endTime = endTimeMs.toString();
            vehicleCard.dataset.status = 'running';
            
            // Start countdown timer
            this.startCountdownTimer(vehicleId, endTimeMs);
            
            // Update UI
            this.updateStatusText(vehicleId, 'running', minutes);
            this.updateVehicleButtons(vehicleId, 'running');
            
            // Update countdown display
            this.updateCountdownDisplay(vehicleId, minutes * 60 * 1000);
            
            // Send to server
            await this.updateVehicleStatus(vehicleId, 'running', endTimeMs, startTimeMs);
            
            // Hide vehicle from current screen (it's now running)
            this.hideVehicleFromCurrentScreen(vehicleId, 'running');
            
            // Speak notification
            this.speakVietnamese(`Xe ${this.getVehicleName(vehicleId)} đã xuất bãi với ${minutes} phút`);
            
            this.showNotification('Bắt đầu xe thành công!', 'success');
        } catch (error) {
            console.error('Error starting vehicle:', error);
            this.showNotification('Có lỗi xảy ra khi bắt đầu xe', 'error');
        }
    }

    /**
     * Pause vehicle
     */
    async pauseVehicle(vehicleId) {
        try {
            // Stop the countdown timer
            if (this.vehicleTimers[vehicleId]) {
                clearInterval(this.vehicleTimers[vehicleId]);
                delete this.vehicleTimers[vehicleId];
            }
            
            // Update the vehicle card's data attributes immediately
            const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
            if (vehicleCard) {
                vehicleCard.dataset.status = 'paused';
            }
            
            // Update countdown display to show "Tạm dừng"
            const countdownElement = document.getElementById(`countdown-${vehicleId}`);
            if (countdownElement) {
                countdownElement.innerHTML = '<span class="text-yellow-600 font-bold">TẠM DỪNG</span>';
                countdownElement.classList.add('text-yellow-600');
            }
            
            // Calculate remaining minutes for display and store for resume
            let remainingMinutes = 0;
            let remainingSeconds = 0;
            if (vehicleCard.dataset.endTime && vehicleCard.dataset.endTime !== '') {
                const endTimeMs = parseInt(vehicleCard.dataset.endTime);
                const now = this.getCurrentTimeMs();
                const timeLeft = endTimeMs - now;
                
                if (timeLeft > 0) {
                    remainingMinutes = Math.floor(timeLeft / (1000 * 60));
                    remainingSeconds = Math.floor(timeLeft / 1000);
                    
                    // Store remaining time in data attribute for resume
                    vehicleCard.dataset.pausedRemainingSeconds = remainingSeconds;
                } else {
                    // Time has already expired, set remaining to 0
                    remainingMinutes = 0;
                    remainingSeconds = 0;
                    vehicleCard.dataset.pausedRemainingSeconds = '0';
                }
            } else {
                // No end time data
                remainingMinutes = 0;
                remainingSeconds = 0;
                vehicleCard.dataset.pausedRemainingSeconds = '0';
            }
            
            // Update status text to show remaining time
            this.updateStatusText(vehicleId, 'paused', remainingMinutes);
            
            // Update button display to show "Tiếp tục" and "Về bãi"
            this.updateVehicleButtons(vehicleId, 'paused');
            
            // Speak notification
            const vehicleName = this.getVehicleName(vehicleId);
            this.speakVietnamese(`Xe ${vehicleName} đã được tạm dừng`);
            
            await this.updateVehicleStatus(vehicleId, 'paused');
            
            // Show navigation hint to user
            this.showNavigationHint(vehicleId, 'paused');
            
            this.showNotification('Tạm dừng xe thành công!', 'success');
        } catch (error) {
            console.error('Error pausing vehicle:', error);
            this.showNotification('Có lỗi xảy ra khi tạm dừng xe', 'error');
        }
    }

    /**
     * Resume vehicle
     */
    async resumeVehicle(vehicleId) {
        try {
            // Get the current end time from the vehicle card
            const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
            if (vehicleCard) {
                vehicleCard.dataset.status = 'running';
                
                // Get the paused remaining time or calculate from end time
                let timeLeft = 0;
                let endTimeMs = 0;
                
                // Check if we have paused remaining time
                if (vehicleCard.dataset.pausedRemainingSeconds) {
                    const pausedSeconds = parseInt(vehicleCard.dataset.pausedRemainingSeconds);
                    if (pausedSeconds > 0) {
                        // Calculate new end time based on paused remaining time
                        const now = this.getCurrentTimeMs();
                        endTimeMs = now + (pausedSeconds * 1000);
                        timeLeft = pausedSeconds * 1000;
                        
                        // Update the end time in data attribute
                        vehicleCard.dataset.endTime = endTimeMs.toString();
                        
                        // Clear paused data
                        delete vehicleCard.dataset.pausedRemainingSeconds;
                    } else {
                        // Paused time is 0 or invalid, set to expired
                        vehicleCard.dataset.status = 'expired';
                        this.updateStatusText(vehicleId, 'expired', null);
                        this.updateVehicleButtons(vehicleId, 'expired');
                        return; // Exit early
                    }
                } else {
                    // No paused data, check if we can calculate from end time
                    const endTime = vehicleCard.dataset.endTime;
                    if (endTime && endTime !== '') {
                        endTimeMs = parseInt(endTime);
                        const now = this.getCurrentTimeMs();
                        timeLeft = endTimeMs - now;
                        
                        // If time has already expired, don't allow resume
                        if (timeLeft <= 0) {
                            vehicleCard.dataset.status = 'expired';
                            this.updateStatusText(vehicleId, 'expired', null);
                            this.updateVehicleButtons(vehicleId, 'expired');
                            return; // Exit early
                        }
                    } else {
                        // No end time data, can't resume
                        vehicleCard.dataset.status = 'ready';
                        this.updateStatusText(vehicleId, 'ready', null);
                        this.updateVehicleButtons(vehicleId, 'ready');
                        return; // Exit early
                    }
                }
                
                // At this point, timeLeft should be > 0 and valid
                if (timeLeft > 0) {
                    // Update countdown display to show remaining time
                    const countdownElement = document.getElementById(`countdown-${vehicleId}`);
                    if (countdownElement) {
                        countdownElement.classList.remove('text-yellow-600');
                        
                        const minutesLeft = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                        const secondsLeft = Math.floor((timeLeft % (1000 * 60)) / 1000);
                        
                        const minutesDisplay = minutesLeft.toString().padStart(2, '0');
                        const secondsDisplay = secondsLeft.toString().padStart(2, '0');
                        
                        countdownElement.innerHTML = `<span class="countdown-minutes">${minutesDisplay}</span>:<span class="countdown-seconds">${secondsDisplay}</span>`;
                    }
                    
                    // Restart countdown timer with the exact remaining time from database
                    this.startCountdownTimer(vehicleId, endTimeMs);
                    
                    // Update status text to show remaining time
                    this.updateStatusText(vehicleId, 'running', Math.floor(timeLeft / (1000 * 60)));
                    
                    // Update button display to show running buttons
                    this.updateVehicleButtons(vehicleId, 'running');
                } else {
                    // This should not happen with the new logic above, but just in case
                    vehicleCard.dataset.status = 'expired';
                    this.updateStatusText(vehicleId, 'expired', null);
                    this.updateVehicleButtons(vehicleId, 'expired');
                }
            }
            
            // Speak notification
            const vehicleName = this.getVehicleName(vehicleId);
            this.speakVietnamese(`Xe ${vehicleName} đã được tiếp tục`);
            
            await this.updateVehicleStatus(vehicleId, 'running');
            
            this.showNotification('Tiếp tục xe thành công!', 'success');
        } catch (error) {
            console.error('Error resuming vehicle:', error);
            this.showNotification('Có lỗi xảy ra khi tiếp tục xe', 'error');
        }
    }

    /**
     * Add time to vehicle
     */
    async addTime(vehicleId, additionalMinutes) {
        try {
            const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
            if (!vehicleCard) return;
            
            // Get current end time or use current time
            let currentEndTime = vehicleCard.dataset.endTime;
            let newEndTime;
            
            if (currentEndTime && currentEndTime !== '') {
                // Add to existing end time
                newEndTime = parseInt(currentEndTime) + (additionalMinutes * 60 * 1000);
            } else {
                // Start from current time
                newEndTime = this.getCurrentTimeMs() + (additionalMinutes * 60 * 1000);
            }
            
            // Update vehicle card
            vehicleCard.dataset.endTime = newEndTime.toString();
            vehicleCard.dataset.status = 'running';
            
            // Start countdown timer
            this.startCountdownTimer(vehicleId, newEndTime);
            
            // Update UI
            this.updateStatusText(vehicleId, 'running', additionalMinutes);
            this.updateVehicleButtons(vehicleId, 'running');
            
            // Update countdown display
            this.updateCountdownDisplay(vehicleId, additionalMinutes * 60 * 1000);
            
            // Send to server
            await this.updateVehicleStatus(vehicleId, 'running', newEndTime);
            
            // Speak notification
            this.speakVietnamese(`Xe ${this.getVehicleName(vehicleId)} đã được thêm ${additionalMinutes} phút`);
            
            this.showNotification(`Đã thêm ${additionalMinutes} phút cho xe!`, 'success');
        } catch (error) {
            console.error('Error adding time to vehicle:', error);
            this.showNotification('Có lỗi xảy ra khi thêm thời gian', 'error');
        }
    }

    /**
     * Get current time in milliseconds (local timezone)
     */
    getCurrentTimeMs() {
        return new Date().getTime();
    }

    /**
     * Update countdown display
     */
    updateCountdownDisplay(vehicleId, timeLeft) {
        const countdownElement = document.getElementById(`countdown-${vehicleId}`);
        if (!countdownElement) return;
        
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
        
        const minutesDisplay = minutes.toString().padStart(2, '0');
        const secondsDisplay = seconds.toString().padStart(2, '0');
        
        countdownElement.innerHTML = `<span class="countdown-minutes">${minutesDisplay}</span>:<span class="countdown-seconds">${secondsDisplay}</span>`;
    }

    /**
     * Update status text
     */
    updateStatusText(vehicleId, status, minutes = null) {
        const statusTextElement = document.getElementById(`status-text-${vehicleId}`);
        if (!statusTextElement) return;
        
        let statusText = '';
        switch (status) {
            case 'running':
                statusText = minutes !== null ? `Xe chạy ${minutes}p` : 'Đang chạy';
                break;
            case 'paused':
                statusText = minutes !== null ? `Xe tạm dừng ${minutes}p` : 'Tạm dừng';
                break;
            case 'ready':
                statusText = 'Đang chờ';
                break;
            case 'expired':
                statusText = 'Hết giờ';
                break;
            case 'ready':
                statusText = minutes !== null ? `Xe chạy ${minutes}p` : 'Ngoài bãi';
                break;
            default:
                statusText = 'Không xác định';
        }
        
        statusTextElement.textContent = statusText;
    }

    /**
     * Update vehicle buttons
     */
    updateVehicleButtons(vehicleId, status) {
        const buttonContainer = document.getElementById(`buttons-${vehicleId}`);
        if (!buttonContainer) return;
        
        let buttonHTML = '';
        
        switch (status) {
            case 'running':
                buttonHTML = `
                    <button onclick="vehicleOperations.pauseVehicle(${vehicleId})" class="btn btn-info btn-sm">
                        ⏸️ Tạm dừng
                    </button>
                    <button onclick="vehicleOperations.returnToYard(${vehicleId})" class="btn btn-primary btn-sm">
                        🏠 Về bãi
                    </button>
                `;
                break;
            case 'paused':
                buttonHTML = `
                    <button onclick="vehicleOperations.resumeVehicle(${vehicleId})" class="btn btn-success btn-sm">
                        ▶️ Tiếp tục
                    </button>
                    <button onclick="vehicleOperations.returnToYard(${vehicleId})" class="btn btn-primary btn-sm">
                        🏠 Về bãi
                    </button>
                `;
                break;
            case 'expired':
                buttonHTML = `
                    <button onclick="vehicleOperations.addTime(${vehicleId}, 10)" class="btn btn-warning btn-sm">
                        ⏰ +10p
                    </button>
                    <button onclick="vehicleOperations.returnToYard(${vehicleId})" class="btn btn-primary btn-sm">
                        🏠 Về bãi
                    </button>
                `;
                break;
            case 'ready':
                buttonHTML = `
                    <button onclick="vehicleOperations.startVehicle(${vehicleId}, 30)" class="btn btn-success btn-sm">
                        🚀 Xuất bãi
                    </button>
                    <button onclick="vehicleOperations.moveToWorkshop(${vehicleId})" class="btn btn-secondary btn-sm">
                        🔧 Về xưởng
                    </button>
                `;
                break;
        }
        
        buttonContainer.innerHTML = buttonHTML;
    }

    /**
     * Get vehicle name
     */
    getVehicleName(vehicleId) {
        const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
        return vehicleCard ? vehicleCard.dataset.vehicleName : `Xe ${vehicleId}`;
    }

    /**
     * Speak Vietnamese text
     */
    speakVietnamese(text) {
        if ('speechSynthesis' in window) {
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'vi-VN';
            utterance.rate = 0.8;
            speechSynthesis.speak(utterance);
        }
    }

    /**
     * Show navigation hint
     */
    showNavigationHint(vehicleId, newStatus) {
        const currentPath = window.location.pathname;
        let targetRoute = null;
        
        // Determine target route based on new status
        switch (newStatus) {
            case 'running':
                targetRoute = '/vehicles/running';
                break;
            case 'paused':
                targetRoute = '/vehicles/paused';
                break;
            case 'expired':
                targetRoute = '/vehicles/expired';
                break;
            case 'ready':
                targetRoute = '/vehicles/ready';
                break;
        }
        
        // Show hint if we're not on the target screen
        if (targetRoute && !currentPath.includes(targetRoute.replace('/vehicles/', ''))) {
            const vehicleName = this.getVehicleName(vehicleId);
            const statusText = newStatus === 'running' ? 'đang chạy' : 
                             newStatus === 'paused' ? 'tạm dừng' : 
                             newStatus === 'expired' ? 'hết giờ' : 'sẵn sàng';
            
            // Optional: Show a small notification to user
            this.showNotification(`Xe ${vehicleName} đã ${statusText}. Chuyển đến màn hình tương ứng để xem.`, 'info');
        }
    }

    /**
     * Hide vehicle from current screen
     */
    hideVehicleFromCurrentScreen(vehicleId, newStatus) {
        const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
        if (vehicleCard) {
            // Add fade-out animation
            vehicleCard.style.transition = 'all 0.5s ease-out';
            vehicleCard.style.opacity = '0';
            vehicleCard.style.transform = 'scale(0.8)';
            vehicleCard.style.marginBottom = '0';
            vehicleCard.style.padding = '0';
            vehicleCard.style.height = '0';
            vehicleCard.style.overflow = 'hidden';
            
            // Remove from DOM after animation
            setTimeout(() => {
                vehicleCard.remove();
                
                // Update the grid layout
                this.updateGridLayout();
            }, 500);
        }
    }

    /**
     * Update grid layout
     */
    updateGridLayout() {
        // Trigger a reflow to update the grid layout
        const vehicleList = document.getElementById('vehicle-list');
        if (vehicleList) {
            vehicleList.style.display = 'none';
            vehicleList.offsetHeight; // Force reflow
            vehicleList.style.display = 'grid';
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Vehicle Operations page loaded');
    
    // Create and initialize VehicleOperations instance
    const vehicleOperations = new VehicleOperations();
    vehicleOperations.init();
    
    // Make it available globally for debugging
    window.vehicleOperations = vehicleOperations;
});

// Export for ES6 modules
export default VehicleOperations;
