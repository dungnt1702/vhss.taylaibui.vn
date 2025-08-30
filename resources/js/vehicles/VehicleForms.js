/**
 * VehicleForms - Vehicle forms and modals management class
 * Extends VehicleBase with form-specific functionality
 */

import { VehicleBase } from './VehicleBase.js';

class VehicleForms extends VehicleBase {
    constructor() {
        super('Vehicle Forms');
        this.expandedVehicles = new Set();
    }

    /**
     * Initialize vehicle forms page
     */
    init() {
        super.init();
        this.setupFormSpecificFeatures();
        console.log('Vehicle Forms page fully initialized');
    }

    /**
     * Setup form-specific features
     */
    setupFormSpecificFeatures() {
        this.setupVehicleForm();
        this.setupStatusForm();
        this.setupWorkshopForm();
        this.setupGlobalFunctions();
    }

    /**
     * Setup vehicle form
     */
    setupVehicleForm() {
        const vehicleForm = document.getElementById('vehicle-form');
        if (vehicleForm) {
            vehicleForm.addEventListener('submit', this.handleVehicleFormSubmit.bind(this));
        }
    }

    /**
     * Setup status form
     */
    setupStatusForm() {
        const statusForm = document.getElementById('status-form');
        if (statusForm) {
            statusForm.addEventListener('submit', this.handleStatusFormSubmit.bind(this));
        }
    }

    /**
     * Setup workshop form
     */
    setupWorkshopForm() {
        const workshopForm = document.getElementById('workshop-form');
        if (workshopForm) {
            workshopForm.addEventListener('submit', this.handleWorkshopFormSubmit.bind(this));
        }
    }

    /**
     * Setup global functions for backward compatibility
     */
    setupGlobalFunctions() {
        // Make functions available globally for onclick handlers
        window.openVehicleModal = (vehicleId = null) => this.openVehicleModal(vehicleId);
        window.closeVehicleModal = () => this.closeVehicleModal();
        window.openStatusModal = (vehicleId = null) => this.openStatusModal(vehicleId);
        window.closeStatusModal = () => this.closeStatusModal();
        window.openWorkshopModal = (vehicleId = null) => this.openWorkshopModal(vehicleId);
        window.closeWorkshopModal = () => this.closeWorkshopModal();
        window.toggleVehicleExpansion = (vehicleId) => this.toggleVehicleExpansion(vehicleId);
    }

