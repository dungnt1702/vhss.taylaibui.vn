@extends('layouts.app')

@section('title', 'Xe đang bảo trì')

@section('content')
<div class="container mx-auto px-4 py-6">

    <!-- Header for Maintaining Vehicles -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-neutral-900">Xe đang bảo trì</h1>
        <p class="text-neutral-600 mt-2">Quản lý xe đang được bảo trì định kỳ</p>
    </div>

    <!-- Grid Display for maintaining vehicles -->
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
                
                @if($vehicle->technicalIssues && $vehicle->technicalIssues->count() > 0)
                    @foreach($vehicle->technicalIssues as $issue)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-2">
                            <div class="text-sm font-medium text-blue-800 mb-1">
                                {{ \App\Models\VehicleTechnicalIssue::getMaintenanceCategories()[$issue->category] ?? $issue->category }}
                            </div>
                            @if($issue->description)
                                <div class="text-xs text-blue-700 mb-1">
                                    {{ Str::limit($issue->description, 100) }}
                                </div>
                            @endif
                            @if($issue->notes)
                                <div class="text-xs text-blue-600">
                                    <span class="font-medium">Ghi chú:</span> {{ Str::limit($issue->notes, 80) }}
                                </div>
                            @endif
                            <div class="text-xs text-blue-600 mt-1">
                                <span class="font-medium">Báo cáo:</span> {{ $issue->reported_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-sm text-neutral-500 italic">
                        Chưa có thông tin kỹ thuật chi tiết
                    </div>
                @endif
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
    <!-- Load VehicleClasses.js for all vehicle functionality -->
    @vite(['resources/js/vehicles/VehicleClasses.js'])
@endpush

@push('styles')
@vite(['resources/css/vehicles/maintaining-vehicles.css'])
@endpush
