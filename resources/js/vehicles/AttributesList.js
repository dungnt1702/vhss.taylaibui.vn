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
        this.setupAttributesSpecificFeatures();
        console.log('Attributes List page fully initialized');
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
                this.showNotification(response.message || 'Thao tác thành công!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showNotification(response.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            console.error('Error performing attribute operation:', error);
            this.showNotification('Có lỗi xảy ra khi thực hiện thao tác', 'error');
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
