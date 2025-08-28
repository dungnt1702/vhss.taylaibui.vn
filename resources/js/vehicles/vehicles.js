/**
 * Vehicle Management System - JavaScript Module
 * Handles all vehicle-related functionality including countdown timers, status updates, and UI interactions
 */

// Global variables
let vehicleTimers = {};
let currentFilter = '';

// Vehicle Status Management
class VehicleManager {
    constructor() {
        this.initializeEventListeners();
        // Countdown timers are now handled by vehicleOperations module
    }

    initializeEventListeners() {
        // Per-page selector
        const perPageSelect = document.getElementById('per-page');
        if (perPageSelect) {
            perPageSelect.addEventListener('change', this.handlePerPageChange.bind(this));
        }

        // Status modal form
        const statusForm = document.getElementById('status-form');
        if (statusForm) {
            statusForm.addEventListener('submit', this.handleStatusUpdate.bind(this));
        }

        // Workshop modal close events
        const workshopModal = document.getElementById('workshop-modal');
        if (workshopModal) {
            workshopModal.addEventListener('click', this.closeWorkshopModal.bind(this));
        }

        // Escape key for modals
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                this.closeWorkshopModal();
            }
        });
    }

    handlePerPageChange(event) {
        const perPage = event.target.value;
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('per_page', perPage);
        currentUrl.searchParams.delete('page');
        
        // Show loading state
        event.target.disabled = true;
        event.target.style.opacity = '0.6';
        
        window.location.href = currentUrl.toString();
    }

    handleStatusUpdate(event) {
        event.preventDefault();
        
        const vehicleId = document.getElementById('vehicle-id').value;
        const formData = new FormData(event.target);
        
        fetch(`/vehicles/${vehicleId}/status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                status: formData.get('status'),
                notes: formData.get('notes')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.closeStatusModal();
                location.reload();
            } else {
                alert('Có lỗi xảy ra: ' + data.message);
            }
        })
        .catch(error => {
            alert('Có lỗi xảy ra khi cập nhật trạng thái xe');
        });
    }

    // Modal Management
    openStatusModal(vehicleId, currentStatus, currentNotes) {
        document.getElementById('vehicle-id').value = vehicleId;
        document.getElementById('status-select').value = currentStatus;
        document.getElementById('status-notes').value = currentNotes || '';
        document.getElementById('status-modal').classList.remove('hidden');
    }

    closeStatusModal() {
        document.getElementById('status-modal').classList.add('hidden');
    }

    openWorkshopModal(vehicleId) {
        document.getElementById('workshop-vehicle-id').value = vehicleId;
        document.getElementById('workshop-modal').classList.remove('hidden');
    }

    closeWorkshopModal() {
        document.getElementById('workshop-modal').classList.add('hidden');
    }

    // Vehicle Operations
    deleteVehicle(vehicleId) {
        if (confirm('Bạn có chắc chắn muốn xóa xe này?')) {
            fetch(`/vehicles/${vehicleId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra: ' + data.message);
                }
            })
            .catch(error => {
                alert('Có lỗi xảy ra khi xóa xe');
            });
        }
    }

    // Countdown Timer Management - This is now handled by vehicleOperations module
    initializeCountdownTimers() {
        // This function is kept for compatibility but actual initialization is done in vehicleOperations
    }

    startCountdownTimer(vehicleId, endTimeMs) {
        // Clear existing timer
        if (vehicleTimers[vehicleId]) {
            clearInterval(vehicleTimers[vehicleId]);
        }
        
        // Start new timer
        vehicleTimers[vehicleId] = setInterval(() => {
            const now = new Date().getTime();
            const timeLeft = endTimeMs - now;
            
            if (timeLeft > 0) {
                this.updateCountdownDisplay(vehicleId, timeLeft);
            } else {
                // Time expired
                clearInterval(vehicleTimers[vehicleId]);
                delete vehicleTimers[vehicleId];
                this.updateVehicleStatus(vehicleId, 'expired');
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
    }

    // Vehicle Status Updates
    updateVehicleStatus(vehicleId, status, endTime = null) {
        const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
        if (!vehicleCard) return;
        
        vehicleCard.dataset.status = status;
        
        if (endTime) {
            vehicleCard.dataset.endTime = endTime.toString();
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
                statusText = minutes !== null ? `Xe chạy ${minutes}p` : 'Ngoài bãi';
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
                    <button onclick="vehicleManager.pauseVehicle(${vehicleId})" class="btn btn-info btn-sm">
                        ⏸️ Tạm dừng
                    </button>
                    <button onclick="vehicleManager.returnToYard(${vehicleId})" class="btn btn-primary btn-sm">
                        🏠 Về bãi
                    </button>
                `;
                break;
            case 'paused':
                buttonHTML = `
                    <button onclick="vehicleManager.resumeVehicle(${vehicleId})" class="btn btn-success btn-sm">
                        ▶️ Tiếp tục
                    </button>
                    <button onclick="vehicleManager.returnToYard(${vehicleId})" class="btn btn-primary btn-sm">
                        🏠 Về bãi
                    </button>
                `;
                break;
            case 'expired':
                buttonHTML = `
                    <button onclick="vehicleManager.addTime(${vehicleId}, 10)" class="btn btn-warning btn-sm">
                        ⏰ +10p
                    </button>
                    <button onclick="vehicleManager.returnToYard(${vehicleId})" class="btn btn-primary btn-sm">
                        🏠 Về bãi
                    </button>
                `;
                break;
            case 'ready':
                buttonHTML = `
                    <button onclick="vehicleManager.startVehicle(${vehicleId}, 30)" class="btn btn-success btn-sm">
                        🚀 Xuất bãi
                    </button>
                    <button onclick="vehicleManager.openWorkshopModal(${vehicleId})" class="btn btn-secondary btn-sm">
                        🔧 Về xưởng
                    </button>
                `;
                break;
        }
        
        buttonContainer.innerHTML = buttonHTML;
    }

    // Vehicle Control Functions
    startVehicle(vehicleId, minutes = 30) {
        const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
        if (!vehicleCard) return;
        
        // Calculate end time
        const now = new Date().getTime();
        const endTimeMs = now + (minutes * 60 * 1000);
        
        // Update vehicle card
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
        this.updateVehicleStatus(vehicleId, 'running', endTimeMs);
        
        // Speak notification
        this.speakVietnamese(`Xe ${this.getVehicleName(vehicleId)} đã xuất bãi với ${minutes} phút`);
    }

    pauseVehicle(vehicleId) {
        // Stop the countdown timer
        if (vehicleTimers[vehicleId]) {
            clearInterval(vehicleTimers[vehicleId]);
            delete vehicleTimers[vehicleId];
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
            const now = new Date().getTime();
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
        
        this.updateVehicleStatus(vehicleId, 'paused');
        
        // Show navigation hint to user
        this.showNavigationHint(vehicleId, 'paused');
    }

    resumeVehicle(vehicleId) {
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
                    const now = new Date().getTime();
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
                    const now = new Date().getTime();
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
        
        this.updateVehicleStatus(vehicleId, 'running');
    }

    returnToYard(vehicleId) {
        // Stop any existing timer for this vehicle
        if (vehicleTimers[vehicleId]) {
            clearInterval(vehicleTimers[vehicleId]);
            delete vehicleTimers[vehicleId];
        }
        
        // Reset countdown display to 00:00
        const countdownElement = document.getElementById(`countdown-${vehicleId}`);
        if (countdownElement) {
            countdownElement.innerHTML = '<span class="countdown-minutes">00</span>:<span class="countdown-seconds">00</span>';
        }
        
        // Update the vehicle card's data attributes immediately
        const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
        if (vehicleCard) {
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
        
        // Update vehicle status to active and clear end_time
        this.updateVehicleStatus(vehicleId, 'ready', null);
        
        // Show navigation hint to user
        this.showNavigationHint(vehicleId, 'waiting');
    }

    addTime(vehicleId, additionalMinutes) {
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
            newEndTime = new Date().getTime() + (additionalMinutes * 60 * 1000);
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
        this.updateVehicleStatus(vehicleId, 'running', newEndTime);
        
        // Speak notification
        this.speakVietnamese(`Xe ${this.getVehicleName(vehicleId)} đã được thêm ${additionalMinutes} phút`);
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

    updateGridLayout() {
        // Trigger a reflow to update the grid layout
        const vehicleList = document.getElementById('vehicle-list');
        if (vehicleList) {
            vehicleList.style.display = 'none';
            vehicleList.offsetHeight; // Force reflow
            vehicleList.style.display = 'grid';
        }
    }

    // Toggle vehicle details using specific IDs
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
    
    // Helper function to close all other vehicles
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
    
    // Helper function to open a specific vehicle
    openVehicle(content, icon) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    }
    
    // Helper function to close a specific vehicle
    closeVehicle(content, icon) {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.vehicleManager = new VehicleManager();
    
    // Update the global toggleVehicle function to use the vehicleManager when available
    if (window.toggleVehicle) {
        const originalToggleVehicle = window.toggleVehicle;
        window.toggleVehicle = function(vehicleId) {
            if (window.vehicleManager && window.vehicleManager.toggleVehicle) {
                window.vehicleManager.toggleVehicle(vehicleId);
            } else {
                // Fallback to original implementation
                originalToggleVehicle(vehicleId);
            }
        };
    }
});

// Export for global access
window.VehicleManager = VehicleManager;
