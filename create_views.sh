#!/bin/bash

# Create running_vehicles.blade.php
cat > resources/views/vehicles/running_vehicles.blade.php << 'RUNNING_EOF'
@extends('layouts.app')

@section('title', 'Xe đang chạy')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-neutral-900 mb-2">Xe đang chạy</h1>
        <p class="text-neutral-600">Quản lý xe đang chở khách</p>
    </div>

    <!-- Vehicle Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="running-vehicles-grid">
        @foreach($vehicles as $vehicle)
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500" data-vehicle-id="{{ $vehicle->id }}" data-status="{{ $vehicle->status }}">
            <div class="flex justify-between items-start mb-3">
                <h3 class="text-lg font-semibold text-neutral-900">{{ $vehicle->name }}</h3>
                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $vehicle->status_color_class }}">
                    {{ $vehicle->status_display_name }}
                </span>
            </div>
            
            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-neutral-600">
                    <div class="w-4 h-4 rounded-full mr-2" style="background-color: {{ $vehicle->color }};"></div>
                    <span>{{ $vehicle->color }}</span>
                </div>
                <div class="text-sm text-neutral-600">
                    <span class="font-medium">Ghế:</span> {{ $vehicle->seats }}
                </div>
                <div class="text-sm text-neutral-600">
                    <span class="font-medium">Thời gian còn lại:</span> 
                    <span id="remaining-time-{{ $vehicle->id }}" class="font-bold text-blue-600">
                        {{ $vehicle->remaining_time ?? 'Đang tính...' }}
                    </span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2" id="vehicle-buttons-{{ $vehicle->id }}">
                <button onclick="vehicleOperations.pauseVehicle({{ $vehicle->id }})" class="btn btn-warning btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Tạm dừng
                </button>
                <button onclick="vehicleOperations.returnToYard({{ $vehicle->id }})" class="btn btn-info btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Về bãi
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Empty State -->
    @if($vehicles->isEmpty())
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-neutral-900">Không có xe nào</h3>
        <p class="mt-1 text-sm text-neutral-500">Hiện tại không có xe nào đang chạy.</p>
    </div>
    @endif
</div>

<!-- Modals -->
@include('vehicles.partials.vehicle_modals')

@endsection

@push('scripts')
<script src="{{ asset('js/running-vehicles.js') }}"></script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/running-vehicles.css') }}">
@endpush
RUNNING_EOF

# Create paused_vehicles.blade.php
cat > resources/views/vehicles/paused_vehicles.blade.php << 'PAUSED_EOF'
@extends('layouts.app')

@section('title', 'Xe tạm dừng')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-neutral-900 mb-2">Xe tạm dừng</h1>
        <p class="text-neutral-600">Quản lý xe đang tạm dừng hoạt động</p>
    </div>

    <!-- Vehicle Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="paused-vehicles-grid">
        @foreach($vehicles as $vehicle)
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-gray-500" data-vehicle-id="{{ $vehicle->id }}" data-status="{{ $vehicle->status }}">
            <div class="flex justify-between items-start mb-3">
                <h3 class="text-lg font-semibold text-neutral-900">{{ $vehicle->name }}</h3>
                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $vehicle->status_color_class }}">
                    {{ $vehicle->status_display_name }}
                </span>
            </div>
            
            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-neutral-600">
                    <div class="w-4 h-4 rounded-full mr-2" style="background-color: {{ $vehicle->color }};"></div>
                    <span>{{ $vehicle->color }}</span>
                </div>
                <div class="text-sm text-neutral-600">
                    <span class="font-medium">Thời gian còn lại:</span> 
                    <span id="remaining-time-{{ $vehicle->id }}" class="font-bold text-gray-600">
                        {{ $vehicle->paused_remaining_time ?? 'Đang tính...' }}
                    </span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2" id="vehicle-buttons-{{ $vehicle->id }}">
                <button onclick="vehicleOperations.resumeVehicle({{ $vehicle->id }})" class="btn btn-success btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Tiếp tục
                </button>
                <button onclick="vehicleOperations.returnToYard({{ $vehicle->id }})" class="btn btn-info btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Về bãi
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Empty State -->
    @if($vehicles->isEmpty())
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-neutral-900">Không có xe nào</h3>
        <p class="mt-1 text-sm text-neutral-500">Hiện tại không có xe nào tạm dừng.</p>
    </div>
    @endif
</div>

<!-- Modals -->
@include('vehicles.partials.vehicle_modals')

@endsection

@push('scripts')
<script src="{{ asset('js/paused-vehicles.js') }}"></script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/paused-vehicles.css') }}">
@endpush
PAUSED_EOF

# Create expired_vehicles.blade.php
cat > resources/views/vehicles/expired_vehicles.blade.php << 'EXPIRED_EOF'
@extends('layouts.app')

@section('title', 'Xe hết giờ')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-neutral-900 mb-2">Xe hết giờ</h1>
        <p class="text-neutral-600">Quản lý xe đã hết thời gian chạy</p>
    </div>

    <!-- Vehicle Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="expired-vehicles-grid">
        @foreach($vehicles as $vehicle)
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-orange-500" data-vehicle-id="{{ $vehicle->id }}" data-status="{{ $vehicle->status }}">
            <div class="flex justify-between items-start mb-3">
                <h3 class="text-lg font-semibold text-neutral-900">{{ $vehicle->name }}</h3>
                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $vehicle->status_color_class }}">
                    {{ $vehicle->status_display_name }}
                </span>
            </div>
            
            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-neutral-600">
                    <div class="w-4 h-4 rounded-full mr-2" style="background-color: {{ $vehicle->color }};"></div>
                    <span>{{ $vehicle->color }}</span>
                </div>
                <div class="text-sm text-neutral-600">
                    <span class="font-medium">Ghế:</span> {{ $vehicle->seats }}
                </div>
                <div class="text-sm text-neutral-600">
                    <span class="font-medium">Công suất:</span> {{ $vehicle->power }}
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2" id="vehicle-buttons-{{ $vehicle->id }}">
                <button onclick="vehicleOperations.returnToYard({{ $vehicle->id }})" class="btn btn-success btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Về bãi
                </button>
                <button onclick="vehicleOperations.moveToWorkshop({{ $vehicle->id }})" class="btn btn-warning btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Về xưởng
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Empty State -->
    @if($vehicles->isEmpty())
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-neutral-900">Không có xe nào</h3>
        <p class="mt-1 text-sm text-neutral-500">Hiện tại không có xe nào hết giờ.</p>
    </div>
    @endif
</div>

<!-- Modals -->
@include('vehicles.partials.vehicle_modals')

@endsection

@push('scripts')
<script src="{{ asset('js/expired-vehicles.js') }}"></script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/expired-vehicles.css') }}">
@endpush
EXPIRED_EOF

echo "Created running_vehicles.blade.php, paused_vehicles.blade.php, and expired_vehicles.blade.php"
