/**
 * Vehicles List JavaScript Module
 * Handles vehicle detail modal and related functionality
 */

// Vehicle Detail Modal Functions
function openVehicleDetailModal(vehicleId) {
    // Call API to get vehicle data from database
    fetch(`/api/vehicles/${vehicleId}/data`)
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                const vehicleData = result.data;
                console.log('Vehicle data for detail modal:', vehicleData);
                
                // Tạo nội dung HTML từ dữ liệu API
                const content = `
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
                
                // Hiển thị nội dung
                document.getElementById('vehicle-detail-content').innerHTML = content;
                
                // Hiển thị modal
                document.getElementById('vehicle-detail-modal').classList.remove('hidden');
            } else {
                console.error('Failed to get vehicle data:', result.message);
                alert('Không thể lấy thông tin xe: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error fetching vehicle data:', error);
            alert('Lỗi khi lấy thông tin xe: ' + error.message);
        });
}

function closeVehicleDetailModal() {
    document.getElementById('vehicle-detail-modal').classList.add('hidden');
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('vehicle-detail-modal');
        if (event.target === modal) {
            closeVehicleDetailModal();
        }
    });

    // Close modal when pressing Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeVehicleDetailModal();
        }
    });
});

// Export functions to global scope
window.openVehicleDetailModal = openVehicleDetailModal;
window.closeVehicleDetailModal = closeVehicleDetailModal;

// Import and re-export vehicle management functions from vehicle-wrappers
window.openEditVehicleModal = function(vehicleId = null) {
    console.log('=== openEditVehicleModal called with vehicleId:', vehicleId, '===');
    
    if (vehicleId) {
        // Edit mode - Fetch vehicle data via API
        fetch(`/api/vehicles/${vehicleId}/data`)
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    const vehicleData = result.data;
                    console.log('Vehicle data from API for edit:', vehicleData);
                    
                    // Populate the edit form
                    populateEditVehicleForm(vehicleData);
                    
                    // Open the modal
                    const modal = document.getElementById('vehicle-modal');
                    if (modal) {
                        modal.classList.remove('hidden');
                        
                        // Update modal title and submit button
                        const modalTitle = document.getElementById('vehicle-modal-title');
                        const submitBtn = document.getElementById('vehicle-submit-btn');
                        if (modalTitle) modalTitle.textContent = 'Chỉnh sửa xe';
                        if (submitBtn) submitBtn.textContent = 'Cập nhật';
                        
                        // Set vehicle ID for edit mode
                        const vehicleEditId = document.getElementById('vehicle-edit-id');
                        if (vehicleEditId) vehicleEditId.value = vehicleId;
                    }
                } else {
                    console.error('Failed to get vehicle data for edit:', result.message);
                    alert('Không thể lấy thông tin xe để chỉnh sửa: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error fetching vehicle data for edit:', error);
                alert('Lỗi khi lấy thông tin xe để chỉnh sửa: ' + error.message);
            });
    } else {
        // Create mode - Open modal without data
        const modal = document.getElementById('vehicle-modal');
        if (modal) {
            modal.classList.remove('hidden');
            
            // Update modal title and submit button
            const modalTitle = document.getElementById('vehicle-modal-title');
            const submitBtn = document.getElementById('vehicle-submit-btn');
            if (modalTitle) modalTitle.textContent = 'Thêm xe mới';
            if (submitBtn) submitBtn.textContent = 'Thêm xe';
            
            // Clear form for new vehicle
            clearEditVehicleForm();
        }
    }
};

// Function to populate edit form with vehicle data
function populateEditVehicleForm(vehicleData) {
    console.log('=== populateEditVehicleForm called with:', vehicleData, '===');
    
    // Populate form fields
    const nameField = document.getElementById('vehicle-name');
    const colorField = document.getElementById('vehicle-color');
    const seatsField = document.getElementById('vehicle-seats');
    const powerField = document.getElementById('vehicle-power');
    const wheelSizeField = document.getElementById('vehicle-wheel-size');
    const notesField = document.getElementById('vehicle-notes');
    const colorPreview = document.getElementById('color-preview');
    const colorName = document.getElementById('color-name');
    
    console.log('=== Form Fields Found ===');
    console.log('Name field:', nameField);
    console.log('Color field:', colorField);
    console.log('Seats field:', seatsField);
    console.log('Power field:', powerField);
    console.log('Wheel size field:', wheelSizeField);
    console.log('Notes field:', notesField);
    
    // Populate fields with validation and proper event triggering
    if (nameField) {
        nameField.value = vehicleData.name || '';
        nameField.defaultValue = vehicleData.name || '';  // Thêm defaultValue
        nameField.setAttribute('value', vehicleData.name || '');
        nameField.dispatchEvent(new Event('input', { bubbles: true }));
        nameField.dispatchEvent(new Event('change', { bubbles: true }));
        console.log('Set name field to:', nameField.value);
    } else {
        console.error('Name field not found!');
    }
    
    if (colorField) {
        colorField.value = vehicleData.color || '';
        colorField.defaultValue = vehicleData.color || '';  // Thêm defaultValue
        colorField.setAttribute('value', vehicleData.color || '');
        colorField.dispatchEvent(new Event('input', { bubbles: true }));
        colorField.dispatchEvent(new Event('change', { bubbles: true }));
        console.log('Set color field to:', colorField.value);
    } else {
        console.error('Color field not found!');
    }
    
    if (seatsField) {
        seatsField.value = vehicleData.seats || '';
        seatsField.defaultValue = vehicleData.seats || '';  // Thêm defaultValue
        seatsField.setAttribute('value', vehicleData.seats || '');
        seatsField.dispatchEvent(new Event('input', { bubbles: true }));
        seatsField.dispatchEvent(new Event('change', { bubbles: true }));
        console.log('Set seats field to:', seatsField.value);
    } else {
        console.error('Seats field not found!');
    }
    
    if (powerField) {
        powerField.value = vehicleData.power || '';
        powerField.defaultValue = vehicleData.power || '';  // Thêm defaultValue
        powerField.setAttribute('value', vehicleData.power || '');
        powerField.dispatchEvent(new Event('input', { bubbles: true }));
        powerField.dispatchEvent(new Event('change', { bubbles: true }));
        console.log('Set power field to:', powerField.value);
    } else {
        console.error('Power field not found!');
    }
    
    if (wheelSizeField) {
        wheelSizeField.value = vehicleData.wheel_size || '';
        wheelSizeField.defaultValue = vehicleData.wheel_size || '';  // Thêm defaultValue
        wheelSizeField.setAttribute('value', vehicleData.wheel_size || '');
        wheelSizeField.dispatchEvent(new Event('input', { bubbles: true }));
        wheelSizeField.dispatchEvent(new Event('change', { bubbles: true }));
        console.log('Set wheel size field to:', wheelSizeField.value);
    } else {
        console.error('Wheel size field not found!');
    }
    
    if (notesField) {
        notesField.value = vehicleData.notes || '';
        notesField.defaultValue = vehicleData.notes || '';  // Thêm defaultValue
        notesField.setAttribute('value', vehicleData.notes || '');
        notesField.dispatchEvent(new Event('input', { bubbles: true }));
        notesField.dispatchEvent(new Event('change', { bubbles: true }));
        console.log('Set notes field to:', notesField.value);
    } else {
        console.error('Notes field not found!');
    }
    
    // Update color preview
    if (colorPreview) {
        colorPreview.style.backgroundColor = vehicleData.color || '#ffffff';
        colorPreview.style.borderColor = vehicleData.color || '#d1d5db';
        console.log('Updated color preview to:', vehicleData.color);
    }
    
    // Verify form data after population (optimized)
    const startTime = performance.now();
    requestAnimationFrame(() => {
        console.log('=== Form Data Verification ===');
        console.log('Name field value:', document.getElementById('vehicle-name')?.value);
        console.log('Color field value:', document.getElementById('vehicle-color')?.value);
        console.log('Seats field value:', document.getElementById('vehicle-seats')?.value);
        console.log('Power field value:', document.getElementById('vehicle-power')?.value);
        console.log('Wheel size field value:', document.getElementById('vehicle-wheel-size')?.value);
        console.log('Notes field value:', document.getElementById('vehicle-notes')?.value);
        
        const endTime = performance.now();
        console.log(`Form verification completed in ${(endTime - startTime).toFixed(2)}ms`);
        console.log('=== Form Population Complete ===');
    });
}

// Global error handler for message channel errors
window.addEventListener('error', function(event) {
    if (event.message && event.message.includes('message channel closed')) {
        console.warn('Message channel error detected, this is usually harmless and caused by browser extensions');
        event.preventDefault();
    }
});

// Function to clear edit form
function clearEditVehicleForm() {
    const form = document.getElementById('vehicle-form');
    if (form) {
        form.reset();
        
        // Clear vehicle edit ID
        const vehicleEditId = document.getElementById('vehicle-edit-id');
        if (vehicleEditId) vehicleEditId.value = '';
        
        // Reset color preview
        const colorPreview = document.getElementById('color-preview');
        if (colorPreview) {
            colorPreview.style.backgroundColor = '#808080';
            colorPreview.style.borderColor = '#d1d5db';
        }
    }
}

window.closeVehicleModal = function() {
    const modal = document.getElementById('vehicle-modal');
    if (modal) {
        modal.classList.add('hidden');
    }
};

window.loadVehicleData = function(vehicleId) {
    // This function is handled by openEditVehicleModal now
    openEditVehicleModal(vehicleId);
};

window.openStatusModal = function(vehicleId, currentStatus, currentNotes) {
    const modal = document.getElementById('status-modal');
    if (modal) {
        // Set vehicle ID
        const vehicleIdField = document.getElementById('vehicle-id');
        if (vehicleIdField) vehicleIdField.value = vehicleId;
        
        // Set current status
        const statusSelect = document.getElementById('status-select');
        if (statusSelect) statusSelect.value = currentStatus;
        
        // Set current notes
        const notesField = document.getElementById('status-notes');
        if (notesField) notesField.value = currentNotes || '';
        
        // Show modal
        modal.classList.remove('hidden');
    }
};

window.closeStatusModal = function() {
    const modal = document.getElementById('status-modal');
    if (modal) {
        modal.classList.add('hidden');
    }
};

window.showWorkshopModal = function(vehicleId) {
    const modal = document.getElementById('workshop-modal');
    if (modal) {
        // Set vehicle ID
        const vehicleIdField = document.getElementById('workshop-vehicle-id');
        if (vehicleIdField) vehicleIdField.value = vehicleId;
        
        // Show modal
        modal.classList.remove('hidden');
    }
};

window.closeWorkshopModal = function() {
    const modal = document.getElementById('workshop-modal');
    if (modal) {
        modal.classList.add('hidden');
    }
};

window.deleteVehicle = function(vehicleId) {
    if (confirm('Bạn có chắc chắn muốn xóa xe này?')) {
        fetch(`/vehicles/${vehicleId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (response.ok) {
                // Reload page to refresh vehicle list
                window.location.reload();
            } else {
                alert('Có lỗi xảy ra khi xóa xe');
            }
        })
        .catch(error => {
            console.error('Error deleting vehicle:', error);
            alert('Có lỗi xảy ra khi xóa xe');
        });
    }
};
