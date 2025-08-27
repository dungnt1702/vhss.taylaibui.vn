// Global variables
let waitingVehicles = [];
let timerVehicles = [];
let routeVehicles = {};

// Debug: Log when script loads
console.log('🚀 active-vehicles.js loaded successfully');

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing...');
    initializePage();
    setupCheckboxes();
    setupPopupEvents();
});

// Functions will be made globally accessible after they are defined

function initializePage() {
    console.log('Initializing page...');
    // Load waiting vehicles from hidden data
    const dataElement = document.getElementById('vehicle-data');
    if (dataElement) {
        waitingVehicles = JSON.parse(dataElement.dataset.vehicles || '[]');
        console.log('Loaded waiting vehicles:', waitingVehicles);
    }
    
    // Initialize route vehicles object
    for (let i = 1; i <= 10; i++) {
        routeVehicles[i] = [];
    }
}

function setupCheckboxes() {
    console.log('Setting up checkboxes...');
    // Select all waiting vehicles
    const selectAllWaiting = document.getElementById('select-all-waiting');
    if (selectAllWaiting) {
        selectAllWaiting.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.waiting-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }

    // Select all timer vehicles
    const selectAllTimer = document.getElementById('select-all-timer');
    if (selectAllTimer) {
        selectAllTimer.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.timer-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    }
}

function setupPopupEvents() {
    console.log('Setting up popup events...');
    // Close popup when clicking overlay
    const overlay = document.getElementById('popup-overlay');
    if (overlay) {
        overlay.addEventListener('click', function() {
            closeNotification();
        });
    }
    
    // Close popup when pressing Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeNotification();
        }
    });
}

function startTimer() {
    console.log('startTimer called');
    const selectedVehicles = getSelectedWaitingVehicles();
    if (selectedVehicles.length === 0) {
        showNotification('Bạn hãy chọn xe trước khi bấm giờ!');
        return;
    }

    const timeMinutes = parseInt(document.getElementById('time-select').value);
    const startTime = new Date();
    const endTime = new Date(startTime.getTime() + timeMinutes * 60000);

    console.log('Starting timer for', selectedVehicles.length, 'vehicles, duration:', timeMinutes, 'minutes');

    selectedVehicles.forEach(vehicleId => {
        const vehicle = waitingVehicles.find(v => v.id == vehicleId);
        if (vehicle) {
            // Add to timer vehicles (Xe chạy đường 1-2)
            const timerVehicle = {
                ...vehicle,
                startTime: startTime,
                endTime: endTime,
                timeMinutes: timeMinutes,
                routeType: 'road_1_2' // Đánh dấu xe chạy đường 1-2
            };
            timerVehicles.push(timerVehicle);
            
            // Remove from waiting vehicles
            waitingVehicles = waitingVehicles.filter(v => v.id != vehicleId);
        }
    });

    updateTables();
    startCountdown();
}

function assignRoute() {
    console.log('assignRoute called');
    const selectedVehicles = getSelectedWaitingVehicles();
    if (selectedVehicles.length === 0) {
        showNotification('Bạn hãy chọn xe trước khi vào cung đường');
        return;
    }

    const routeNumber = document.getElementById('route-select').value;
    if (!routeNumber) {
        alert('Vui lòng chọn đường');
        return;
    }

    console.log('Assigning', selectedVehicles.length, 'vehicles to route', routeNumber);

    selectedVehicles.forEach(vehicleId => {
        const vehicle = waitingVehicles.find(v => v.id == vehicleId);
        if (vehicle) {
            // Add to route vehicles
            const routeVehicle = {
                ...vehicle,
                routeNumber: routeNumber,
                startTime: new Date()
            };
            routeVehicles[routeNumber].push(routeVehicle);
            
            // Remove from waiting vehicles
            waitingVehicles = waitingVehicles.filter(v => v.id != vehicleId);
        }
    });

    updateTables();
}

function returnToYard() {
    console.log('returnToYard called');
    const selectedVehicles = getSelectedTimerVehicles();
    if (selectedVehicles.length === 0) {
        alert('Vui lòng chọn xe để về bãi');
        return;
    }

    console.log('Returning', selectedVehicles.length, 'vehicles to yard');

    selectedVehicles.forEach(vehicleId => {
        const vehicle = timerVehicles.find(v => v.id == vehicleId);
        if (vehicle) {
            // Add back to waiting vehicles
            waitingVehicles.push({
                id: vehicle.id,
                name: vehicle.name,
                color: vehicle.color,
                seats: vehicle.seats
            });
            
            // Remove from timer vehicles
            timerVehicles = timerVehicles.filter(v => v.id != vehicleId);
        }
    });

    updateTables();
}

