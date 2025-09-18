/**
 * AttributesList - Class for managing vehicle attributes
 * Extends VehicleBase with attributes-specific functionality
 */


class AttributesList extends VehicleBase {
    constructor() {
        super('Attributes List');
        this.attributeTypes = ['color', 'seat', 'power', 'wheel_size'];
    }

    /**
     * Initialize attributes list page
     */
    init() {
        super.init();
        this.checkAuthentication();
        this.setupAttributesSpecificFeatures();
        this.updateDeleteButtonsVisibility();
        console.log('Attributes List page fully initialized');
    }

    /**
     * Check if user is authenticated
     */
    async checkAuthentication() {
        try {
            const response = await this.makeApiCall('/api/attributes', {
                method: 'GET'
            });
            
            if (!response.success) {
                this.showNotificationModal(
                    'Yêu cầu đăng nhập', 
                    'Vui lòng đăng nhập để sử dụng tính năng này.', 
                    'warning',
                    () => {
                        window.location.href = '/login';
                    }
                );
            }
        } catch (error) {
            if (error.message === 'AUTHENTICATION_REQUIRED') {
                this.showNotificationModal(
                    'Yêu cầu đăng nhập', 
                    'Vui lòng đăng nhập để sử dụng tính năng này.', 
                    'warning',
                    () => {
                        window.location.href = '/login';
                    }
                );
            }
        }
    }

    /**
     * Setup attributes-specific features
     */
    setupAttributesSpecificFeatures() {
        this.setupAddAttribute();
        this.setupDeleteAttribute();
        this.setupAttributeManagement();
        this.setupGlobalFunctions();
    }

