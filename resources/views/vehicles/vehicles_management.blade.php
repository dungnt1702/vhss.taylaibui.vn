@extends('layouts.app')

@section('title', 'Quản lý xe')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Content based on filter -->
    @if($filter === 'active')
        @include('vehicles.active_vehicles')
    @elseif($filter === 'ready')
        @include('vehicles.ready_vehicles')
    @elseif($filter === 'waiting')
                @include('vehicles.waiting_vehicles')
            @elseif($filter === 'running')
                @include('vehicles.running_vehicles')
    @elseif($filter === 'paused')
        @include('vehicles.paused_vehicles')
            @elseif($filter === 'expired')
                @include('vehicles.expired_vehicles')
            @elseif($filter === 'workshop')
                @include('vehicles.workshop_vehicles')
            @elseif($filter === 'repairing')
                @include('vehicles.repairing_vehicles')
            @elseif($filter === 'maintaining')
                @include('vehicles.maintaining_vehicles')
    @elseif($filter === 'attributes')
        @include('vehicles.attributes_list')
            @elseif($filter === 'vehicles_list')
                @include('vehicles.vehicles_list')
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
                                    
@include('vehicles.partials.vehicle_modals')

@endsection
    
    @push('scripts')
    <!-- Load VehicleClasses.js for all vehicle functionality -->
    @vite(['resources/js/vehicles/VehicleClasses.js'])
@endpush

@push('styles')
    <!-- Load appropriate CSS based on filter -->
    @if($filter === 'active')
        @vite(['resources/css/vehicles/active-vehicles.css'])
    @elseif($filter === 'ready')
        @vite(['resources/css/vehicles/ready-vehicles.css'])
    @elseif($filter === 'waiting')
        @vite(['resources/css/vehicles/ready-vehicles.css'])
    @elseif($filter === 'running')
        @vite(['resources/css/vehicles/running-vehicles.css'])
    @elseif($filter === 'paused')
        @vite(['resources/css/vehicles/paused-vehicles.css'])
    @elseif($filter === 'expired')
        @vite(['resources/css/vehicles/expired-vehicles.css'])
    @elseif($filter === 'workshop')
        @vite(['resources/css/vehicles/workshop-vehicles.css'])
    @elseif($filter === 'repairing')
        @vite(['resources/css/vehicles/repairing-vehicles.css'])
    @elseif($filter === 'maintaining')
        @vite(['resources/css/vehicles/maintaining-vehicles.css'])
    @elseif($filter === 'attributes')
        @vite(['resources/css/vehicles/attributes-list.css'])
    @elseif($filter === 'vehicles_list')
        @vite(['resources/css/vehicles/vehicles-list.css'])
    @endif
    @endpush
