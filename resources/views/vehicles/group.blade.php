<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Khách đoàn') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Khách đoàn</h1>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Quản lý xe khách đoàn theo thời gian và cung đường</p>
                    </div>
                    <div class="mt-4 sm:mt-0 flex flex-wrap gap-2">
                        <button onclick="refreshPage()" class="px-3 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            🔄 Làm mới
                        </button>
                        <button onclick="exportData()" class="px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            📥 Xuất dữ liệu
                        </button>
                        <button onclick="clearAllData()" class="px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            🗑️ Xóa tất cả
                        </button>
                    </div>
                </div>
            </div>

            <!-- Hidden data for JavaScript -->
            <div id="vehicle-data" data-vehicles='@json($vehicles)' style="display: none;"></div>

            <!-- Three Column Layout -->
            <div class="xl:grid xl:grid-cols-3 gap-6">
                <!-- Block 1: Xe đang chờ -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Xe đang chờ</h2>
                        
                        @if($vehicles && count($vehicles) > 0)
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-3 py-2 text-left">
                                            <input type="checkbox" id="select-all-waiting" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                                        </th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Xe số</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Màu sắc</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Chỗ ngồi</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody id="waiting-vehicles" class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($vehicles as $vehicle)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-3 py-2">
                                                <input type="checkbox" value="{{ $vehicle->id }}" class="waiting-checkbox rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $vehicle->name }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">{{ $vehicle->color }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">{{ $vehicle->seats }}</td>
                                            <td class="px-3 py-2">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    Ngoài bãi
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">Không có xe nào đang chờ</p>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="mt-6">
                            <div style="display: flex; flex-direction: row; align-items: center; gap: 16px; justify-content: center;">
                                <!-- Time Selection Button Group -->
                                <div style="display: flex; align-items: stretch;">
                                    <select id="time-select" style="padding: 8px 12px; border: 1px solid #d1d5db; border-right: none; border-radius: 6px 0 0 6px; background: white; font-size: 14px; outline: none; height: 40px; box-sizing: border-box;">
                                        <option value="45">45 phút</option>
                                        <option value="30" selected>30 phút</option>
                                        <option value="10">10 phút</option>
                                    </select>
                                    <button onclick="startTimer()" style="padding: 8px 16px; background: #2563eb; color: white; border: none; border-radius: 0 6px 6px 0; font-size: 14px; font-weight: 500; cursor: pointer; min-width: 120px; transition: background-color 0.2s; outline: none; height: 40px; box-sizing: border-box;" onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                                        Bấm giờ
                                    </button>
                                </div>
                                <!-- Route Selection Button Group -->
                                <div style="display: flex; align-items: stretch;">
                                    <select id="route-select" style="padding: 8px 12px; border: 1px solid #d1d5db; border-right: none; border-radius: 6px 0 0 6px; background: white; font-size: 14px; outline: none; height: 40px; box-sizing: border-box;">
                                        <option value="1">Cung đường 1</option>
                                        <option value="2">Cung đường 2</option>
                                        <option value="3" selected>Cung đường 3</option>
                                        <option value="4">Cung đường 4</option>
                                        <option value="5">Cung đường 5</option>
                                        <option value="6">Cung đường 6</option>
                                        <option value="7">Cung đường 7</option>
                                        <option value="8">Cung đường 8</option>
                                        <option value="9">Cung đường 9</option>
                                        <option value="10">Cung đường 10</option>
                                    </select>
                                    <button onclick="assignRoute()" style="padding: 8px 16px; background: #16a34a; color: white; border: none; border-radius: 0 6px 6px 0; font-size: 14px; font-weight: 500; cursor: pointer; min-width: 120px; transition: background-color 0.2s; outline: none; height: 40px; box-sizing: border-box;" onmouseover="this.style.background='#15803d'" onmouseout="this.style.background='#16a34a'">
                                        Chạy
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Block 2: Xe chạy đường 1-2 -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Xe chạy đường 1-2</h2>
                        
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 text-left">
                                        <input type="checkbox" id="select-all-timer" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                                    </th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Xe số</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Màu sắc</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Chỗ ngồi</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Thời gian bắt đầu</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Thời gian kết thúc</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Đếm ngược</th>
                                </tr>
                            </thead>
                            <tbody id="timer-vehicles" class="divide-y divide-gray-200 dark:divide-gray-700">
                                <!-- Timer vehicles will be populated by JavaScript -->
                            </tbody>
                        </table>

                        <div class="mt-4">
                            <button onclick="returnToYard()" class="w-full px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                Về bãi
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Block 3: Xe theo cung đường -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Xe theo cung đường</h2>
                        
                        <div id="route-groups" class="space-y-4">
                            <!-- Route groups will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Popup -->
    <div id="notification-popup" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); z-index: 1000; min-width: 300px; text-align: center;">
        <div style="margin-bottom: 16px;">
            <svg style="width: 48px; height: 48px; color: #f59e0b; margin: 0 auto;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </div>
        <h3 style="margin: 0 0 12px 0; font-size: 18px; font-weight: 600; color: #111827;">Thông báo</h3>
        <p id="notification-message" style="margin: 0 0 20px 0; color: #6b7280; line-height: 1.5;"></p>
        <button onclick="closeNotification()" style="padding: 8px 24px; background: #3b82f6; color: white; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
            Đóng
        </button>
    </div>
    <!-- Overlay for popup -->
    <div id="popup-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999;"></div>
