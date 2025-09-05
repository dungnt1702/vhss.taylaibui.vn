/**
 * VehicleBase - Base class for all vehicle-related functionality
 * Contains common methods and utilities used across all vehicle statuses
 */

export class VehicleBase {
    constructor(pageName) {
        this.pageName = pageName;
        this.vehicleCards = [];
        this.actionButtons = [];
        this.initialized = false;
        this.isSpeaking = false; // Track if a speech is currently in progress

    }

    /**
     * Initialize the vehicle page
     */
    init() {
        if (this.initialized) return;
        
        this.initializeElements();
        this.initializeEventListeners();
        this.initializeCountdownTimers();
        this.initialized = true;
    }

    /**
     * Initialize DOM elements
     */
    initializeElements() {
        this.vehicleCards = document.querySelectorAll('.vehicle-card');
        this.actionButtons = document.querySelectorAll('[data-action]');
        
        if (this.vehicleCards.length === 0) {
            return;
        }
    }

    /**
     * Initialize event listeners for common actions
     */
    initializeEventListeners() {
        if (this.actionButtons.length === 0) {
            return;
        }
        
        this.actionButtons.forEach((button, index) => {
            const action = button.dataset.action;
            if (action) {
                this.setupActionListener(button, action);
            }
        });
    }

    /**
     * Setup action listener for a specific button
     */
    setupActionListener(button, action) {
        switch (action) {
            case 'assign-timer':
                // assign-timer được xử lý bởi VehicleBase.js
                button.addEventListener('click', (e) => this.handleAssignTimer(e));
                break;
            case 'return-yard':
                button.addEventListener('click', (e) => this.handleReturnYard(e));
                break;
            case 'move-workshop':
                button.addEventListener('click', (e) => this.handleMoveWorkshop(e));
                break;
            case 'assign-route':
                button.addEventListener('click', (e) => this.handleAssignRoute(e));
                break;
            case 'close-notification':
                button.addEventListener('click', (e) => this.closeNotification(e));
                break;
            case 'toggle-vehicle':
                button.addEventListener('click', (e) => this.handleToggleVehicle(e));
                break;
            case 'add-time':
                button.addEventListener('click', (e) => this.handleAddTime(e));
                break;
            case 'open-workshop-modal':
                button.addEventListener('click', (e) => this.handleOpenWorkshopModal(e));
                break;
            default:
                // Unknown action - ignore
        }
    }

    /**
     * Initialize countdown timers for all vehicles
     */
    initializeCountdownTimers() {
        if (this.vehicleCards.length === 0) return;
        
        this.vehicleCards.forEach(card => {
            this.startCountdownTimer(card);
        });
    }

    /**
     * Start countdown timer for a specific vehicle card
     */
    startCountdownTimer(card) {
        // Tìm countdown element bằng id hoặc class
        const vehicleId = card.dataset.vehicleId;
        const timerElement = card.querySelector(`#countdown-${vehicleId}`) || card.querySelector('.countdown-display');
        
        if (!timerElement) {
            return;
        }

        // Lấy end time từ data attribute của vehicle card
        const endTime = card.dataset.endTime;
        if (!endTime) {
            return;
        }

        // Kiểm tra vehicle status - chỉ start countdown cho running vehicles
        const vehicleStatus = card.dataset.status;
        if (vehicleStatus !== 'running') {
            // Với paused vehicles, hiển thị thời gian còn lại từ paused_remaining_seconds
            if (vehicleStatus === 'paused') {
                this.displayPausedTime(timerElement, card);
            }
            
            // Với expired vehicles, hiển thị 00:00
            if (vehicleStatus === 'expired') {
                this.displayExpiredTime(timerElement);
            }
            
            return;
        }

        // Update ngay lập tức
        this.updateCountdown(timerElement, endTime);
        
        // Update every second
        const intervalId = setInterval(() => {
            // Always use the latest end time from dataset
            const currentEndTime = card.dataset.endTime;
            if (currentEndTime) {
                this.updateCountdown(timerElement, currentEndTime);
            }
        }, 1000);

        // Lưu interval ID để có thể clear sau này
        card.dataset.countdownInterval = intervalId;
    }

