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
        console.log(`${this.pageName} class initialized`);
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
        console.log(`${this.pageName} page initialized`);
    }

    /**
     * Initialize DOM elements
     */
    initializeElements() {
        this.vehicleCards = document.querySelectorAll('.vehicle-card');
        this.actionButtons = document.querySelectorAll('[data-action]');
        
        if (this.vehicleCards.length === 0) {
            console.log(`${this.pageName}: No vehicle cards found`);
            return;
        }
        
        console.log(`${this.pageName}: Found ${this.vehicleCards.length} vehicle cards`);
    }

    /**
     * Initialize event listeners for common actions
     */
    initializeEventListeners() {
        if (this.actionButtons.length === 0) return;
        
        this.actionButtons.forEach(button => {
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
            case 'start-timer':
                button.addEventListener('click', (e) => this.handleStartTimer(e));
                break;
            case 'pause-timer':
                button.addEventListener('click', (e) => this.handlePauseTimer(e));
                break;
            case 'resume-timer':
                button.addEventListener('click', (e) => this.handleResumeTimer(e));
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
        const timerElement = card.querySelector('.countdown-timer');
        if (!timerElement) return;

        const endTime = timerElement.dataset.endTime;
        if (!endTime) return;

        this.updateCountdown(timerElement, endTime);
        
        // Update every second
        setInterval(() => {
            this.updateCountdown(timerElement, endTime);
        }, 1000);
    }

    /**
     * Update countdown display
     */
    updateCountdown(timerElement, endTime) {
        const now = new Date().getTime();
        const end = new Date(endTime).getTime();
        const distance = end - now;

        if (distance < 0) {
            timerElement.textContent = 'Hết giờ';
            timerElement.classList.add('text-red-500');
            return;
        }

        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        timerElement.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }

    /**
     * Handle start timer action
     */
    handleStartTimer(e) {
        const vehicleId = e.target.dataset.vehicleId;
        const duration = parseInt(e.target.dataset.duration);
        
        if (!vehicleId || !duration) {
            console.error('Missing vehicle ID or duration');
            return;
        }

        if (confirm(`Bạn có chắc muốn bắt đầu bấm giờ cho xe ${vehicleId} trong ${duration} phút?`)) {
            this.startTimer(vehicleId, duration, e.target);
        }
    }

    /**
     * Handle pause timer action
     */
    handlePauseTimer(e) {
        const vehicleId = e.target.dataset.vehicleId;
        if (vehicleId) {
            this.pauseTimer(vehicleId, e.target);
        }
    }

    /**
     * Handle resume timer action
     */
    handleResumeTimer(e) {
        const vehicleId = e.target.dataset.vehicleId;
        if (vehicleId) {
            this.resumeTimer(vehicleId, e.target);
        }
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
     * Start timer for a vehicle
     */
    async startTimer(vehicleId, duration, button) {
        try {
            this.showButtonLoading(button, 'Đang bấm giờ...');
            
            const response = await this.makeApiCall('/api/vehicles/start-timer', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId],
                    duration: duration
                })
            });

            if (response.success) {
                this.showNotification('Bấm giờ thành công!', 'success');
                // Reload page to show updated status
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error starting timer:', error);
            this.showNotification('Có lỗi xảy ra khi bấm giờ', 'error');
        } finally {
            this.restoreButtonState(button);
        }
    }

    /**
     * Pause timer for a vehicle
     */
    async pauseTimer(vehicleId, button) {
        try {
            this.showButtonLoading(button, 'Đang tạm dừng...');
            
            const response = await this.makeApiCall('/api/vehicles/pause-timer', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId]
                })
            });

            if (response.success) {
                this.showNotification('Tạm dừng thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error pausing timer:', error);
            this.showNotification('Có lỗi xảy ra khi tạm dừng', 'error');
        } finally {
            this.restoreButtonState(button);
        }
    }

    /**
     * Resume timer for a vehicle
     */
    async resumeTimer(vehicleId, button) {
        try {
            this.showButtonLoading(button, 'Đang tiếp tục...');
            
            const response = await this.makeApiCall('/api/vehicles/resume-timer', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId]
                })
            });

            if (response.success) {
                this.showNotification('Tiếp tục thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error resuming timer:', error);
            this.showNotification('Có lỗi xảy ra khi tiếp tục', 'error');
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
                this.showNotification('Chuyển xưởng thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error moving to workshop:', error);
            this.showNotification('Có lỗi xảy ra khi chuyển xưởng', 'error');
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
                this.showNotification('Phân tuyến thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error assigning route:', error);
            this.showNotification('Có lỗi xảy ra khi phân tuyến', 'error');
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
        
        // 2. SHOW VISUAL NOTIFICATION
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;
        
        // Set background color based on type
        let bgColor, textColor, icon;
        switch (type) {
            case 'success':
                bgColor = 'bg-green-500';
                textColor = 'text-white';
                icon = '✅';
                break;
            case 'error':
                bgColor = 'bg-red-500';
                textColor = 'text-white';
                icon = '❌';
                break;
            case 'warning':
                bgColor = 'bg-yellow-500';
                textColor = 'text-white';
                icon = '⚠️';
                break;
            case 'info':
            default:
                bgColor = 'bg-blue-500';
                textColor = 'text-white';
                icon = 'ℹ️';
                break;
        }
        
        notification.className += ` ${bgColor} ${textColor}`;
        
        // Set content
        notification.innerHTML = `
            <div class="flex items-center">
                <span class="text-lg mr-2">${icon}</span>
                <span class="font-medium">${message}</span>
                <button class="ml-auto text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }
        }, 5000);
    }

    /**
     * Speak message using text-to-speech (Đọc thông báo)
     */
    speakMessage(message) {
        // Check if speech synthesis is available
        if ('speechSynthesis' in window) {
            // Stop any current speech
            window.speechSynthesis.cancel();
            
            // Create speech utterance
            const utterance = new SpeechSynthesisUtterance(message);
            
            // Set Vietnamese language and voice
            utterance.lang = 'vi-VN';
            utterance.rate = 0.9; // Slightly slower for clarity
            utterance.pitch = 1.0;
            utterance.volume = 0.8;
            
            // Try to find Vietnamese voice
            const voices = window.speechSynthesis.getVoices();
            const vietnameseVoice = voices.find(voice => 
                voice.lang.includes('vi') || 
                voice.lang.includes('VN') ||
                voice.name.toLowerCase().includes('vietnamese')
            );
            
            if (vietnameseVoice) {
                utterance.voice = vietnameseVoice;
            }
            
            // Speak the message
            window.speechSynthesis.speak(utterance);
            
            console.log('🔊 Đang đọc thông báo:', message);
        } else {
            console.log('❌ Text-to-speech không được hỗ trợ trên trình duyệt này');
        }
    }

    /**
     * Show success notification
     */
    showSuccess(message) {
        this.showNotification(message, 'success');
    }

    /**
     * Show error notification
     */
    showError(message) {
        this.showNotification(message, 'error');
    }

    /**
     * Show warning notification
     */
    showWarning(message) {
        this.showNotification(message, 'warning');
    }

    /**
     * Show info notification
     */
    showInfo(message) {
        this.showNotification(message, 'info');
    }

    /**
     * Show loading state on button
     */
    showButtonLoading(button, text) {
        button.dataset.originalText = button.textContent;
        button.textContent = text;
        button.disabled = true;
    }

    /**
     * Restore button to original state
     */
    restoreButtonState(button) {
        if (button.dataset.originalText) {
            button.textContent = button.dataset.originalText;
            delete button.dataset.originalText;
        }
        button.disabled = false;
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
}
