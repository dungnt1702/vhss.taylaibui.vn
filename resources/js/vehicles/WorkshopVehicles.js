/**
 * WorkshopVehicles - Class for managing workshop vehicles
 * Extends VehicleBase with workshop-specific functionality
 */

import { VehicleBase } from './VehicleBase.js';

class WorkshopVehicles extends VehicleBase {
    constructor() {
        super('Workshop Vehicles');
    }

    /**
     * Initialize workshop vehicles page
     */
    init() {
        super.init();
        this.setupWorkshopSpecificFeatures();
        console.log('Workshop Vehicles page fully initialized');
    }

    /**
     * Setup workshop-specific features
     */
    setupWorkshopSpecificFeatures() {
        this.setupReturnToYard();
        this.setupEditVehicle();
        this.setupWorkshopActions();
    }

    /**
     * Setup return to yard functionality
     */
    setupReturnToYard() {
        const returnButtons = document.querySelectorAll('[data-action="return-yard"]');
        returnButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const vehicleId = e.target.dataset.vehicleId;
                if (vehicleId) {
                    this.returnToYard(vehicleId, e.target);
                }
            });
        });
    }

    /**
     * Setup edit vehicle functionality
     */
    setupEditVehicle() {
        const editButtons = document.querySelectorAll('[data-action="edit-vehicle"]');
        editButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const vehicleId = e.target.dataset.vehicleId;
                if (vehicleId) {
                    this.editVehicle(vehicleId);
                }
            });
        });
    }

    /**
     * Setup workshop-specific actions
     */
    setupWorkshopActions() {
        // Add any workshop-specific action buttons here
        const workshopActionButtons = document.querySelectorAll('[data-action="workshop-action"]');
        workshopActionButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const action = e.target.dataset.workshopAction;
                const vehicleId = e.target.dataset.vehicleId;
                if (action && vehicleId) {
                    this.handleWorkshopAction(action, vehicleId, e.target);
                }
            });
        });
    }

    /**
     * Handle workshop-specific actions
     */
    handleWorkshopAction(action, vehicleId, button) {
        switch (action) {
            case 'start-repair':
                this.startRepair(vehicleId, button);
                break;
            case 'pause-repair':
                this.pauseRepair(vehicleId, button);
                break;
            case 'complete-repair':
                this.completeRepair(vehicleId, button);
                break;
            case 'move-to-testing':
                this.moveToTesting(vehicleId, button);
                break;
            default:
                console.log(`Unknown workshop action: ${action}`);
        }
    }

    /**
     * Start repair for vehicle
     */
    async startRepair(vehicleId, button) {
        try {
            this.showButtonLoading(button, 'Đang bắt đầu sửa chữa...');
            
            const response = await this.makeApiCall('/api/vehicles/start-repair', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId]
                })
            });

            if (response.success) {
                this.showNotification('Bắt đầu sửa chữa thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error starting repair:', error);
            this.showNotification('Có lỗi xảy ra khi bắt đầu sửa chữa', 'error');
        } finally {
            this.restoreButtonState(button);
        }
    }

    /**
     * Pause repair for vehicle
     */
    async pauseRepair(vehicleId, button) {
        try {
            this.showButtonLoading(button, 'Đang tạm dừng sửa chữa...');
            
            const response = await this.makeApiCall('/api/vehicles/pause-repair', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId]
                })
            });

            if (response.success) {
                this.showNotification('Tạm dừng sửa chữa thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error pausing repair:', error);
            this.showNotification('Có lỗi xảy ra khi tạm dừng sửa chữa', 'error');
        } finally {
            this.restoreButtonState(button);
        }
    }

    /**
     * Complete repair for vehicle
     */
    async completeRepair(vehicleId, button) {
        if (confirm(`Bạn có chắc muốn hoàn thành sửa chữa xe ${vehicleId}?`)) {
            try {
                this.showButtonLoading(button, 'Đang hoàn thành sửa chữa...');
                
                const response = await this.makeApiCall(`/api/vehicles/${vehicleId}/complete-repair`, {
                    method: 'POST',
                    body: JSON.stringify({})
                });

                if (response.success) {
                    this.showNotification('Hoàn thành sửa chữa thành công!', 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
                }
            } catch (error) {
                console.error('Error completing repair:', error);
                this.showNotification('Có lỗi xảy ra khi hoàn thành sửa chữa', 'error');
            } finally {
                this.restoreButtonState(button);
            }
        }
    }

    /**
     * Move vehicle to testing
     */
    async moveToTesting(vehicleId, button) {
        try {
            this.showButtonLoading(button, 'Đang chuyển đến kiểm tra...');
            
            const response = await this.makeApiCall('/api/vehicles/move-to-testing', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId]
                })
            });

            if (response.success) {
                this.showNotification('Chuyển đến kiểm tra thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error moving to testing:', error);
            this.showNotification('Có lỗi xảy ra khi chuyển đến kiểm tra', 'error');
        } finally {
            this.restoreButtonState(button);
        }
    }

    /**
     * Edit vehicle
     */
    editVehicle(vehicleId) {
        window.location.href = `/vehicles/${vehicleId}/edit`;
    }

    /**
     * Override returnToYard for workshop-specific logic
     */
    async returnToYard(vehicleId, button) {
        try {
            this.showButtonLoading(button, 'Đang đưa về bãi...');
            
            const response = await this.makeApiCall('/api/vehicles/return-yard', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId]
                })
            });

            if (response.success) {
                console.log('Đưa về bãi thành công!');
                // Simple success - just reload page
                window.location.reload();
            } else {
                console.error('Return to yard failed:', response.message);
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error returning to yard:', error);
            this.showNotification('Có lỗi xảy ra khi đưa về bãi', 'error');
        } finally {
            this.restoreButtonState(button);
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Workshop Vehicles page loaded');
    
    // Create and initialize WorkshopVehicles instance
    const workshopVehicles = new WorkshopVehicles();
    workshopVehicles.init();
    
    // Make it available globally for debugging
    window.workshopVehicles = workshopVehicles;
});

// Export for ES6 modules
export default WorkshopVehicles;
