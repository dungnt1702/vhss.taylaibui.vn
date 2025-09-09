@extends('layouts.app')

@section('title', 'Quản lý xe')

@section('content')
@php
    // Define filter mappings
    $filterMappings = [
        'active' => [
            'view' => 'vehicles.active_vehicles',
            'modals' => 'vehicles.partials.modals.active_modals',
            'css' => 'resources/css/vehicles/active-vehicles.css',
            'js' => 'resources/js/vehicles/ActiveVehicles.js'
        ],
        'ready' => [
            'view' => 'vehicles.ready_vehicles',
            'modals' => 'vehicles.partials.modals.ready_modals',
            'css' => 'resources/css/vehicles/ready-vehicles.css',
            'js' => 'resources/js/vehicles/ReadyVehicles.js'
        ],
        'running' => [
            'view' => 'vehicles.running_vehicles',
            'modals' => 'vehicles.partials.modals.running_modals',
            'css' => 'resources/css/vehicles/running-vehicles.css',
            'js' => 'resources/js/vehicles/RunningVehicles.js'
        ],
        'paused' => [
            'view' => 'vehicles.paused_vehicles',
            'modals' => 'vehicles.partials.modals.paused_modals',
            'css' => 'resources/css/vehicles/paused-vehicles.css',
            'js' => 'resources/js/vehicles/PausedVehicles.js'
        ],
        'expired' => [
            'view' => 'vehicles.expired_vehicles',
            'modals' => 'vehicles.partials.modals.expired_modals',
            'css' => 'resources/css/vehicles/expired-vehicles.css',
            'js' => 'resources/js/vehicles/ExpiredVehicles.js'
        ],
        'workshop' => [
            'view' => 'vehicles.workshop_vehicles',
            'modals' => 'vehicles.partials.modals.workshop_modals',
            'css' => 'resources/css/vehicles/workshop-vehicles.css',
            'js' => 'resources/js/vehicles/WorkshopVehicles.js'
        ],
        'repairing' => [
            'view' => 'vehicles.repairing_vehicles',
            'modals' => 'vehicles.partials.modals.repairing_modals',
            'css' => 'resources/css/vehicles/repairing-vehicles.css',
            'js' => 'resources/js/vehicles/RepairingVehicles.js'
        ],
        'attributes' => [
            'view' => 'vehicles.attributes_list',
            'modals' => 'vehicles.partials.modals.attributes_modals',
            'css' => 'resources/css/vehicles/attributes-list.css',
            'js' => 'resources/js/vehicles/AttributesList.js'
        ],
        'vehicles_list' => [
            'view' => 'vehicles.vehicles_list',
            'modals' => 'vehicles.partials.modals.vehicles_list_modals',
            'css' => 'resources/css/vehicles/vehicles-list.css',
            'js' => 'resources/js/vehicles/VehiclesList.js'
        ]
    ];

    // Get current filter mapping
    $currentFilter = $filterMappings[$filter] ?? null;
@endphp

<div class="container mx-auto px-4 py-6">
    <!-- Content based on filter -->
    @if($currentFilter)
        @include($currentFilter['view'])
    @else
        <!-- Default view -->
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-neutral-900">Chọn bộ lọc</h3>
            <p class="mt-1 text-sm text-neutral-500">
                Vui lòng chọn một bộ lọc để xem danh sách xe.
            </p>
        </div>
    @endif
</div>
                                    

<!-- Include modals based on filter -->
@if($currentFilter)
    <!-- Including modals: {{ $currentFilter['modals'] }} -->
    @include($currentFilter['modals'])
@else
    <!-- No currentFilter set -->
@endif

@endsection
    
@push('scripts')
<!-- Load VehicleBase.js first -->
@vite(['resources/js/vehicles/VehicleBase.js'])

<!-- Load specific JS based on filter -->
@if($currentFilter)
    @vite([$currentFilter['js']])
@endif

<!-- Fallback global functions -->
<script>
// Fallback global functions in case VehicleBase.js fails to load
if (typeof window.openWorkshopModal === 'undefined') {
    console.log('🔧 Creating fallback global functions...');
    
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
    
    window.closeStartTimerModal = function() {
        const modal = document.getElementById('start-timer-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };
    
    window.closeAssignRouteModal = function() {
        const modal = document.getElementById('assign-route-modal');
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
    
    window.closeEditNotesModal = function() {
        const modal = document.getElementById('edit-notes-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };
    
    window.closeTechnicalUpdateModal = function() {
        const modal = document.getElementById('technical-update-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };
    
    window.closeProcessIssueModal = function() {
        const modal = document.getElementById('process-issue-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };
    
    window.closeDescriptionDetailModal = function() {
        const modal = document.getElementById('description-detail-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };
    
    window.closeReturnToYardModal = function() {
        const modal = document.getElementById('return-to-yard-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };
    
    window.closeEditIssueModal = function() {
        const modal = document.getElementById('edit-issue-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };
    
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
    
    // Additional functions for different screens
    window.closeStatusModal = function() {
        const modal = document.getElementById('status-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };
    
    window.closeAddRepairModal = function() {
        const modal = document.getElementById('add-repair-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    };
    
    window.closeAddAttributeModal = function() {
        const modal = document.getElementById('add-attribute-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };
    
    window.closeVehicleDetailModal = function() {
        const modal = document.getElementById('vehicle-detail-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };
    
    console.log('✅ Fallback global functions created');
}
</script>
@endpush
    
@push('styles')
    <!-- Load appropriate CSS based on filter -->
    @if($currentFilter)
        @vite([$currentFilter['css']])
    @endif
@endpush