</x-app-layout>

@push('scripts')
<script>
// Global variables
let waitingVehicles = [];
let timerVehicles = [];
let routeVehicles = {};

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
        showNotification('Bạn phải chọn xe rồi mới Bấm giờ');
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
        showNotification('Bạn phải chọn xe rồi mới Chạy');
        return;
    }

    const routeNumber = document.getElementById('route-select').value;
    if (!routeNumber) {
        alert('Vui lòng chọn cung đường');
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
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-3 py-2">
                    <input type="checkbox" value="${vehicle.id}" class="waiting-checkbox rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                </td>
                <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">${vehicle.name}</td>
                <td class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">${vehicle.color}</td>
                <td class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">${vehicle.seats}</td>
                <td class="px-3 py-2">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
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
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-3 py-2">
                    <input type="checkbox" value="${vehicle.id}" class="timer-checkbox rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                </td>
                <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">${vehicle.name}</td>
                <td class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">${vehicle.color}</td>
                <td class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">${vehicle.seats}</td>
                <td class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">${startTimeStr}</td>
                <td class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">${endTimeStr}</td>
                <td class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">
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
                <div class="border border-gray-200 dark:border-gray-600 rounded-lg" data-route="${routeNumber}">
                    <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Cung đường ${routeNumber}</h3>
                    </div>
                    <div class="p-3">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-2 py-1 text-left">
                                        <input type="checkbox" class="route-select-all rounded border-gray-300 text-brand-600 focus:ring-brand-500" onchange="toggleRouteCheckboxes(${routeNumber}, this.checked)">
                                    </th>
                                    <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Xe số</th>
                                    <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Màu sắc</th>
                                    <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Chỗ ngồi</th>
                                    <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Thời gian bắt đầu</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                ${vehicles.map(vehicle => `
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-2 py-1">
                                            <input type="checkbox" value="${vehicle.id}" class="route-checkbox rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                                        </td>
                                        <td class="px-2 py-1 text-xs text-gray-900 dark:text-gray-100">${vehicle.name}</td>
                                        <td class="px-2 py-1 text-xs text-gray-500 dark:text-gray-400">${vehicle.color}</td>
                                        <td class="px-2 py-1 text-xs text-gray-500 dark:text-gray-400">${vehicle.seats}</td>
                                        <td class="px-2 py-1 text-xs text-gray-500 dark:text-gray-400">${vehicle.startTime.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' })}</td>
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
</script>

<style>
/* Mobile responsive styles */
@media (max-width: 1280px) {
    .xl\:grid-cols-3 {
        grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
    }
    
    .xl\:grid {
        display: grid !important;
        gap: 1.5rem !important;
    }
}

@media (max-width: 640px) {
    .p-6 {
        padding: 1rem !important;
    }
    
    .gap-6 {
        gap: 1rem !important;
    }
    
    .text-2xl {
        font-size: 1.5rem !important;
        line-height: 2rem !important;
    }
    
    .text-lg {
        font-size: 1.125rem !important;
        line-height: 1.75rem !important;
    }
    
    /* Button group responsive */
    div[style*="display: flex"] {
        flex-direction: column !important;
        gap: 12px !important;
    }
    
    /* Button group styling */
    div[style*="display: flex"] {
        gap: 0 !important;
    }
    
    /* Button group border radius on mobile */
    select[style*="border-radius: 6px 0 0 6px"] {
        border-radius: 6px !important;
        border-right: 1px solid #d1d5db !important;
    }
    
    button[style*="border-radius: 0 6px 6px 0"] {
        border-radius: 6px !important;
    }
    
    /* Popup responsive */
    #notification-popup {
        min-width: 280px !important;
        margin: 20px !important;
    }
    
    #notification-popup h3 {
        font-size: 16px !important;
    }
    
    #notification-popup p {
        font-size: 14px !important;
    }
}
</style>
@endpush
