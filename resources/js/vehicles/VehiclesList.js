/**
 * VehiclesList - Class for managing vehicles list page
 * Extends VehicleBase with list-specific functionality
 */

class VehiclesList extends VehicleBase {
    constructor() {
        super('Vehicles List');
    }

    /**
     * Initialize vehicles list page
     */
    init() {
        super.init();
        this.setupEventListeners();
        this.exposeGlobalFunctions();
        console.log('Vehicles List page loaded');
    }

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        // Handle vehicle form submission
        document.addEventListener('submit', (event) => {
            if (event.target.id === 'vehicle-form') {
                event.preventDefault();
                this.handleVehicleFormSubmit(event);
            }
        });

        // Handle status form submission
        document.addEventListener('submit', (event) => {
            if (event.target.id === 'status-form') {
                event.preventDefault();
                this.handleStatusFormSubmit(event);
            }
        });
    }

    /**
     * Expose global functions for HTML onclick handlers
     */
    exposeGlobalFunctions() {
        // Vehicle detail modal functions
        window.openVehicleDetailModal = (vehicleId) => this.openVehicleDetailModal(vehicleId);
        window.closeVehicleDetailModal = () => this.closeVehicleDetailModal();
        
        // Vehicle edit modal functions
        window.openEditVehicleModal = (vehicleId) => this.openEditVehicleModal(vehicleId);
        window.closeVehicleModal = () => this.closeVehicleModal();
        
        // Status modal functions
        window.openStatusModal = (vehicleId, currentStatus, currentNotes) => this.openStatusModal(vehicleId, currentStatus, currentNotes);
        window.closeStatusModal = () => this.closeStatusModal();
        
        // Delete function
        window.deleteVehicle = (vehicleId) => this.deleteVehicle(vehicleId);
    }

    /**
     * Open vehicle detail modal
     */
    async openVehicleDetailModal(vehicleId) {
        try {
            const response = await fetch(`/api/vehicles/${vehicleId}/data`);
            const result = await response.json();
            
            if (result.success) {
                const vehicleData = result.data;
                const content = this.createVehicleDetailContent(vehicleData);
                
                const contentElement = document.getElementById('vehicle-detail-content');
                if (contentElement) {
                    contentElement.innerHTML = content;
                }
                
                const modal = document.getElementById('vehicle-detail-modal');
                if (modal) {
                    modal.classList.remove('hidden');
                }
            } else {
                this.showNotification('Không thể lấy thông tin xe: ' + result.message, 'error');
            }
        } catch (error) {
            console.error('Error fetching vehicle data:', error);
            this.showNotification('Lỗi khi lấy thông tin xe: ' + error.message, 'error');
        }
    }

    /**
     * Close vehicle detail modal
     */
    closeVehicleDetailModal() {
        const modal = document.getElementById('vehicle-detail-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    /**
     * Open edit vehicle modal
     */
    async openEditVehicleModal(vehicleId) {
        console.log('=== openEditVehicleModal called with vehicleId:', vehicleId, '===');
        
        try {
            const response = await fetch(`/api/vehicles/${vehicleId}/data`);
            const result = await response.json();
            
            if (result.success) {
                const vehicleData = result.data;
                this.populateEditVehicleForm(vehicleData);
                
                const modal = document.getElementById('vehicle-modal');
                if (modal) {
                    modal.classList.remove('hidden');
                }
            } else {
                this.showNotification('Không thể lấy thông tin xe: ' + result.message, 'error');
            }
        } catch (error) {
            console.error('Error fetching vehicle data:', error);
            this.showNotification('Lỗi khi lấy thông tin xe: ' + error.message, 'error');
        }
    }

    /**
     * Close vehicle modal
     */
    closeVehicleModal() {
        const modal = document.getElementById('vehicle-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
        this.clearEditVehicleForm();
    }

    /**
     * Populate edit vehicle form with data
     */
    populateEditVehicleForm(vehicleData) {
        const fields = {
            'vehicle-edit-id': vehicleData.id,
            'vehicle-name': vehicleData.name,
            'vehicle-color': vehicleData.color,
            'vehicle-seats': vehicleData.seats,
            'vehicle-power': vehicleData.power,
            'vehicle-wheel-size': vehicleData.wheel_size,
            'vehicle-current-location': vehicleData.current_location,
            'vehicle-notes': vehicleData.notes
        };

        Object.entries(fields).forEach(([fieldId, value]) => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.value = value || '';
                field.dispatchEvent(new Event('input', { bubbles: true }));
                field.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    }

    /**
     * Clear edit vehicle form
     */
    clearEditVehicleForm() {
        const fields = [
            'vehicle-edit-id', 'vehicle-name', 'vehicle-color',
            'vehicle-seats', 'vehicle-power', 'vehicle-wheel-size',
            'vehicle-current-location', 'vehicle-notes'
        ];

        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.value = '';
            }
        });
    }

    /**
     * Handle vehicle form submission
     */
    async handleVehicleFormSubmit(event) {
        event.preventDefault();
        
        const vehicleId = document.getElementById('vehicle-edit-id')?.value;
        if (!vehicleId) {
            this.showNotification('Không tìm thấy ID xe', 'error');
            return;
        }

        const formData = {
            name: document.getElementById('vehicle-name')?.value || '',
            color: document.getElementById('vehicle-color')?.value || '',
            seats: document.getElementById('vehicle-seats')?.value || '',
            power: document.getElementById('vehicle-power')?.value || '',
            wheel_size: document.getElementById('vehicle-wheel-size')?.value || '',
            current_location: document.getElementById('vehicle-current-location')?.value || '',
            notes: document.getElementById('vehicle-notes')?.value || ''
        };

        try {
            const response = await fetch(`/vehicles/${vehicleId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();
            
            if (response.ok && result.success) {
                this.showNotification('Cập nhật xe thành công!', 'success');
                this.closeVehicleModal();
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(result.message || 'Có lỗi xảy ra khi cập nhật xe', 'error');
            }
        } catch (error) {
            console.error('Error updating vehicle:', error);
            this.showNotification('Lỗi khi cập nhật xe: ' + error.message, 'error');
        }
    }

    /**
     * Open status modal
     */
    openStatusModal(vehicleId, currentStatus, currentNotes) {
        const vehicleIdInput = document.getElementById('status-vehicle-id');
        if (vehicleIdInput) {
            vehicleIdInput.value = vehicleId;
        }
        
        const notesInput = document.getElementById('status-notes');
        if (notesInput) {
            notesInput.value = currentNotes || '';
        }
        
        const modal = document.getElementById('status-modal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    /**
     * Close status modal
     */
    closeStatusModal() {
        const modal = document.getElementById('status-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    /**
     * Handle status form submission
     */
    async handleStatusFormSubmit(event) {
        event.preventDefault();
        
        const vehicleId = document.getElementById('status-vehicle-id')?.value;
        const notes = document.getElementById('status-notes')?.value || '';
        
        if (!vehicleId) {
            this.showNotification('Không tìm thấy ID xe', 'error');
            return;
        }

        try {
            const response = await fetch(`/vehicles/${vehicleId}/notes`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ notes })
            });

            const result = await response.json();
            
            if (response.ok && result.success) {
                this.showNotification('Cập nhật ghi chú thành công!', 'success');
                this.closeStatusModal();
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(result.message || 'Có lỗi xảy ra khi cập nhật ghi chú', 'error');
            }
        } catch (error) {
            console.error('Error updating notes:', error);
            this.showNotification('Lỗi khi cập nhật ghi chú: ' + error.message, 'error');
        }
    }

    /**
     * Delete vehicle
     */
    async deleteVehicle(vehicleId) {
        this.showNotificationModal(
            'Xác nhận', 
            'Bạn có chắc chắn muốn xóa xe này?', 
            'confirm',
            async () => {
                try {
                    const response = await fetch(`/vehicles/${vehicleId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                } catch (error) {
                    console.error('Error deleting vehicle:', error);
                    this.showNotification('Không thể xóa xe. Vui lòng thử lại.', 'error');
                }
            }
        );
    }

    /**
     * Create vehicle detail content
     */
    createVehicleDetailContent(vehicleData) {
        return `
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Xe số</label>
                        <p class="mt-1 text-sm text-gray-900">${vehicleData.name || 'N/A'}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Màu sắc</label>
                        <div class="mt-1 flex items-center">
                            <div class="w-6 h-6 rounded border border-gray-300 mr-2" style="background-color: ${vehicleData.color || '#000'}"></div>
                            <span class="text-sm text-gray-900">${vehicleData.color || 'N/A'}</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Số chỗ ngồi</label>
                        <p class="mt-1 text-sm text-gray-900">${vehicleData.seats || 'N/A'}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Công suất</label>
                        <p class="mt-1 text-sm text-gray-900">${vehicleData.power || 'N/A'}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kích cỡ bánh</label>
                        <p class="mt-1 text-sm text-gray-900">${vehicleData.wheel_size || 'N/A'}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Vị trí hiện tại</label>
                        <p class="mt-1 text-sm text-gray-900">${vehicleData.current_location || 'N/A'}</p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ghi chú</label>
                    <p class="mt-1 text-sm text-gray-900">${vehicleData.notes || 'Không có ghi chú'}</p>
                </div>
            </div>
        `;
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    const vehiclesList = new VehiclesList();
    vehiclesList.init();
    
    // Make available globally for debugging
    window.vehiclesList = vehiclesList;
});

// Make VehiclesList available globally
window.VehiclesList = VehiclesList;
