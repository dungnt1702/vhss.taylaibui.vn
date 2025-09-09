/**
 * Global Modal Functions
 * 
 * This file contains all global modal functions that are used across different vehicle screens.
 * These functions are made available immediately when the script loads to ensure they're
 * available for onclick attributes in HTML.
 */

console.log('🔧 Loading GlobalModalFunctions.js...');

// Create global functions immediately
(function() {
    console.log('🔧 Creating global modal functions...');

    // Workshop Modal functions
    window.openWorkshopModal = function(vehicleId) {
        console.log('🔧 openWorkshopModal called with vehicleId:', vehicleId);
        const modal = document.getElementById('move-workshop-modal');
        const vehicleIdInput = document.getElementById('workshop-vehicle-id');
        if (modal && vehicleIdInput) {
            vehicleIdInput.value = vehicleId;
            modal.classList.remove('hidden');
            console.log('✅ Workshop modal opened successfully');
        } else {
            console.error('❌ Workshop modal elements not found');
        }
    };

    window.closeWorkshopModal = function() {
        const modal = document.getElementById('move-workshop-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    window.closeMoveWorkshopModal = function() {
        const modal = document.getElementById('move-workshop-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    // Timer Modal functions
    window.closeStartTimerModal = function() {
        const modal = document.getElementById('start-timer-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    // Route Modal functions
    window.closeAssignRouteModal = function() {
        const modal = document.getElementById('assign-route-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    // Notes Modal functions
    window.closeEditNotesModal = function() {
        const modal = document.getElementById('edit-notes-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    // Technical Update Modal functions
    window.closeTechnicalUpdateModal = function() {
        const modal = document.getElementById('technical-update-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    // Process Issue Modal functions
    window.closeProcessIssueModal = function() {
        const modal = document.getElementById('process-issue-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    // Description Detail Modal functions
    window.closeDescriptionDetailModal = function() {
        const modal = document.getElementById('description-detail-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    // Return to Yard Modal functions
    window.closeReturnToYardModal = function() {
        const modal = document.getElementById('return-to-yard-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    // Edit Issue Modal functions
    window.closeEditIssueModal = function() {
        const modal = document.getElementById('edit-issue-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    // Status Modal functions
    window.closeStatusModal = function() {
        const modal = document.getElementById('status-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    // Add Repair Modal functions
    window.closeAddRepairModal = function() {
        const modal = document.getElementById('add-repair-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    };

    // Add Attribute Modal functions
    window.closeAddAttributeModal = function() {
        const modal = document.getElementById('add-attribute-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    // Vehicle Detail Modal functions
    window.closeVehicleDetailModal = function() {
        const modal = document.getElementById('vehicle-detail-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    // Toggle Section function
    window.toggleSection = function(sectionId) {
        const section = document.getElementById(sectionId);
        const arrow = document.getElementById(sectionId.replace('-section', '-arrow'));

        if (section && arrow) {
            if (section.style.display === 'none' || section.classList.contains('hidden')) {
                section.style.display = 'block';
                section.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
            } else {
                section.style.display = 'none';
                section.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
            }
        }
    };

    // Return Selected Vehicles to Yard functions
    window.returnSelectedVehiclesToYard = function() {
        if (window.activeVehicles && window.activeVehicles.returnSelectedVehiclesToYard) {
            window.activeVehicles.returnSelectedVehiclesToYard();
        } else {
            console.log('⚠️ window.activeVehicles not found, using fallback');
            // Fallback: Find selected vehicles and return them to yard
            const selectedVehicles = document.querySelectorAll('.ready-checkbox:checked');
            if (selectedVehicles.length > 0) {
                const vehicleIds = Array.from(selectedVehicles).map(cb => cb.value);
                console.log('Returning vehicles to yard:', vehicleIds);
                // Add your fallback logic here
            } else {
                alert('Vui lòng chọn ít nhất một xe để về bãi');
            }
        }
    };

    window.returnSelectedRoutingVehiclesToYard = function() {
        if (window.activeVehicles && window.activeVehicles.returnSelectedRoutingVehiclesToYard) {
            window.activeVehicles.returnSelectedRoutingVehiclesToYard();
        } else {
            console.log('⚠️ window.activeVehicles not found, using fallback');
            // Fallback: Find selected routing vehicles and return them to yard
            const selectedVehicles = document.querySelectorAll('.routing-checkbox:checked');
            if (selectedVehicles.length > 0) {
                const vehicleIds = Array.from(selectedVehicles).map(cb => cb.value);
                console.log('Returning routing vehicles to yard:', vehicleIds);
                // Add your fallback logic here
            } else {
                alert('Vui lòng chọn ít nhất một xe để về bãi');
            }
        }
    };

    console.log('✅ Global modal functions created successfully');
})();

console.log('✅ GlobalModalFunctions.js loaded successfully');
