/**
 * VehiclesList - Vehicle list management class
 * Extends VehicleBase with list-specific functionality
 */

import { VehicleBase } from './VehicleBase.js';

class VehiclesList extends VehicleBase {
    constructor() {
        super('Vehicles List');
    }

    /**
     * Initialize vehicles list page
     */
    init() {
        super.init();
        this.setupListSpecificFeatures();
        console.log('Vehicles List page fully initialized');
    }

    /**
     * Setup list-specific features
     */
    setupListSpecificFeatures() {
        this.setupVehicleDetailModal();
        this.setupGlobalFunctions();
        this.setupEventListeners();
    }

    /**
     * Setup vehicle detail modal
     */
    setupVehicleDetailModal() {
        // Modal functionality is handled by VehicleBase
        // Additional modal-specific setup can be added here
    }

    /**
     * Setup global functions for backward compatibility
     */
    setupGlobalFunctions() {
        // Make functions available globally for onclick handlers
        window.openVehicleDetailModal = (vehicleId) => this.openVehicleDetailModal(vehicleId);
        window.closeVehicleDetailModal = () => this.closeVehicleDetailModal();
        window.toggleVehicleExpansion = (vehicleId) => this.toggleVehicleExpansion(vehicleId);
        
        // Additional functions needed for vehicles_list.blade.php
        window.openStatusModal = (vehicleId, currentStatus, currentNotes) => this.openStatusModal(vehicleId, currentStatus, currentNotes);
        window.openEditVehicleModal = (vehicleId) => this.openEditVehicleModal(vehicleId);
        window.closeVehicleModal = () => this.closeVehicleModal();
        window.deleteVehicle = (vehicleId) => this.deleteVehicle(vehicleId);
    }

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        // Close modal when clicking outside
        document.addEventListener('click', (event) => {
            const modal = document.getElementById('vehicle-detail-modal');
            if (modal && event.target === modal) {
                this.closeVehicleDetailModal();
            }
        });