    /**
     * Display paused time (static, not counting down)
     */
    displayPausedTime(timerElement, card) {
        const pausedRemainingSeconds = card.dataset.pausedRemainingSeconds;
        if (pausedRemainingSeconds) {
            const minutes = Math.floor(pausedRemainingSeconds / 60);
            const seconds = pausedRemainingSeconds % 60;
            
            const minutesElement = timerElement.querySelector('.countdown-minutes');
            const secondsElement = timerElement.querySelector('.countdown-seconds');
            
            if (minutesElement) {
                minutesElement.textContent = minutes.toString().padStart(2, '0');
            }
            if (secondsElement) {
                secondsElement.textContent = seconds.toString().padStart(2, '0');
            }
            
            // Thêm class xám cho paused vehicles
            timerElement.classList.add('text-gray-500');
        }
    }

    /**
     * Display expired time (00:00)
     */
    displayExpiredTime(timerElement) {
        const minutesElement = timerElement.querySelector('.countdown-minutes');
        const secondsElement = timerElement.querySelector('.countdown-seconds');
        
        if (minutesElement) minutesElement.textContent = '00';
        if (secondsElement) secondsElement.textContent = '00';
        
        // Thêm class đỏ cho expired vehicles
        timerElement.classList.add('text-red-500');
    }

    /**
     * Update countdown display
     */
    updateCountdown(timerElement, endTime) {
        const now = new Date().getTime();
        const end = parseInt(endTime); // endTime đã là timestamp từ data attribute
        
        if (isNaN(end)) {
            return;
        }

        const distance = end - now;

        if (distance < 0) {
            // Hết giờ - cập nhật cả minutes và seconds
            const minutesElement = timerElement.querySelector('.countdown-minutes');
            const secondsElement = timerElement.querySelector('.countdown-seconds');
            
            if (minutesElement) minutesElement.textContent = '00';
            if (secondsElement) secondsElement.textContent = '00';
            
            // Thêm class đỏ cho hết giờ
            timerElement.classList.add('text-red-500');
            return;
        }

        // Tính toán thời gian còn lại
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Debug: Log thời gian mỗi lần update (chỉ log mỗi 10 giây để tránh spam)
        const debugInterval = 10000; // 10 giây
        if (distance % debugInterval < 1000) {
            console.log('Countdown update:', {
                endTime: end,
                now: now,
                distance: distance,
                minutes: minutes,
                seconds: seconds,
                totalMinutes: Math.floor(distance / (1000 * 60))
            });
        }

        // Cập nhật từng phần tử riêng biệt
        const minutesElement = timerElement.querySelector('.countdown-minutes');
        const secondsElement = timerElement.querySelector('.countdown-seconds');
        
        if (minutesElement) {
            minutesElement.textContent = minutes.toString().padStart(2, '0');
        }
        
        if (secondsElement) {
            secondsElement.textContent = seconds.toString().padStart(2, '0');
        }
    }

    /**
     * Handle assign timer action
     */
    handleAssignTimer(e) {
        const vehicleId = e.target.dataset.vehicleId;
        const duration = parseInt(e.target.dataset.duration);
        
        if (!vehicleId || !duration) {
            return;
        }

        // Bỏ confirm dialog, gọi trực tiếp assignTimer
        console.log(`Assigning timer for vehicle ${vehicleId} with duration ${duration} minutes`);
        this.assignTimer(vehicleId, duration, e.target);
    }



    /**
     * Handle return yard action
     */
    handleReturnYard(e) {
        const vehicleId = e.target.dataset.vehicleId;
        if (vehicleId) {
            this.returnToYard(vehicleId, e.target);
        }
    }

    /**
     * Handle move workshop action
     */
    handleMoveWorkshop(e) {
        const vehicleId = e.target.dataset.vehicleId;
        if (vehicleId) {
            this.moveToWorkshop(vehicleId, e.target);
        }
    }

    /**
     * Handle assign route action
     */
    handleAssignRoute(e) {
        const vehicleId = e.target.dataset.vehicleId;
        if (vehicleId) {
            this.assignRoute(vehicleId, e.target);
        }
    }

