/**
 * Vehicles - Main vehicle management class
 * Extends VehicleBase with general vehicle functionality
 */

import { VehicleBase } from './VehicleBase.js';

class Vehicles extends VehicleBase {
    constructor() {
        super('Vehicles');
        this.currentFilter = '';
        this.vehicleTimers = {};
    }

    /**
     * Initialize vehicles page
     */
    init() {
        super.init();
        this.setupVehicleSpecificFeatures();
        console.log('Vehicles page fully initialized');
    }

    /**
     * Setup vehicle-specific features
     */
    setupVehicleSpecificFeatures() {
        this.setupPerPageSelector();
        this.setupStatusModal();
        this.setupWorkshopModal();
        this.setupEscapeKeyHandling();
        this.setupGlobalFunctions();
    }

    /**
     * Setup per page selector
     */
    setupPerPageSelector() {
        const perPageSelect = document.getElementById('per-page');
        if (perPageSelect) {
            perPageSelect.addEventListener('change', this.handlePerPageChange.bind(this));
        }
    }

    /**
     * Setup status modal
     */
    setupStatusModal() {
        const statusForm = document.getElementById('status-form');
        if (statusForm) {
            statusForm.addEventListener('submit', this.handleStatusUpdate.bind(this));
        }
    }

    /**
     * Setup workshop modal
     */
    setupWorkshopModal() {
        const workshopModal = document.getElementById('workshop-modal');
        if (workshopModal) {
            workshopModal.addEventListener('click', this.closeWorkshopModal.bind(this));
        }
    }