function returnToYardFromRoute(routeNumber) {
    console.log('returnToYardFromRoute called for route', routeNumber);
    const routeGroup = document.querySelector(`[data-route="${routeNumber}"]`);
    const checkboxes = routeGroup.querySelectorAll('.route-checkbox:checked');
    
    console.log('Returning', checkboxes.length, 'vehicles from route', routeNumber, 'to yard');
    
    checkboxes.forEach(checkbox => {
        const vehicleId = checkbox.value;
        const vehicle = routeVehicles[routeNumber].find(v => v.id == vehicleId);
        if (vehicle) {
            // Add back to waiting vehicles
            waitingVehicles.push({
                id: vehicle.id,
                name: vehicle.name,
                color: vehicle.color,
                seats: vehicle.seats
            });
            
            // Remove from route vehicles
            routeVehicles[routeNumber] = routeVehicles[routeNumber].filter(v => v.id != vehicleId);
        }
    });

    updateTables();
}

function getSelectedWaitingVehicles() {
    const checkboxes = document.querySelectorAll('.waiting-checkbox:checked');
    const selectedVehicles = Array.from(checkboxes).map(cb => cb.value);
    console.log('Selected waiting vehicles:', selectedVehicles);
    return selectedVehicles;
}

function getSelectedTimerVehicles() {
    const checkboxes = document.querySelectorAll('.timer-checkbox:checked');
    const selectedVehicles = Array.from(checkboxes).map(cb => cb.value);
    console.log('Selected timer vehicles:', selectedVehicles);
    return selectedVehicles;
}

function updateTables() {
    console.log('Updating tables...');
    updateWaitingTable();
    updateTimerTable();
    updateRouteTable();
}

