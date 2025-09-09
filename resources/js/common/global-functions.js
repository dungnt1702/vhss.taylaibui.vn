/**
 * Global utility functions used across the application
 */

// Password toggle functionality
window.togglePassword = function(fieldId) {
    const field = document.getElementById(fieldId);
    const eyeIcon = document.getElementById(fieldId + '-eye');
    
    if (field && eyeIcon) {
        if (field.type === 'password') {
            field.type = 'text';
            eyeIcon.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                </svg>
            `;
        } else {
            field.type = 'password';
            eyeIcon.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            `;
        }
    }
};

// Select all checkboxes functionality
window.selectAll = function() {
    document.querySelectorAll('input[type="checkbox"][name="permissions[]"]').forEach(checkbox => {
        checkbox.checked = true;
    });
};

// Confirmation dialogs - now using modal system
window.confirmDelete = function(message = 'Bạn có chắc chắn muốn xóa?') {
    // This function is kept for backward compatibility but now uses modal
    // The actual implementation should be handled by the specific class that calls it
    return confirm(message); // Fallback to native confirm for now
};

// Section toggle functionality
window.toggleSection = function(sectionId) {
    const section = document.getElementById(sectionId);
    const arrow = document.getElementById(sectionId.replace('-section', '-arrow'));
    
    if (section && arrow) {
        section.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
    }
};

// Reset form fields
window.resetReturnNotes = function() {
    const notesField = document.getElementById('return_notes');
    if (notesField) {
        notesField.value = '';
    }
};

// Show schedule details (placeholder)
window.showScheduleDetails = function(scheduleId) {
    // This would typically make an AJAX call to get schedule details
    // For now, we'll just show a placeholder using notification
    if (window.vehicleBase && window.vehicleBase.showNotification) {
        window.vehicleBase.showNotification('Chi tiết lịch bảo trì ID: ' + scheduleId, 'info');
    } else {
        // Fallback to alert if vehicleBase is not available
        alert('Chi tiết lịch bảo trì ID: ' + scheduleId);
    }
};

// Maintenance type change handler
window.handleMaintenanceTypeChange = function() {
    const maintenanceTypeSelect = document.getElementById('maintenance_type_id');
    const detailsDiv = document.getElementById('maintenance-type-details');
    
    if (maintenanceTypeSelect && detailsDiv) {
        const selectedValue = maintenanceTypeSelect.value;
        
        // Clear previous details
        detailsDiv.innerHTML = '';
        
        if (selectedValue) {
            // Show loading or specific details based on type
            detailsDiv.innerHTML = '<p class="text-sm text-gray-600">Đang tải chi tiết...</p>';
        }
    }
};

// Time input handlers
window.setupTimeInputs = function() {
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    
    if (startTimeInput && endTimeInput) {
        startTimeInput.addEventListener('change', function() {
            const startTime = new Date('1970-01-01T' + this.value + ':00');
            const endTime = new Date(startTime.getTime() + 30 * 60000); // Add 30 minutes
            endTimeInput.value = endTime.toTimeString().slice(0, 5);
        });
    }
};

// Initialize common functionality when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Setup time inputs if they exist
    setupTimeInputs();
    
    // Setup maintenance type change handler if it exists
    const maintenanceTypeSelect = document.getElementById('maintenance_type_id');
    if (maintenanceTypeSelect) {
        maintenanceTypeSelect.addEventListener('change', handleMaintenanceTypeChange);
    }
});
