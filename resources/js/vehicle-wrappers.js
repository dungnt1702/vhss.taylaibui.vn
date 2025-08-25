/**
 * Vehicle Wrappers - JavaScript Module
 * Contains all wrapper functions that call the main modules
 * This file serves as a bridge between HTML onclick events and the main modules
 */

// Immediately export critical functions to global scope to prevent "not defined" errors
window.toggleVehicle = function(vehicleId) {
    // Fallback implementation if vehicleManager is not available yet
    const content = document.getElementById(`content-${vehicleId}`);
    const icon = document.getElementById(`icon-${vehicleId}`);
    
    if (content && icon) {
        const isHidden = content.classList.contains('hidden');
        
        if (isHidden) {
            content.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            content.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }
};

window.startVehicle = function(vehicleId) {
    // Fallback implementation
};

window.pauseVehicle = function(vehicleId) {
    // Fallback implementation
};

window.resumeVehicle = function(vehicleId) {
    // Fallback implementation
};

// Status Modal Functions
function openStatusModal(vehicleId, currentStatus, currentNotes) {
    if (window.vehicleForms) {
        window.vehicleForms.openStatusModal(vehicleId, currentStatus, currentNotes);
    }
}

function closeStatusModal() {
    if (window.vehicleForms) {
        window.vehicleForms.closeStatusModal();
    }
}

// Vehicle Modal Functions
function openVehicleModal(vehicleId = null) {
    if (window.vehicleForms) {
        window.vehicleForms.openVehicleModal(vehicleId);
    }
}

function closeVehicleModal() {
    if (window.vehicleForms) {
        window.vehicleForms.closeVehicleModal();
    }
}

// Vehicle Data Functions
function loadVehicleData(vehicleId) {
    if (window.vehicleForms) {
        window.vehicleForms.loadVehicleData(vehicleId);
    }
}

// Vehicle Control Functions
// Toggle vehicle details
function toggleVehicle(vehicleId) {
    if (window.vehicleManager && window.vehicleManager.toggleVehicle) {
        window.vehicleManager.toggleVehicle(vehicleId);
    } else {
        // Use the global fallback implementation
        window.toggleVehicle(vehicleId);
    }
}

// Vehicle Operation Functions
function startVehicle(vehicleId, minutes = 30) {
    if (window.vehicleOperations) {
        window.vehicleOperations.startVehicle(vehicleId, minutes);
    }
}

function pauseVehicle(vehicleId) {
    if (window.vehicleOperations) {
        window.vehicleOperations.pauseVehicle(vehicleId);
    }
}

function resumeVehicle(vehicleId) {
    if (window.vehicleOperations) {
        window.vehicleOperations.resumeVehicle(vehicleId);
    }
}

function returnToYard(vehicleId) {
    if (window.vehicleOperations) {
        window.vehicleOperations.returnToYard(vehicleId);
    }
}

function addTime(vehicleId, additionalMinutes) {
    if (window.vehicleOperations) {
        window.vehicleOperations.addTime(vehicleId, additionalMinutes);
    }
}

// Workshop Modal Functions
function showWorkshopModal(vehicleId) {
    if (window.vehicleForms) {
        window.vehicleForms.openWorkshopModal(vehicleId);
    }
}

function closeWorkshopModal() {
    if (window.vehicleForms) {
        window.vehicleForms.closeWorkshopModal();
    }
}

// Utility Functions
function speakVietnamese(text) {
    if (window.vehicleOperations) {
        window.vehicleOperations.speakVietnamese(text);
    }
}

function getVehicleName(vehicleId) {
    if (window.vehicleOperations) {
        return window.vehicleOperations.getVehicleName(vehicleId);
    }
    return vehicleId;
}

// Timer Functions
function startTimer(vehicleId, minutes) {
    if (window.vehicleOperations) {
        window.vehicleOperations.startVehicle(vehicleId, minutes);
    }
}

// Vehicle Status Functions
function updateVehicleStatus(vehicleId, status, endTime = null) {
    if (window.vehicleOperations) {
        window.vehicleOperations.updateVehicleStatus(vehicleId, status, endTime);
    }
}

// Countdown Timer Functions
function startCountdownTimer(vehicleId, endTime) {
    if (window.vehicleOperations) {
        window.vehicleOperations.startCountdownTimer(vehicleId, endTime);
    }
}

function updateCountdownDisplay(vehicleId, timeLeft) {
    if (window.vehicleOperations) {
        window.vehicleOperations.updateCountdownDisplay(vehicleId, timeLeft);
    }
}

// Grid Layout Functions
function updateGridLayout() {
    if (window.vehicleOperations) {
        window.vehicleOperations.updateGridLayout();
    }
}

function refreshAllCountdowns() {
    // Function moved to vehicle-operations.js module
}

// Vehicle Button Functions
function updateVehicleButtons(vehicleId, status) {
    if (window.vehicleOperations) {
        window.vehicleOperations.updateVehicleButtons(vehicleId, status);
    }
}

// Status Text Functions
function updateStatusText(vehicleId, status, minutes = null) {
    if (window.vehicleOperations) {
        window.vehicleOperations.updateStatusText(vehicleId, status, minutes);
    }
}

// Navigation Functions
function showNavigationHint(vehicleId, newStatus) {
    if (window.vehicleOperations) {
        window.vehicleOperations.showNavigationHint(vehicleId, newStatus);
    }
}

// Initialize Functions
function initializeCountdownTimers() {
    if (window.vehicleOperations) {
        window.vehicleOperations.initializeCountdownTimers();
    }
}

// Delete Functions
function deleteVehicle(vehicleId) {
    if (window.vehicleManager) {
        window.vehicleManager.deleteVehicle(vehicleId);
    }
}

// Export all functions to global scope immediately
window.openStatusModal = openStatusModal;
window.closeStatusModal = closeStatusModal;
window.openVehicleModal = openVehicleModal;
window.closeVehicleModal = closeVehicleModal;
window.loadVehicleData = loadVehicleData;
window.toggleVehicle = toggleVehicle;
window.startVehicle = startVehicle;
window.pauseVehicle = pauseVehicle;
window.resumeVehicle = resumeVehicle;
window.returnToYard = returnToYard;
window.addTime = addTime;
window.showWorkshopModal = showWorkshopModal;
window.closeWorkshopModal = closeWorkshopModal;
window.speakVietnamese = speakVietnamese;
window.getVehicleName = getVehicleName;
window.startTimer = startTimer;
window.updateVehicleStatus = updateVehicleStatus;
window.startCountdownTimer = startCountdownTimer;
window.updateCountdownDisplay = updateCountdownDisplay;
window.updateGridLayout = updateGridLayout;
window.refreshAllCountdowns = refreshAllCountdowns;
window.updateVehicleButtons = updateVehicleButtons;
window.updateStatusText = updateStatusText;
window.showNavigationHint = showNavigationHint;
window.initializeCountdownTimers = initializeCountdownTimers;
window.deleteVehicle = deleteVehicle;

// Also export when DOM is ready to ensure availability
document.addEventListener('DOMContentLoaded', function() {
    // Re-export all functions to ensure they're available
    window.openStatusModal = openStatusModal;
    window.closeStatusModal = closeStatusModal;
    window.openVehicleModal = openVehicleModal;
    window.closeVehicleModal = closeVehicleModal;
    window.loadVehicleData = loadVehicleData;
    window.toggleVehicle = toggleVehicle;
    window.startVehicle = startVehicle;
    window.pauseVehicle = pauseVehicle;
    window.resumeVehicle = resumeVehicle;
    window.returnToYard = returnToYard;
    window.addTime = addTime;
    window.showWorkshopModal = showWorkshopModal;
    window.closeWorkshopModal = closeWorkshopModal;
    window.speakVietnamese = speakVietnamese;
    window.getVehicleName = getVehicleName;
    window.startTimer = startTimer;
    window.updateVehicleStatus = updateVehicleStatus;
    window.startCountdownTimer = startCountdownTimer;
    window.updateCountdownDisplay = updateCountdownDisplay;
    window.updateGridLayout = updateGridLayout;
    window.refreshAllCountdowns = refreshAllCountdowns;
    window.updateVehicleButtons = updateVehicleButtons;
    window.updateStatusText = updateStatusText;
    window.showNavigationHint = showNavigationHint;
    window.initializeCountdownTimers = initializeCountdownTimers;
    window.deleteVehicle = deleteVehicle;
});

// Also try to export immediately if DOM is already loaded
if (document.readyState !== 'loading') {
    // Re-export all functions immediately
    window.openStatusModal = openStatusModal;
    window.closeStatusModal = closeStatusModal;
    window.openVehicleModal = openVehicleModal;
    window.closeVehicleModal = closeVehicleModal;
    window.loadVehicleData = loadVehicleData;
    window.toggleVehicle = toggleVehicle;
    window.startVehicle = startVehicle;
    window.pauseVehicle = pauseVehicle;
    window.resumeVehicle = resumeVehicle;
    window.returnToYard = returnToYard;
    window.addTime = addTime;
    window.showWorkshopModal = showWorkshopModal;
    window.closeWorkshopModal = closeWorkshopModal;
    window.speakVietnamese = speakVietnamese;
    window.getVehicleName = getVehicleName;
    window.startTimer = startTimer;
    window.updateVehicleStatus = updateVehicleStatus;
    window.startCountdownTimer = startCountdownTimer;
    window.updateCountdownDisplay = updateCountdownDisplay;
    window.updateGridLayout = updateGridLayout;
    window.refreshAllCountdowns = refreshAllCountdowns;
    window.updateVehicleButtons = updateVehicleButtons;
    window.updateStatusText = updateStatusText;
    window.showNavigationHint = showNavigationHint;
    window.initializeCountdownTimers = initializeCountdownTimers;
    window.deleteVehicle = deleteVehicle;
}