function updateWaitingTable() {
    const tbody = document.getElementById('waiting-vehicles');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    waitingVehicles.forEach(vehicle => {
        const row = `
            <tr class="hover:bg-gray-50">
                <td class="px-3 py-2">
                    <input type="checkbox" value="${vehicle.id}" class="waiting-checkbox rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                </td>
                <td class="px-3 py-2 text-sm text-gray-900">${vehicle.name}</td>
                <td class="px-3 py-2">
                    <div class="w-6 h-6 rounded border border-gray-300" style="background-color: ${vehicle.color};" title="${vehicle.color}"></div>
                </td>
                <td class="px-3 py-2 text-sm text-gray-500">${vehicle.seats}</td>
                <td class="px-3 py-2">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        Ngoài bãi
                    </span>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
    
    console.log('Updated waiting table with', waitingVehicles.length, 'vehicles');
}

function updateTimerTable() {
    const tbody = document.getElementById('timer-vehicles');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    timerVehicles.forEach(vehicle => {
        const startTimeStr = vehicle.startTime.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
        const endTimeStr = vehicle.endTime.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
        
        const row = `
            <tr class="hover:bg-gray-50">
                <td class="px-3 py-2">
                    <input type="checkbox" value="${vehicle.id}" class="timer-checkbox rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                </td>
                <td class="px-3 py-2 text-sm text-gray-900">${vehicle.name}</td>
                <td class="px-3 py-2">
                    <div class="w-6 h-6 rounded border border-gray-300" style="background-color: ${vehicle.color};" title="${vehicle.color}"></div>
                </td>
                <td class="px-3 py-2 text-sm text-gray-500">${vehicle.seats}</td>
                <td class="px-3 py-2 text-sm text-gray-500">${startTimeStr}</td>
                <td class="px-3 py-2 text-sm text-gray-500">${endTimeStr}</td>
                <td class="px-3 py-2 text-sm text-gray-500">
                    <span class="countdown" data-end="${vehicle.endTime.getTime()}">--:--</span>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
    
    console.log('Updated timer table with', timerVehicles.length, 'vehicles');
}

function updateRouteTable() {
    const container = document.getElementById('route-groups');
    if (!container) return;
    
    container.innerHTML = '';
    
    Object.keys(routeVehicles).forEach(routeNumber => {
        const vehicles = routeVehicles[routeNumber];
        if (vehicles.length > 0) {
            const routeGroup = `
                <div class="border border-gray-200 rounded-lg" data-route="${routeNumber}">
                    <div class="px-3 py-2 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900">Đường ${routeNumber}</h3>
                    </div>
                    <div class="p-3">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-2 py-1 text-left">
                                        <input type="checkbox" class="route-select-all rounded border-gray-300 text-brand-600 focus:ring-brand-500" onchange="toggleRouteCheckboxes(${routeNumber}, this.checked)">
                                    </th>
                                    <th class="px-2 py-1 text-left text-xs font-medium text-gray-500">Xe số</th>
                                    <th class="px-2 py-1 text-left text-xs font-medium text-gray-500">Màu sắc</th>
                                    <th class="px-2 py-1 text-left text-xs font-medium text-gray-500">Chỗ ngồi</th>
                                    <th class="px-2 py-1 text-left text-xs font-medium text-gray-500">Thời gian bắt đầu</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                ${vehicles.map(vehicle => `
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-2 py-1">
                                            <input type="checkbox" value="${vehicle.id}" class="route-checkbox rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                                        </td>
                                        <td class="px-2 py-1 text-xs text-gray-900">${vehicle.name}</td>
                                        <td class="px-2 py-1">
                                            <div class="w-5 h-5 rounded border border-gray-300" style="background-color: ${vehicle.color};" title="${vehicle.color}"></div>
                                        </td>
                                        <td class="px-2 py-1 text-xs text-gray-500">${vehicle.seats}</td>
                                        <td class="px-2 py-1 text-xs text-gray-500">${vehicle.startTime.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' })}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                        <div class="mt-3">
                            <button onclick="returnToYardFromRoute(${routeNumber})" class="w-full px-2 py-1 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                Về bãi
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.innerHTML += routeGroup;
        }
    });
    
    console.log('Updated route table');
}

function showNotification(message) {
    console.log('Showing notification:', message);
    const popup = document.getElementById('notification-popup');
    const overlay = document.getElementById('popup-overlay');
    const messageElement = document.getElementById('notification-message');
    
    if (popup && overlay && messageElement) {
        messageElement.textContent = message;
        popup.style.display = 'block';
        overlay.style.display = 'block';
        console.log('Popup displayed successfully');
    } else {
        console.error('Popup elements not found');
        alert(message); // Fallback to alert if popup fails
    }
}

function closeNotification() {
    const popup = document.getElementById('notification-popup');
    const overlay = document.getElementById('popup-overlay');
    
    if (popup) popup.style.display = 'none';
    if (overlay) overlay.style.display = 'none';
}

function toggleRouteCheckboxes(routeNumber, checked) {
    const routeGroup = document.querySelector(`[data-route="${routeNumber}"]`);
    if (!routeGroup) return;
    
    const checkboxes = routeGroup.querySelectorAll('.route-checkbox');
    checkboxes.forEach(cb => cb.checked = checked);
}

function startCountdown() {
    console.log('Starting countdown...');
    setInterval(() => {
        const countdownElements = document.querySelectorAll('.countdown');
        countdownElements.forEach(element => {
            const endTime = parseInt(element.dataset.end);
            const now = new Date().getTime();
            const timeLeft = endTime - now;
            
            if (timeLeft > 0) {
                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                element.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            } else {
                element.textContent = '00:00';
                element.classList.add('text-red-600', 'font-bold');
            }
        });
    }, 1000);
}

// Additional features
function refreshPage() {
    location.reload();
}

function exportData() {
    const data = {
        waiting: waitingVehicles,
        timer: timerVehicles,
        routes: routeVehicles,
        timestamp: new Date().toISOString()
    };
    
    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `khach-doan-${new Date().toISOString().split('T')[0]}.json`;
    a.click();
    URL.revokeObjectURL(url);
}

function clearAllData() {
    if (confirm('Bạn có chắc muốn xóa tất cả dữ liệu?')) {
        waitingVehicles = [];
        timerVehicles = [];
        routeVehicles = {};
        updateTables();
        showNotification('Đã xóa tất cả dữ liệu');
    }
}

// Auto-save data every 30 seconds
setInterval(() => {
    if (waitingVehicles.length > 0 || timerVehicles.length > 0 || Object.values(routeVehicles).some(v => v.length > 0)) {
        localStorage.setItem('khachDoanData', JSON.stringify({
            waiting: waitingVehicles,
            timer: timerVehicles,
            routes: routeVehicles
        }));
    }
}, 30000);

// Load saved data on page load
window.addEventListener('load', () => {
    const savedData = localStorage.getItem('khachDoanData');
    if (savedData) {
        try {
            const data = JSON.parse(savedData);
            if (data.waiting) waitingVehicles = data.waiting;
            if (data.timer) timerVehicles = data.timer;
            if (data.routes) routeVehicles = data.routes;
            updateTables();
            console.log('Loaded saved data');
        } catch (e) {
            console.error('Error loading saved data:', e);
        }
    }
});

// Make all functions globally accessible
window.startTimer = startTimer;
window.assignRoute = assignRoute;
window.returnToYard = returnToYard;
window.returnToYardFromRoute = returnToYardFromRoute;
window.toggleRouteCheckboxes = toggleRouteCheckboxes;
window.closeNotification = closeNotification;
window.refreshPage = refreshPage;
window.exportData = exportData;
window.clearAllData = clearAllData;

console.log('All functions exposed to global scope');
