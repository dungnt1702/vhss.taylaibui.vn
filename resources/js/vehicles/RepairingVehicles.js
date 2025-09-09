/**
 * RepairingVehicles - Class for managing repairing vehicles
 * Extends VehicleBase with repair-specific functionality
 */

import { VehicleBase } from './VehicleBase.js';

class RepairingVehicles extends VehicleBase {
    constructor() {
        super('Repairing Vehicles');
    }

    /**
     * Initialize repairing vehicles page
     */
    init() {
        super.init();
        this.setupRepairSpecificFeatures();
        console.log('Repairing Vehicles page fully initialized');
    }

    /**
     * Setup repair-specific features
     */
    setupRepairSpecificFeatures() {
        this.setupCompleteRepair();
        this.setupUpdateRepairStatus();
        this.setupRepairActions();
        this.setupRepairTracking();
    }

    /**
     * Setup complete repair functionality
     */
    setupCompleteRepair() {
        const completeButtons = document.querySelectorAll('[data-action="complete-repair"]');
        completeButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const vehicleId = e.target.dataset.vehicleId;
                if (vehicleId) {
                    this.completeRepair(vehicleId, e.target);
                }
            });
        });
    }

    /**
     * Setup update repair status functionality
     */
    setupUpdateRepairStatus() {
        const updateButtons = document.querySelectorAll('[data-action="update-repair-status"]');
        updateButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const vehicleId = e.target.dataset.vehicleId;
                if (vehicleId) {
                    this.updateRepairStatus(vehicleId, e.target);
                }
            });
        });
    }

    /**
     * Setup repair-specific actions
     */
    setupRepairActions() {
        const repairActionButtons = document.querySelectorAll('[data-action="repair-action"]');
        repairActionButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const action = e.target.dataset.repairAction;
                const vehicleId = e.target.dataset.vehicleId;
                if (action && vehicleId) {
                    this.handleRepairAction(action, vehicleId, e.target);
                }
            });
        });
    }

    /**
     * Setup repair tracking
     */
    setupRepairTracking() {
        // Track repair progress and time
        this.vehicleCards.forEach(card => {
            this.setupRepairProgress(card);
        });
    }

    /**
     * Setup repair progress for a vehicle card
     */
    setupRepairProgress(card) {
        const progressElement = card.querySelector('.repair-progress');
        if (progressElement) {
            const startTime = parseInt(card.dataset.repairStartTime);
            const estimatedDuration = parseInt(card.dataset.estimatedDuration);
            
            if (startTime && estimatedDuration) {
                this.updateRepairProgress(progressElement, startTime, estimatedDuration);
                
                // Update every minute
                setInterval(() => {
                    this.updateRepairProgress(progressElement, startTime, estimatedDuration);
                }, 60000);
            }
        }
    }

    /**
     * Update repair progress
     */
    updateRepairProgress(progressElement, startTime, estimatedDuration) {
        const now = new Date().getTime();
        const elapsed = now - startTime;
        const progress = Math.min((elapsed / (estimatedDuration * 60 * 1000)) * 100, 100);
        
        progressElement.style.width = `${progress}%`;
        
        // Change color based on progress
        if (progress > 80) {
            progressElement.classList.add('bg-green-500');
            progressElement.classList.remove('bg-yellow-500', 'bg-red-500');
        } else if (progress > 50) {
            progressElement.classList.add('bg-yellow-500');
            progressElement.classList.remove('bg-red-500', 'bg-green-500');
        } else {
            progressElement.classList.add('bg-red-500');
            progressElement.classList.remove('bg-yellow-500', 'bg-green-500');
        }
    }

    /**
     * Handle repair-specific actions
     */
    handleRepairAction(action, vehicleId, button) {
        switch (action) {
            case 'start-repair':
                this.startRepair(vehicleId, button);
                break;
            case 'pause-repair':
                this.pauseRepair(vehicleId, button);
                break;
            case 'resume-repair':
                this.resumeRepair(vehicleId, button);
                break;
            case 'move-to-testing':
                this.moveToTesting(vehicleId, button);
                break;
            case 'move-to-maintenance':
                this.moveToMaintenance(vehicleId, button);
                break;
            case 'order-parts':
                this.orderParts(vehicleId, button);
                break;
            default:
                console.log(`Unknown repair action: ${action}`);
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
     * Resume repair for vehicle
     */
    async resumeRepair(vehicleId, button) {
        try {
            this.showButtonLoading(button, 'Đang tiếp tục sửa chữa...');
            
            const response = await this.makeApiCall('/api/vehicles/resume-repair', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId]
                })
            });

            if (response.success) {
                this.showNotification('Tiếp tục sửa chữa thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error resuming repair:', error);
            this.showNotification('Có lỗi xảy ra khi tiếp tục sửa chữa', 'error');
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
     * Update repair status
     */
    async updateRepairStatus(vehicleId, button) {
        const status = prompt('Cập nhật trạng thái sửa chữa:');
        if (status) {
            try {
                this.showButtonLoading(button, 'Đang cập nhật...');
                
                const response = await this.makeApiCall(`/api/vehicles/${vehicleId}/update-repair-status`, {
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
                console.error('Error updating repair status:', error);
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
     * Move vehicle to maintenance
     */
    async moveToMaintenance(vehicleId, button) {
        try {
            this.showButtonLoading(button, 'Đang chuyển đến bảo trì...');
            
            const response = await this.makeApiCall('/api/vehicles/move-to-maintenance', {
                method: 'POST',
                body: JSON.stringify({
                    vehicle_ids: [vehicleId]
                })
            });

            if (response.success) {
                this.showNotification('Chuyển đến bảo trì thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error moving to maintenance:', error);
            this.showNotification('Có lỗi xảy ra khi chuyển đến bảo trì', 'error');
        } finally {
            this.restoreButtonState(button);
        }
    }

    /**
     * Order parts for vehicle
     */
    async orderParts(vehicleId, button) {
        const parts = prompt('Nhập danh sách phụ tùng cần đặt (phân cách bằng dấu phẩy):');
        if (parts) {
            try {
                this.showButtonLoading(button, 'Đang đặt phụ tùng...');
                
                const response = await this.makeApiCall('/api/vehicles/order-parts', {
                    method: 'POST',
                    body: JSON.stringify({
                        vehicle_id: vehicleId,
                        parts: parts.split(',').map(part => part.trim())
                    })
                });

                if (response.success) {
                    this.showNotification('Đặt phụ tùng thành công!', 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
                }
            } catch (error) {
                console.error('Error ordering parts:', error);
                this.showNotification('Có lỗi xảy ra khi đặt phụ tùng', 'error');
            } finally {
                this.restoreButtonState(button);
            }
        }
    }

    /**
     * Get repair statistics
     */
    getRepairStats() {
        const repairCount = this.vehicleCards.length;
        const inProgressCount = document.querySelectorAll('.vehicle-card.repair-in-progress').length;
        const pausedCount = document.querySelectorAll('.vehicle-card.repair-paused').length;
        const completedCount = document.querySelectorAll('.vehicle-card.repair-completed').length;
        const readyPartsCount = document.querySelectorAll('.vehicle-card.ready-parts').length;
        
        return {
            repair: repairCount,
            inProgress: inProgressCount,
            paused: pausedCount,
            completed: completedCount,
            readyParts: readyPartsCount,
            total: repairCount
        };
    }

    /**
     * Update repair display
     */
    updateRepairDisplay() {
        const stats = this.getRepairStats();
        
        // Update stats display if it exists
        const statsElement = document.querySelector('.repair-stats');
        if (statsElement) {
            statsElement.innerHTML = `
                <div class="text-sm text-gray-600">
                    <span class="font-semibold text-red-600">${stats.repair}</span> xe đang sửa chữa | 
                    <span class="font-semibold text-green-600">${stats.inProgress}</span> đang tiến hành | 
                    <span class="font-semibold text-yellow-600">${stats.paused}</span> tạm dừng | 
                    <span class="font-semibold text-gray-600">${stats.completed}</span> hoàn thành | 
                    <span class="font-semibold text-orange-600">${stats.readyParts}</span> sẵn sàng phụ tùng
                </div>
            `;
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Repairing Vehicles page loaded');
    
    // Create and initialize RepairingVehicles instance
    const repairingVehicles = new RepairingVehicles();
    repairingVehicles.init();
    
    // Make it available globally for debugging
    window.repairingVehicles = repairingVehicles;
});

// Export for ES6 modules
export default RepairingVehicles;
