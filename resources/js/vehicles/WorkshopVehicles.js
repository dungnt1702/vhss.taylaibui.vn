/**
 * WorkshopVehicles - Class for managing workshop vehicles
 * Extends VehicleBase with workshop-specific functionality
 */

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
        this.setupGlobalFunctions();
        console.log('Workshop Vehicles page fully initialized');
    }

    /**
     * Setup workshop-specific features
     */
    setupWorkshopSpecificFeatures() {
        this.setupReturnToYard();
        this.setupEditVehicle();
        this.setupWorkshopActions();
        this.setupReturnToYardModal();
        this.setupTechnicalUpdateModal();
    }

    /**
     * Setup global functions for backward compatibility
     */
    setupGlobalFunctions() {
        // Make functions available globally for onclick handlers
        window.vehicleOperations = {
            openReturnToYardModal: (vehicleId) => this.openReturnToYardModal(vehicleId),
            closeReturnToYardModal: () => this.closeReturnToYardModal(),
            resetReturnNotes: () => this.resetReturnNotes(),
            editVehicle: (vehicleId) => this.editVehicle(vehicleId),
            openTechnicalUpdateModal: (vehicleId) => this.openTechnicalUpdateModal(vehicleId),
            closeTechnicalUpdateModal: () => this.closeTechnicalUpdateModal()
        };
        
        // Add closeEditNotesModal function globally
        window.closeEditNotesModal = () => this.closeEditNotesModal();
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
        this.showNotificationModal(
            'Xác nhận', 
            `Bạn có chắc muốn hoàn thành sửa chữa xe ${vehicleId}?`, 
            'confirm',
            async () => {
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
        );
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
     * Edit vehicle notes - open modal popup for notes only
     */
    async editVehicle(vehicleId) {
        try {
            // Call API to get vehicle data for editing notes
            const response = await fetch(`/api/vehicles/${vehicleId}/data`);
            const result = await response.json();
            
            if (result.success) {
                const vehicleData = result.data;
                console.log('Vehicle data for edit notes modal:', vehicleData);
                
                // Populate form with vehicle data
                this.populateEditNotesForm(vehicleData);
                
                // Update modal title
                const modalTitle = document.getElementById('edit-notes-modal-title');
                if (modalTitle) {
                    modalTitle.textContent = 'Chỉnh sửa ghi chú xe';
                }
                
                // Update vehicle ID in form
                const vehicleEditId = document.getElementById('edit-notes-vehicle-id');
                if (vehicleEditId) {
                    vehicleEditId.value = vehicleId;
                }
                
                // Show modal
                const modal = document.getElementById('edit-notes-modal');
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
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
     * Populate edit notes form with data
     */
    populateEditNotesForm(vehicleData) {
        console.log('=== populateEditNotesForm called with:', vehicleData, '===');
        
        // Populate notes field only
        const notesField = document.getElementById('edit-notes-textarea');
        if (notesField) {
            notesField.value = vehicleData.notes || '';
        }
        
        console.log('Notes form populated with vehicle data');
    }

    /**
     * Close edit notes modal
     */
    closeEditNotesModal() {
        const modal = document.getElementById('edit-notes-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    /**
     * Setup edit notes functionality
     */
    setupEditVehicle() {
        const form = document.getElementById('edit-notes-form');
        if (form) {
            form.addEventListener('submit', (e) => this.handleEditNotesSubmit(e));
        }
    }

    /**
     * Handle edit notes form submission
     */
    async handleEditNotesSubmit(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const vehicleId = formData.get('vehicle_id');
        const notes = formData.get('notes');
        
        const submitBtn = document.getElementById('edit-notes-submit-btn');
        this.showButtonLoading(submitBtn, 'Đang cập nhật...');
        
        try {
            const response = await fetch(`/api/vehicles/${vehicleId}/update-notes`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ notes: notes })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showNotification('Cập nhật ghi chú xe thành công!', 'success');
                this.closeEditNotesModal();
                // Reload page to show updated data
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                this.showNotification('Lỗi khi cập nhật ghi chú: ' + result.message, 'error');
            }
        } catch (error) {
            console.error('Error updating vehicle notes:', error);
            this.showNotification('Lỗi khi cập nhật ghi chú: ' + error.message, 'error');
        } finally {
            this.restoreButtonState(submitBtn);
        }
    }

    /**
     * Setup return to yard modal
     */
    setupReturnToYardModal() {
        const form = document.getElementById('return-to-yard-form');
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleReturnToYardSubmit();
            });
        }
    }

    /**
     * Open return to yard modal
     */
    openReturnToYardModal(vehicleId) {
        const modal = document.getElementById('return-to-yard-modal');
        const vehicleIdInput = document.getElementById('return-vehicle-id');
        const notesTextarea = document.getElementById('return-notes');
        
        if (modal && vehicleIdInput && notesTextarea) {
            vehicleIdInput.value = vehicleId;
            notesTextarea.value = 'Xe hoạt động tốt';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            notesTextarea.focus();
        }
    }

    /**
     * Close return to yard modal
     */
    closeReturnToYardModal() {
        const modal = document.getElementById('return-to-yard-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    /**
     * Reset return notes to empty
     */
    resetReturnNotes() {
        const notesTextarea = document.getElementById('return-notes');
        if (notesTextarea) {
            notesTextarea.value = '';
            notesTextarea.focus();
        }
    }

    /**
     * Handle return to yard form submission
     */
    async handleReturnToYardSubmit() {
        const vehicleIdInput = document.getElementById('return-vehicle-id');
        const notesTextarea = document.getElementById('return-notes');
        const submitButton = document.querySelector('#return-to-yard-form button[type="submit"]');
        
        if (!vehicleIdInput || !notesTextarea || !submitButton) {
            console.error('Required elements not found');
            return;
        }

        const vehicleId = vehicleIdInput.value;
        const notes = notesTextarea.value.trim();

        if (!vehicleId) {
            this.showError('Không tìm thấy ID xe');
            return;
        }

        // Debug logging
        console.log('Returning vehicle to yard:', {
            vehicleId: vehicleId,
            notes: notes
        });

        try {
            // Show loading state
            const originalText = submitButton.textContent;
            submitButton.textContent = 'Đang xử lý...';
            submitButton.disabled = true;
            
            const requestData = {
                vehicle_ids: [vehicleId],
                notes: notes || '' // Đảm bảo gửi chuỗi rỗng nếu notes trống
            };
            
            console.log('Sending request data:', requestData);
            
            const response = await this.makeApiCall('/api/vehicles/return-yard-with-notes', {
                method: 'POST',
                body: JSON.stringify(requestData)
            });

            if (response.success) {
                const message = notes ? 
                    'Đưa xe về bãi và cập nhật ghi chú thành công!' : 
                    'Đưa xe về bãi thành công!';
                this.showNotification(message, 'success');
                this.closeReturnToYardModal();
                // Reload page to update the list
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showError(response.message || 'Có lỗi xảy ra khi đưa xe về bãi');
            }
        } catch (error) {
            console.error('Error returning vehicle to yard:', error);
            this.showError('Có lỗi xảy ra khi đưa xe về bãi');
        } finally {
            // Restore button state
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        }
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

    /**
     * Setup technical update modal
     */
    setupTechnicalUpdateModal() {
        const form = document.getElementById('technical-update-form');
        if (form) {
            form.addEventListener('submit', (event) => this.handleTechnicalUpdateSubmit(event));
        }

        // Initialize with repair categories
        this.updateCategoryOptions('repair');
    }

    /**
     * Open technical update modal
     */
    openTechnicalUpdateModal(vehicleId) {
        console.log('Opening technical update modal for vehicle:', vehicleId);
        
        // Check if modal exists in DOM
        const modal = document.getElementById('technical-update-modal');
        const vehicleIdInput = document.getElementById('technical-vehicle-id');
        
        console.log('Modal found:', modal);
        console.log('Vehicle ID input found:', vehicleIdInput);
        
        if (modal && vehicleIdInput) {
            vehicleIdInput.value = vehicleId;
            modal.classList.remove('hidden');
            modal.classList.add('flex', 'items-center', 'justify-center', 'p-4');
            
            console.log('Modal classes after opening:', modal.className);
            console.log('Modal display style:', modal.style.display);
            console.log('Modal computed style:', window.getComputedStyle(modal).display);
            
            // Reset form
            document.getElementById('technical-update-form').reset();
            vehicleIdInput.value = vehicleId;
            
            // Update category options
            this.updateCategoryOptions('repair');
            
            // Focus on category select
            const categorySelect = document.getElementById('technical-category');
            if (categorySelect) {
                categorySelect.focus();
            }
            
            console.log('Modal should be visible now');
        } else {
            console.error('Modal or vehicle ID input not found!');
            console.error('Modal:', modal);
            console.error('Vehicle ID input:', vehicleIdInput);
            
            // Check if modal exists anywhere in the DOM
            const allModals = document.querySelectorAll('[id*="modal"]');
            console.log('All modals in DOM:', allModals);
        }
    }

    /**
     * Close technical update modal
     */
    closeTechnicalUpdateModal() {
        const modal = document.getElementById('technical-update-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex', 'items-center', 'justify-center', 'p-4');
        }
    }

    /**
     * Update category options based on issue type
     */
    updateCategoryOptions(issueType) {
        console.log('updateCategoryOptions called with issueType:', issueType);
        const categorySelect = document.getElementById('technical-category');
        console.log('categorySelect element:', categorySelect);
        
        if (!categorySelect) {
            console.error('Category select element not found!');
            return;
        }

        // Clear existing options
        categorySelect.innerHTML = '<option value="">-- Chọn hạng mục --</option>';

        if (!issueType) {
            console.log('No issue type provided');
            return;
        }

        // Define categories based on issue type
        const categories = {
            'repair': {
                'engine': 'Động cơ',
                'brake_system': 'Hệ thống phanh',
                'transmission': 'Hộp số',
                'electrical': 'Hệ thống điện',
                'suspension': 'Hệ thống treo',
                'steering': 'Hệ thống lái',
                'exhaust': 'Hệ thống xả',
                'cooling': 'Hệ thống làm mát',
                'fuel_system': 'Hệ thống nhiên liệu',
                'tires': 'Lốp xe',
                'lights': 'Hệ thống đèn',
                'air_conditioning': 'Điều hòa',
                'other': 'Khác'
            },
            'maintenance': {
                'oil_change': 'Thay dầu',
                'filter_replacement': 'Thay lọc',
                'brake_inspection': 'Kiểm tra phanh',
                'tire_rotation': 'Đảo lốp',
                'battery_check': 'Kiểm tra ắc quy',
                'belt_replacement': 'Thay dây curoa',
                'spark_plug': 'Thay bugi',
                'air_filter': 'Thay lọc gió',
                'coolant_check': 'Kiểm tra nước làm mát',
                'general_inspection': 'Kiểm tra tổng thể',
                'cleaning': 'Vệ sinh',
                'other': 'Khác'
            }
        };

        const selectedCategories = categories[issueType] || {};
        
        // Add options
        console.log('Adding categories:', selectedCategories);
        Object.entries(selectedCategories).forEach(([value, label]) => {
            const option = document.createElement('option');
            option.value = value;
            option.textContent = label;
            categorySelect.appendChild(option);
        });
        
        console.log('Final categorySelect options count:', categorySelect.options.length);
        console.log('Category select innerHTML:', categorySelect.innerHTML);
    }

    /**
     * Handle technical update form submission
     */
    async handleTechnicalUpdateSubmit(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const vehicleId = formData.get('vehicle_id');
        const issueType = formData.get('issue_type');
        const category = formData.get('category');
        const description = formData.get('description');
        const notes = formData.get('notes');
        
        const submitBtn = document.getElementById('technical-update-submit-btn');
        this.showButtonLoading(submitBtn, 'Đang cập nhật...');
        
        try {
            const response = await fetch('/api/vehicles/technical-update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    vehicle_id: vehicleId,
                    issue_type: issueType,
                    category: category,
                    description: description,
                    notes: notes
                })
            });

            const result = await response.json();
            
            if (result.success) {
                this.showNotification('Cập nhật kỹ thuật thành công!', 'success');
                this.closeTechnicalUpdateModal();
                // Reload page to show updated data
                window.location.reload();
            } else {
                this.showNotification('Lỗi khi cập nhật kỹ thuật: ' + result.message, 'error');
            }
        } catch (error) {
            console.error('Error updating technical info:', error);
            this.showNotification('Lỗi khi cập nhật kỹ thuật: ' + error.message, 'error');
        } finally {
            this.restoreButtonState(submitBtn);
        }
    }

    /**
     * Close process issue modal
     */
    closeProcessIssueModal() {
        const modal = document.getElementById('process-issue-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    /**
     * Close description detail modal
     */
    closeDescriptionDetailModal() {
        const modal = document.getElementById('description-detail-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    /**
     * Close edit issue modal
     */
    closeEditIssueModal() {
        const modal = document.getElementById('edit-issue-modal');
        if (modal) {
            modal.classList.add('hidden');
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

// Make WorkshopVehicles available globally
window.WorkshopVehicles = WorkshopVehicles;
