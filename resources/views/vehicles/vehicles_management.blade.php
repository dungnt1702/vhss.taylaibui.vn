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
<!-- Load GlobalModalFunctions.js first (creates global functions immediately) -->
@vite(['resources/js/vehicles/GlobalModalFunctions.js'])

<!-- Load VehicleBase.js -->
@vite(['resources/js/vehicles/VehicleBase.js'])

<!-- Load specific JS based on filter -->
@if($currentFilter)
    @vite([$currentFilter['js']])
@endif
@endpush
    
@push('styles')
    <!-- Load appropriate CSS based on filter -->
    @if($currentFilter)
        @vite([$currentFilter['css']])
    @endif
@endpush