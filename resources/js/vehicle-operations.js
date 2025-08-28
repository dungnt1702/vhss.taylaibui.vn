/**
 * Vehicle Operations - JavaScript Module
 * Handles vehicle control operations like start, pause, resume, etc.
 */

class VehicleOperations {
    constructor() {
        this.vehicleTimers = {};
        this.currentFilter = '';
    }

    // Helper function to get current time in milliseconds (local timezone)
    getCurrentTimeMs() {
        return new Date().getTime();
    }

    // Vehicle Control Functions
    startVehicle(vehicleId, minutes = 30) {
        const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
        if (!vehicleCard) return;
        
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
        
        // Send to server with start_time and end_time
        if (window.sendVehicleStatusToServer) {
            window.sendVehicleStatusToServer(vehicleId, 'running', endTimeMs, startTimeMs);
        }
        this.updateVehicleStatus(vehicleId, 'running', endTimeMs, startTimeMs);
        
        // Hide vehicle from current screen (it's now running)
        this.hideVehicleFromCurrentScreen(vehicleId, 'running');
        
        // Speak notification
        this.speakVietnamese(`Xe ${this.getVehicleName(vehicleId)} đã xuất bãi với ${minutes} phút`);
    }

    pauseVehicle(vehicleId) {
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
        
        if (window.sendVehicleStatusToServer) {
            window.sendVehicleStatusToServer(vehicleId, 'paused');
        }
        this.updateVehicleStatus(vehicleId, 'paused');
        
        // Hide vehicle from current screen (it's now paused)
        this.hideVehicleFromCurrentScreen(vehicleId, 'paused');
        
        // Show navigation hint to user
        this.showNavigationHint(vehicleId, 'paused');
    }

