@extends('layouts.app')

@section('title', 'Xe đang sửa chữa')

@section('content')
<div class="container mx-auto px-4 py-6">

    <!-- Header for Repairing Vehicles -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-neutral-900">Xe đang sửa chữa</h1>
        <p class="text-neutral-600 mt-2">Quản lý xe đang được sửa chữa</p>
    </div>

    <!-- Grid Display for repairing vehicles -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="repairing-vehicles-grid">
        @foreach($vehicles as $vehicle)
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500" data-vehicle-id="{{ $vehicle->id }}" data-status="{{ $vehicle->status }}">
            <div class="flex justify-between items-start mb-3">
                <h3 class="text-lg font-semibold text-neutral-900">{{ $vehicle->name }}</h3>
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                    Đang sửa chữa
                </span>
            </div>
            
            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-neutral-600">
                    <div class="w-4 h-4 rounded-full mr-2" style="background-color: {{ $vehicle->color }};"></div>
                    <span>{{ $vehicle->color }}</span>
                </div>
                <div class="text-sm text-neutral-600">
                    <span class="font-medium">Vấn đề:</span> {{ $vehicle->repair_issue ?? 'Không có' }}
                </div>
                <div class="text-sm text-neutral-600">
                    <span class="font-medium">Ngày bắt đầu:</span> {{ $vehicle->repair_start_date ?? 'Không có' }}
                </div>
                <div class="text-sm text-neutral-600">
                    <span class="font-medium">Dự kiến hoàn thành:</span> {{ $vehicle->repair_estimated_date ?? 'Không có' }}
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2" id="vehicle-buttons-{{ $vehicle->id }}">
                <button onclick="vehicleOperations.completeRepair({{ $vehicle->id }})" class="btn btn-success btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Hoàn thành
                </button>
                <button onclick="vehicleOperations.updateRepairStatus({{ $vehicle->id }})" class="btn btn-info btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Cập nhật
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Empty State -->
    @if($vehicles->isEmpty())
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-neutral-900">Không có xe nào</h3>
        <p class="mt-1 text-sm text-neutral-500">Hiện tại không có xe nào đang sửa chữa.</p>
    </div>
    @endif
</div>

<!-- Modals -->
@include('vehicles.partials.vehicle_modals')

@endsection

@push('scripts')
    <!-- Load VehicleClasses.js for all vehicle functionality -->
    @vite(['resources/js/vehicles/VehicleClasses.js'])
@endpush

@push('styles')
@vite(['resources/css/vehicles/repairing-vehicles.css'])
@endpush
