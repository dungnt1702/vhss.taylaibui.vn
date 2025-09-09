@extends('layouts.app')

@section('title', 'Thực hiện bảo trì')

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
                <h1 class="text-2xl font-bold text-neutral-900">Thực hiện bảo trì</h1>
                <p class="text-neutral-600 mt-2">Thực hiện bảo trì cho {{ $schedule->vehicle->name }}</p>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <!-- Schedule Info -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin lịch bảo trì</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded border border-gray-300 flex items-center justify-center text-white font-semibold text-lg" style="background-color: {{ $schedule->vehicle->color }};">
                        {{ $schedule->vehicle->name }}
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">{{ $schedule->vehicle->name }}</div>
                        <div class="text-sm text-gray-500">{{ $schedule->vehicle->status }}</div>
                    </div>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500">Loại bảo trì</div>
                    <div class="flex items-center mt-1">
                        <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $schedule->maintenanceType->color }};"></div>
                        <span class="text-sm font-medium text-gray-900">{{ $schedule->maintenanceType->name }}</span>
                    </div>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500">Ngày dự kiến</div>
                    <div class="text-sm font-medium text-gray-900">{{ $schedule->scheduled_date->format('d/m/Y') }}</div>
                </div>
            </div>
        </div>

        <!-- Maintenance Form -->
        <form method="POST" action="{{ route('maintenance.schedules.record', $schedule) }}" class="space-y-6">
            @csrf

            <!-- Performance Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin thực hiện</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="performed_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Ngày thực hiện <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="performed_date" name="performed_date" 
                               value="{{ old('performed_date', \Carbon\Carbon::today()->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        @error('performed_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Giờ bắt đầu
                        </label>
                        <input type="time" id="start_time" name="start_time" 
                               value="{{ old('start_time') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Giờ kết thúc
                        </label>
                        <input type="time" id="end_time" name="end_time" 
                               value="{{ old('end_time') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Maintenance Tasks -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Danh sách nhiệm vụ</h3>
                <div class="space-y-4">
                    @foreach($schedule->maintenanceType->tasks->sortBy('sort_order') as $index => $task)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       id="task_{{ $task->id }}" 
                                       name="task_results[{{ $index }}][completed]" 
                                       value="1"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <input type="hidden" name="task_results[{{ $index }}][task_id]" value="{{ $task->id }}">
                            </div>
                            <div class="ml-3 flex-1">
                                <label for="task_{{ $task->id }}" class="text-sm font-medium text-gray-900 cursor-pointer">
                                    {{ $task->task_name }}
                                    @if($task->is_required)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                @if($task->description)
                                    <p class="text-sm text-gray-500 mt-1">{{ $task->description }}</p>
                                @endif
                                <div class="mt-2">
                                    <textarea name="task_results[{{ $index }}][notes]" 
                                              rows="2" 
                                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                              placeholder="Ghi chú về nhiệm vụ này..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- General Notes -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ghi chú tổng quát</h3>
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Ghi chú về quá trình bảo trì
                    </label>
                    <textarea id="notes" name="notes" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Mô tả chi tiết về quá trình bảo trì, phát hiện vấn đề, khuyến nghị...">{{ old('notes') }}</textarea>
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
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Hoàn thành bảo trì
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    
    // Auto-calculate duration
    function calculateDuration() {
        if (startTimeInput.value && endTimeInput.value) {
            const start = new Date('2000-01-01 ' + startTimeInput.value);
            const end = new Date('2000-01-01 ' + endTimeInput.value);
            
            if (end > start) {
                const diffMs = end - start;
                const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
                const diffMinutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
                
                // You could display this somewhere if needed
                console.log(`Duration: ${diffHours}h ${diffMinutes}m`);
            }
        }
    }
    
    startTimeInput.addEventListener('change', calculateDuration);
    endTimeInput.addEventListener('change', calculateDuration);
    
    // Set current time as default for start_time
    if (!startTimeInput.value) {
        const now = new Date();
        const timeString = now.toTimeString().slice(0, 5);
        startTimeInput.value = timeString;
    }
});
</script>
@endsection
