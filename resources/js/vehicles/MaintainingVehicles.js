/**
 * MaintainingVehicles - Class for managing maintaining vehicles
 * Extends VehicleBase with maintenance-specific functionality
 */

import { VehicleBase } from './VehicleBase.js';

class MaintainingVehicles extends VehicleBase {
    constructor() {
        super('Maintaining Vehicles');
    }

    /**
     * Initialize maintaining vehicles page
     */
    init() {
        super.init();
        this.setupMaintenanceSpecificFeatures();
        console.log('Maintaining Vehicles page fully initialized');
    }

    /**
     * Setup maintenance-specific features
     */
    setupMaintenanceSpecificFeatures() {
        this.setupCompleteMaintenance();
        this.setupUpdateMaintenanceStatus();
        this.setupMaintenanceActions();
        this.setupMaintenanceTracking();
    }

    /**
     * Setup complete maintenance functionality
     */
    setupCompleteMaintenance() {
        const completeButtons = document.querySelectorAll('[data-action="complete-maintenance"]');
        completeButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const vehicleId = e.target.dataset.vehicleId;
                if (vehicleId) {
                    this.completeMaintenance(vehicleId, e.target);
                }
            });
        });
    }

    /**
     * Setup update maintenance status functionality
     */
    setupUpdateMaintenanceStatus() {
        const updateButtons = document.querySelectorAll('[data-action="update-maintenance-status"]');
        updateButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const vehicleId = e.target.dataset.vehicleId;
                if (vehicleId) {
                    this.updateMaintenanceStatus(vehicleId, e.target);
                }
            });
        });
    }

    /**
     * Setup maintenance-specific actions
     */
    setupMaintenanceActions() {
        const maintenanceActionButtons = document.querySelectorAll('[data-action="maintenance-action"]');
        maintenanceActionButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const action = e.target.dataset.maintenanceAction;
                const vehicleId = e.target.dataset.vehicleId;
                if (action && vehicleId) {
                    this.handleMaintenanceAction(action, vehicleId, e.target);
                }
            });
        });
    }

    /**
     * Setup maintenance tracking
     */
    setupMaintenanceTracking() {
        // Track maintenance progress and time
        this.vehicleCards.forEach(card => {
            this.setupMaintenanceProgress(card);
        });
    }

    /**
     * Setup maintenance progress for a vehicle card
     */
    setupMaintenanceProgress(card) {
        const progressElement = card.querySelector('.maintenance-progress');
        if (progressElement) {
            const startTime = parseInt(card.dataset.maintenanceStartTime);
            const estimatedDuration = parseInt(card.dataset.estimatedDuration);
            
            if (startTime && estimatedDuration) {
                this.updateMaintenanceProgress(progressElement, startTime, estimatedDuration);
                
                // Update every minute
                setInterval(() => {
                    this.updateMaintenanceProgress(progressElement, startTime, estimatedDuration);
                }, 60000);
            }
        }
    }

    /**
     * Update maintenance progress
     */
    updateMaintenanceProgress(progressElement, startTime, estimatedDuration) {
        const now = new Date().getTime();
        const elapsed = now - startTime;
        const progress = Math.min((elapsed / (estimatedDuration * 60 * 1000)) * 100, 100);
        
        progressElement.style.width = `${progress}%`;
        
        // Change color based on progress
        if (progress > 80) {
            progressElement.classList.add('bg-green-500');
            progressElement.classList.remove('bg-yellow-500', 'bg-blue-500');
        } else if (progress > 50) {
            progressElement.classList.add('bg-yellow-500');
            progressElement.classList.remove('bg-blue-500', 'bg-green-500');
        } else {
            progressElement.classList.add('bg-blue-500');
            progressElement.classList.remove('bg-yellow-500', 'bg-green-500');
        }
    }

    /**
     * Handle maintenance-specific actions
     */
    handleMaintenanceAction(action, vehicleId, button) {
        switch (action) {
            case 'start-maintenance':
                this.startMaintenance(vehicleId, button);
                break;
            case 'pause-maintenance':
                this.pauseMaintenance(vehicleId, button);
                break;
            case 'resume-maintenance':
                this.resumeMaintenance(vehicleId, button);
                break;
            case 'move-to-testing':
                this.moveToTesting(vehicleId, button);
                break;
            case 'move-to-repair':
                this.moveToRepair(vehicleId, button);
                break;
            default:
                console.log(`Unknown maintenance action: ${action}`);
        }
    }

    /**
     * Start maintenance for vehicle
     */
    async startMaintenance(vehicleId, button) {
        try {
            this.showButtonLoading(button, 'Đang bắt đầu bảo trì...');
            
            const response = await this.makeApiCall('/api/vehicles/start-maintenance', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId]
                })
            });

            if (response.success) {
                this.showNotification('Bắt đầu bảo trì thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error starting maintenance:', error);
            this.showNotification('Có lỗi xảy ra khi bắt đầu bảo trì', 'error');
        } finally {
            this.restoreButtonState(button);
        }
    }

    /**
     * Pause maintenance for vehicle
     */
    async pauseMaintenance(vehicleId, button) {
        try {
            this.showButtonLoading(button, 'Đang tạm dừng bảo trì...');
            
            const response = await this.makeApiCall('/api/vehicles/pause-maintenance', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId]
                })
            });

            if (response.success) {
                this.showNotification('Tạm dừng bảo trì thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error pausing maintenance:', error);
            this.showNotification('Có lỗi xảy ra khi tạm dừng bảo trì', 'error');
        } finally {
            this.restoreButtonState(button);
        }
    }

    /**
     * Resume maintenance for vehicle
     */
    async resumeMaintenance(vehicleId, button) {
        try {
            this.showButtonLoading(button, 'Đang tiếp tục bảo trì...');
            
            const response = await this.makeApiCall('/api/vehicles/resume-maintenance', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId]
                })
            });

            if (response.success) {
                this.showNotification('Tiếp tục bảo trì thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error resuming maintenance:', error);
            this.showNotification('Có lỗi xảy ra khi tiếp tục bảo trì', 'error');
        } finally {
            this.restoreButtonState(button);
        }
    }

    /**
     * Complete maintenance for vehicle
     */
    async completeMaintenance(vehicleId, button) {
        if (confirm(`Bạn có chắc muốn hoàn thành bảo trì xe ${vehicleId}?`)) {
            try {
                this.showButtonLoading(button, 'Đang hoàn thành bảo trì...');
                
                const response = await this.makeApiCall(`/api/vehicles/${vehicleId}/complete-maintenance`, {
                    method: 'POST',
                    body: JSON.stringify({})
                });

                if (response.success) {
                    this.showNotification('Hoàn thành bảo trì thành công!', 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
                }
            } catch (error) {
                console.error('Error completing maintenance:', error);
                this.showNotification('Có lỗi xảy ra khi hoàn thành bảo trì', 'error');
            } finally {
                this.restoreButtonState(button);
            }
        }
    }

    /**
     * Update maintenance status
     */
    async updateMaintenanceStatus(vehicleId, button) {
        const status = prompt('Cập nhật trạng thái bảo trì:');
        if (status) {
            try {
                this.showButtonLoading(button, 'Đang cập nhật...');
                
                const response = await this.makeApiCall(`/api/vehicles/${vehicleId}/update-maintenance-status`, {
                    method: 'POST',
                    body: JSON.stringify({
                        status: status
                    })
                });

                if (response.success) {
                    this.showNotification('Cập nhật trạng thái thành công!', 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
                }
            } catch (error) {
                console.error('Error updating maintenance status:', error);
                this.showNotification('Có lỗi xảy ra khi cập nhật trạng thái', 'error');
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
     * Move vehicle to repair
     */
    async moveToRepair(vehicleId, button) {
        try {
            this.showButtonLoading(button, 'Đang chuyển đến sửa chữa...');
            
            const response = await this.makeApiCall('/api/vehicles/move-to-repair', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId]
                })
            });

            if (response.success) {
                this.showNotification('Chuyển đến sửa chữa thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error moving to repair:', error);
            this.showNotification('Có lỗi xảy ra khi chuyển đến sửa chữa', 'error');
        } finally {
            this.restoreButtonState(button);
        }
    }

    /**
     * Get maintenance statistics
     */
    getMaintenanceStats() {
        const maintenanceCount = this.vehicleCards.length;
        const inProgressCount = document.querySelectorAll('.vehicle-card.maintenance-in-progress').length;
        const pausedCount = document.querySelectorAll('.vehicle-card.maintenance-paused').length;
        const completedCount = document.querySelectorAll('.vehicle-card.maintenance-completed').length;
        
        return {
            maintenance: maintenanceCount,
            inProgress: inProgressCount,
            paused: pausedCount,
            completed: completedCount,
            total: maintenanceCount
        };
    }

    /**
     * Update maintenance display
     */
    updateMaintenanceDisplay() {
        const stats = this.getMaintenanceStats();
        
        // Update stats display if it exists
        const statsElement = document.querySelector('.maintenance-stats');
        if (statsElement) {
            statsElement.innerHTML = `
                <div class="text-sm text-gray-600">
                    <span class="font-semibold text-blue-600">${stats.maintenance}</span> xe đang bảo trì | 
                    <span class="font-semibold text-green-600">${stats.inProgress}</span> đang tiến hành | 
                    <span class="font-semibold text-yellow-600">${stats.paused}</span> tạm dừng | 
                    <span class="font-semibold text-gray-600">${stats.completed}</span> hoàn thành
                </div>
            `;
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Maintaining Vehicles page loaded');
    
    // Create and initialize MaintainingVehicles instance
    const maintainingVehicles = new MaintainingVehicles();
    maintainingVehicles.init();
    
    // Make it available globally for debugging
    window.maintainingVehicles = maintainingVehicles;
});

// Export for ES6 modules
export default MaintainingVehicles;