    resumeVehicle(vehicleId) {
        // Get the current end time from the vehicle card
        const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
        if (!vehicleCard) return;
        
        // Get the paused remaining time
        let timeLeft = 0;
        let endTimeMs = 0;
        let startTimeMs = 0;
        
        // Check if we have paused remaining time
        if (vehicleCard.dataset.pausedRemainingSeconds) {
            const pausedSeconds = parseInt(vehicleCard.dataset.pausedRemainingSeconds);
            if (pausedSeconds > 0) {
                // Calculate new end time based on paused remaining time
                const now = this.getCurrentTimeMs();
                endTimeMs = now + (pausedSeconds * 1000);
                timeLeft = pausedSeconds * 1000;
                
                // Calculate start time to maintain the original duration
                const originalEndTime = parseInt(vehicleCard.dataset.endTime) || 0;
                const originalStartTime = parseInt(vehicleCard.dataset.startTime) || 0;
                if (originalStartTime > 0 && originalEndTime > 0) {
                    // Use original start time to maintain duration consistency
                    startTimeMs = originalStartTime;
                    // Recalculate end time based on original start time + remaining time
                    const totalDuration = originalEndTime - originalStartTime;
                    const remainingDuration = pausedSeconds * 1000;
                    endTimeMs = startTimeMs + remainingDuration;
                    timeLeft = remainingDuration;
                } else {
                    // Fallback: use current time as start
                    startTimeMs = now;
                    endTimeMs = now + (pausedSeconds * 1000);
                    timeLeft = pausedSeconds * 1000;
                }
                
                // Update the end time in data attribute
                vehicleCard.dataset.endTime = endTimeMs.toString();
                vehicleCard.dataset.startTime = startTimeMs.toString();
                
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
            // No paused data, can't resume
                                vehicleCard.dataset.status = 'ready';
            this.updateStatusText(vehicleId, 'ready', null);
            this.updateVehicleButtons(vehicleId, 'ready');
            return; // Exit early
        }
        
        // At this point, timeLeft should be > 0 and valid
        if (timeLeft > 0) {
            // Update vehicle status to running
            vehicleCard.dataset.status = 'running';
            
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
            
            // Restart countdown timer with the exact remaining time
            this.startCountdownTimer(vehicleId, endTimeMs);
            
            // Update status text to show remaining time
            this.updateStatusText(vehicleId, 'running', Math.floor(timeLeft / (1000 * 60)));
            
            // Update button display to show running buttons
            this.updateVehicleButtons(vehicleId, 'running');
            
            // Speak notification
            const vehicleName = this.getVehicleName(vehicleId);
            this.speakVietnamese(`Xe ${vehicleName} đã được tiếp tục`);
            
            // Send to server
            if (window.sendVehicleStatusToServer) {
                window.sendVehicleStatusToServer(vehicleId, 'running', endTimeMs, startTimeMs);
            }
            this.updateVehicleStatus(vehicleId, 'running', endTimeMs, startTimeMs);
            
            // Hide vehicle from current screen (it's now running)
            this.hideVehicleFromCurrentScreen(vehicleId, 'running');
        } else {
            // This should not happen with the new logic above, but just in case
            vehicleCard.dataset.status = 'expired';
            this.updateStatusText(vehicleId, 'expired', null);
            this.updateVehicleButtons(vehicleId, 'expired');
        }
    }

    returnToYard(vehicleId) {
        // Stop any existing timer for this vehicle
        if (this.vehicleTimers[vehicleId]) {
            clearInterval(this.vehicleTimers[vehicleId]);
            delete this.vehicleTimers[vehicleId];
        }
        
        // Reset countdown display to 00:00
        const countdownElement = document.getElementById(`countdown-${vehicleId}`);
        if (countdownElement) {
            countdownElement.innerHTML = '<span class="countdown-minutes">00</span>:<span class="countdown-seconds">00</span>';
        }
        
        // Update the vehicle card's data attributes immediately
        const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
        if (vehicleCard) {
            vehicleCard.dataset.startTime = '';
            vehicleCard.dataset.endTime = '';
            vehicleCard.dataset.status = 'ready';
        }
        
        // Update status text to show active status
        this.updateStatusText(vehicleId, 'ready', null);
        
        // Update button display to show active buttons
        this.updateVehicleButtons(vehicleId, 'ready');
        
        // Speak notification
        const vehicleName = this.getVehicleName(vehicleId);
        this.speakVietnamese(`Xe ${vehicleName} đã về bãi`);
        
        // Remove the vehicle card from the current screen with animation
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
        
        // Update vehicle status to active and clear all timing data
        if (window.sendVehicleStatusToServer) {
            window.sendVehicleStatusToServer(vehicleId, 'ready', null, null);
        }
        this.updateVehicleStatus(vehicleId, 'ready', null, null);
        
        // Show navigation hint to user
        this.showNavigationHint(vehicleId, 'ready');
    }

    addTime(vehicleId, additionalMinutes) {
        const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
        if (!vehicleCard) return;
        
        const currentStatus = vehicleCard.dataset.status;
        let newEndTime;
        let startTimeMs;
        
        // Check if vehicle is expired or has no valid end time
        if (currentStatus === 'expired' || !vehicleCard.dataset.endTime || vehicleCard.dataset.endTime === '') {
            // Start new round with additionalMinutes
            startTimeMs = this.getCurrentTimeMs();
            newEndTime = startTimeMs + (additionalMinutes * 60 * 1000);
            
            // Update start time
            vehicleCard.dataset.startTime = startTimeMs.toString();
        } else {
            // Add to existing end time (vehicle is still running)
            const currentEndTime = parseInt(vehicleCard.dataset.endTime);
            newEndTime = currentEndTime + (additionalMinutes * 60 * 1000);
            startTimeMs = parseInt(vehicleCard.dataset.startTime) || this.getCurrentTimeMs();
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
        if (window.sendVehicleStatusToServer) {
            window.sendVehicleStatusToServer(vehicleId, 'running', newEndTime, startTimeMs);
        }
        this.updateVehicleStatus(vehicleId, 'running', newEndTime, startTimeMs);
        
        // Hide vehicle from current screen (it's now running)
        this.hideVehicleFromCurrentScreen(vehicleId, 'running');
        
        // Speak notification
        this.speakVietnamese(`Xe ${this.getVehicleName(vehicleId)} đã được thêm ${additionalMinutes} phút`);
    }

    // Countdown Timer Management
    startCountdownTimer(vehicleId, endTimeMs) {
        // Clear existing timer
        if (this.vehicleTimers[vehicleId]) {
            clearInterval(this.vehicleTimers[vehicleId]);
        }
        
        // Start new timer
        this.vehicleTimers[vehicleId] = setInterval(() => {
            const now = this.getCurrentTimeMs();
            const timeLeft = endTimeMs - now;
            
            if (timeLeft > 0) {
                this.updateCountdownDisplay(vehicleId, timeLeft);
            } else {
                // Time expired
                clearInterval(this.vehicleTimers[vehicleId]);
                delete this.vehicleTimers[vehicleId];
                if (window.sendVehicleStatusToServer) {
                    window.sendVehicleStatusToServer(vehicleId, 'expired');
                }
                this.updateVehicleStatus(vehicleId, 'expired');
                
                // Hide vehicle from current screen (it's now expired)
                this.hideVehicleFromCurrentScreen(vehicleId, 'expired');
            }
        }, 1000);
    }

    updateCountdownDisplay(vehicleId, timeLeft) {
        const countdownElement = document.getElementById(`countdown-${vehicleId}`);
        if (!countdownElement) return;
        
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
        
        const minutesDisplay = minutes.toString().padStart(2, '0');
        const secondsDisplay = seconds.toString().padStart(2, '0');
        
        countdownElement.innerHTML = `<span class="countdown-minutes">${minutesDisplay}</span>:<span class="countdown-seconds">${secondsDisplay}</span>`;
        
        // Also update the status text if it exists
        const statusTextElement = document.getElementById(`status-text-${vehicleId}`);
        if (statusTextElement) {
            statusTextElement.textContent = `Xe chạy ${minutes}p`;
        }
    }

    // Vehicle Status Updates
    updateVehicleStatus(vehicleId, status, endTime = null, startTime = null) {
        const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
        if (!vehicleCard) return;
        
        vehicleCard.dataset.status = status;
        
        if (endTime) {
            vehicleCard.dataset.endTime = endTime.toString();
        }

        if (startTime) {
            vehicleCard.dataset.startTime = startTime.toString();
        }
        
        // Update UI based on status
        this.updateStatusText(vehicleId, status);
        this.updateVehicleButtons(vehicleId, status);
        
        // Update countdown display
        if (status === 'expired') {
            const countdownElement = document.getElementById(`countdown-${vehicleId}`);
            if (countdownElement) {
                countdownElement.innerHTML = '<span class="text-red-600 font-bold">HẾT GIỜ</span>';
            }
        }
    }

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
            case 'waiting':
                statusText = 'Đang chờ';
                break;
            case 'expired':
                statusText = 'Hết giờ';
                break;
            case 'ready':
                statusText = 'Sẵn sàng chạy';
                break;
            default:
                statusText = 'Không xác định';
        }
        
        statusTextElement.textContent = statusText;
    }

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
                        🚗 30p
                    </button>
                    <button onclick="vehicleOperations.startVehicle(${vehicleId}, 45)" class="btn btn-primary btn-sm">
                        🚙 45p
                    </button>
                    <button onclick="vehicleForms.openWorkshopModal(${vehicleId})" class="btn btn-secondary btn-sm">
                        🔧 Về xưởng
                    </button>
                `;
                break;
            case 'ready':
                buttonHTML = `
                    <button onclick="vehicleOperations.startVehicle(${vehicleId}, 30)" class="btn btn-success btn-sm">
                        🚗 30p
                    </button>
                    <button onclick="vehicleOperations.startVehicle(${vehicleId}, 45)" class="btn btn-primary btn-sm">
                        🚙 45p
                    </button>
                    <button onclick="vehicleForms.openWorkshopModal(${vehicleId})" class="btn btn-secondary btn-sm">
                        🔧 Về xưởng
                    </button>
                `;
                break;
        }
        
        buttonContainer.innerHTML = buttonHTML;
    }

    // Utility Functions
    getVehicleName(vehicleId) {
        const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
        return vehicleCard ? vehicleCard.dataset.vehicleName : `Xe ${vehicleId}`;
    }

    speakVietnamese(text) {
        if ('speechSynthesis' in window) {
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'vi-VN';
            utterance.rate = 0.8;
            speechSynthesis.speak(utterance);
        }
    }

    showNavigationHint(vehicleId, newStatus) {
        const currentFilter = new URLSearchParams(window.location.search).get('filter');
        let targetFilter = null;
        
        // Determine target filter based on new status
        switch (newStatus) {
            case 'running':
                targetFilter = 'running';
                break;
            case 'paused':
                targetFilter = 'paused';
                break;
            case 'expired':
                targetFilter = 'expired';
                break;
            case 'waiting':
                targetFilter = 'ready'; // active screen shows waiting vehicles
                break;
        }
        
        // Show hint if we're not on the target screen
        if (targetFilter && currentFilter !== targetFilter) {
            // Optional: Show a small notification to user
            const vehicleName = this.getVehicleName(vehicleId);
            const statusText = newStatus === 'running' ? 'đang chạy' : 
                             newStatus === 'paused' ? 'tạm dừng' : 
                             newStatus === 'expired' ? 'hết giờ' : 'đang chờ';
        }
    }

    // Hide vehicle from current screen when status changes
    hideVehicleFromCurrentScreen(vehicleId, newStatus) {
        const currentFilter = new URLSearchParams(window.location.search).get('filter');
        
        // Don't hide from these screens (they show all vehicles)
        if (currentFilter === 'ready' || currentFilter === 'route') {
            return;
        }
        
        // Hide vehicle based on current filter and new status
        const shouldHide = this.shouldHideVehicle(currentFilter, newStatus);
        
        if (shouldHide) {
            const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
            if (vehicleCard) {
                // Add fade-out animation
                vehicleCard.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                vehicleCard.style.opacity = '0';
                vehicleCard.style.transform = 'scale(0.95)';
                
                // Remove from DOM after animation
                setTimeout(() => {
                    vehicleCard.remove();
                    // Update grid layout
                    this.updateGridLayout();
                }, 500);
            }
        }
    }

    // Determine if vehicle should be hidden from current screen
    shouldHideVehicle(currentFilter, newStatus) {
        switch (currentFilter) {
            case 'ready': // Xe sẵn sàng chạy
                // Hide when status changes from active
                return newStatus !== 'ready';
                
                          case 'ready': // Xe đang chờ (active status)
                        // Hide when status changes from active
        return newStatus !== 'ready';
                
            case 'running': // Xe đang chạy
                // Hide when status changes from running
                return newStatus !== 'running';
                
            case 'paused': // Xe tạm dừng
                // Hide when status changes from paused
                return newStatus !== 'paused';
                
            case 'expired': // Xe hết giờ
                // Hide when status changes from expired
                return newStatus !== 'expired';
                
            default:
                return false;
        }
    }

    updateGridLayout() {
        // Trigger a reflow to update the grid layout
        const vehicleList = document.getElementById('vehicle-list');
        if (vehicleList) {
            vehicleList.style.display = 'none';
            vehicleList.offsetHeight; // Force reflow
            vehicleList.style.display = 'grid';
        }
    }

    // Initialize countdown timers for vehicles with end_time
    initializeCountdownTimers() {
        // Find all vehicle cards
        const vehicleCards = document.querySelectorAll('[data-vehicle-id]');
        
        vehicleCards.forEach(card => {
            const vehicleId = card.dataset.vehicleId;
            const endTime = card.dataset.endTime;
            const status = card.dataset.status;
            
            // Only process vehicles with end_time and running status
            if (endTime && endTime !== '' && status === 'running') {
                const endTimeMs = parseInt(endTime);
                const now = this.getCurrentTimeMs();
                const timeLeft = endTimeMs - now;
                
                if (timeLeft > 0) {
                    // Start countdown timer
                    this.startCountdownTimer(vehicleId, endTimeMs);
                    
                    // Update countdown display immediately
                    this.updateCountdownDisplay(vehicleId, timeLeft);
                } else {
                    // Time has expired, update status
                    if (window.sendVehicleStatusToServer) {
                        window.sendVehicleStatusToServer(vehicleId, 'expired');
                    }
                    this.updateVehicleStatus(vehicleId, 'expired');
                    
                    // Hide vehicle from current screen (it's now expired)
                    this.hideVehicleFromCurrentScreen(vehicleId, 'expired');
                }
            }
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.vehicleOperations = new VehicleOperations();
    
    // Note: initializeCountdownTimers will be called from app.js after all modules are ready
});

// Export for global access
window.VehicleOperations = VehicleOperations;
