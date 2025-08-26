/**
 * Vehicle Forms and Modals - JavaScript Module
 * Handles vehicle creation, editing, and modal interactions
 */

class VehicleForms {
    constructor() {
        this.initializeEventListeners();
        this.expandedVehicles = new Set();
    }

    initializeEventListeners() {
        // Vehicle form submission
        const vehicleForm = document.getElementById('vehicle-form');
        if (vehicleForm) {
            vehicleForm.addEventListener('submit', this.handleVehicleFormSubmit.bind(this));
        }

        // Status form submission
        const statusForm = document.getElementById('status-form');
        if (statusForm) {
            statusForm.addEventListener('submit', this.handleStatusFormSubmit.bind(this));
        }

        // Workshop form submission
        const workshopForm = document.getElementById('workshop-form');
        if (workshopForm) {
            workshopForm.addEventListener('submit', this.handleWorkshopFormSubmit.bind(this));
        }
    }

    // Vehicle Modal Functions
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
        
        modal.classList.remove('hidden');
    }

    closeVehicleModal() {
        const modal = document.getElementById('vehicle-modal');
        modal.classList.add('hidden');
        this.resetVehicleForm();
    }

    resetVehicleForm() {
        const form = document.getElementById('vehicle-form');
        if (form) {
            form.reset();
            document.getElementById('vehicle-edit-id').value = '';
        }
    }

    loadVehicleData(vehicleId) {
        fetch(`/vehicles/${vehicleId}/edit`)
            .then(response => response.json())
            .then(vehicle => {
                try {
                    document.getElementById('vehicle-edit-id').value = vehicle.id;
                    document.getElementById('vehicle-name').value = vehicle.name;
                    document.getElementById('vehicle-color').value = vehicle.color;
                    document.getElementById('vehicle-seats').value = vehicle.seats;
                    document.getElementById('vehicle-power').value = vehicle.power;
                    document.getElementById('vehicle-wheel-size').value = vehicle.wheel_size;
                    document.getElementById('vehicle-notes').value = vehicle.notes || '';
                } catch (error) {
                    // Silent error handling
                }
            })
            .catch(error => {
                // Silent error handling
            });
    }

    handleVehicleFormSubmit(event) {
        event.preventDefault();
        
        try {
            // Get form elements directly
            const vehicleId = document.getElementById('vehicle-edit-id').value;
            const isEdit = vehicleId && vehicleId !== '';
            
            const url = isEdit ? `/vehicles/${vehicleId}` : '/vehicles';
            const method = isEdit ? 'PUT' : 'POST';
            
            // Create form data for submission
            const submitData = new FormData();
            submitData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            submitData.append('name', document.getElementById('vehicle-name').value);
            submitData.append('color', document.getElementById('vehicle-color').value);
            submitData.append('seats', document.getElementById('vehicle-seats').value);
            submitData.append('power', document.getElementById('vehicle-power').value);
            submitData.append('wheel_size', document.getElementById('vehicle-wheel-size').value);
            submitData.append('notes', document.getElementById('vehicle-notes').value);
            
            fetch(url, {
                method: method,
                body: submitData
            })
            .then(response => {
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    this.closeVehicleModal();
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra: ' + data.message);
                }
            })
            .catch(error => {
                alert('Có lỗi xảy ra khi ' + (isEdit ? 'cập nhật' : 'thêm') + ' xe');
            });
        } catch (error) {
            alert('Có lỗi JavaScript: ' + error.message);
        }
    }

    handleStatusFormSubmit(event) {
        event.preventDefault();
        
        const vehicleId = document.getElementById('vehicle-id').value;
        const formData = new FormData(event.target);
        
        fetch(`/vehicles/${vehicleId}/status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                status: formData.get('status'),
                notes: formData.get('notes')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.closeStatusModal();
                location.reload();
            } else {
                alert('Có lỗi xảy ra: ' + data.message);
            }
        })
        .catch(error => {
            alert('Có lỗi xảy ra khi cập nhật trạng thái xe');
        });
    }

    handleWorkshopFormSubmit(event) {
        event.preventDefault();
        
        const vehicleId = document.getElementById('workshop-vehicle-id').value;
        const formData = new FormData(event.target);
        
        fetch(`/vehicles/${vehicleId}/workshop`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.closeWorkshopModal();
                location.reload();
            } else {
                alert('Có lỗi xảy ra: ' + data.message);
            }
        })
        .catch(error => {
            alert('Có lỗi xảy ra khi gửi xe về xưởng');
        });
    }

    // Status Modal Functions
    openStatusModal(vehicleId, currentStatus, currentNotes) {
        document.getElementById('vehicle-id').value = vehicleId;
        document.getElementById('status-select').value = currentStatus;
        document.getElementById('status-notes').value = currentNotes || '';
        document.getElementById('status-modal').classList.remove('hidden');
    }

    closeStatusModal() {
        document.getElementById('status-modal').classList.add('hidden');
    }

    // Workshop Modal Functions
    openWorkshopModal(vehicleId) {
        document.getElementById('workshop-vehicle-id').value = vehicleId;
        document.getElementById('workshop-modal').classList.remove('hidden');
    }

    closeWorkshopModal() {
        document.getElementById('workshop-modal').classList.add('hidden');
    }

    // Vehicle collapse/expand functionality using specific IDs
    toggleVehicle(vehicleId) {
        // Use specific IDs to toggle the exact vehicle clicked
        const contentId = `content-${vehicleId}`;
        const iconId = `icon-${vehicleId}`;
        
        const content = document.getElementById(contentId);
        const icon = document.getElementById(iconId);
        const card = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
        
        if (!content || !icon || !card) {
            console.warn(`Vehicle elements not found: contentId=${contentId}, iconId=${iconId}, vehicleId=${vehicleId}`);
            return;
        }
        
        const isHidden = content.classList.contains('hidden');
        
        if (isHidden) {
            // Close all other vehicle contents first using their specific IDs
            this.closeAllOtherVehicles(contentId);
            
            // Expand the clicked vehicle using its specific ID
            this.openVehicle(content, icon, card, vehicleId);
        } else {
            // Collapse the clicked vehicle using its specific ID
            this.closeVehicle(content, icon, card, vehicleId);
        }
    }
    
    // Helper function to close all other vehicles
    closeAllOtherVehicles(currentContentId) {
        // Only close other vehicles, not the current one
        const allContents = document.querySelectorAll('.vehicle-content');
        
        allContents.forEach((contentEl) => {
            const contentId = contentEl.id;
            if (contentId !== currentContentId) {
                // Close this content
                contentEl.classList.add('hidden');
                
                // Find and reset the corresponding icon
                const vehicleId = contentId.replace('content-', '');
                const icon = document.getElementById(`icon-${vehicleId}`);
                if (icon) {
                    icon.style.transform = 'rotate(0deg)';
                }
                
                // Find and reset the corresponding card
                const card = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
                if (card) {
                    card.style.transform = 'scale(1)';
                }
            }
        });
    }
    
    // Helper function to open a specific vehicle
    openVehicle(content, icon, card, vehicleId) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
        this.expandedVehicles.add(vehicleId);
        
        // Add smooth animation
        card.style.transition = 'all 0.3s ease-in-out';
        card.style.transform = 'scale(1.02)';
        
        // Reset transform after animation
        setTimeout(() => {
            card.style.transform = 'scale(1)';
        }, 300);
    }
    
    // Helper function to close a specific vehicle
    closeVehicle(content, icon, card, vehicleId) {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
        this.expandedVehicles.delete(vehicleId);
        
        // Add smooth animation
        card.style.transition = 'all 0.3s ease-in-out';
        card.style.transform = 'scale(0.98)';
        
        // Reset transform after animation
        setTimeout(() => {
            card.style.transform = 'scale(1)';
        }, 300);
    }

    // Utility Functions
    addSmoothAnimations() {
        const vehicleCards = document.querySelectorAll('.vehicle-card');
        vehicleCards.forEach(card => {
            card.style.transition = 'all 0.3s ease-in-out';
            card.style.transform = 'scale(1)';
            
            // Add hover effects
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'scale(1.02)';
                card.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.15)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'scale(1)';
                card.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
            });
        });
    }

    // Initialize page
    initializePage() {
        // Add smooth animation to all vehicle cards
        this.addSmoothAnimations();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.vehicleForms = new VehicleForms();
    window.vehicleForms.initializePage();
});

// Export for global access
window.VehicleForms = VehicleForms;