    /**
     * Handle toggle vehicle action
     */
    handleToggleVehicle(e) {
        const vehicleId = e.target.dataset.vehicleId;
        if (vehicleId) {
            console.log('Toggle vehicle content for vehicle:', vehicleId);
            // This is handled by app.js toggleVehicleSimple function
            if (window.toggleVehicleSimple) {
                window.toggleVehicleSimple(vehicleId);
            }
        }
    }

    /**
     * Handle resume vehicle action
     */
    handleResumeVehicle(e) {
        const vehicleId = e.target.dataset.vehicleId;
        if (vehicleId) {
            console.log('Resuming vehicle:', vehicleId);
            // TODO: Implement resume logic
        }
    }

    /**
     * Handle add time action
     */
    handleAddTime(e) {
        const vehicleId = e.target.dataset.vehicleId;
        const duration = parseInt(e.target.dataset.duration);
        if (vehicleId && duration) {
            this.addTime(vehicleId, duration, e.target);
        }
    }

    /**
     * Add time to vehicle timer - handles both running and expired vehicles
     */
        async addTime(vehicleId, duration, button) {
        // Prevent duplicate requests - check both disabled state and data attribute
        if (button.disabled || button.dataset.processing === 'true') {
            console.log('Button already processing, skipping duplicate request');
            return;
        }
        
        try {
            console.log(`=== ADD TIME REQUEST ===`);
            console.log('Vehicle ID:', vehicleId);
            console.log('Duration:', duration);
            console.log('Button element:', button);
            console.log('Button disabled state:', button.disabled);
            console.log('Button processing state:', button.dataset.processing);
            
            // Mark button as processing BEFORE calling showButtonLoading
            button.dataset.processing = 'true';
            console.log('Button marked as processing:', button.dataset.processing);
            
            // Double-check button state
            console.log('Button state after marking processing:', {
                disabled: button.disabled,
                processing: button.dataset.processing
            });
            
            // Set button loading state manually to avoid conflicts
            button.dataset.originalText = button.innerHTML;
            button.innerHTML = `Đang thêm ${duration} phút...`;
            button.disabled = true;
            
            console.log('Button loading state set manually:', {
                disabled: button.disabled,
                processing: button.dataset.processing,
                text: button.innerHTML
            });
            
            // Final check before API call
            console.log('Final button state before API call:', {
                disabled: button.disabled,
                processing: button.dataset.processing,
                text: button.innerHTML
            });

            const response = await this.makeApiCall('/api/vehicles/add-time', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId],
                    duration: duration
                })
            });

            if (response.success) {
                this.showNotificationModal('Thành công', `Đã thêm ${duration} phút thành công!`, 'success');
                
                // Xử lý khác nhau cho từng loại xe
                const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
                if (vehicleCard) {
                    const vehicleStatus = vehicleCard.dataset.status;
                    console.log('=== ADD TIME RESPONSE ===');
                    console.log('Vehicle status:', vehicleStatus);
                    console.log('Request duration:', duration, 'minutes');
                    console.log('Response:', response);
                    console.log('Current end time in dataset:', vehicleCard.dataset.endTime);
                    
                    if (vehicleStatus === 'running') {
                        // Xe running sẽ được xử lý bởi RunningVehicles.js
                        console.log('Running vehicle - should be handled by RunningVehicles.js');
                    } else if (vehicleStatus === 'expired') {
                        // Xe expired sẽ được xử lý bởi ExpiredVehicles.js
                        console.log('Expired vehicle - should be handled by ExpiredVehicles.js');
                    } else if (vehicleStatus === 'waiting') {
                        // Xe waiting sẽ được xử lý bởi WaitingVehicles.js
                        console.log('Waiting vehicle - should be handled by WaitingVehicles.js');
                    } else {
                        console.log('Unknown vehicle status:', vehicleStatus);
                    }
                } else {
                    console.log('Vehicle card not found for ID:', vehicleId);
                }
                
            } else {
                this.showNotificationModal('Lỗi', response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error adding time:', error);
            this.showNotificationModal('Lỗi', 'Có lỗi xảy ra khi thêm thời gian', 'error');
        } finally {
            // Restore button state manually to avoid conflicts
            if (button.dataset.originalText) {
                button.innerHTML = button.dataset.originalText;
                delete button.dataset.originalText;
            }
            button.disabled = false;
            delete button.dataset.processing;
            
            console.log('Button state restored manually:', {
                disabled: button.disabled,
                processing: button.dataset.processing,
                text: button.innerHTML
            });
        }
    }





    /**
     * Handle pause vehicle action
     */
    handlePauseVehicle(e) {
        const vehicleId = e.target.dataset.vehicleId;
        if (vehicleId) {
            console.log('Pausing vehicle:', vehicleId);
            // TODO: Implement pause logic
        }
    }

    /**
     * Handle open workshop modal action
     */
    handleOpenWorkshopModal(e) {
        const vehicleId = e.target.dataset.vehicleId;
        if (vehicleId) {
            console.log('Opening workshop modal for vehicle:', vehicleId);
            // TODO: Implement workshop modal logic
        }
    }



    /**
     * Hide vehicle card after successful action
     */
    hideVehicleCard(vehicleId) {
        // Clear countdown interval trước khi ẩn card
        this.clearCountdownInterval(vehicleId);
        
        const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
        if (vehicleCard) {
            // Animate out
            vehicleCard.style.transition = 'all 0.3s ease';
            vehicleCard.style.transform = 'scale(0.8)';
            vehicleCard.style.opacity = '0';
            
            // Remove after animation
            setTimeout(() => {
                vehicleCard.remove();
                
                // Check if no more vehicles
                const remainingCards = document.querySelectorAll('.vehicle-card');
                if (remainingCards.length === 0) {
                    this.showEmptyState();
                }
            }, 300);
        }
    }

    /**
     * Show empty state when no vehicles remain
     */
    showEmptyState() {
        const vehicleList = document.getElementById('vehicle-list');
        if (vehicleList) {
            vehicleList.innerHTML = `
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-neutral-900">Không có xe nào</h3>
                        <p class="mt-1 text-sm text-neutral-500">
                            Hiện tại không có xe nào đang chờ.
                        </p>
                    </div>
                </div>
            `;
        }
    }

    /**
     * Handle assign timer action
     */
    handleAssignTimer(e) {
        const button = e.target;
        const vehicleId = button.dataset.vehicleId;
        const duration = parseInt(button.dataset.duration);
        
        // Nếu có vehicleId và duration trực tiếp trên button (single vehicle action)
        if (vehicleId && duration) {
            this.assignTimer(vehicleId, duration, button);
            return;
        }
        
        // Nếu không có vehicleId (bulk action - cần chọn xe từ bảng)
        const selectedVehicles = this.getSelectedVehicles();
        
        if (selectedVehicles.length === 0) {
            this.showNotificationModal('Cảnh báo', 'Bạn phải chọn xe trước!', 'warning');
            return;
        }

        // Lấy duration từ select box
        const timeSelect = document.getElementById('time-select');
        const durationFromSelect = parseInt(timeSelect.value);
        
        if (durationFromSelect) {
            // Gọi method từ ReadyVehicles nếu có
            if (this.assignTimerBulk && typeof this.assignTimerBulk === 'function') {
                this.assignTimerBulk(durationFromSelect);
            }
        } else {
            this.showNotificationModal('Cảnh báo', 'Vui lòng chọn thời gian.', 'warning');
        }
    }

    /**
     * Handle custom actions - can be overridden by child classes
     */
    handleCustomAction(action, vehicleId, button) {
        console.log(`VehicleBase: Handling custom action: ${action} for vehicle: ${vehicleId}`);
        
        switch (action) {
            case 'assign-timer':
                this.handleAssignTimer({ target: button });
                break;
            case 'pause-timer':
                this.handlePauseTimer({ target: button });
                break;
            case 'resume-timer':
                this.handleResumeTimer({ target: button });
                break;
            case 'return-yard':
                this.handleReturnYard({ target: button });
                break;
            case 'move-workshop':
                this.handleMoveWorkshop({ target: button });
                break;
            case 'assign-route':
                this.handleAssignRoute({ target: button });
                break;
            case 'toggle-vehicle':
                this.handleToggleVehicle({ target: button });
                break;
            case 'resume-vehicle':
                this.handleResumeVehicle({ target: button });
                break;
            case 'add-time':
                this.handleAddTime({ target: button });
                break;
            case 'pause-vehicle':
                this.handlePauseVehicle({ target: button });
                break;
            case 'open-workshop-modal':
                this.handleOpenWorkshopModal({ target: button });
                break;
            default:
                console.log(`VehicleBase: Unknown action: ${action}`);
        }
    }

    /**
     * Assign timer for a vehicle
     */
    async assignTimer(vehicleId, duration, button) {
        try {
            console.log(`Assigning timer: vehicleId=${vehicleId}, duration=${duration}`);
            this.showButtonLoading(button, 'Đang bấm giờ...');
            
            const requestBody = {
                vehicle_ids: [vehicleId],
                duration: duration
            };
            console.log('Request body:', requestBody);
            
            const response = await this.makeApiCall('/api/vehicles/start-timer', {
                method: 'POST',
                body: JSON.stringify(requestBody)
            });

            console.log('API response:', response);

            if (response.success) {
                this.showNotificationModal('Thành công', 'Bấm giờ thành công!', 'success');
                // Không reload page, chỉ ẩn vehicle card
                this.hideVehicleCard(vehicleId);
            } else {
                this.showNotificationModal('Lỗi', response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error assigning timer:', error);
            this.showNotificationModal('Lỗi', `Có lỗi xảy ra khi bấm giờ: ${error.message}`, 'error');
        } finally {
            this.restoreButtonState(button);
        }
    }

    /**
     * Assign timer for multiple vehicles (bulk action)
     */
    async assignTimerBulk(vehicleIds, duration, button) {
        try {
            if (!Array.isArray(vehicleIds) || vehicleIds.length === 0) {
                this.showWarning('Không có xe nào được chọn để bấm giờ');
                return;
            }

            this.showButtonLoading(button, `Đang bấm giờ ${vehicleIds.length} xe...`);
            
            const response = await this.makeApiCall('/api/vehicles/start-timer', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: vehicleIds,
                    duration: duration
                })
            });

            if (response.success) {
                this.showNotificationModal('Thành công', `Đã bấm giờ thành công cho ${vehicleIds.length} xe!`, 'success');
                
                // Không reload page, ẩn tất cả vehicle cards
                vehicleIds.forEach(id => this.hideVehicleCard(id));
                
                // Trả về response để child class có thể xử lý thêm
                return response;
            } else {
                this.showNotificationModal('Lỗi', response.message || 'Có lỗi xảy ra', 'error');
                return response;
            }
        } catch (error) {
            console.error('Error assigning timer for multiple vehicles:', error);
            this.showNotificationModal('Lỗi', 'Có lỗi xảy ra khi bấm giờ', 'error');
            return { success: false, message: 'Có lỗi xảy ra khi bấm giờ' };
        } finally {
            this.restoreButtonState(button);
        }
    }



    /**
     * Return vehicle(s) to yard (handles both single and multiple vehicles)
     */
    async returnToYard(vehicleIds, button) {
        try {
            // Convert single ID to array if needed
            const ids = Array.isArray(vehicleIds) ? vehicleIds : [vehicleIds];
            
            if (ids.length === 0) {
                this.showWarning('Không có xe nào được chọn để đưa về bãi');
                return;
            }

            // Show loading state
            if (button) {
                this.showButtonLoading(button, 'Đang xử lý...');
            }
            
            const response = await this.makeApiCall('/api/vehicles/return-yard', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: ids
                })
            });

            if (response.success) {
                if (ids.length === 1) {
                    this.showSuccess('Đã đưa xe về bãi thành công!');
                } else {
                    this.showSuccess(`Đã đưa ${ids.length} xe về bãi thành công!`);
                }
                
                // Hide vehicle cards instead of reloading page
                this.hideVehicleCards(ids);
                
            } else {
                // Show error if needed
                this.showError(response.message || 'Có lỗi xảy ra khi đưa xe về bãi');
                if (button) {
                    this.restoreButtonState(button);
                    button.textContent = 'Lỗi - Thử lại';
                    setTimeout(() => {
                        this.restoreButtonState(button);
                    }, 2000);
                }
            }
        } catch (error) {
            console.error('Error returning vehicle(s) to yard:', error);
            this.showError('Có lỗi xảy ra khi đưa xe về bãi');
            if (button) {
                this.restoreButtonState(button);
                button.textContent = 'Lỗi - Thử lại';
                setTimeout(() => {
            this.restoreButtonState(button);
                }, 2000);
            }
        }
    }


    /**
     * Hide vehicle cards after successful return to yard
     */
    hideVehicleCards(vehicleIds) {
        vehicleIds.forEach(vehicleId => {
            // Find vehicle card by data-vehicle-id
            const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
            if (vehicleCard) {
                // Add fade-out animation
                vehicleCard.style.transition = 'all 0.5s ease';
                vehicleCard.style.opacity = '0';
                vehicleCard.style.transform = 'scale(0.95)';
                
                // Remove card after animation
                setTimeout(() => {
                    if (vehicleCard.parentElement) {
                        vehicleCard.remove();
                        
                        // Check if no more vehicles
                        const remainingCards = document.querySelectorAll('[data-vehicle-id]');
                        if (remainingCards.length === 0) {
                            this.showEmptyState();
                        }
                    }
                }, 500);
            }
        });
    }

    /**
     * Show empty state when no vehicles remain
     */
    showEmptyState() {
        const vehicleList = document.getElementById('vehicle-list');
        if (vehicleList) {
            vehicleList.innerHTML = `
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-neutral-900">Không có xe nào</h3>
                        <p class="mt-1 text-sm text-neutral-500">
                            Tất cả xe đã được đưa về bãi.
                        </p>
                    </div>
                </div>
            `;
        }
    }

    /**
     * Clear countdown intervals for a specific vehicle
     */
    clearCountdownInterval(vehicleId) {
        const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
        if (vehicleCard && vehicleCard.dataset.countdownInterval) {
            clearInterval(parseInt(vehicleCard.dataset.countdownInterval));
            delete vehicleCard.dataset.countdownInterval;
            console.log(`Cleared countdown interval for vehicle ${vehicleId}`);
        }
    }

    /**
     * Clear all countdown intervals
     */
    clearAllCountdownIntervals() {
        const vehicleCards = document.querySelectorAll('.vehicle-card');
        vehicleCards.forEach(card => {
            if (card.dataset.countdownInterval) {
                clearInterval(parseInt(card.dataset.countdownInterval));
                delete card.dataset.countdownInterval;
            }
        });
        console.log('Cleared all countdown intervals');
    }

    /**
     * Move vehicle to workshop
     */
    async moveToWorkshop(vehicleId, button) {
        try {
            this.showButtonLoading(button, 'Đang chuyển xưởng...');
            
            const response = await this.makeApiCall('/api/vehicles/move-workshop', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId]
                })
            });

            if (response.success) {
                this.showNotificationModal('Thành công', 'Chuyển xưởng thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotificationModal('Lỗi', response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error moving to workshop:', error);
            this.showNotificationModal('Lỗi', 'Có lỗi xảy ra khi chuyển xưởng', 'error');
        } finally {
            this.restoreButtonState(button);
        }
    }

    /**
     * Assign route to vehicle
     */
    async assignRoute(vehicleId, button) {
        try {
            this.showButtonLoading(button, 'Đang phân tuyến...');
            
            const response = await this.makeApiCall('/api/vehicles/assign-route', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId]
                })
            });

            if (response.success) {
                this.showNotificationModal('Thành công', 'Phân tuyến thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotificationModal('Lỗi', response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error assigning route:', error);
            this.showNotificationModal('Lỗi', 'Có lỗi xảy ra khi phân tuyến', 'error');
        } finally {
            this.restoreButtonState(button);
        }
    }

    /**
     * Make API call with error handling
     */
    async makeApiCall(url, options = {}) {
        try {
            const response = await fetch(url, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    ...options.headers
                },
                ...options
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('API call failed:', error);
            throw error;
        }
    }

    /**
     * Show notification to user with Vietnamese messages and text-to-speech
     */
    showNotification(message, type = 'info') {
        // 1. SPEAK THE MESSAGE (Text-to-Speech)
        this.speakMessage(message);
        
        // DEPRECATED: Redirect to showNotificationModal
        console.warn('showNotification is deprecated, use showNotificationModal instead');
        
        // Chuyển đổi type để tương thích
        let modalType = 'info';
        switch (type) {
            case 'success': modalType = 'success'; break;
            case 'error': modalType = 'error'; break;
            case 'warning': modalType = 'warning'; break;
            default: modalType = 'info';
        }
        
        // Gọi showNotificationModal thay vì tạo toast
        this.showNotificationModal('Thông báo', message, modalType);
    }

    /**
     * Speak message using text-to-speech
     */
    speakMessage(message) {
        console.log('🔊 speakMessage được gọi với message:', message);
        
        // Check if speech synthesis is available
        if ('speechSynthesis' in window) {
            console.log('✅ Speech synthesis được hỗ trợ');
            
            // Prevent multiple calls to the same message
            if (this.isSpeaking) {
                console.log('⏸️ Đang đọc rồi, bỏ qua lần gọi này');
                return;
            }
            
            this.isSpeaking = true;
            let hasSpoken = false; // Track if we've already spoken
            
            // Wait for voices to be loaded (fix for Chrome)
            const speakWithVoices = () => {
                // Prevent multiple executions
                if (hasSpoken) {
                    console.log('🔄 Đã đọc rồi, bỏ qua lần gọi này');
                    return;
                }
                
                hasSpoken = true;
                
                const voices = window.speechSynthesis.getVoices();
                console.log('🎤 Tổng số voices có sẵn:', voices.length);
                
                // Stop any current speech
                window.speechSynthesis.cancel();
                console.log('🛑 Đã dừng speech cũ');
                
                // Create speech utterance
                const utterance = new SpeechSynthesisUtterance(message);
                console.log('📝 Đã tạo utterance:', utterance);
                
                // Set Vietnamese language and voice
                utterance.lang = 'vi-VN';
                utterance.rate = 0.9;
                utterance.pitch = 1.0;
                utterance.volume = 0.8;
                console.log('🌏 Đã set language:', utterance.lang);
                
                const vietnameseVoice = voices.find(voice => 
                    voice.lang.includes('vi') || 
                    voice.lang.includes('VN') || 
                    voice.name.toLowerCase().includes('vietnamese')
                );
                
                if (vietnameseVoice) {
                    utterance.voice = vietnameseVoice;
                    console.log('🇻🇳 Đã tìm thấy voice tiếng Việt:', vietnameseVoice.name);
                } else {
                    console.log('🌍 Không tìm thấy voice tiếng Việt, sử dụng voice mặc định');
                }
                
                // Add event listeners for debugging
                utterance.onstart = () => {
                    console.log('🎬 Bắt đầu đọc thông báo');
                };
                
                utterance.onend = () => {
                    console.log('✅ Đã đọc xong thông báo');
                    this.isSpeaking = false;
                };
                
                utterance.onerror = (event) => {
                    console.error('❌ Lỗi khi đọc:', event.error);
                    this.isSpeaking = false;
                };
                
                // Speak the message
                window.speechSynthesis.speak(utterance);
                console.log('🔊 Đã gọi speak()');
            };
            
            // Check if voices are already loaded
            if (window.speechSynthesis.getVoices().length > 0) {
                speakWithVoices();
            } else {
                // Wait for voices to load (Chrome issue)
                console.log('⏳ Đang chờ voices load...');
                window.speechSynthesis.onvoiceschanged = () => {
                    console.log('🎯 Voices đã load xong, bắt đầu đọc');
                    speakWithVoices();
                };
                
                // Fallback: try to speak anyway after a short delay
                setTimeout(() => {
                    if (window.speechSynthesis.getVoices().length > 0 && this.isSpeaking && !hasSpoken) {
                        console.log('⏰ Fallback: voices đã sẵn sàng');
                        speakWithVoices();
                    } else if (this.isSpeaking && !hasSpoken) {
                        console.log('❌ Không thể load voices sau timeout');
                        this.isSpeaking = false;
                    } else {
                        console.log('⏸️ Fallback: đã đọc rồi hoặc không còn active');
                    }
                }, 1000);
            }
            
        } else {
            console.log('❌ Text-to-speech không được hỗ trợ trên trình duyệt này');
        }
    }

    /**
     * Show success notification
     */
    showSuccess(message) {
        this.showNotificationModal('Thành công', message, 'success');
    }

    /**
     * Show error notification
     */
    showError(message) {
        this.showNotificationModal('Lỗi', message, 'error');
    }

    /**
     * Show warning notification
     */
    showWarning(message) {
        this.showNotificationModal('Cảnh báo', message, 'warning');
    }

    /**
     * Show info notification
     */
    showInfo(message) {
        this.showNotificationModal('Thông báo', message, 'info');
    }

    /**
     * Show loading state on button
     */
    showButtonLoading(button, text) {
        if (!button) return;
        
        button.dataset.originalText = button.textContent;
        button.textContent = text;
        button.disabled = true;
        
        // Don't set processing here - it's already set in addTime
        // button.dataset.processing = 'true';
        
        console.log('Button loading state set:', {
            disabled: button.disabled,
            processing: button.dataset.processing,
            text: button.textContent
        });
    }

    /**
     * Restore button to original state
     */
    restoreButtonState(button) {
        if (!button) return;
        
        if (button.dataset.originalText) {
            button.textContent = button.dataset.originalText;
            delete button.dataset.originalText;
        }
        button.disabled = false;
        
        // Clear processing state
        delete button.dataset.processing;
        
        console.log('Button state restored:', {
            disabled: button.disabled,
            processing: button.dataset.processing,
            text: button.textContent
        });
    }

    /**
     * Close notification
     */
    closeNotification(event) {
        const notification = event.target.closest('.notification');
        if (notification) {
            notification.remove();
        }
    }

    /**
     * Show notification modal
     */
    showNotificationModal(title, message, type = 'info') {
        const modal = document.getElementById('notification-modal');
        const modalTitle = document.getElementById('notification-title');
        const modalMessage = document.getElementById('notification-message');
        const iconContainer = document.getElementById('notification-icon-container');
        const icon = document.getElementById('notification-icon');
        const closeBtn = document.getElementById('notification-close-btn');

        if (!modal || !modalTitle || !modalMessage || !iconContainer || !icon || !closeBtn) {
            console.error('Notification modal elements not found');
            return;
        }

        // Set title and message
        modalTitle.textContent = title;
        modalMessage.textContent = message;

        // Setup close button event listener
        closeBtn.onclick = () => this.closeNotificationModal();

        // Set icon and color based on type
        let iconSvg = '';
        let iconColor = 'text-brand-600';
        let bgColor = 'bg-brand-100';

        switch (type) {
            case 'success':
                iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />';
                iconColor = 'text-green-600';
                bgColor = 'bg-green-100';
                break;
            case 'error':
                iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
                iconColor = 'text-red-600';
                bgColor = 'bg-red-100';
                break;
            case 'warning':
                iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />';
                iconColor = 'text-yellow-600';
                bgColor = 'bg-yellow-100';
                break;
            default:
                iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />';
                iconColor = 'text-blue-600';
                bgColor = 'bg-blue-100';
        }

        // Update icon and colors
        icon.innerHTML = iconSvg;
        icon.setAttribute('class', `h-6 w-6 ${iconColor}`);
        iconContainer.setAttribute('class', `mx-auto flex items-center justify-center h-12 w-12 rounded-full ${bgColor}`);

        // Show modal
        modal.classList.remove('hidden');
    }

    /**
     * Close notification modal
     */
    closeNotificationModal() {
        const modal = document.getElementById('notification-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    /**
     * Get selected vehicles
     */
    getSelectedVehicles() {
        const checkboxes = document.querySelectorAll('.vehicle-checkbox:checked');
        return Array.from(checkboxes).map(cb => cb.value);
    }

    /**
     * Setup select all functionality
     */
    setupSelectAll(containerId, checkboxClass) {
        const selectAll = document.getElementById(`select-all-${containerId}`);
        if (!selectAll) return;

        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll(`.${checkboxClass}`);
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
}

// Make VehicleBase available globally for backward compatibility
if (typeof window !== 'undefined') {
    window.VehicleBase = VehicleBase;
    
    // Add global test functions for debugging
    window.testSpeech = () => {
        if (window.vehicleBase) {
            window.vehicleBase.testSpeech();
        }
    };
    
    window.testSpeak = (message) => {
        if (window.vehicleBase) {
            window.vehicleBase.speakMessage(message || 'Test message tiếng Việt');
        }
    };
    

}