    /**
     * Setup escape key handling
     */
    setupEscapeKeyHandling() {
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                this.closeWorkshopModal();
            }
        });
    }

    /**
     * Setup global functions for backward compatibility
     */
    setupGlobalFunctions() {
        // Make functions available globally for onclick handlers
        window.openStatusModal = (vehicleId, currentStatus, currentNotes) => 
            this.openStatusModal(vehicleId, currentStatus, currentNotes);
        window.closeStatusModal = () => this.closeStatusModal();
        window.openWorkshopModal = (vehicleId) => this.openWorkshopModal(vehicleId);
        window.closeWorkshopModal = () => this.closeWorkshopModal();
    }

    /**
     * Handle per page change
     */
    handlePerPageChange(event) {
        const perPage = event.target.value;
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('per_page', perPage);
        currentUrl.searchParams.delete('page');
        
        // Show loading state
        event.target.disabled = true;
        event.target.style.opacity = '0.6';
        
        window.location.href = currentUrl.toString();
    }

    /**
     * Handle status update
     */
    async handleStatusUpdate(event) {
        event.preventDefault();
        
        const vehicleId = document.getElementById('vehicle-id').value;
        const formData = new FormData(event.target);
        
        try {
            const response = await this.makeApiCall(`/vehicles/${vehicleId}/status`, {
                method: 'PATCH',
                body: JSON.stringify({
                    status: formData.get('status'),
                    notes: formData.get('notes')
                })
            });

            if (response.success) {
                this.closeStatusModal();
                this.showNotification('Cập nhật trạng thái thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error updating status:', error);
            this.showNotification('Có lỗi xảy ra khi cập nhật trạng thái xe', 'error');
        }
    }

    /**
     * Open status modal
     */
    openStatusModal(vehicleId, currentStatus, currentNotes) {
        const vehicleIdInput = document.getElementById('vehicle-id');
        const statusSelect = document.getElementById('status-select');
        const statusNotes = document.getElementById('status-notes');
        const statusModal = document.getElementById('status-modal');

        if (vehicleIdInput && statusSelect && statusNotes && statusModal) {
            vehicleIdInput.value = vehicleId;
            statusSelect.value = currentStatus;
            statusNotes.value = currentNotes || '';
            statusModal.classList.remove('hidden');
        }
    }

    /**
     * Close status modal
     */
    closeStatusModal() {
        const statusModal = document.getElementById('status-modal');
        if (statusModal) {
            statusModal.classList.add('hidden');
        }
    }

    /**
     * Open workshop modal
     */
    openWorkshopModal(vehicleId) {
        const workshopModal = document.getElementById('workshop-modal');
        const vehicleIdInput = document.getElementById('workshop-vehicle-id');
        
        if (workshopModal && vehicleIdInput) {
            vehicleIdInput.value = vehicleId;
            workshopModal.classList.remove('hidden');
        }
    }

    /**
     * Close workshop modal
     */
    closeWorkshopModal() {
        const workshopModal = document.getElementById('workshop-modal');
        if (workshopModal) {
            workshopModal.classList.add('hidden');
        }
    }

    /**
     * Get vehicle statistics
     */
    getVehicleStats() {
        const totalVehicles = this.vehicleCards.length;
        const readyCount = document.querySelectorAll('.vehicle-card[data-status="ready"]').length;
        const waitingCount = document.querySelectorAll('.vehicle-card[data-status="waiting"]').length;
        const runningCount = document.querySelectorAll('.vehicle-card[data-status="running"]').length;
        const pausedCount = document.querySelectorAll('.vehicle-card[data-status="paused"]').length;
        const expiredCount = document.querySelectorAll('.vehicle-card[data-status="expired"]').length;
        const workshopCount = document.querySelectorAll('.vehicle-card[data-status="workshop"]').length;
        
        return {
            total: totalVehicles,
            ready: readyCount,
            waiting: waitingCount,
            running: runningCount,
            paused: pausedCount,
            expired: expiredCount,
            workshop: workshopCount
        };
    }

    /**
     * Update vehicle display
     */
    updateVehicleDisplay() {
        const stats = this.getVehicleStats();
        
        // Update stats display if it exists
        const statsElement = document.querySelector('.vehicle-stats');
        if (statsElement) {
            statsElement.innerHTML = `
                <div class="text-sm text-gray-600">
                    <span class="font-semibold text-blue-600">${stats.total}</span> tổng cộng | 
                    <span class="font-semibold text-green-600">${stats.ready}</span> sẵn sàng | 
                    <span class="font-semibold text-yellow-600">${stats.waiting}</span> chờ | 
                    <span class="font-semibold text-blue-600">${stats.running}</span> chạy | 
                    <span class="font-semibold text-orange-600">${stats.paused}</span> tạm dừng | 
                    <span class="font-semibold text-red-600">${stats.expired}</span> hết giờ | 
                    <span class="font-semibold text-blue-600">${stats.workshop}</span> xưởng
                </div>
            `;
        }
    }

    /**
     * Filter vehicles by status
     */
    filterVehicles(status) {
        this.currentFilter = status;
        
        this.vehicleCards.forEach(card => {
            const vehicleStatus = card.dataset.status;
            if (status === 'all' || vehicleStatus === status) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
        
        this.updateVehicleDisplay();
    }

    /**
     * Search vehicles
     */
    searchVehicles(query) {
        if (!query) {
            this.vehicleCards.forEach(card => card.style.display = 'block');
            return;
        }
        
        const searchTerm = query.toLowerCase();
        
        this.vehicleCards.forEach(card => {
            const vehicleName = card.dataset.vehicleName?.toLowerCase() || '';
            const vehicleColor = card.dataset.vehicleColor?.toLowerCase() || '';
            const vehicleNotes = card.dataset.vehicleNotes?.toLowerCase() || '';
            
            const matches = vehicleName.includes(searchTerm) || 
                           vehicleColor.includes(searchTerm) || 
                           vehicleNotes.includes(searchTerm);
            
            card.style.display = matches ? 'block' : 'none';
        });
        
        this.updateVehicleDisplay();
    }

    /**
     * Export vehicles data
     */
    async exportVehicles(format = 'csv') {
        try {
            const response = await fetch(`/api/vehicles/export?format=${format}`);
            
            if (response.ok) {
                const blob = await response.blob();
                const downloadUrl = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = downloadUrl;
                a.download = `vehicles_${new Date().toISOString().split('T')[0]}.${format}`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(downloadUrl);
                
                this.showNotification('Export thành công!', 'success');
            } else {
                this.showNotification('Có lỗi xảy ra khi export', 'error');
            }
        } catch (error) {
            console.error('Error exporting vehicles:', error);
            this.showNotification('Có lỗi xảy ra khi export', 'error');
        }
    }

    /**
     * Import vehicles data
     */
    async importVehicles(file) {
        if (!file) {
            this.showNotification('Vui lòng chọn file để import', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('file', file);

        try {
            const response = await fetch('/api/vehicles/import', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification(`Import thành công ${result.count} xe!`, 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(result.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error importing vehicles:', error);
            this.showNotification('Có lỗi xảy ra khi import', 'error');
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Vehicles page loaded');
    
    // Create and initialize Vehicles instance
    const vehicles = new Vehicles();
    vehicles.init();
    
    // Make it available globally for debugging
    window.vehicles = vehicles;
});

// Export for ES6 modules
export default Vehicles;