        // Close modal when pressing Escape key
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                this.closeVehicleDetailModal();
            }
        });
    }

    /**
     * Open vehicle detail modal
     */
    async openVehicleDetailModal(vehicleId) {
        try {
            // Call API to get vehicle data from database
            const response = await fetch(`/api/vehicles/${vehicleId}/data`);
            const result = await response.json();
            
            if (result.success) {
                const vehicleData = result.data;
                console.log('Vehicle data for detail modal:', vehicleData);
                
                // Create HTML content from API data
                const content = this.createVehicleDetailContent(vehicleData);
                
                // Display content
                const contentElement = document.getElementById('vehicle-detail-content');
                if (contentElement) {
                    contentElement.innerHTML = content;
                }
                
                // Show modal
                const modal = document.getElementById('vehicle-detail-modal');
                if (modal) {
                    modal.classList.remove('hidden');
                }
            } else {
                console.error('Failed to get vehicle data:', result.message);
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
     * Create vehicle detail content HTML
     */
    createVehicleDetailContent(vehicleData) {
        return `
            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Thông tin cơ bản</h4>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-neutral-700">Xe số:</span>
                                <span class="ml-2 text-sm text-neutral-900">${vehicleData.name}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-neutral-700">Màu sắc:</span>
                                <div class="ml-2 flex items-center space-x-2">
                                    <div class="w-6 h-6 rounded border border-neutral-300" style="background-color: ${vehicleData.color};"></div>
                                    <span class="text-sm text-neutral-900">${vehicleData.color}</span>
                                </div>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-neutral-700">Số chỗ ngồi:</span>
                                <span class="ml-2 text-sm text-neutral-900">${vehicleData.seats}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-neutral-700">Công suất:</span>
                                <span class="ml-2 text-sm text-neutral-900">${vehicleData.power}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-neutral-700">Kích cỡ bánh:</span>
                                <span class="ml-2 text-sm text-neutral-900">${vehicleData.wheel_size}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Trạng thái & Ghi chú</h4>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-neutral-700">Trạng thái:</span>
                                <span class="ml-2 text-sm text-neutral-900">${vehicleData.status_display_name}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-neutral-700">Ghi chú:</span>
                                <div class="ml-2 mt-1 p-3 bg-neutral-50 rounded-lg">
                                    <span class="text-sm text-neutral-900">${vehicleData.notes || 'Không có ghi chú'}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Toggle vehicle expansion
     */
    toggleVehicleExpansion(vehicleId) {
        const contentId = `content-${vehicleId}`;
        const iconId = `icon-${vehicleId}`;
        
        const content = document.getElementById(contentId);
        const icon = document.getElementById(iconId);
        
        if (!content || !icon) {
            console.warn(`Vehicle elements not found: contentId=${contentId}, iconId=${iconId}`);
            return;
        }
        
        const isHidden = content.classList.contains('hidden');
        
        if (isHidden) {
            // Close all other vehicles first
            this.closeAllOtherVehicles(contentId);
            
            // Open the clicked vehicle
            content.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            // Close the clicked vehicle
            content.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }

    /**
     * Close all other vehicles
     */
    closeAllOtherVehicles(currentContentId) {
        const allContents = document.querySelectorAll('.vehicle-content');
        
        allContents.forEach((contentEl) => {
            const contentId = contentEl.id;
            
            if (contentId !== currentContentId) {
                contentEl.classList.add('hidden');
                
                // Reset icon
                const vehicleId = contentId.replace('content-', '');
                const icon = document.getElementById(`icon-${vehicleId}`);
                if (icon) {
                    icon.style.transform = 'rotate(0deg)';
                }
            }
        });
    }

    /**
     * Get vehicle list statistics
     */
    getVehicleListStats() {
        const totalVehicles = this.vehicleCards.length;
        const expandedVehicles = document.querySelectorAll('.vehicle-content:not(.hidden)').length;
        const collapsedVehicles = totalVehicles - expandedVehicles;
        
        return {
            total: totalVehicles,
            expanded: expandedVehicles,
            collapsed: collapsedVehicles
        };
    }

    /**
     * Update vehicle list display
     */
    updateVehicleListDisplay() {
        const stats = this.getVehicleListStats();
        
        // Update stats display if it exists
        const statsElement = document.querySelector('.vehicle-list-stats');
        if (statsElement) {
            statsElement.innerHTML = `
                <div class="text-sm text-gray-600">
                    <span class="font-semibold text-blue-600">${stats.total}</span> tổng cộng | 
                    <span class="font-semibold text-green-600">${stats.expanded}</span> đang mở | 
                    <span class="font-semibold text-gray-600">${stats.collapsed}</span> đang đóng
                </div>
            `;
        }
    }

    /**
     * Expand all vehicles
     */
    expandAllVehicles() {
        const allContents = document.querySelectorAll('.vehicle-content');
        const allIcons = document.querySelectorAll('[id^="icon-"]');
        
        allContents.forEach(content => {
            content.classList.remove('hidden');
        });
        
        allIcons.forEach(icon => {
            icon.style.transform = 'rotate(180deg)';
        });
        
        this.updateVehicleListDisplay();
        this.showNotification('Đã mở tất cả xe', 'success');
    }

    /**
     * Collapse all vehicles
     */
    collapseAllVehicles() {
        const allContents = document.querySelectorAll('.vehicle-content');
        const allIcons = document.querySelectorAll('[id^="icon-"]');
        
        allContents.forEach(content => {
            content.classList.add('hidden');
        });
        
        allIcons.forEach(icon => {
            icon.style.transform = 'rotate(0deg)';
        });
        
        this.updateVehicleListDisplay();
        this.showNotification('Đã đóng tất cả xe', 'success');
    }

    /**
     * Search vehicles in list
     */
    searchVehiclesInList(query) {
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
        
        this.updateVehicleListDisplay();
    }

    /**
     * Filter vehicles by status in list
     */
    filterVehiclesByStatus(status) {
        this.vehicleCards.forEach(card => {
            const vehicleStatus = card.dataset.status;
            if (status === 'all' || vehicleStatus === status) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
        
        this.updateVehicleListDisplay();
    }

    /**
     * Sort vehicles in list
     */
    sortVehicles(sortBy = 'name', sortOrder = 'asc') {
        const vehicleList = document.getElementById('vehicle-list');
        if (!vehicleList) return;
        
        const vehicleCards = Array.from(this.vehicleCards);
        
        vehicleCards.sort((a, b) => {
            let aValue, bValue;
            
            switch (sortBy) {
                case 'name':
                    aValue = a.dataset.vehicleName || '';
                    bValue = b.dataset.vehicleName || '';
                    break;
                case 'status':
                    aValue = a.dataset.status || '';
                    bValue = b.dataset.status || '';
                    break;
                case 'color':
                    aValue = a.dataset.vehicleColor || '';
                    bValue = b.dataset.vehicleColor || '';
                    break;
                default:
                    aValue = a.dataset.vehicleName || '';
                    bValue = b.dataset.vehicleName || '';
            }
            
            if (sortOrder === 'asc') {
                return aValue.localeCompare(bValue);
            } else {
                return bValue.localeCompare(aValue);
            }
        });
        
        // Reorder DOM elements
        vehicleCards.forEach(card => {
            vehicleList.appendChild(card);
        });
        
        this.showNotification(`Đã sắp xếp xe theo ${sortBy} (${sortOrder})`, 'success');
    }

    /**
     * Export vehicle list
     */
    async exportVehicleList(format = 'csv') {
        try {
            const response = await fetch(`/api/vehicles/export?format=${format}`);
            
            if (response.ok) {
                const blob = await response.blob();
                const downloadUrl = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = downloadUrl;
                a.download = `vehicle_list_${new Date().toISOString().split('T')[0]}.${format}`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(downloadUrl);
                
                this.showNotification('Export danh sách xe thành công!', 'success');
            } else {
                this.showNotification('Có lỗi xảy ra khi export', 'error');
            }
        } catch (error) {
            console.error('Error exporting vehicle list:', error);
            this.showNotification('Có lỗi xảy ra khi export', 'error');
        }
    }

    /**
     * Open status modal
     */
    openStatusModal(vehicleId, currentStatus, status, currentNotes) {
        // Call function from vehicle-forms.js if available
        if (typeof window.openStatusModal === 'function') {
            window.openStatusModal(vehicleId, currentStatus, status, currentNotes);
        } else {
            console.warn('openStatusModal function not available');
        }
    }

    /**
     * Open edit vehicle modal
     */
    async openEditVehicleModal(vehicleId) {
        try {
            // Call API to get vehicle data for editing
            const response = await fetch(`/api/vehicles/${vehicleId}/data`);
            const result = await response.json();
            
            if (result.success) {
                const vehicleData = result.data;
                console.log('Vehicle data for edit modal:', vehicleData);
                
                // Populate form with vehicle data
                this.populateEditVehicleForm(vehicleData);
                
                // Update modal title
                const modalTitle = document.getElementById('vehicle-modal-title');
                if (modalTitle) {
                    modalTitle.textContent = 'Chỉnh sửa thông tin xe';
                }
                
                // Update vehicle ID in form
                const vehicleEditId = document.getElementById('vehicle-edit-id');
                if (vehicleEditId) {
                    vehicleEditId.value = vehicleId;
                }
                
                // Show modal
                const modal = document.getElementById('vehicle-modal');
                if (modal) {
                    modal.classList.remove('hidden');
                }
            } else {
                console.error('Failed to get vehicle data for edit:', result.message);
                this.showNotification('Không thể lấy thông tin xe để chỉnh sửa: ' + result.message, 'error');
            }
        } catch (error) {
            console.error('Error fetching vehicle data for edit:', error);
            this.showNotification('Lỗi khi lấy thông tin xe để chỉnh sửa: ' + error.message, 'error');
        }
    }

    /**
     * Populate edit vehicle form with data
     */
    populateEditVehicleForm(vehicleData) {
        console.log('=== populateEditVehicleForm called with:', vehicleData, '===');
        
        // Populate form fields
        const fields = {
            'vehicle-name': vehicleData.name,
            'vehicle-color': vehicleData.color,
            'vehicle-seats': vehicleData.seats,
            'vehicle-power': vehicleData.power,
            'vehicle-wheel-size': vehicleData.wheel_size,
            'vehicle-notes': vehicleData.notes || ''
        };
        
        Object.keys(fields).forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                const value = fields[fieldId];
                
                // Set value for input
                if (field.type === 'select-one') {
                    field.value = value;
                } else {
                    field.value = value;
                    field.setAttribute('value', value);
                    field.defaultValue = value;
                }
                
                // Dispatch events to trigger validation
                field.dispatchEvent(new Event('input', { bubbles: true }));
                field.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
        
        // Update color preview
        const colorPreview = document.getElementById('color-preview');
        if (colorPreview && vehicleData.color) {
            colorPreview.style.backgroundColor = vehicleData.color;
        }
        
        // Verify form has been populated
        requestAnimationFrame(() => {
            const nameField = document.getElementById('vehicle-name');
            if (nameField && nameField.value === vehicleData.name) {
                console.log('=== Form populated successfully ===');
            } else {
                console.warn('Form population verification failed');
            }
        });
    }

    /**
     * Delete vehicle
     */
    async deleteVehicle(vehicleId) {
        if (confirm('Bạn có chắc chắn muốn xóa xe này?')) {
            try {
                // Call API to delete vehicle
                const response = await fetch(`/vehicles/${vehicleId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    // Reload page after successful deletion
                    window.location.reload();
                } else {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
            } catch (error) {
                console.error('Error deleting vehicle:', error);
                this.showNotification('Không thể xóa xe. Vui lòng thử lại.', 'error');
            }
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Vehicles List page loaded');
    
    // Create and initialize VehiclesList instance
    const vehiclesList = new VehiclesList();
    vehiclesList.init();
    
    // Make it available globally for debugging
    window.vehiclesList = vehiclesList;
});

/**
 * Close vehicle modal
 */
VehiclesList.prototype.closeVehicleModal = function() {
    const modal = document.getElementById('vehicle-modal');
    if (modal) {
        modal.classList.add('hidden');
        // Clear form if needed
        this.clearEditVehicleForm();
    }
};

/**
 * Clear edit vehicle form
 */
VehiclesList.prototype.clearEditVehicleForm = function() {
    // Clear form fields
    const nameField = document.getElementById('vehicle-name');
    const colorField = document.getElementById('vehicle-color');
    const seatsField = document.getElementById('vehicle-seats');
    const powerField = document.getElementById('vehicle-power');
    const wheelSizeField = document.getElementById('vehicle-wheel-size');
    const notesField = document.getElementById('vehicle-notes');
    const colorPreview = document.getElementById('color-preview');
    
    if (nameField) nameField.value = '';
    if (colorField) colorField.value = '#808080';
    if (seatsField) seatsField.value = '';
    if (powerField) powerField.value = '';
    if (wheelSizeField) wheelSizeField.value = '';
    if (notesField) notesField.value = '';
    if (colorPreview) colorPreview.style.backgroundColor = '#808080';
    
    // Clear vehicle ID
    const vehicleIdField = document.getElementById('vehicle-edit-id');
    if (vehicleIdField) vehicleIdField.value = '';
};

// Export for ES6 modules
export default VehiclesList;