    /**
     * Setup add attribute functionality
     */
    setupAddAttribute() {
        const addButtons = document.querySelectorAll('[data-action="add-attribute"]');
        addButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const type = e.target.dataset.attributeType;
                if (type) {
                    this.addAttribute(type);
                }
            });
        });
    }

    /**
     * Setup delete attribute functionality
     */
    setupDeleteAttribute() {
        const deleteButtons = document.querySelectorAll('[data-action="delete-attribute"]');
        deleteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const type = e.target.dataset.attributeType;
                const value = e.target.dataset.attributeValue;
                if (type && value) {
                    this.deleteAttribute(type, value);
                }
            });
        });
    }

    /**
     * Setup attribute management functionality
     */
    setupAttributeManagement() {
        // Add any additional attribute management features here
        const editButtons = document.querySelectorAll('[data-action="edit-attribute"]');
        editButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const type = e.target.dataset.attributeType;
                const value = e.target.dataset.attributeValue;
                if (type && value) {
                    this.editAttribute(type, value);
                }
            });
        });
    }

    /**
     * Setup global functions for backward compatibility
     */
    setupGlobalFunctions() {
        // Make functions available globally for onclick handlers
        window.addAttribute = (type) => this.addAttribute(type);
        window.deleteAttribute = (type, value) => this.deleteAttribute(type, value);
        window.editAttribute = (type, value) => this.editAttribute(type, value);
        
        // Modal functions
        window.openAddAttributeModal = (type) => this.openAddAttributeModal(type);
        window.openEditAttributeModal = (type, value) => this.openEditAttributeModal(type, value);
        window.openDeleteAttributeModal = (type, value) => this.openDeleteAttributeModal(type, value);
        window.closeAttributeModal = () => this.closeAttributeModal();
        window.closeDeleteAttributeModal = () => this.closeDeleteAttributeModal();
        window.closeBulkAddModal = () => this.closeBulkAddModal();
        window.saveAttribute = () => this.saveAttribute();
        window.confirmDeleteAttribute = () => this.confirmDeleteAttribute();
        window.saveBulkAttributes = () => this.saveBulkAttributes();
    }

    /**
     * Add new attribute
     */
    addAttribute(type) {
        const value = prompt(`Nhập giá trị cho ${this.getTypeName(type)}:`);
        if (value) {
            this.performAttributeOperation('/api/attributes/add', {
                type: type,
                value: value
            });
        }
    }

    /**
     * Delete attribute
     */
    deleteAttribute(type, value) {
        // Check if this is the last attribute of this type
        const count = this.getAttributeCount(type);
        if (count <= 1) {
            this.showNotification('Không thể xóa thuộc tính cuối cùng của loại này', 'error');
            return;
        }

        this.showNotificationModal(
            'Xác nhận', 
            `Bạn có chắc muốn xóa ${this.getTypeName(type)} "${value}"?`, 
            'confirm',
            () => {
                this.performAttributeOperation('/api/attributes/delete', {
                    type: type,
                    value: value
                });
            }
        );
    }

    /**
     * Edit attribute
     */
    editAttribute(type, value) {
        // For now, use a simple prompt replacement with modal
        this.showNotificationModal(
            'Chỉnh sửa', 
            `Chỉnh sửa ${this.getTypeName(type)} "${value}":`, 
            'info'
        );
        
        // TODO: Implement proper input modal for editing
        // For now, we'll keep the prompt functionality
        const newValue = prompt(`Chỉnh sửa ${this.getTypeName(type)} "${value}":`, value);
        if (newValue && newValue !== value) {
            this.performAttributeOperation('/api/attributes/edit', {
                type: type,
                old_value: value,
                new_value: newValue
            });
        }
    }

    /**
     * Get type name in Vietnamese
     */
    getTypeName(type) {
        const typeNames = {
            'color': 'màu sắc',
            'seat': 'số ghế',
            'power': 'công suất',
            'wheel_size': 'kích thước bánh',
            'brand': 'thương hiệu',
            'model': 'dòng xe',
            'year': 'năm sản xuất',
            'fuel_type': 'loại nhiên liệu'
        };
        return typeNames[type] || type;
    }

    /**
     * Perform attribute operation
     */
    async performAttributeOperation(url, data) {
        try {
            const response = await this.makeApiCall(url, {
                method: 'POST',
                body: JSON.stringify(data)
            });

            if (response.success) {
                // Close the modal first
                this.closeAttributeModal();
                
                this.showNotificationModal(
                    'Thành công', 
                    response.message || 'Thao tác thành công!', 
                    'success'
                );
                // Update UI instead of reloading
                this.updateAttributeUI(data.type);
            } else {
                this.showNotificationModal(
                    'Lỗi', 
                    response.message || 'Có lỗi xảy ra', 
                    'error'
                );
            }
        } catch (error) {
            console.error('Error performing attribute operation:', error);
            
            // Handle specific error cases
            if (error.message === 'AUTHENTICATION_REQUIRED') {
                this.showNotificationModal(
                    'Yêu cầu đăng nhập', 
                    'Vui lòng đăng nhập để sử dụng tính năng này.', 
                    'warning',
                    () => {
                        window.location.href = '/login';
                    }
                );
                return;
            }
            
            if (error.message.startsWith('VALIDATION_ERROR:')) {
                // Validation error is already handled in makeApiCall
                return;
            }
            
            // Handle 422 validation errors
            if (error.message.includes('HTTP error! status: 422')) {
                alert('Lỗi dữ liệu: Vui lòng kiểm tra lại thông tin nhập vào.');
                return;
            }
            
            this.showNotificationModal(
                'Lỗi hệ thống', 
                'Có lỗi xảy ra khi thực hiện thao tác. Vui lòng thử lại.', 
                'error'
            );
        }
    }

    /**
     * Get attribute count for a specific type
     */
    getAttributeCount(type) {
        const listId = this.getListId(type);
        const list = document.getElementById(listId);
        if (!list) return 0;
        
        return list.querySelectorAll('[data-attribute-type]').length;
    }

    /**
     * Get list ID for attribute type
     */
    getListId(type) {
        const typeMap = {
            'color': 'colors-list',
            'seats': 'seats-list', 
            'power': 'power-list',
            'wheel_size': 'wheel-sizes-list'
        };
        return typeMap[type] || '';
    }

    /**
     * Update attribute UI after operations
     */
    updateAttributeUI(type) {
        // Reload the page to get fresh data
        setTimeout(() => window.location.reload(), 1000);
    }

    /**
     * Update delete buttons visibility
     */
    updateDeleteButtonsVisibility() {
        this.attributeTypes.forEach(type => {
            const count = this.getAttributeCount(type);
            const listId = this.getListId(type);
            const list = document.getElementById(listId);
            
            if (list) {
                const deleteButtons = list.querySelectorAll('button[onclick*="openDeleteAttributeModal"]');
                deleteButtons.forEach(button => {
                    if (count <= 1) {
                        button.style.display = 'none';
                    } else {
                        button.style.display = 'block';
                    }
                });
            }
        });
    }

    /**
     * Open add attribute modal
     */
    openAddAttributeModal(type) {
        this.currentAttributeType = type;
        this.currentAttributeValue = null;
        this.isEditMode = false;
        
        const modal = document.getElementById('attributeModal');
        const title = document.getElementById('attributeModalTitle');
        const label = document.getElementById('attributeLabel');
        const valueInput = document.getElementById('attributeValue');
        const colorPreview = document.getElementById('colorPreview');
        const saveBtn = document.getElementById('saveAttributeBtn');
        
        // Set modal content
        title.textContent = `Thêm ${this.getTypeName(type)} mới`;
        label.textContent = `Giá trị ${this.getTypeName(type)}`;
        valueInput.value = '';
        valueInput.placeholder = `Nhập ${this.getTypeName(type)}...`;
        saveBtn.textContent = 'Thêm';
        
        // Show/hide color preview
        if (type === 'color') {
            colorPreview.classList.remove('hidden');
        } else {
            colorPreview.classList.add('hidden');
        }
        
        // Show modal
        modal.classList.remove('hidden');
        valueInput.focus();
        
        // Add color preview functionality
        if (type === 'color') {
            valueInput.addEventListener('input', this.updateColorPreview.bind(this));
        }
    }

    /**
     * Open edit attribute modal
     */
    openEditAttributeModal(type, value, sortOrder = 1) {
        console.log('openEditAttributeModal called with:', { type, value, sortOrder });
        this.currentAttributeType = type;
        this.currentAttributeValue = value;
        this.currentSortOrder = sortOrder;
        this.isEditMode = true;
        
        const modal = document.getElementById('attributeModal');
        const title = document.getElementById('attributeModalTitle');
        const label = document.getElementById('attributeLabel');
        const valueInput = document.getElementById('attributeValue');
        const sortOrderInput = document.getElementById('attributeSortOrder');
        const colorPreview = document.getElementById('colorPreview');
        const saveBtn = document.getElementById('saveAttributeBtn');
        
        // Set modal content
        title.textContent = `Chỉnh sửa ${this.getTypeName(type)}`;
        label.textContent = `Giá trị ${this.getTypeName(type)}`;
        valueInput.value = value;
        console.log('Setting sort order input value to:', sortOrder);
        sortOrderInput.value = sortOrder;
        console.log('Sort order input value after setting:', sortOrderInput.value);
        saveBtn.textContent = 'Cập nhật';
        
        // Show/hide color preview
        if (type === 'color') {
            colorPreview.classList.remove('hidden');
            document.getElementById('colorPreviewBox').style.backgroundColor = value;
        } else {
            colorPreview.classList.add('hidden');
        }
        
        // Show modal
        modal.classList.remove('hidden');
        valueInput.focus();
        valueInput.select();
    }

    /**
     * Open delete attribute modal
     */
    openDeleteAttributeModal(type, value) {
        this.currentAttributeType = type;
        this.currentAttributeValue = value;
        
        const modal = document.getElementById('deleteAttributeModal');
        const message = document.getElementById('deleteAttributeMessage');
        
        message.textContent = `Bạn có chắc chắn muốn xóa ${this.getTypeName(type)} "${value}"? Hành động này không thể hoàn tác.`;
        
        modal.classList.remove('hidden');
    }

    /**
     * Close attribute modal
     */
    closeAttributeModal() {
        const modal = document.getElementById('attributeModal');
        modal.classList.add('hidden');
        this.resetAttributeForm();
    }

    /**
     * Close delete attribute modal
     */
    closeDeleteAttributeModal() {
        const modal = document.getElementById('deleteAttributeModal');
        modal.classList.add('hidden');
    }

    /**
     * Close bulk add modal
     */
    closeBulkAddModal() {
        const modal = document.getElementById('bulkAddModal');
        modal.classList.add('hidden');
    }

    /**
     * Reset attribute form
     */
    resetAttributeForm() {
        document.getElementById('attributeForm').reset();
        document.getElementById('attributeType').value = '';
        document.getElementById('attributeOldValue').value = '';
        document.getElementById('colorPreview').classList.add('hidden');
    }

    /**
     * Update color preview
     */
    updateColorPreview(event) {
        const colorValue = event.target.value;
        const colorPreviewBox = document.getElementById('colorPreviewBox');
        
        if (colorValue && this.isValidColor(colorValue)) {
            colorPreviewBox.style.backgroundColor = colorValue;
        } else {
            colorPreviewBox.style.backgroundColor = '#ffffff';
        }
    }

    /**
     * Check if color value is valid
     */
    isValidColor(color) {
        // Check if it's a valid hex color
        return /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(color) || 
               /^rgb\(/.test(color) || 
               /^rgba\(/.test(color) ||
               /^hsl\(/.test(color) ||
               /^hsla\(/.test(color);
    }

    /**
     * Save attribute
     */
    async saveAttribute() {
        const form = document.getElementById('attributeForm');
        const formData = new FormData(form);
        
        const data = {
            type: this.currentAttributeType,
            value: formData.get('value'),
            sort_order: formData.get('sort_order') || 1,
            is_active: formData.get('is_active') ? true : false
        };
        
        // Validate required fields
        if (!data.value || data.value.trim() === '') {
            this.showNotificationModal(
                'Lỗi dữ liệu', 
                'Vui lòng nhập giá trị thuộc tính', 
                'error'
            );
            return;
        }
        
        console.log('Saving attribute:', data);
        
        if (this.isEditMode) {
            // For edit mode, rename 'value' to 'new_value' as expected by controller
            data.old_value = this.currentAttributeValue;
            data.new_value = data.value;
            delete data.value; // Remove the original 'value' field
            await this.performAttributeOperation('/api/attributes/edit', data);
        } else {
            await this.performAttributeOperation('/api/attributes/add', data);
        }
    }

    /**
     * Confirm delete attribute
     */
    async confirmDeleteAttribute() {
        await this.performAttributeOperation('/api/attributes/delete', {
            type: this.currentAttributeType,
            value: this.currentAttributeValue
        });
    }

    /**
     * Save bulk attributes
     */
    async saveBulkAttributes() {
        const valuesText = document.getElementById('bulkAttributeValues').value;
        const values = valuesText.split('\n').filter(v => v.trim() !== '');
        
        if (values.length === 0) {
            this.showNotification('Vui lòng nhập ít nhất một giá trị', 'error');
            return;
        }
        
        // Add each value individually
        for (const value of values) {
            await this.performAttributeOperation('/api/attributes/add', {
                type: this.currentAttributeType,
                value: value.trim(),
                sort_order: 1,
                is_active: true
            });
        }
    }

    /**
     * Bulk add attributes
     */
    async bulkAddAttributes(type, values) {
        if (!Array.isArray(values) || values.length === 0) {
            this.showNotification('Danh sách giá trị không hợp lệ', 'error');
            return;
        }

        try {
            const response = await this.makeApiCall('/api/attributes/bulk-add', {
                method: 'POST',
                body: JSON.stringify({
                    type: type,
                    values: values
                })
            });

            if (response.success) {
                this.showNotification(`Đã thêm ${values.length} ${this.getTypeName(type)} thành công!`, 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error bulk adding attributes:', error);
            this.showNotification('Có lỗi xảy ra khi thêm hàng loạt', 'error');
        }
    }

    /**
     * Import attributes from file
     */
    async importAttributes(file) {
        if (!file) {
            this.showNotification('Vui lòng chọn file để import', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('file', file);

        try {
            const response = await fetch('/api/attributes/import', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification(`Import thành công ${result.count} thuộc tính!`, 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(result.message || 'Có lỗi xảy ra khi import', 'error');
            }
        } catch (error) {
            console.error('Error importing attributes:', error);
            this.showNotification('Có lỗi xảy ra khi import', 'error');
        }
    }

    /**
     * Export attributes to file
     */
    async exportAttributes(type = null) {
        try {
            const url = type ? `/api/attributes/export?type=${type}` : '/api/attributes/export';
            const response = await fetch(url);
            
            if (response.ok) {
                const blob = await response.blob();
                const downloadUrl = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = downloadUrl;
                a.download = type ? `attributes_${type}.csv` : 'all_attributes.csv';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(downloadUrl);
                
                this.showNotification('Export thành công!', 'success');
            } else {
                this.showNotification('Có lỗi xảy ra khi export', 'error');
            }
        } catch (error) {
            console.error('Error exporting attributes:', error);
            this.showNotification('Có lỗi xảy ra khi export', 'error');
        }
    }

    /**
     * Get attributes statistics
     */
    getAttributesStats() {
        const stats = {};
        this.attributeTypes.forEach(type => {
            const count = document.querySelectorAll(`[data-attribute-type="${type}"]`).length;
            stats[type] = count;
        });
        
        const total = Object.values(stats).reduce((sum, count) => sum + count, 0);
        stats.total = total;
        
        return stats;
    }

    /**
     * Update attributes display
     */
    updateAttributesDisplay() {
        const stats = this.getAttributesStats();
        
        // Update stats display if it exists
        const statsElement = document.querySelector('.attributes-stats');
        if (statsElement) {
            let statsHtml = '<div class="text-sm text-gray-600">';
            this.attributeTypes.forEach(type => {
                const count = stats[type];
                const typeName = this.getTypeName(type);
                statsHtml += `<span class="font-semibold">${count}</span> ${typeName} | `;
            });
            statsHtml += `<span class="font-semibold">${stats.total}</span> tổng cộng</div>`;
            statsElement.innerHTML = statsHtml;
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Attributes List page loaded');
    
    // Create and initialize AttributesList instance
    const attributesList = new AttributesList();
    attributesList.init();
    
    // Make it available globally for debugging
    window.attributesList = attributesList;
});

// Make AttributesList available globally
window.AttributesList = AttributesList;
