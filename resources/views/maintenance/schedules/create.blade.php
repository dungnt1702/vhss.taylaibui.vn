@extends('layouts.app')

@section('title', 'Tạo lịch bảo trì')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center">
            <a href="{{ route('maintenance.schedules.index') }}" class="mr-4 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-neutral-900">Tạo lịch bảo trì</h1>
                <p class="text-neutral-600 mt-2">Tạo lịch bảo trì mới cho xe</p>
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto">
        <form method="POST" action="{{ route('maintenance.schedules.store') }}" class="space-y-6">
            @csrf

            <!-- Vehicle Selection -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Chọn xe</h3>
                <div>
                    <label for="vehicle_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Xe <span class="text-red-500">*</span>
                    </label>
                    <select id="vehicle_id" name="vehicle_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Chọn xe</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                {{ $vehicle->name }} - {{ $vehicle->status }}
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Maintenance Type Selection -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Loại bảo trì</h3>
                <div>
                    <label for="maintenance_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Loại bảo trì <span class="text-red-500">*</span>
                    </label>
                    <select id="maintenance_type_id" name="maintenance_type_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Chọn loại bảo trì</option>
                        @foreach($maintenanceTypes as $type)
                            <option value="{{ $type->id }}" {{ old('maintenance_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }} ({{ $type->interval_days }} ngày)
                            </option>
                        @endforeach
                    </select>
                    @error('maintenance_type_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Maintenance Type Details -->
                <div id="maintenance-type-details" class="mt-4 hidden">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-2">Thông tin loại bảo trì</h4>
                        <div id="type-description" class="text-sm text-gray-600 mb-3"></div>
                        <div class="text-sm text-gray-500">
                            <span class="font-medium">Chu kỳ:</span> <span id="type-interval"></span> ngày
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule Date -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ngày bảo trì</h3>
                <div>
                    <label for="scheduled_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Ngày dự kiến <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="scheduled_date" name="scheduled_date" 
                           value="{{ old('scheduled_date', \Carbon\Carbon::tomorrow()->format('Y-m-d')) }}"
                           min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    @error('scheduled_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Notes -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ghi chú</h3>
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Ghi chú thêm
                    </label>
                    <textarea id="notes" name="notes" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Ghi chú về lịch bảo trì...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('maintenance.schedules.index') }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Hủy
                </a>
                <button type="submit" 
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Tạo lịch bảo trì
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const maintenanceTypeSelect = document.getElementById('maintenance_type_id');
    const detailsDiv = document.getElementById('maintenance-type-details');
    const descriptionDiv = document.getElementById('type-description');
    const intervalDiv = document.getElementById('type-interval');

    // Maintenance types data
    const maintenanceTypes = @json($maintenanceTypes->keyBy('id'));

    maintenanceTypeSelect.addEventListener('change', function() {
        const typeId = this.value;
        
        if (typeId && maintenanceTypes[typeId]) {
            const type = maintenanceTypes[typeId];
            descriptionDiv.textContent = type.description || 'Không có mô tả';
            intervalDiv.textContent = type.interval_days;
            detailsDiv.classList.remove('hidden');
        } else {
            detailsDiv.classList.add('hidden');
        }
    });

    // Trigger change event if there's a selected value
    if (maintenanceTypeSelect.value) {
        maintenanceTypeSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