    /**
     * Open vehicle modal
     */
    openVehicleModal(vehicleId = null) {
        const modal = document.getElementById('vehicle-modal');
        const modalTitle = document.getElementById('vehicle-modal-title');
        const submitBtn = document.getElementById('vehicle-submit-btn');
        
        if (vehicleId) {
            // Edit mode
            modalTitle.textContent = 'Chỉnh sửa xe';
            submitBtn.textContent = 'Cập nhật';
            this.loadVehicleData(vehicleId);
        } else {
            // Create mode
            modalTitle.textContent = 'Thêm xe mới';
            submitBtn.textContent = 'Thêm xe';
            this.resetVehicleForm();
        }
        
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    /**
     * Close vehicle modal
     */
    closeVehicleModal() {
        const modal = document.getElementById('vehicle-modal');
        if (modal) {
            modal.classList.add('hidden');
            this.resetVehicleForm();
        }
    }

    /**
     * Reset vehicle form
     */
    resetVehicleForm() {
        const form = document.getElementById('vehicle-form');
        if (form) {
            form.reset();
            const editIdInput = document.getElementById('vehicle-edit-id');
            if (editIdInput) {
                editIdInput.value = '';
            }
        }
    }

    /**
     * Load vehicle data for editing
     */
    async loadVehicleData(vehicleId) {
        try {
            const response = await fetch(`/vehicles/${vehicleId}/edit`);
            const vehicle = await response.json();
            
            this.populateVehicleForm(vehicle);
        } catch (error) {
            console.error('Error loading vehicle data:', error);
            this.showNotification('Không thể tải thông tin xe', 'error');
        }
    }

    /**
     * Populate vehicle form with data
     */
    populateVehicleForm(vehicle) {
        try {
            const fields = {
                'vehicle-edit-id': vehicle.id,
                'vehicle-name': vehicle.name,
                'vehicle-color': vehicle.color,
                'vehicle-seats': vehicle.seats,
                'vehicle-power': vehicle.power,
                'vehicle-wheel-size': vehicle.wheel_size,
                'vehicle-notes': vehicle.notes || ''
            };

            Object.entries(fields).forEach(([fieldId, value]) => {
                const element = document.getElementById(fieldId);
                if (element) {
                    element.value = value;
                }
            });
        } catch (error) {
            console.error('Error populating form:', error);
        }
    }

    /**
     * Handle vehicle form submission
     */
    async handleVehicleFormSubmit(event) {
        event.preventDefault();
        
        try {
            const vehicleId = document.getElementById('vehicle-edit-id')?.value;
            const isEdit = vehicleId && vehicleId !== '';
            
            const url = isEdit ? `/vehicles/${vehicleId}` : '/vehicles';
            const method = isEdit ? 'PUT' : 'POST';
            
            const formData = this.getVehicleFormData();
            
            const response = await this.makeApiCall(url, {
                method: method,
                body: JSON.stringify(formData)
            });

            if (response.success) {
                this.closeVehicleModal();
                this.showNotification(
                    isEdit ? 'Cập nhật xe thành công!' : 'Thêm xe thành công!', 
                    'success'
                );
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error submitting vehicle form:', error);
            this.showNotification('Có lỗi xảy ra khi lưu xe', 'error');
        }
    }

    /**
     * Get vehicle form data
     */
    getVehicleFormData() {
        const fields = [
            'vehicle-name', 'vehicle-color', 'vehicle-seats', 
            'vehicle-power', 'vehicle-wheel-size', 'vehicle-notes'
        ];
        
        const data = {};
        fields.forEach(fieldId => {
            const element = document.getElementById(fieldId);
            if (element) {
                const fieldName = fieldId.replace('vehicle-', '');
                data[fieldName] = element.value;
            }
        });
        
        return data;
    }

    /**
     * Handle status form submission
     */
    async handleStatusFormSubmit(event) {
        event.preventDefault();
        
        try {
            const formData = new FormData(event.target);
            const statusData = {
                status: formData.get('status'),
                notes: formData.get('notes')
            };
            
            const response = await this.makeApiCall('/api/vehicles/status', {
                method: 'POST',
                body: JSON.stringify(statusData)
            });

            if (response.success) {
                this.closeStatusModal();
                this.showNotification('Cập nhật trạng thái thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error submitting status form:', error);
            this.showNotification('Có lỗi xảy ra khi cập nhật trạng thái', 'error');
        }
    }

    /**
     * Handle workshop form submission
     */
    async handleWorkshopFormSubmit(event) {
        event.preventDefault();
        
        try {
            const formData = new FormData(event.target);
            const workshopData = {
                vehicle_id: formData.get('vehicle_id'),
                workshop_type: formData.get('workshop_type'),
                notes: formData.get('notes')
            };
            
            const response = await this.makeApiCall('/api/vehicles/workshop', {
                method: 'POST',
                body: JSON.stringify(workshopData)
            });

            if (response.success) {
                this.closeWorkshopModal();
                this.showNotification('Chuyển xe về xưởng thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error submitting workshop form:', error);
            this.showNotification('Có lỗi xảy ra khi chuyển xe về xưởng', 'error');
        }
    }

    /**
     * Open status modal
     */
    openStatusModal(vehicleId = null) {
        const modal = document.getElementById('status-modal');
        if (modal) {
            if (vehicleId) {
                const vehicleIdInput = document.getElementById('status-vehicle-id');
                if (vehicleIdInput) {
                    vehicleIdInput.value = vehicleId;
                }
            }
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
     * Open workshop modal
     */
    openWorkshopModal(vehicleId = null) {
        const modal = document.getElementById('workshop-modal');
        if (modal) {
            if (vehicleId) {
                const vehicleIdInput = document.getElementById('workshop-vehicle-id');
                if (vehicleIdInput) {
                    vehicleIdInput.value = vehicleId;
                }
            }
            modal.classList.remove('hidden');
        }
    }

    /**
     * Close workshop modal
     */
    closeWorkshopModal() {
        const modal = document.getElementById('workshop-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
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
            this.expandedVehicles.add(vehicleId);
        } else {
            // Close the clicked vehicle
            content.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
            this.expandedVehicles.delete(vehicleId);
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
                
                this.expandedVehicles.delete(vehicleId);
            }
        });
    }

    /**
     * Validate vehicle form
     */
    validateVehicleForm() {
        const requiredFields = ['vehicle-name', 'vehicle-color', 'vehicle-seats'];
        let isValid = true;
        
        requiredFields.forEach(fieldId => {
            const element = document.getElementById(fieldId);
            if (element && !element.value.trim()) {
                element.classList.add('border-red-500');
                isValid = false;
            } else if (element) {
                element.classList.remove('border-red-500');
            }
        });
        
        return isValid;
    }

    /**
     * Show form validation errors
     */
    showFormValidationErrors(errors) {
        Object.entries(errors).forEach(([field, message]) => {
            const element = document.getElementById(`vehicle-${field}`);
            if (element) {
                element.classList.add('border-red-500');
                
                // Add error message below field
                const errorDiv = document.createElement('div');
                errorDiv.className = 'text-red-500 text-sm mt-1';
                errorDiv.textContent = message;
                
                const existingError = element.parentNode.querySelector('.text-red-500');
                if (existingError) {
                    existingError.remove();
                }
                
                element.parentNode.appendChild(errorDiv);
            }
        });
    }

    /**
     * Clear form validation errors
     */
    clearFormValidationErrors() {
        const errorElements = document.querySelectorAll('.text-red-500');
        errorElements.forEach(element => element.remove());
        
        const formFields = document.querySelectorAll('#vehicle-form input, #vehicle-form select, #vehicle-form textarea');
        formFields.forEach(field => field.classList.remove('border-red-500'));
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Vehicle Forms page loaded');
    
    // Create and initialize VehicleForms instance
    const vehicleForms = new VehicleForms();
    vehicleForms.init();
    
    // Make it available globally for debugging
    window.vehicleForms = vehicleForms;
});

// Export for ES6 modules
export default VehicleForms;
