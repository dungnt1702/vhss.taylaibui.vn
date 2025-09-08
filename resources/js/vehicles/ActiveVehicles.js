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
        console.log('Setting up row click handlers in ActiveVehicles...');
        this.setupRowClickHandlers();
        this.setupWorkshopModal();
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
        this.setupSelectAll('routing', 'routing-checkbox');
    }

    /**
     * Setup row click handlers for all tables
     */
    setupRowClickHandlers() {
        // Setup row click for waiting vehicles table
        this.setupTableRowClick('waiting-vehicles', 'waiting-checkbox');
        
        // Setup row click for timer vehicles table
        this.setupTableRowClick('timer-vehicles', 'vehicle-checkbox');
        
        // Setup row click for routing vehicles table
        this.setupTableRowClick('routing-vehicles', 'routing-checkbox');
        
        // Setup mobile responsive headers
        this.setupMobileHeaders();
    }

    /**
     * Setup row click handler for a specific table
     */
    setupTableRowClick(tableId, checkboxClass) {
        const tableBody = document.getElementById(tableId);
        if (!tableBody) {
            console.log('Table body not found:', tableId);
            return;
        }
        
        // Check if event listener already exists
        if (tableBody.hasAttribute('data-row-click-setup')) {
            console.log('Row click already setup for table:', tableId);
            return;
        }
        
        console.log('Setting up row click for table:', tableId, 'with checkbox class:', checkboxClass);

        // Use event delegation for dynamically added rows
        tableBody.addEventListener('click', (e) => {
            console.log('Table row clicked:', e.target, 'Table:', tableId);
            
            // Find the closest row
            const row = e.target.closest('tr');
            if (!row) {
                console.log('No row found');
                return;
            }

            // Don't trigger if clicking on checkbox, button, or any element inside a button
            if (e.target.type === 'checkbox' || 
                e.target.tagName === 'BUTTON' || 
                e.target.tagName === 'INPUT' || 
                e.target.closest('button')) {
                console.log('Click on interactive element, ignoring');
                return;
            }

            // Find checkbox in this row
            const checkbox = row.querySelector(`.${checkboxClass}`);
            console.log('Checkbox found:', checkbox, 'Class:', checkboxClass);
            if (checkbox) {
                // Toggle checkbox
                checkbox.checked = !checkbox.checked;
                console.log('Checkbox toggled to:', checkbox.checked);
                
                // Trigger change event to update select all checkboxes
                checkbox.dispatchEvent(new Event('change', { bubbles: true }));
            } else {
                console.log('No checkbox found with class:', checkboxClass);
            }
        }, true); // Use capture phase
        
        // Mark as setup to prevent duplicates
        tableBody.setAttribute('data-row-click-setup', 'true');
    }

    /**
     * Setup mobile responsive headers
     */
    setupMobileHeaders() {
        // Check if we're on mobile
        const checkMobile = () => {
            return window.innerWidth <= 768;
        };

        // Update headers based on screen size
        const updateHeaders = () => {
            const timerTable = document.querySelector('#timer-vehicles');
            if (!timerTable) return;

            const headers = timerTable.parentElement.querySelectorAll('th');
            
            if (checkMobile()) {
                // Mobile headers - update titles for mobile view
                if (headers[1]) headers[1].textContent = 'Xe số';
                if (headers[2]) headers[2].textContent = 'Trạng thái';
                if (headers[3]) headers[3].innerHTML = '⏰'; // Clock icon for start/end time
                // headers[4] is "Bắt đầu" - keep original text
                // headers[5] is hidden by CSS (Kết thúc)
                if (headers[6]) headers[6].textContent = 'Đếm ngược';
            } else {
                // Desktop headers - all 6 columns visible
                if (headers[1]) headers[1].textContent = 'Xe số';
                if (headers[2]) headers[2].textContent = 'Trạng thái';
                if (headers[3]) headers[3].textContent = 'Bắt đầu';
                if (headers[4]) headers[4].textContent = 'Kết thúc';
                if (headers[5]) headers[5].textContent = 'Đếm ngược';
            }
        };

        // Update on load
        updateHeaders();

        // Update on resize
        window.addEventListener('resize', updateHeaders);
    }

    /**
     * Setup workshop modal functionality
     */
    setupWorkshopModal() {
        const form = document.getElementById('move-workshop-form');
        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(form);
            const vehicleId = formData.get('vehicle_id');
            const reason = formData.get('reason');
            const notes = formData.get('notes');
            
            if (!vehicleId) {
                this.showError('Không tìm thấy ID xe');
                return;
            }
            
            try {
                // Show loading state
                const submitButton = form.querySelector('button[type="submit"]');
                const originalText = submitButton.textContent;
                submitButton.textContent = 'Đang chuyển...';
                submitButton.disabled = true;
                
                // Prepare reason text - get the display text of selected option
                const reasonSelect = form.querySelector('#workshop-reason');
                const selectedOption = reasonSelect.options[reasonSelect.selectedIndex];
                const reasonText = selectedOption.textContent; // Get the display text (e.g., "Bảo trì", "Sửa chữa", etc.)
                const fullReason = `${reasonText} - ${notes}`;
                
                // Call API
                const response = await this.makeApiCall('/api/vehicles/move-workshop', {
                    method: 'POST',
                    body: JSON.stringify({
                        vehicle_id: vehicleId,
                        reason: fullReason
                    })
                });
                
                if (response.success) {
                    this.showSuccess('Xe đã được chuyển về xưởng thành công!');
                    
                    // Close modal
                    closeMoveWorkshopModal();
                    
                    // Hide vehicle from waiting table
                    this.hideVehicleFromWaitingTable(vehicleId);
                    
                    // Reload page after delay
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.showError(response.message || 'Có lỗi xảy ra khi chuyển xe về xưởng');
                }
            } catch (error) {
                console.error('Error moving vehicle to workshop:', error);
                this.showError('Có lỗi xảy ra khi chuyển xe về xưởng');
            } finally {
                // Restore button state
                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.textContent = 'Chuyển về xưởng';
                submitButton.disabled = false;
            }
        });
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
            
            // Cập nhật bảng "Xe chạy theo thời gian" sau khi thành công
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

        // Get selected route number
        const routeSelect = document.getElementById('route-select');
        const routeNumber = routeSelect ? routeSelect.value : null;
        
        if (!routeNumber) {
            VehicleBase.prototype.showNotificationModal.call(this, 'Cảnh báo', 'Vui lòng chọn cung đường.', 'warning');
            return;
        }

        // Get vehicle names for display
        const selectedVehicleNames = selectedVehicles.map(vehicleId => {
            const checkbox = document.querySelector(`.waiting-checkbox[value="${vehicleId}"]`);
            if (checkbox) {
                const row = checkbox.closest('tr');
                const vehicleNameElement = row.querySelector('td:nth-child(2) .vehicle-number-with-color div');
                return vehicleNameElement ? vehicleNameElement.textContent : 'Unknown';
            }
            return 'Unknown';
        });

        try {
            const response = await this.makeApiCall('/api/vehicles/assign-route', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: selectedVehicles,
                    route_number: parseInt(routeNumber)
                })
            });

            if (response.success) {
                // Show success message with vehicle names and route
                const vehicleNamesText = selectedVehicleNames.join(', ');
                this.showSuccess(`Xe số ${vehicleNamesText} đã được chạy theo cung đường ${routeNumber}`);
                
                // Hide selected vehicles from waiting table
                this.hideSelectedVehiclesFromWaitingTable(selectedVehicles);
                
                // Add vehicles to routing table
                this.addVehiclesToRoutingTable(selectedVehicles, selectedVehicleNames, routeNumber);
                
            } else {
                VehicleBase.prototype.showNotificationModal.call(this, 'Lỗi', response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error assigning bulk route:', error);
            VehicleBase.prototype.showNotificationModal.call(this, 'Lỗi', 'Có lỗi xảy ra khi phân tuyến hàng loạt', 'error');
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
        await super.returnToYard(selectedVehicles);
    }

    /**
     * Return selected vehicles to yard (public function for HTML onclick)
     */
    returnSelectedVehiclesToYard() {
        console.log('=== returnSelectedVehiclesToYard called ===');
        
        // Get selected vehicles from timer table
        const selectedCheckboxes = document.querySelectorAll('#timer-vehicles .vehicle-checkbox:checked');
        console.log('Selected checkboxes:', selectedCheckboxes.length);
        
        if (selectedCheckboxes.length === 0) {
            this.showWarning('Bạn phải chọn xe trước');
            return;
        }
        
        // Get vehicle IDs and names
        const selectedVehicleIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
        const selectedVehicleNames = Array.from(selectedCheckboxes).map(checkbox => {
            const row = checkbox.closest('tr');
            return row.querySelector('td:nth-child(2)').textContent; // Xe số column
        });
        
        console.log('Selected vehicle IDs:', selectedVehicleIds);
        console.log('Selected vehicle names:', selectedVehicleNames);
        
        // Get vehicle details from timer table before hiding
        const vehicleDetails = this.getVehicleDetailsFromTimerTable(selectedVehicleIds);
        console.log('Vehicle details:', vehicleDetails);
        
        // Show confirmation message
        const vehicleNamesText = selectedVehicleNames.join(', ');
        this.showSuccess(`Xe số ${vehicleNamesText} đã được chuyển về bãi`);
        
        // Update UI immediately (optimistic update)
        console.log('About to add vehicles to waiting table...');
        this.addVehiclesToWaitingTableFromTimer(selectedVehicleIds, selectedVehicleNames, vehicleDetails);
        console.log('About to hide vehicles from timer table...');
        this.hideSelectedVehiclesFromTimerTable(selectedVehicleIds);
        
        // Call returnToYard with selected vehicle IDs in background
        this.returnToYardAndUpdateUI(selectedVehicleIds, selectedVehicleNames, vehicleDetails);
    }

    /**
     * Get vehicle details from timer table before hiding
     */
    getVehicleDetailsFromTimerTable(vehicleIds) {
        const timerTableBody = document.getElementById('timer-vehicles');
        const vehicleDetails = {};
        
        if (!timerTableBody) return vehicleDetails;
        
        vehicleIds.forEach(vehicleId => {
            const checkbox = timerTableBody.querySelector(`.vehicle-checkbox[value="${vehicleId}"]`);
            if (checkbox) {
                const row = checkbox.closest('tr');
                if (row) {
                    // Get color from the row (3rd column in timer table - color square)
                    const colorCell = row.querySelector('td:nth-child(3) div');
                    console.log(`Timer color cell for vehicle ${vehicleId}:`, colorCell);
                    const vehicleColor = colorCell ? colorCell.style.backgroundColor || '#3b82f6' : '#3b82f6';
                    console.log(`Timer extracted color for vehicle ${vehicleId}:`, vehicleColor);
                    
                    // Get notes (we'll use default since timer table doesn't have notes column)
                    const vehicleNotes = 'Không có ghi chú';
                    
                    vehicleDetails[vehicleId] = {
                        color: vehicleColor,
                        notes: vehicleNotes
                    };
                }
            }
        });
        
        return vehicleDetails;
    }

    /**
     * Add vehicles to waiting table from timer table with details
     */
    addVehiclesToWaitingTableFromTimer(vehicleIds, vehicleNames, vehicleDetails) {
        console.log('=== addVehiclesToWaitingTableFromTimer called ===');
        console.log('Vehicle IDs:', vehicleIds);
        console.log('Vehicle names:', vehicleNames);
        console.log('Vehicle details:', vehicleDetails);
        
        const waitingTableBody = document.getElementById('waiting-vehicles');
        console.log('Waiting table body found:', !!waitingTableBody);
        if (!waitingTableBody) {
            console.error('waiting-vehicles table body not found!');
            return;
        }

        // Check if table is showing empty state and clear it
        const emptyStateRow = waitingTableBody.querySelector('tr td[colspan]');
        if (emptyStateRow) {
            console.log('Found empty state row, clearing it...');
            waitingTableBody.innerHTML = '';
        }

        vehicleIds.forEach((vehicleId, index) => {
            console.log(`Processing vehicle ${index + 1}/${vehicleIds.length}: ID=${vehicleId}`);
            const vehicleName = vehicleNames[index];
            const details = vehicleDetails[vehicleId] || {};
            const vehicleColor = details.color || '#3b82f6';
            const vehicleNotes = details.notes || 'Không có ghi chú';
            
            console.log(`Vehicle ${vehicleId} details:`, { vehicleName, vehicleColor, vehicleNotes });
            
            // Create new row for waiting table
            const newRow = document.createElement('tr');
            newRow.className = 'hover:bg-gray-50 clickable-row';
            newRow.dataset.vehicleId = vehicleId;
            console.log(`Created new row for vehicle ${vehicleId}:`, newRow);
            
            newRow.innerHTML = `
                <td class="px-3 py-2">
                    <input type="checkbox" value="${vehicleId}" class="waiting-checkbox rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                </td>
                <td class="px-3 py-2">
                    <div class="vehicle-number-with-color flex items-center">
                        <div class="w-8 h-8 rounded border border-gray-300 flex items-center justify-center text-white font-semibold text-sm" style="background-color: ${vehicleColor};" title="${vehicleColor}">
                            ${vehicleName}
                        </div>
                    </div>
                </td>
                <td class="px-3 py-2">
                    ${vehicleNotes ? 
                        `<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            ${vehicleNotes}
                        </span>` : 
                        `<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                            Không có ghi chú
                        </span>`
                    }
                </td>
                <td class="px-3 py-2">
                    <button onclick="openWorkshopModal(${vehicleId})" class="text-gray-600 hover:text-gray-900 transition-colors duration-200" title="Về xưởng">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </button>
                </td>
            `;
            
            console.log(`HTML content for vehicle ${vehicleId}:`, newRow.innerHTML);
            
            // Add animation fade in
            newRow.style.opacity = '0';
            newRow.style.transform = 'scale(0.95)';
            waitingTableBody.appendChild(newRow);
            console.log(`Appended vehicle ${vehicleId} to waiting table`);
            console.log('Waiting table body after append:', waitingTableBody.innerHTML);
            
            // Trigger animation
            setTimeout(() => {
                newRow.style.transition = 'all 0.3s ease';
                newRow.style.opacity = '1';
                newRow.style.transform = 'scale(1)';
                console.log(`Animation triggered for vehicle ${vehicleId}`);
            }, 100);
        });
        
        console.log('=== Finished adding vehicles to waiting table ===');
    }

    /**
     * Return selected routing vehicles to yard (public function for HTML onclick)
     */
    returnSelectedRoutingVehiclesToYard() {
        console.log('=== returnSelectedRoutingVehiclesToYard called ===');
        console.log('this context:', this);
        console.log('window.activeVehicles:', window.activeVehicles);
        
        // Get selected vehicles from routing table
        const selectedCheckboxes = document.querySelectorAll('#routing-vehicles .routing-checkbox:checked');
        console.log('Selected checkboxes:', selectedCheckboxes.length);
        
        if (selectedCheckboxes.length === 0) {
            this.showWarning('Bạn phải chọn xe trước');
            return;
        }
        
        // Get vehicle IDs and names
        const selectedVehicleIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
        const selectedVehicleNames = Array.from(selectedCheckboxes).map(checkbox => {
            const row = checkbox.closest('tr');
            const vehicleNameElement = row.querySelector('td:nth-child(2) .vehicle-number-with-color div');
            return vehicleNameElement ? vehicleNameElement.textContent : 'Unknown';
        });
        
        console.log('Selected vehicle IDs:', selectedVehicleIds);
        console.log('Selected vehicle names:', selectedVehicleNames);
        
        // Get vehicle details from routing table before hiding
        const vehicleDetails = this.getVehicleDetailsFromRoutingTable(selectedVehicleIds);
        console.log('Vehicle details:', vehicleDetails);
        
        // Show confirmation message
        const vehicleNamesText = selectedVehicleNames.join(', ');
        this.showSuccess(`Xe số ${vehicleNamesText} đã được chuyển về bãi`);
        
        // Update UI immediately (optimistic update)
        console.log('About to add vehicles to waiting table...');
        this.addVehiclesToWaitingTableFromRouting(selectedVehicleIds, selectedVehicleNames, vehicleDetails);
        console.log('About to hide vehicles from routing table...');
        this.hideSelectedVehiclesFromRoutingTable(selectedVehicleIds);
        
        // Call returnToYard with selected vehicle IDs in background
        this.returnToYardAndUpdateUI(selectedVehicleIds, selectedVehicleNames, vehicleDetails);
    }

    /**
     * Return vehicles to yard and update UI after API call completes
     */
    async returnToYardAndUpdateUI(selectedVehicleIds, selectedVehicleNames, vehicleDetails) {
        try {
            console.log('Starting returnToYardAndUpdateUI...');
            
            // Call returnToYard with selected vehicle IDs
            await super.returnToYard(selectedVehicleIds);
            console.log('API call completed successfully');
            
            // UI has already been updated optimistically, no need to do it again
            
        } catch (error) {
            console.error('Error in returnToYardAndUpdateUI:', error);
            this.showError('Có lỗi xảy ra khi chuyển xe về bãi');
            
            // If API call fails, we should revert the UI changes
            // For now, just show error message
        }
    }

    /**
     * Get vehicle details from routing table before hiding
     */
    getVehicleDetailsFromRoutingTable(vehicleIds) {
        const routingTableBody = document.getElementById('routing-vehicles');
        const vehicleDetails = {};
        
        if (!routingTableBody) return vehicleDetails;
        
        vehicleIds.forEach(vehicleId => {
            const checkbox = routingTableBody.querySelector(`.routing-checkbox[value="${vehicleId}"]`);
            if (checkbox) {
                const row = checkbox.closest('tr');
                if (row) {
                    // Get color from the row (2nd column - vehicle number with color)
                    const colorCell = row.querySelector('td:nth-child(2) .vehicle-number-with-color div');
                    console.log(`Routing color cell for vehicle ${vehicleId}:`, colorCell);
                    const vehicleColor = colorCell ? colorCell.style.backgroundColor || '#3b82f6' : '#3b82f6';
                    console.log(`Routing extracted color for vehicle ${vehicleId}:`, vehicleColor);
                    
                    // Get notes (we'll use default since routing table doesn't have notes column)
                    const vehicleNotes = 'Không có ghi chú';
                    
                    vehicleDetails[vehicleId] = {
                        color: vehicleColor,
                        notes: vehicleNotes
                    };
                }
            }
        });
        
        return vehicleDetails;
    }

    /**
     * Add vehicles to waiting table from routing table with details
     */
    addVehiclesToWaitingTableFromRouting(vehicleIds, vehicleNames, vehicleDetails) {
        console.log('=== addVehiclesToWaitingTableFromRouting called ===');
        console.log('Vehicle IDs:', vehicleIds);
        console.log('Vehicle names:', vehicleNames);
        console.log('Vehicle details:', vehicleDetails);
        
        // Debug: Check if we can find the table
        console.log('Looking for waiting-vehicles table...');
        const allTables = document.querySelectorAll('table');
        console.log('All tables found:', allTables.length);
        allTables.forEach((table, index) => {
            console.log(`Table ${index}:`, table.id, table.className);
        });
        
        const waitingTableBody = document.getElementById('waiting-vehicles');
        console.log('Waiting table body found:', !!waitingTableBody);
        console.log('Waiting table body element:', waitingTableBody);
        console.log('Waiting table body content:', waitingTableBody ? waitingTableBody.innerHTML : 'null');
        if (!waitingTableBody) {
            console.error('waiting-vehicles table body not found!');
            return;
        }

        // Check if table is showing empty state and clear it
        const emptyStateRow = waitingTableBody.querySelector('tr td[colspan]');
        if (emptyStateRow) {
            console.log('Found empty state row, clearing it...');
            waitingTableBody.innerHTML = '';
        }

        vehicleIds.forEach((vehicleId, index) => {
            console.log(`Processing vehicle ${index + 1}/${vehicleIds.length}: ID=${vehicleId}`);
            const vehicleName = vehicleNames[index];
            const details = vehicleDetails[vehicleId] || {};
            const vehicleColor = details.color || '#3b82f6';
            const vehicleNotes = details.notes || 'Không có ghi chú';
            
            console.log(`Vehicle ${vehicleId} details:`, { vehicleName, vehicleColor, vehicleNotes });
            
            // Create new row for waiting table
            const newRow = document.createElement('tr');
            newRow.className = 'hover:bg-gray-50 clickable-row';
            newRow.dataset.vehicleId = vehicleId;
            console.log(`Created new row for vehicle ${vehicleId}:`, newRow);
            
            newRow.innerHTML = `
                <td class="px-3 py-2">
                    <input type="checkbox" value="${vehicleId}" class="waiting-checkbox rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                </td>
                <td class="px-3 py-2">
                    <div class="vehicle-number-with-color flex items-center">
                        <div class="w-8 h-8 rounded border border-gray-300 flex items-center justify-center text-white font-semibold text-sm" style="background-color: ${vehicleColor};" title="${vehicleColor}">
                            ${vehicleName}
                        </div>
                    </div>
                </td>
                <td class="px-3 py-2">
                    ${vehicleNotes ? 
                        `<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            ${vehicleNotes}
                        </span>` : 
                        `<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                            Không có ghi chú
                        </span>`
                    }
                </td>
                <td class="px-3 py-2">
                    <button onclick="openWorkshopModal(${vehicleId})" class="text-gray-600 hover:text-gray-900 transition-colors duration-200" title="Về xưởng">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </button>
                </td>
            `;
            
            console.log(`HTML content for vehicle ${vehicleId}:`, newRow.innerHTML);
            
            // Add animation fade in
            newRow.style.opacity = '0';
            newRow.style.transform = 'scale(0.95)';
            waitingTableBody.appendChild(newRow);
            console.log(`Appended vehicle ${vehicleId} to waiting table`);
            console.log('Waiting table body after append:', waitingTableBody.innerHTML);
            
            // Trigger animation
            setTimeout(() => {
                newRow.style.transition = 'all 0.3s ease';
                newRow.style.opacity = '1';
                newRow.style.transform = 'scale(1)';
                console.log(`Animation triggered for vehicle ${vehicleId}`);
            }, 100);
        });
        
        console.log('=== Finished adding vehicles to waiting table ===');
    }

    /**
     * Hide single vehicle from waiting table
     */
    hideVehicleFromWaitingTable(vehicleId) {
        const waitingTableBody = document.getElementById('waiting-vehicles');
        if (!waitingTableBody) return;

        const checkbox = waitingTableBody.querySelector(`.waiting-checkbox[value="${vehicleId}"]`);
        if (checkbox) {
            const row = checkbox.closest('tr');
            if (row) {
                // Add animation fade out
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '0';
                row.style.transform = 'scale(0.95)';
                
                // Remove row after animation
                setTimeout(() => {
                    if (row.parentElement) {
                        row.remove();
                        
                        // Check if no more vehicles in waiting table
                        const remainingRows = waitingTableBody.querySelectorAll('tr');
                        if (remainingRows.length === 0) {
                            this.showEmptyWaitingState();
                        }
                    }
                }, 300);
            }
        }
    }

    /**
     * Hide selected vehicles from timer table
     */
    hideSelectedVehiclesFromTimerTable(vehicleIds) {
        vehicleIds.forEach(vehicleId => {
            const timerTableBody = document.getElementById('timer-vehicles');
            if (timerTableBody) {
                const checkbox = timerTableBody.querySelector(`.vehicle-checkbox[value="${vehicleId}"]`);
                if (checkbox) {
                    const row = checkbox.closest('tr');
                    if (row) {
                        // Add animation fade out
                        row.style.transition = 'all 0.3s ease';
                        row.style.opacity = '0';
                        row.style.transform = 'scale(0.95)';
                        
                        // Remove row after animation
                        setTimeout(() => {
                            row.remove();
                        }, 300);
                    }
                }
            }
        });
    }

    /**
     * Hide selected vehicles from routing table
     */
    hideSelectedVehiclesFromRoutingTable(vehicleIds) {
        vehicleIds.forEach(vehicleId => {
            const routingTableBody = document.getElementById('routing-vehicles');
            if (routingTableBody) {
                const checkbox = routingTableBody.querySelector(`.routing-checkbox[value="${vehicleId}"]`);
                if (checkbox) {
                    const row = checkbox.closest('tr');
                    if (row) {
                        // Add animation fade out
                        row.style.transition = 'all 0.3s ease';
                        row.style.opacity = '0';
                        row.style.transform = 'scale(0.95)';
                        
                        // Remove row after animation
                        setTimeout(() => {
                            row.remove();
                            
                            // Check if no more vehicles in routing table
                            const remainingRows = routingTableBody.querySelectorAll('tr');
                            if (remainingRows.length === 0) {
                                this.showEmptyRoutingState();
                            }
                        }, 300);
                    }
                }
            }
        });
    }

    /**
     * Add vehicles to routing table
     */
    addVehiclesToRoutingTable(vehicleIds, vehicleNames, routeNumber) {
        console.log('=== addVehiclesToRoutingTable called ===');
        console.log('Vehicle IDs:', vehicleIds);
        console.log('Vehicle names:', vehicleNames);
        console.log('Route number:', routeNumber);
        
        const routingTableBody = document.getElementById('routing-vehicles');
        console.log('Routing table body found:', !!routingTableBody);
        if (!routingTableBody) {
            console.error('routing-vehicles table body not found!');
            return;
        }

        // Check if table is showing empty state and clear it
        const emptyStateRow = routingTableBody.querySelector('tr td[colspan]');
        if (emptyStateRow) {
            console.log('Found empty state row in routing table, clearing it...');
            routingTableBody.innerHTML = '';
        }

        vehicleIds.forEach((vehicleId, index) => {
            console.log(`Processing vehicle ${index + 1}/${vehicleIds.length}: ID=${vehicleId}`);
            const vehicleName = vehicleNames[index];
            
            // Get vehicle details from waiting table before hiding
            const waitingTableBody = document.getElementById('waiting-vehicles');
            let vehicleColor = '#3b82f6';
            
            if (waitingTableBody) {
                const checkbox = waitingTableBody.querySelector(`.waiting-checkbox[value="${vehicleId}"]`);
                if (checkbox) {
                    const row = checkbox.closest('tr');
                    if (row) {
                        // Get color from the row
                        const colorCell = row.querySelector('td:nth-child(2) .vehicle-number-with-color div');
                        if (colorCell) {
                            vehicleColor = colorCell.style.backgroundColor || '#3b82f6';
                        }
                    }
                }
            }
            
            // Get current time for start_time
            const startTime = new Date();
            const startTimeStr = startTime.toLocaleTimeString('vi-VN', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            
            // Create new row for routing table
            const newRow = document.createElement('tr');
            newRow.className = 'hover:bg-gray-50 clickable-row';
            newRow.dataset.vehicleId = vehicleId;
            
            newRow.innerHTML = `
                <td class="px-3 py-2">
                    <input type="checkbox" value="${vehicleId}" class="routing-checkbox rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                </td>
                <td class="px-3 py-2">
                    <div class="vehicle-number-with-color flex items-center">
                        <div class="w-8 h-8 rounded border border-gray-300 flex items-center justify-center text-white font-semibold text-sm" style="background-color: ${vehicleColor};" title="${vehicleColor}">
                            ${vehicleName}
                        </div>
                    </div>
                </td>
                <td class="px-3 py-2 text-sm text-gray-900">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        Đường ${routeNumber}
                    </span>
                </td>
                <td class="px-3 py-2 text-sm text-gray-900">
                    ${startTimeStr}
                </td>
            `;
            
            console.log(`HTML content for vehicle ${vehicleId}:`, newRow.innerHTML);
            
            // Add animation fade in
            newRow.style.opacity = '0';
            newRow.style.transform = 'scale(0.95)';
            routingTableBody.appendChild(newRow);
            console.log(`Appended vehicle ${vehicleId} to routing table`);
            console.log('Routing table body after append:', routingTableBody.innerHTML);
            
            // Trigger animation
            setTimeout(() => {
                newRow.style.transition = 'all 0.3s ease';
                newRow.style.opacity = '1';
                newRow.style.transform = 'scale(1)';
                console.log(`Animation triggered for vehicle ${vehicleId}`);
            }, 100);
        });
        
        console.log('=== Finished adding vehicles to routing table ===');
    }

    /**
     * Add vehicles back to waiting table
     */
    addVehiclesToWaitingTable(vehicleIds, vehicleNames) {
        const waitingTableBody = document.getElementById('waiting-vehicles');
        if (!waitingTableBody) return;

        vehicleIds.forEach((vehicleId, index) => {
            const vehicleName = vehicleNames[index];
            
            // Get vehicle details from timer table before hiding
            const timerTableBody = document.getElementById('timer-vehicles');
            let vehicleSeats = '-';
            let vehicleColor = '#3b82f6';
            let vehicleNotes = 'Không có ghi chú';
            
            if (timerTableBody) {
                const checkbox = timerTableBody.querySelector(`.vehicle-checkbox[value="${vehicleId}"]`);
                if (checkbox) {
                    const row = checkbox.closest('tr');
                    if (row) {
                        // Get color from the row (3rd column in timer table - color square)
                        const colorCell = row.querySelector('td:nth-child(3) div');
                        console.log(`Color cell for vehicle ${vehicleId}:`, colorCell);
                        if (colorCell) {
                            vehicleColor = colorCell.style.backgroundColor || '#3b82f6';
                            console.log(`Extracted color for vehicle ${vehicleId}:`, vehicleColor);
                        } else {
                            console.log(`No color cell found for vehicle ${vehicleId}`);
                        }
                        
                        // Get vehicle data from the original data arrays
                        // We need to find the vehicle in our data arrays
                        const allVehicles = [
                            ...(this.runningVehicles || []),
                            ...(this.pausedVehicles || []),
                            ...(this.expiredVehicles || [])
                        ];
                        
                        const vehicleData = allVehicles.find(v => v.id == vehicleId);
                        if (vehicleData) {
                            vehicleSeats = vehicleData.seats || '-';
                            vehicleNotes = vehicleData.notes || 'Không có ghi chú';
                        }
                    }
                }
            }
            
            // Create new row for waiting table
            const newRow = document.createElement('tr');
            newRow.className = 'hover:bg-gray-50 clickable-row';
            newRow.dataset.vehicleId = vehicleId;
            
            newRow.innerHTML = `
                <td class="px-3 py-2">
                    <input type="checkbox" value="${vehicleId}" class="waiting-checkbox rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                </td>
                <td class="px-3 py-2">
                    <div class="vehicle-number-with-color flex items-center">
                        <div class="w-8 h-8 rounded border border-gray-300 flex items-center justify-center text-white font-semibold text-sm" style="background-color: ${vehicleColor};" title="${vehicleColor}">
                            ${vehicleName}
                        </div>
                    </div>
                </td>
                <td class="px-3 py-2">
                    ${vehicleNotes ? 
                        `<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            ${vehicleNotes}
                        </span>` : 
                        `<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                            Không có ghi chú
                        </span>`
                    }
                </td>
                <td class="px-3 py-2">
                    <button onclick="openWorkshopModal(${vehicleId})" class="text-gray-600 hover:text-gray-900 transition-colors duration-200" title="Về xưởng">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </button>
                </td>
            `;
            
            // Add animation fade in
            newRow.style.opacity = '0';
            newRow.style.transform = 'scale(0.95)';
            waitingTableBody.appendChild(newRow);
            
            // Trigger animation
            setTimeout(() => {
                newRow.style.transition = 'all 0.3s ease';
                newRow.style.opacity = '1';
                newRow.style.transform = 'scale(1)';
            }, 100);
        });
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
            newRow.className = 'hover:bg-gray-50 clickable-row';
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
                <td class="px-3 py-2">
                    <span class="status-badge status-running">Đang chạy</span>
                </td>
                <td class="px-3 py-2 text-sm text-gray-900">
                    <div class="mobile-time-content">
                        <div class="mobile-start-time">${startTimeStr}</div>
                        <div class="mobile-end-time">${endTimeStr}</div>
                    </div>
                    <span class="desktop-start-time">${startTimeStr}</span>
                </td>
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
        // Load all vehicle types: running, paused, expired
        const allVehicles = [];
        
        // Initialize arrays
        this.runningVehicles = [];
        this.pausedVehicles = [];
        this.expiredVehicles = [];
        
        // Load running vehicles
        const runningData = document.getElementById('running-vehicles-data');
        if (runningData) {
            try {
                const runningVehicles = JSON.parse(runningData.dataset.vehicles);
                if (runningVehicles && runningVehicles.length > 0) {
                    console.log('Running vehicles data:', runningVehicles);
                    runningVehicles.forEach(vehicle => {
                        // Force status to running for vehicles from running query
                        vehicle.status = 'running';
                        this.runningVehicles.push(vehicle);
                        allVehicles.push(vehicle);
                    });
                }
            } catch (error) {
                console.error('Error parsing running vehicles data:', error);
            }
        }
        
        // Load paused vehicles
        const pausedData = document.getElementById('paused-vehicles-data');
        if (pausedData) {
            try {
                const pausedVehicles = JSON.parse(pausedData.dataset.vehicles);
                if (pausedVehicles && pausedVehicles.length > 0) {
                    console.log('Paused vehicles data:', pausedVehicles);
                    pausedVehicles.forEach(vehicle => {
                        // Force status to paused for vehicles from paused query
                        vehicle.status = 'paused';
                        this.pausedVehicles.push(vehicle);
                        allVehicles.push(vehicle);
                    });
                }
            } catch (error) {
                console.error('Error parsing paused vehicles data:', error);
            }
        }
        
        // Load expired vehicles
        const expiredData = document.getElementById('expired-vehicles-data');
        if (expiredData) {
            try {
                const expiredVehicles = JSON.parse(expiredData.dataset.vehicles);
                if (expiredVehicles && expiredVehicles.length > 0) {
                    console.log('Expired vehicles data:', expiredVehicles);
                    expiredVehicles.forEach(vehicle => {
                        // Force status to expired for vehicles from expired query
                        vehicle.status = 'expired';
                        this.expiredVehicles.push(vehicle);
                        allVehicles.push(vehicle);
                    });
                }
            } catch (error) {
                console.error('Error parsing expired vehicles data:', error);
            }
        }
        
        if (allVehicles.length > 0) {
            this.populateTimerTable(allVehicles);
        }
    }

    /**
     * Populate timer table with all vehicle types (running, paused, expired)
     */
    populateTimerTable(allVehicles) {
        const timerTableBody = document.getElementById('timer-vehicles');
        if (!timerTableBody) return;

        // Clear existing content
        timerTableBody.innerHTML = '';

        allVehicles.forEach(vehicle => {
            // Parse times
            const endTime = new Date(vehicle.end_time);
            const startTime = new Date(vehicle.start_time || vehicle.created_at);
            
            // Calculate remaining time based on status
            let remainingMinutes = 0;
            let remainingSeconds = 0;
            
            if (vehicle.status === 'running') {
                // Running vehicles: countdown continues
                const now = new Date();
                const remainingMs = endTime.getTime() - now.getTime();
                remainingMinutes = Math.max(0, Math.floor(remainingMs / (1000 * 60)));
                remainingSeconds = Math.max(0, Math.floor((remainingMs % (1000 * 60)) / 1000));
            } else if (vehicle.status === 'paused') {
                // Paused vehicles: show paused remaining time
                if (vehicle.paused_remaining_seconds) {
                    const pausedSeconds = parseInt(vehicle.paused_remaining_seconds);
                    remainingMinutes = Math.floor(pausedSeconds / 60);
                    remainingSeconds = pausedSeconds % 60;
                } else {
                    remainingMinutes = 0;
                    remainingSeconds = 0;
                }
            } else if (vehicle.status === 'expired') {
                // Expired vehicles: show 00:00
                remainingMinutes = 0;
                remainingSeconds = 0;
            }
            
            // Format times
            const startTimeStr = startTime.toLocaleTimeString('vi-VN', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            const endTimeStr = endTime.toLocaleTimeString('vi-VN', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });

            // Get status display text and CSS classes
            let statusText = '';
            let statusClass = '';
            let rowClass = '';
            
            switch(vehicle.status) {
                case 'running':
                    statusText = 'Đang chạy';
                    statusClass = 'status-badge status-running';
                    rowClass = 'vehicle-row-running hover:bg-green-50';
                    break;
                case 'paused':
                    statusText = 'Tạm dừng';
                    statusClass = 'status-badge status-paused';
                    rowClass = 'vehicle-row-paused hover:bg-yellow-50';
                    break;
                case 'expired':
                    statusText = 'Hết giờ';
                    statusClass = 'status-badge status-expired';
                    rowClass = 'vehicle-row-expired hover:bg-red-50';
                    break;
                default:
                    statusText = 'Không xác định';
                    statusClass = 'status-badge';
                    rowClass = 'hover:bg-gray-50';
                    console.warn('Unknown vehicle status:', vehicle.status);
            }
            
            // Debug log
            console.log(`Vehicle ${vehicle.name} - Status: ${vehicle.status}, StatusText: ${statusText}, StatusClass: ${statusClass}`);

            // Create row
            const newRow = document.createElement('tr');
            newRow.className = rowClass + ' clickable-row';
            newRow.dataset.vehicleId = vehicle.id;
            newRow.dataset.endTime = endTime.getTime();
            newRow.dataset.status = vehicle.status;
            
            newRow.innerHTML = `
                <td class="px-3 py-2">
                    <input type="checkbox" class="vehicle-checkbox rounded border-gray-300 text-brand-600 focus:ring-brand-500" value="${vehicle.id}">
                </td>
                <td class="px-3 py-2 text-sm text-gray-900">${vehicle.name}</td>
                <td class="px-3 py-2">
                    <div class="w-4 h-4 rounded border border-gray-300" style="background-color: ${vehicle.color};" title="${vehicle.color}"></div>
                </td>
                <td class="px-3 py-2">
                    <span class="${statusClass}">${statusText}</span>
                </td>
                <td class="px-3 py-2 text-sm text-gray-900">
                    <div class="mobile-time-content">
                        <div class="mobile-start-time">${startTimeStr}</div>
                        <div class="mobile-end-time">${endTimeStr}</div>
                    </div>
                    <span class="desktop-start-time">${startTimeStr}</span>
                </td>
                <td class="px-3 py-2 text-sm text-gray-900">${endTimeStr}</td>
                <td class="px-3 py-2 text-sm text-gray-900">
                    <div class="countdown-display" data-end-time="${endTime.getTime()}" data-status="${vehicle.status}">
                        <span class="countdown-minutes">${remainingMinutes.toString().padStart(2, '0')}</span>:<span class="countdown-seconds">${remainingSeconds.toString().padStart(2, '0')}</span>
                    </div>
                </td>
            `;
            
            // Debug: Log the generated HTML for running vehicles
            if (vehicle.status === 'running') {
                console.log(`Generated HTML for running vehicle ${vehicle.name}:`, newRow.innerHTML);
            }

            // Add row to table
            timerTableBody.appendChild(newRow);
            
            // Start countdown timer only for running vehicles
            if (vehicle.status === 'running') {
                console.log(`Starting countdown timer for running vehicle: ${vehicle.name}`);
                this.startCountdownTimer(newRow);
            } else {
                console.log(`Not starting countdown for ${vehicle.status} vehicle: ${vehicle.name}`);
            }
        });

        console.log(`Đã load ${allVehicles.length} xe vào bảng timer (running: ${allVehicles.filter(v => v.status === 'running').length}, paused: ${allVehicles.filter(v => v.status === 'paused').length}, expired: ${allVehicles.filter(v => v.status === 'expired').length})`);
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
                    <td colspan="4" class="px-3 py-8 text-center text-gray-500">
                        Không có xe nào đang chờ
                    </td>
                </tr>
            `;
        }
    }

    /**
     * Show empty state for routing vehicles table
     */
    showEmptyRoutingState() {
        const routingTableBody = document.getElementById('routing-vehicles');
        if (routingTableBody) {
            routingTableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-3 py-8 text-center text-gray-500">
                        Không có xe nào đang theo đường
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
        // Check if instance already exists to prevent duplicates
        if (window.activeVehicles) {
            console.log('🔍 ActiveVehicles instance already exists, skipping creation');
            return;
        }
        
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
