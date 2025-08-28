/**
 * Vehicle Wrappers - JavaScript Module
 * Contains all wrapper functions that call the main modules
 * This file serves as a bridge between HTML onclick events and the main modules
 */

// Simple function to toggle only the specific vehicle clicked
function toggleVehicleById(vehicleId) {
    const contentId = `content-${vehicleId}`;
    const iconId = `icon-${vehicleId}`;
    
    const content = document.getElementById(contentId);
    const icon = document.getElementById(iconId);
    
    if (!content || !icon) {
        console.warn(`Vehicle elements not found: contentId=${contentId}, iconId=${iconId}`);
        return;
    }
    
    console.log(`Toggling vehicle ${vehicleId}: contentId=${contentId}, iconId=${iconId}`);
    
    const isHidden = content.classList.contains('hidden');
    
    if (isHidden) {
        console.log(`Opening vehicle ${vehicleId}`);
        // Close all other vehicles first
        closeAllOtherVehiclesSimple(contentId);
        
        // Open the clicked vehicle
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        console.log(`Closing vehicle ${vehicleId}`);
        // Close the clicked vehicle
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

// Function to close all other vehicles - only affects individual vehicle cards
function closeAllOtherVehiclesSimple(currentContentId) {
    console.log(`Closing all other vehicles except: ${currentContentId}`);
    
    // Get all vehicle content elements
    const allContents = document.querySelectorAll('.vehicle-content');
    console.log(`Found ${allContents.length} vehicle contents`);
    
    allContents.forEach((contentEl) => {
        const contentId = contentEl.id;
        
        if (contentId !== currentContentId) {
            console.log(`Closing: ${contentId}`);
            // Close this specific content
            contentEl.classList.add('hidden');
            
            // Get the vehicle ID from content ID and reset its icon
            const vehicleId = contentId.replace('content-', '');
            const icon = document.getElementById(`icon-${vehicleId}`);
            
            if (icon) {
                icon.style.transform = 'rotate(0deg)';
                console.log(`Reset icon for vehicle: ${vehicleId}`);
            }
        } else {
            console.log(`Keeping open: ${contentId}`);
        }
    });
}

// Legacy functions - keeping for compatibility but not using
// These can be removed later if not needed elsewhere

// Test function to debug the issue - SIMPLE VERSION
window.testToggleVehicle = function(vehicleId) {
    console.log(`=== TESTING SIMPLE TOGGLE VEHICLE ${vehicleId} ===`);
    
    const contentId = `content-${vehicleId}`;
    const iconId = `icon-${vehicleId}`;
    
    console.log(`Looking for elements:`);
    console.log(`- Content ID: ${contentId}`);
    console.log(`- Icon ID: ${iconId}`);
    
    const content = document.getElementById(contentId);
    const icon = document.getElementById(iconId);
    
    console.log(`Found elements:`);
    console.log(`- Content:`, content);
    console.log(`- Icon:`, icon);
    
    if (content && icon) {
        const isHidden = content.classList.contains('hidden');
        console.log(`Content is hidden: ${isHidden}`);
        
        if (isHidden) {
            console.log(`Opening vehicle ${vehicleId}`);
            content.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            console.log(`Closing vehicle ${vehicleId}`);
            content.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    } else {
        console.error(`Elements not found for vehicle ${vehicleId}`);
    }
    
    console.log(`=== END TEST ===`);
};

// Test function to toggle only one vehicle without affecting others
window.testToggleVehicleOnly = function(vehicleId) {
    console.log(`=== TESTING TOGGLE ONLY VEHICLE ${vehicleId} ===`);
    
    const contentId = `content-${vehicleId}`;
    const iconId = `icon-${vehicleId}`;
    
    const content = document.getElementById(contentId);
    const icon = document.getElementById(iconId);
    
    if (content && icon) {
        const isHidden = content.classList.contains('hidden');
        
        if (isHidden) {
            console.log(`Opening ONLY vehicle ${vehicleId}`);
            content.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            console.log(`Closing ONLY vehicle ${vehicleId}`);
            content.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }
    
            console.log(`=== END TEST ONLY ===`);
};

// Test function to check all vehicle cards and their IDs
window.checkAllVehicleCards = function() {
    console.log(`=== CHECKING ALL VEHICLE CARDS ===`);
    
    const allCards = document.querySelectorAll('.vehicle-card');
    console.log(`Found ${allCards.length} vehicle cards`);
    
    allCards.forEach((card, index) => {
        const vehicleId = card.dataset.vehicleId;
        const contentId = `content-${vehicleId}`;
        const iconId = `icon-${vehicleId}`;
        
        const content = document.getElementById(contentId);
        const icon = document.getElementById(iconId);
        
        console.log(`Card ${index + 1}:`);
        console.log(`  - Vehicle ID: ${vehicleId}`);
        console.log(`  - Content ID: ${contentId}`);
        console.log(`  - Icon ID: ${iconId}`);
        console.log(`  - Content found: ${!!content}`);
        console.log(`  - Icon found: ${!!icon}`);
        console.log(`  - Content hidden: ${content ? content.classList.contains('hidden') : 'N/A'}`);
        console.log(`  - Icon transform: ${icon ? icon.style.transform : 'N/A'}`);
        console.log(`  ---`);
    });
    
    console.log(`=== END CHECK ===`);
};

// Test function to check if there are duplicate IDs
window.checkForDuplicateIds = function() {
    console.log(`=== CHECKING FOR DUPLICATE IDs ===`);
    
    const allContents = document.querySelectorAll('.vehicle-content');
    const allIcons = document.querySelectorAll('.vehicle-header svg');
    
    const contentIds = Array.from(allContents).map(el => el.id);
    const iconIds = Array.from(allIcons).map(el => el.id);
    
    console.log(`Content IDs:`, contentIds);
    console.log(`Icon IDs:`, iconIds);
    
    // Check for duplicates
    const contentDuplicates = contentIds.filter((id, index) => contentIds.indexOf(id) !== index);
    const iconDuplicates = iconIds.filter((id, index) => iconIds.indexOf(id) !== index);
    
    if (contentDuplicates.length > 0) {
        console.warn(`Duplicate content IDs found:`, contentDuplicates);
    } else {
        console.log(`No duplicate content IDs found`);
    }
    
    if (iconDuplicates.length > 0) {
        console.warn(`Duplicate icon IDs found:`, iconDuplicates);
    } else {
        console.log(`No duplicate icon IDs found`);
    }
    
    console.log(`=== END DUPLICATE CHECK ===`);
};

// Toggle function that only affects the specific vehicle card clicked
window.toggleVehicle = function(vehicleId) {
    console.log(`Global toggleVehicle called with vehicleId: ${vehicleId}`);
    
    // Find the specific vehicle card and its elements
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
        console.log(`Opening vehicle ${vehicleId}`);
        // Close all other vehicles first
        closeAllOtherVehiclesSimple(contentId);
        
        // Open this specific vehicle
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        console.log(`Closing vehicle ${vehicleId}`);
        // Close this specific vehicle
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
};



window.startVehicle = function(vehicleId, minutes = 30) {
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
    if (window.vehicleOperations && window.vehicleOperations.startVehicle) {
        window.vehicleOperations.startVehicle(vehicleId, minutes);
    } else if (window.startVehicle) {
        // Fallback to global startVehicle function
        window.startVehicle(vehicleId, minutes);
    }
}

// Vehicle Status Functions
function updateVehicleStatus(vehicleId, status, endTime = null) {
    if (window.vehicleOperations) {
        window.vehicleOperations.updateVehicleStatus(vehicleId, status, endTime);
    }
}

// Send vehicle status to server via API
function sendVehicleStatusToServer(vehicleId, status, endTime = null, startTime = null) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }

    const data = {
        status: status,
        end_time: endTime,
        start_time: startTime
    };

    fetch(`/vehicles/${vehicleId}/status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log(`Vehicle ${vehicleId} status updated to ${status}`);
        } else {
            console.error('Failed to update vehicle status:', data.message);
        }
    })
    .catch(error => {
        console.error('Error updating vehicle status:', error);
    });
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
window.sendVehicleStatusToServer = sendVehicleStatusToServer;
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
window.sendVehicleStatusToServer = sendVehicleStatusToServer;
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