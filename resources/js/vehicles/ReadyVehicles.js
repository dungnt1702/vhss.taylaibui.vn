/**
 * ReadyVehicles - Class for managing ready vehicles
 * Extends VehicleBase with ready-specific functionality
 */

class ReadyVehicles extends VehicleBase {
    constructor() {
        super('Ready Vehicles');
        this.specificActions = ['open-workshop-modal'];
    }

    /**
     * Initialize ready vehicles page
     */
    init() {
        super.init();
        this.setupReadySpecificFeatures();
        console.log('Ready Vehicles page fully initialized');
    }

    /**
     * Setup ready-specific features
     */
    setupReadySpecificFeatures() {
        // Add any ready-specific initialization here
        this.setupWorkshopTransfer();
        this.setupWorkshopModal();
    }

    /**
     * Setup workshop transfer functionality
     */
    setupWorkshopTransfer() {
        const workshopButtons = document.querySelectorAll('[data-action="open-workshop-modal"]');
        workshopButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const vehicleId = e.target.dataset.vehicleId;
                if (vehicleId) {
                    this.openWorkshopModal(vehicleId);
                }
            });
        });
    }

    /**
     * Override handleCustomAction for ready-specific actions
     */
    handleCustomAction(action, vehicleId, button) {
        switch (action) {
            case 'open-workshop-modal':
                this.openWorkshopModal(vehicleId);
                break;
            default:
                // Delegate all other actions to VehicleBase
                super.handleCustomAction(action, vehicleId, button);
        }
    }

    /**
     * Setup workshop modal functionality
     */
    setupWorkshopModal() {
        console.log('Setting up workshop modal in ReadyVehicles...');
        const form = document.getElementById('move-workshop-form');
        if (!form) {
            console.log('Workshop form not found in ReadyVehicles');
            return;
        }
        console.log('Workshop form found in ReadyVehicles');

        // Setup form validation
        console.log('Setting up form validation in ReadyVehicles...');
        if (typeof setupWorkshopFormValidation === 'function') {
            setupWorkshopFormValidation();
        } else {
            console.log('setupWorkshopFormValidation function not found');
        }

        form.addEventListener('submit', async (e) => {
            console.log('Workshop form submitted in ReadyVehicles');
            e.preventDefault();
            
            const formData = new FormData(form);
            const vehicleId = formData.get('vehicle_id');
            const reason = formData.get('reason');
            const notes = formData.get('notes');
            
            if (!vehicleId) {
                this.showError('Không tìm thấy ID xe');
                return;
            }
            
            try {
                // Show loading state
                const submitButton = form.querySelector('button[type="submit"]');
                const originalText = submitButton.textContent;
                submitButton.textContent = 'Đang chuyển...';
                submitButton.disabled = true;
                
                // Prepare reason text - get the display text of selected option
                const reasonSelect = form.querySelector('#workshop-reason');
                const selectedOption = reasonSelect.options[reasonSelect.selectedIndex];
                const reasonText = selectedOption.textContent; // Get the display text (e.g., "Bảo trì", "Sửa chữa", etc.)
                const fullReason = `${reasonText} - ${notes}`;
                
                // Call API
                const response = await this.makeApiCall('/api/vehicles/move-workshop', {
                    method: 'POST',
                    body: JSON.stringify({
                        vehicle_id: vehicleId,
                        reason: fullReason
                    })
                });
                
                if (response.success) {
                    this.showSuccess('Xe đã được chuyển về xưởng thành công!');
                    
                    // Close modal
                    this.closeWorkshopModal();
                    
                    // Hide vehicle from ready table
                    this.hideVehicleFromReadyTable(vehicleId);
                    
                    // Reload page after delay
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.showError(response.message || 'Có lỗi xảy ra khi chuyển xe về xưởng');
                }
            } catch (error) {
                console.error('Error moving vehicle to workshop:', error);
                this.showError('Có lỗi xảy ra khi chuyển xe về xưởng');
            } finally {
                // Restore button state
                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.textContent = 'Chuyển về xưởng';
                submitButton.disabled = false;
            }
        });
    }


    /**
     * Open workshop modal for vehicle
     */
    openWorkshopModal(vehicleId) {
        const modal = document.getElementById('move-workshop-modal');
        const vehicleIdInput = document.getElementById('workshop-vehicle-id');
        
        if (modal) {
            // Store vehicle ID in hidden input
            if (vehicleIdInput) {
                vehicleIdInput.value = vehicleId;
            } else {
                // Create hidden input if it doesn't exist
                const form = modal.querySelector('#move-workshop-form');
                if (form) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.id = 'workshop-vehicle-id';
                    hiddenInput.name = 'vehicle_id';
                    hiddenInput.value = vehicleId;
                    form.appendChild(hiddenInput);
                }
            }
            
            // Setup form validation with delay to ensure DOM is ready
            if (typeof setupWorkshopFormValidation === 'function') {
                setTimeout(() => {
                    setupWorkshopFormValidation();
                }, 100);
            }
            
            modal.classList.remove('hidden');
        } else {
            console.error('Workshop modal not found');
            this.showError('Lỗi: Không thể mở modal');
        }
    }

    /**
     * Close workshop modal
     */
    closeWorkshopModal() {
        const modal = document.getElementById('move-workshop-modal');
        if (modal) {
            modal.classList.add('hidden');
            // Reset form
            const form = modal.querySelector('#move-workshop-form');
            if (form) {
                form.reset();
            }
        }
    }

    /**
     * Hide vehicle from ready table
     */
    hideVehicleFromReadyTable(vehicleId) {
        const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
        if (vehicleCard) {
            // Add animation fade out
            vehicleCard.style.transition = 'all 0.3s ease';
            vehicleCard.style.opacity = '0';
            vehicleCard.style.transform = 'scale(0.95)';
            
            // Remove card after animation
            setTimeout(() => {
                if (vehicleCard.parentElement) {
                    vehicleCard.remove();
                    
                    // Check if no more vehicles
                    const remainingCards = document.querySelectorAll('.vehicle-card');
                    if (remainingCards.length === 0) {
                        this.showEmptyReadyState();
                    }
                }
            }, 300);
        }
    }

    /**
     * Show empty state for ready vehicles
     */
    showEmptyReadyState() {
        const vehicleList = document.getElementById('vehicle-list');
        if (vehicleList) {
            vehicleList.innerHTML = `
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-neutral-900">Không có xe nào</h3>
                        <p class="mt-1 text-sm text-neutral-500">
                            Hiện tại không có xe nào sẵn sàng.
                        </p>
                    </div>
                </div>
            `;
        }
    }

    /**
     * Close move workshop modal
     */
    closeMoveWorkshopModal() {
        const modal = document.getElementById('move-workshop-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Ready Vehicles page loaded');
    
    // Create and initialize ReadyVehicles instance
    const readyVehicles = new ReadyVehicles();
    readyVehicles.init();
    
    // Make it available globally for debugging
    window.readyVehicles = readyVehicles;
});

// Make ReadyVehicles available globally
window.ReadyVehicles = ReadyVehicles;
