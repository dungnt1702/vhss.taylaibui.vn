// Paused Vehicles JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Paused Vehicles JS loaded');
    
    // Initialize paused vehicles functionality
    initializePausedVehicles();
});

function initializePausedVehicles() {
    // Check if there are any vehicles before setting up listeners
    const vehicleCards = document.querySelectorAll('.vehicle-card');
    
    if (vehicleCards.length === 0) {
        console.log('No vehicles found, skipping initialization');
        return;
    }
    
    // Set up event listeners for vehicle cards
    setupVehicleCardListeners();
    
    // Initialize countdown timers if any
    initializeCountdownTimers();
}

function setupVehicleCardListeners() {
    // Add click listeners for vehicle headers
    const vehicleHeaders = document.querySelectorAll('.vehicle-header');
    
    if (vehicleHeaders.length === 0) {
        console.log('No vehicle headers found');
        return;
    }
    
    vehicleHeaders.forEach(header => {
        if (header) {
            header.addEventListener('click', function() {
                const vehicleCard = this.closest('.vehicle-card');
                if (vehicleCard) {
                    const vehicleId = vehicleCard.dataset.vehicleId;
                    if (vehicleId) {
                        toggleVehicleSimple(vehicleId);
                    }
                }
            });
        }
    });
}

function toggleVehicleSimple(vehicleId) {
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
}

function resumeTimer(vehicleId) {
    console.log(`Resuming timer for vehicle ${vehicleId}`);
    
    // Show loading state
    const button = event.target;
    if (!button) return;
    
    const originalText = button.innerHTML;
    button.innerHTML = '<span class="loading"></span>';
    button.disabled = true;
    
    // Make API call to resume timer
    fetch('/api/vehicles/resume-timer', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            vehicle_id: vehicleId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Thành công', 'Đã tiếp tục đếm ngược cho xe', 'success');
            // Optionally redirect or refresh
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification('Lỗi', data.message || 'Không thể tiếp tục đếm ngược', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Lỗi', 'Đã xảy ra lỗi khi tiếp tục đếm ngược', 'error');
    })
    .finally(() => {
        // Restore button state
        if (button) {
            button.innerHTML = originalText;
            button.disabled = false;
        }
    });
}

function initializeCountdownTimers() {
    // Initialize any existing countdown timers
    const countdownElements = document.querySelectorAll('.countdown-display');
    
    if (countdownElements.length === 0) {
        console.log('No countdown elements found');
        return;
    }
    
    countdownElements.forEach(element => {
        if (element) {
            const vehicleCard = element.closest('.vehicle-card');
            if (vehicleCard) {
                const vehicleId = vehicleCard.dataset.vehicleId;
                const endTime = vehicleCard.dataset.endTime;
                
                if (endTime) {
                    startCountdown(vehicleId, parseInt(endTime));
                }
            }
        }
    });
}

function startCountdown(vehicleId, endTime) {
    const countdownElement = document.getElementById(`countdown-${vehicleId}`);
    if (!countdownElement) return;
    
    const timer = setInterval(() => {
        const now = Date.now();
        const timeLeft = endTime - now;
        
        if (timeLeft <= 0) {
            clearInterval(timer);
            countdownElement.innerHTML = '<span class="text-red-600 font-black text-6xl drop-shadow-lg">HẾT GIỜ</span>';
            return;
        }
        
        const minutes = Math.floor(timeLeft / 60000);
        const seconds = Math.floor((timeLeft % 60000) / 1000);
        
        const minutesDisplay = String(minutes).padStart(2, '0');
        const secondsDisplay = String(seconds).padStart(2, '0');
        
        countdownElement.innerHTML = `
            <span class="countdown-minutes text-6xl font-black drop-shadow-lg">${minutesDisplay}</span>
            <span class="text-6xl font-black drop-shadow-lg">:</span>
            <span class="countdown-seconds text-6xl font-black drop-shadow-lg">${secondsDisplay}</span>
        `;
    }, 1000);
}

function showNotification(title, message, type = 'info') {
    // Simple notification function
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    
    notification.innerHTML = `
        <h4 class="font-bold">${title}</h4>
        <p>${message}</p>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification && notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

// Global functions that might be called from HTML
window.resumeTimer = resumeTimer;
window.toggleVehicleSimple = toggleVehicleSimple;
