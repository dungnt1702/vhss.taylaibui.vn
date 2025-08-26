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
    
    if (nameField) nameField.value = vehicleData.name || '';
    if (colorField) colorField.value = vehicleData.color || '';
    if (seatsField) seatsField.value = vehicleData.seats || '';
    if (powerField) powerField.value = vehicleData.power || '';
    if (wheelSizeField) wheelSizeField.value = vehicleData.wheel_size || '';
    if (notesField) notesField.value = vehicleData.notes || '';
    
    // Update color preview
    if (colorPreview) {
        colorPreview.style.backgroundColor = vehicleData.color || '#ffffff';
        colorPreview.style.borderColor = vehicleData.color || '#d1d5db';
    }
    if (colorName) colorName.textContent = vehicleData.color || 'Chọn màu';
}

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
        const colorName = document.getElementById('color-name');
        if (colorPreview) {
            colorPreview.style.backgroundColor = '#ffffff';
            colorPreview.style.borderColor = '#d1d5db';
        }
        if (colorName) colorName.textContent = 'Chọn màu';
    }
}

window.closeVehicleModal = function() {
    if (window.vehicleForms) {
        window.vehicleForms.closeVehicleModal();
    }
};

window.loadVehicleData = function(vehicleId) {
    if (window.vehicleForms) {
        window.vehicleForms.loadVehicleData(vehicleId);
    }
};

window.openStatusModal = function(vehicleId, currentStatus, currentNotes) {
    if (window.vehicleForms) {
        window.vehicleForms.openStatusModal(vehicleId, currentStatus, currentNotes);
    }
};

window.closeStatusModal = function() {
    if (window.vehicleForms) {
        window.vehicleForms.closeStatusModal();
    }
};

window.showWorkshopModal = function(vehicleId) {
    if (window.vehicleForms) {
        window.vehicleForms.openWorkshopModal(vehicleId);
    }
};

window.closeWorkshopModal = function() {
    if (window.vehicleForms) {
        window.vehicleForms.closeWorkshopModal();
    }
};

window.deleteVehicle = function(vehicleId) {
    if (window.vehicleManager) {
        window.vehicleManager.deleteVehicle(vehicleId);
    }
};
