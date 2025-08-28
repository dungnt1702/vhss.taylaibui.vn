@extends('layouts.app')

@section('title', 'Xe đang bảo trì')

@section('content')
<div class="container mx-auto px-4 py-6">

    <!-- Vehicle Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="maintaining-vehicles-grid">
        @foreach($vehicles as $vehicle)
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-indigo-500" data-vehicle-id="{{ $vehicle->id }}" data-status="{{ $vehicle->status }}">
            <div class="flex justify-between items-start mb-3">
                <h3 class="text-lg font-semibold text-neutral-900">{{ $vehicle->name }}</h3>
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800">
                    Đang bảo trì
                </span>
            </div>
            
            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-neutral-600">
                    <div class="w-4 h-4 rounded-full mr-2" style="background-color: {{ $vehicle->color }};"></div>
                    <span>{{ $vehicle->color }}</span>
                </div>
                <div class="text-sm text-neutral-600">
                    <span class="font-medium">Loại bảo trì:</span> {{ $vehicle->maintenance_type ?? 'Định kỳ' }}
                </div>
                <div class="text-sm text-neutral-600">
                    <span class="font-medium">Ngày bắt đầu:</span> {{ $vehicle->maintenance_start_date ?? 'Không có' }}
                </div>
                <div class="text-sm text-neutral-600">
                    <span class="font-medium">Dự kiến hoàn thành:</span> {{ $vehicle->maintenance_estimated_date ?? 'Không có' }}
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2" id="vehicle-buttons-{{ $vehicle->id }}">
                <button onclick="vehicleOperations.completeMaintenance({{ $vehicle->id }})" class="btn btn-success btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Hoàn thành
                </button>
                <button onclick="vehicleOperations.updateMaintenanceStatus({{ $vehicle->id }})" class="btn btn-info btn-sm">
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-neutral-900">Không có xe nào</h3>
        <p class="mt-1 text-sm text-neutral-500">Hiện tại không có xe nào đang bảo trì.</p>
    </div>
    @endif
</div>

<!-- Modals -->
@include('vehicles.partials.vehicle_modals')

@endsection

@push('scripts')
@vite(['resources/js/vehicles/maintaining-vehicles.js'])
@endpush

@push('styles')
@vite(['resources/css/vehicles/maintaining-vehicles.css'])
@endpush
