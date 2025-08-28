@extends('layouts.app')

@section('title', 'Xe sẵn sàng chạy')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-neutral-900 mb-2">Xe sẵn sàng chạy</h1>
        <p class="text-neutral-600">Quản lý xe đang sẵn sàng để chạy</p>
    </div>

    <!-- Vehicle Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="ready-vehicles-grid">
        @foreach($vehicles as $vehicle)
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500" data-vehicle-id="{{ $vehicle->id }}" data-status="{{ $vehicle->status }}">
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
                <div class="text-sm text-neutral-600">
                    <span class="font-medium">Bánh xe:</span> {{ $vehicle->wheel_size }}
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2" id="vehicle-buttons-{{ $vehicle->id }}">
                <button onclick="vehicleOperations.startVehicle({{ $vehicle->id }}, 30)" class="btn btn-success btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Chạy 30p
                </button>
                <button onclick="vehicleOperations.startVehicle({{ $vehicle->id }}, 45)" class="btn btn-success btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Chạy 45p
                </button>
                <button onclick="vehicleOperations.assignRoute({{ $vehicle->id }})" class="btn btn-info btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4-2m-4 2V5m-4 2l4-2m-4 2v10" />
                    </svg>
                    Phân tuyến
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-neutral-900">Không có xe nào</h3>
        <p class="mt-1 text-sm text-neutral-500">Hiện tại không có xe nào sẵn sàng chạy.</p>
    </div>
    @endif
</div>

<!-- Modals -->
@include('vehicles.partials.vehicle_modals')

@endsection

@push('scripts')
<script src="{{ asset('js/ready-vehicles.js') }}"></script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/ready-vehicles.css') }}">
@endpush
