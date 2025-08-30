/**
 * ReadyVehicles - Class for managing ready vehicles
 * Extends VehicleBase with ready-specific functionality
 */

import { VehicleBase } from './VehicleBase.js';

class ReadyVehicles extends VehicleBase {
    constructor() {
        super('Ready Vehicles');
        this.durationOptions = [15, 30, 45, 60, 90, 120];
        this.bulkActions = [];
    }

    /**
     * Initialize ready vehicles page
     */
    init() {
        super.init();
        this.setupReadySpecificFeatures();
        this.setupBulkActions();
        console.log('Ready Vehicles page fully initialized');
    }

    /**
     * Setup ready-specific features
     */
    setupReadySpecificFeatures() {
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
                    this.startTimer(vehicleId, duration, e.target);
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
        this.setupSelectAll('ready', 'vehicle-checkbox');
        this.setupSelectAll('timer', 'vehicle-checkbox');
    }

    /**
     * Setup bulk actions
     */
    setupBulkActions() {
        this.setupBulkStartTimer();
        this.setupBulkRouteAssignment();
        this.setupBulkWorkshopTransfer();
    }

    /**
     * Setup bulk start timer
     */
    setupBulkStartTimer() {
        const bulkStartButtons = document.querySelectorAll('[data-action="start-timer-bulk"]');
        bulkStartButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const duration = parseInt(e.target.dataset.duration);
                if (duration) {
                    this.startTimerBulk(duration);
                }
            });
        });
    }

    /**
     * Setup bulk route assignment
     */
    setupBulkRouteAssignment() {
        const bulkRouteButtons = document.querySelectorAll('[data-action="assign-route-bulk"]');
        bulkRouteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                this.assignRouteBulk();
            });
        });
    }

    /**
     * Setup bulk workshop transfer
     */
    setupBulkWorkshopTransfer() {
        const bulkWorkshopButtons = document.querySelectorAll('[data-action="move-workshop-bulk"]');
        bulkWorkshopButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                this.moveToWorkshopBulk();
            });
        });
    }

    /**
     * Start timer for multiple vehicles
     */
    async startTimerBulk(duration) {
        const selectedVehicles = this.getSelectedVehicles();
        if (selectedVehicles.length === 0) {
            this.showNotification('Vui lòng chọn ít nhất một xe.', 'warning');
            return;
        }

        if (confirm(`Bạn có chắc muốn bắt đầu bấm giờ cho ${selectedVehicles.length} xe trong ${duration} phút?`)) {
            try {
                const response = await this.makeApiCall('/api/vehicles/start-timer', {
                    method: 'POST',
                    body: JSON.stringify({
                        vehicle_ids: selectedVehicles,
                        duration: duration
                    })
                });

                if (response.success) {
                    this.showNotification(`Bấm giờ thành công cho ${selectedVehicles.length} xe!`, 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
                }
            } catch (error) {
                console.error('Error starting bulk timer:', error);
                this.showNotification('Có lỗi xảy ra khi bấm giờ hàng loạt', 'error');
            }
        }
    }

    /**
     * Assign route to multiple vehicles
     */
    async assignRouteBulk() {
        const selectedVehicles = this.getSelectedVehicles();
        if (selectedVehicles.length === 0) {
            this.showNotification('Vui lòng chọn ít nhất một xe.', 'warning');
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
                    this.showNotification(`Phân tuyến thành công cho ${selectedVehicles.length} xe!`, 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
                }
            } catch (error) {
                console.error('Error assigning bulk route:', error);
                this.showNotification('Có lỗi xảy ra khi phân tuyến hàng loạt', 'error');
            }
        }
    }

    /**
     * Move multiple vehicles to workshop
     */
    async moveToWorkshopBulk() {
        const selectedVehicles = this.getSelectedVehicles();
        if (selectedVehicles.length === 0) {
            this.showNotification('Vui lòng chọn ít nhất một xe.', 'warning');
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
                    this.showNotification(`Chuyển xưởng thành công cho ${selectedVehicles.length} xe!`, 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
                }
            } catch (error) {
                console.error('Error moving bulk to workshop:', error);
                this.showNotification('Có lỗi xảy ra khi chuyển xưởng hàng loạt', 'error');
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
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Ready Vehicles page loaded');
    
    // Create and initialize ReadyVehicles instance
    const readyVehicles = new ReadyVehicles();
    readyVehicles.init();
    
    // Make it available globally for debugging
    window.readyVehicles = readyVehicles;
});

// Export for ES6 modules
export default ReadyVehicles;
