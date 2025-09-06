/**
 * ActiveVehicles - Class for managing active vehicles
 * Extends VehicleBase with active-specific functionality
 */

import { VehicleBase } from './VehicleBase.js';

class ActiveVehicles extends VehicleBase {
    constructor() {
        super('Active Vehicles');
        this.durationOptions = [15, 30, 45, 60, 90, 120];
        this.bulkActions = [];
    }

    /**
     * Initialize active vehicles page
     */
    init() {
        super.init();
        this.setupActiveSpecificFeatures();
        this.setupBulkActions();
        this.loadRunningVehiclesOnInit();
    }

    /**
     * Setup active-specific features
     */
    setupActiveSpecificFeatures() {
        this.setupDurationSelectors();
        this.setupRouteAssignment();
        this.setupWorkshopTransfer();
        this.setupVehicleSelection();
    }

    /**
     * Setup duration selectors for start timer
     */
    setupDurationSelectors() {
        const durationButtons = document.querySelectorAll('[data-duration]');
        durationButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const duration = parseInt(e.target.dataset.duration);
                const vehicleId = e.target.dataset.vehicleId;
                
                if (duration && vehicleId) {
                    this.assignTimer(vehicleId, duration, e.target);
                }
            });
        });

        // Setup custom duration input
        const customDurationInput = document.querySelector('#custom-duration');
        if (customDurationInput) {
            customDurationInput.addEventListener('change', (e) => {
                const duration = parseInt(e.target.value);
                if (duration && duration > 0 && duration <= 120) {
                    this.updateCustomDurationButtons(duration);
                }
            });
        }
    }

    /**
     * Update custom duration buttons
     */
    updateCustomDurationButtons(duration) {
        const customButtons = document.querySelectorAll('[data-action="custom-start"]');
        customButtons.forEach(button => {
            button.dataset.duration = duration;
            button.textContent = `Bắt đầu ${duration} phút`;
        });
    }

    /**
     * Setup route assignment functionality
     */
    setupRouteAssignment() {
        const routeButtons = document.querySelectorAll('[data-action="assign-route"]');
        routeButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const vehicleId = e.target.dataset.vehicleId;
                if (vehicleId) {
                    this.assignRoute(vehicleId, e.target);
                }
            });
        });
    }

    /**
     * Setup workshop transfer functionality
     */
    setupWorkshopTransfer() {
        const workshopButtons = document.querySelectorAll('[data-action="move-workshop"]');
        workshopButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const vehicleId = e.target.dataset.vehicleId;
                if (vehicleId) {
                    this.moveToWorkshop(vehicleId, e.target);
                }
            });
        });
    }

    /**
     * Setup vehicle selection functionality
     */
    setupVehicleSelection() {
        this.setupSelectAll('waiting', 'waiting-checkbox');
        this.setupSelectAll('timer', 'vehicle-checkbox');
    }

    /**
     * Setup bulk actions
     */
    setupBulkActions() {
        this.setupBulkAssignTimer();
        this.setupBulkRouteAssignment();
        this.setupBulkWorkshopTransfer();
    }

    /**
     * Setup bulk assign timer
     */
    setupBulkAssignTimer() {
        // assign-timer action is handled by VehicleBase.js setupActionListener
    }

    /**
     * Setup bulk route assignment
     */
    setupBulkRouteAssignment() {
        const bulkRouteButtons = document.querySelectorAll('[data-action="assign-route"]');
        bulkRouteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                // Kiểm tra xem có xe nào được chọn không
                const selectedVehicles = this.getSelectedVehicles();
                if (selectedVehicles.length === 0) {
                    console.log('ActiveVehicles: Không có xe nào được chọn, hiển thị modal cảnh báo');
                    VehicleBase.prototype.showNotificationModal.call(this, 'Cảnh báo', 'Bạn phải chọn xe trước!', 'warning');
                    return;
                }

                this.assignRouteBulk();
            });
        });
    }

    /**
     * Setup bulk workshop transfer
     */
    setupBulkWorkshopTransfer() {
        const bulkWorkshopButtons = document.querySelectorAll('[data-action="move-workshop"]');
        bulkWorkshopButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                // Kiểm tra xem có xe nào được chọn không
                const selectedVehicles = this.getSelectedVehicles();
                if (selectedVehicles.length === 0) {
                    console.log('ActiveVehicles: Không có xe nào được chọn, hiển thị modal cảnh báo');
                    VehicleBase.prototype.showNotificationModal.call(this, 'Cảnh báo', 'Bạn phải chọn xe trước!', 'warning');
                    return;
                }

                this.moveToWorkshopBulk();
            });
        });
    }

    /**
     * Assign timer for multiple vehicles
     */
    async assignTimerBulk(duration) {
        const selectedVehicles = this.getSelectedVehicles();
        if (selectedVehicles.length === 0) {
            // Hiển thị thông báo "Bạn phải chọn xe trước!" khi chưa chọn xe nào
            VehicleBase.prototype.showNotificationModal.call(this, 'Cảnh báo', 'Bạn phải chọn xe trước!', 'warning');
            return;
        }

        try {
            // Use the bulk timer function from VehicleBase
            const button = document.querySelector('[data-action="assign-timer"]');
            const response = await super.assignTimerBulk(selectedVehicles, duration, button);
            
            // Cập nhật bảng "Xe chạy đường 1-2" sau khi thành công
            if (response && response.success) {
                // Không hiển thị thông báo ở đây vì VehicleBase.js đã hiển thị rồi
                
                // Cập nhật bảng timer
                this.updateTimerVehiclesTable(selectedVehicles, duration, response.vehicles);
                
                // Ẩn xe khỏi bảng "Xe đang chờ"
                this.hideSelectedVehiclesFromWaitingTable(selectedVehicles);
            }
        } catch (error) {
            console.error('Error in assignTimerBulk:', error);
            VehicleBase.prototype.showNotificationModal.call(this, 'Lỗi', 'Có lỗi xảy ra khi bấm giờ', 'error');
        }
    }

    /**
     * Assign route to multiple vehicles
     */
    async assignRouteBulk() {
        const selectedVehicles = this.getSelectedVehicles();
        if (selectedVehicles.length === 0) {
            VehicleBase.prototype.showNotificationModal.call(this, 'Cảnh báo', 'Vui lòng chọn ít nhất một xe.', 'warning');
            return;
        }

        if (confirm(`Bạn có chắc muốn phân tuyến cho ${selectedVehicles.length} xe?`)) {
            try {
                const response = await this.makeApiCall('/api/vehicles/assign-route', {
                    method: 'POST',
                    body: JSON.stringify({
                        vehicle_ids: selectedVehicles
                    })
                });

                if (response.success) {
                    VehicleBase.prototype.showNotificationModal.call(this, 'Thành công', `Phân tuyến thành công cho ${selectedVehicles.length} xe!`, 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    VehicleBase.prototype.showNotificationModal.call(this, 'Lỗi', response.message || 'Có lỗi xảy ra', 'error');
                }
            } catch (error) {
                console.error('Error assigning bulk route:', error);
                VehicleBase.prototype.showNotificationModal.call(this, 'Lỗi', 'Có lỗi xảy ra khi phân tuyến hàng loạt', 'error');
            }
        }
    }

    /**
     * Move multiple vehicles to workshop
     */
    async moveToWorkshopBulk() {
        const selectedVehicles = this.getSelectedVehicles();
        if (selectedVehicles.length === 0) {
            VehicleBase.prototype.showNotificationModal.call(this, 'Cảnh báo', 'Vui lòng chọn ít nhất một xe.', 'warning');
            return;
        }

        if (confirm(`Bạn có chắc muốn chuyển ${selectedVehicles.length} xe vào xưởng?`)) {
            try {
                const response = await this.makeApiCall('/api/vehicles/move-workshop', {
                    method: 'POST',
                    body: JSON.stringify({
                        vehicle_ids: selectedVehicles
                    })
                });

                if (response.success) {
                    VehicleBase.prototype.showNotificationModal.call(this, 'Thành công', `Chuyển xưởng thành công cho ${selectedVehicles.length} xe!`, 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    VehicleBase.prototype.showNotificationModal.call(this, 'Lỗi', response.message || 'Có lỗi xảy ra', 'error');
                }
            } catch (error) {
                console.error('Error moving bulk to workshop:', error);
                VehicleBase.prototype.showNotificationModal.call(this, 'Lỗi', 'Có lỗi xảy ra khi chuyển xưởng hàng loạt', 'error');
            }
        }
    }

    /**
     * Return selected vehicles to yard
     */
    async returnToYard() {
        const selectedVehicles = this.getSelectedVehicles();
        if (selectedVehicles.length === 0) {
            console.log('Không có xe nào được chọn để đưa về bãi');
            return;
        }

        // Use VehicleBase function for multiple vehicles
        await this.returnToYard(selectedVehicles);
    }

    /**
     * Return selected vehicles to yard (public function for HTML onclick)
     */
    returnSelectedVehiclesToYard() {
        this.returnToYard();
    }

    /**
     * Update timer vehicles table after successful bulk assign timer
     */
    updateTimerVehiclesTable(vehicleIds, duration, vehicles) {
        const timerTableBody = document.getElementById('timer-vehicles');
        if (!timerTableBody) return;

        vehicles.forEach(vehicle => {
            // Tính toán thời gian bắt đầu và kết thúc
            const startTime = new Date();
            const endTime = new Date(startTime.getTime() + (duration * 60 * 1000));
            
            // Format thời gian
            const startTimeStr = startTime.toLocaleTimeString('vi-VN', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            const endTimeStr = endTime.toLocaleTimeString('vi-VN', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });

            // Tạo row mới
            const newRow = document.createElement('tr');
            newRow.className = 'hover:bg-gray-50';
            newRow.dataset.vehicleId = vehicle.id;
            newRow.dataset.endTime = endTime.getTime();
            newRow.dataset.status = 'running';
            
            newRow.innerHTML = `
                <td class="px-3 py-2">
                    <input type="checkbox" class="vehicle-checkbox rounded border-gray-300 text-brand-600 focus:ring-brand-500" value="${vehicle.id}">
                </td>
                <td class="px-3 py-2 text-sm text-gray-900">${vehicle.name}</td>
                <td class="px-3 py-2">
                    <div class="w-4 h-4 rounded border border-gray-300" style="background-color: ${vehicle.color};" title="${vehicle.color}"></div>
                </td>
                <td class="px-3 py-2 text-sm text-gray-900">${startTimeStr}</td>
                <td class="px-3 py-2 text-sm text-gray-900">${endTimeStr}</td>
                <td class="px-3 py-2 text-sm text-gray-900">
                    <div class="countdown-display" data-end-time="${endTime.getTime()}">
                        <span class="countdown-minutes">${duration.toString().padStart(2, '0')}</span>:<span class="countdown-seconds">00</span>
                    </div>
                </td>
            `;

            // Thêm row vào đầu bảng
            timerTableBody.insertBefore(newRow, timerTableBody.firstChild);
            
            // Start countdown timer cho row mới
            this.startCountdownTimer(newRow);
        });

        console.log(`Đã cập nhật bảng timer vehicles với ${vehicles.length} xe`);
    }

    /**
     * Load running vehicles on page initialization
     */
    loadRunningVehiclesOnInit() {
        const runningData = document.getElementById('running-vehicles-data');
        if (runningData) {
            try {
                const runningVehicles = JSON.parse(runningData.dataset.vehicles);
                if (runningVehicles && runningVehicles.length > 0) {
                    this.populateTimerTable(runningVehicles);
                }
            } catch (error) {
                console.error('Error parsing running vehicles data:', error);
            }
        }
    }

    /**
     * Populate timer table with running vehicles data
     */
    populateTimerTable(runningVehicles) {
        const timerTableBody = document.getElementById('timer-vehicles');
        if (!timerTableBody) return;

        // Clear existing content
        timerTableBody.innerHTML = '';

        runningVehicles.forEach(vehicle => {
            // Parse end_time from database
            const endTime = new Date(vehicle.end_time);
            const startTime = new Date(vehicle.start_time || vehicle.created_at);
            
            // Calculate remaining time
            const now = new Date();
            const remainingMs = endTime.getTime() - now.getTime();
            const remainingMinutes = Math.max(0, Math.floor(remainingMs / (1000 * 60)));
            const remainingSeconds = Math.max(0, Math.floor((remainingMs % (1000 * 60)) / 1000));
            
            // Format times
            const startTimeStr = startTime.toLocaleTimeString('vi-VN', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            const endTimeStr = endTime.toLocaleTimeString('vi-VN', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });

            // Create row
            const newRow = document.createElement('tr');
            newRow.className = 'hover:bg-gray-50';
            newRow.dataset.vehicleId = vehicle.id;
            newRow.dataset.endTime = endTime.getTime();
            newRow.dataset.status = 'running';
            
            newRow.innerHTML = `
                <td class="px-3 py-2">
                    <input type="checkbox" class="vehicle-checkbox rounded border-gray-300 text-brand-600 focus:ring-brand-500" value="${vehicle.id}">
                </td>
                <td class="px-3 py-2 text-sm text-gray-900">${vehicle.name}</td>
                <td class="px-3 py-2">
                    <div class="w-4 h-4 rounded border border-gray-300" style="background-color: ${vehicle.color};" title="${vehicle.color}"></div>
                </td>
                <td class="px-3 py-2 text-sm text-gray-900">${startTimeStr}</td>
                <td class="px-3 py-2 text-sm text-gray-900">${endTimeStr}</td>
                <td class="px-3 py-2 text-sm text-gray-900">
                    <div class="countdown-display" data-end-time="${endTime.getTime()}">
                        <span class="countdown-minutes">${remainingMinutes.toString().padStart(2, '0')}</span>:<span class="countdown-seconds">${remainingSeconds.toString().padStart(2, '0')}</span>
                    </div>
                </td>
            `;

            // Add row to table
            timerTableBody.appendChild(newRow);
            
            // Start countdown timer for this row
            this.startCountdownTimer(newRow);
        });

        console.log(`Đã load ${runningVehicles.length} xe đang chạy vào bảng timer`);
    }

    /**
     * Hide selected vehicles from waiting table after successful timer assignment
     */
    hideSelectedVehiclesFromWaitingTable(vehicleIds) {
        vehicleIds.forEach(vehicleId => {
            // Tìm row trong bảng "Xe đang chờ" bằng checkbox value với class waiting-checkbox
            const waitingTableBody = document.getElementById('waiting-vehicles');
            if (waitingTableBody) {
                const checkbox = waitingTableBody.querySelector(`.waiting-checkbox[value="${vehicleId}"]`);
                if (checkbox) {
                    const row = checkbox.closest('tr');
                    if (row) {
                        // Thêm animation fade out
                        row.style.transition = 'all 0.3s ease';
                        row.style.opacity = '0';
                        row.style.transform = 'scale(0.95)';
                        
                        // Xóa row sau animation
                        setTimeout(() => {
                            if (row.parentElement) {
                                row.remove();
                                
                                // Kiểm tra nếu không còn xe nào trong bảng chờ
                                const remainingRows = waitingTableBody.querySelectorAll('tr');
                                if (remainingRows.length === 0) {
                                    this.showEmptyWaitingState();
                                }
                            }
                        }, 300);
                    }
                }
            }
        });
    }

    /**
     * Show empty state for waiting vehicles table
     */
    showEmptyWaitingState() {
        const waitingTableBody = document.getElementById('waiting-vehicles');
        if (waitingTableBody) {
            waitingTableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-3 py-8 text-center text-gray-500">
                        Không có xe nào đang chờ
                    </td>
                </tr>
            `;
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔍 ActiveVehicles DOMContentLoaded event fired');
    
    try {
        // Create and initialize ActiveVehicles instance
        console.log('🔍 Creating ActiveVehicles instance...');
        const activeVehicles = new ActiveVehicles();
        console.log('🔍 ActiveVehicles instance created:', activeVehicles);
        
        console.log('🔍 Calling activeVehicles.init()...');
        activeVehicles.init();
        
        // Make it available globally for debugging
        window.activeVehicles = activeVehicles;
        console.log('✅ ActiveVehicles initialized and available as window.activeVehicles');
    } catch (error) {
        console.error('❌ Error initializing ActiveVehicles:', error);
    }
});

// Export for ES6 modules
export default ActiveVehicles;
