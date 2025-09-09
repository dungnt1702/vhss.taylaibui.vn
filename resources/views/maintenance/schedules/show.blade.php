@extends('layouts.app')

@section('title', 'Chi tiết lịch bảo trì')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('maintenance.schedules.index') }}" class="mr-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-neutral-900">Chi tiết lịch bảo trì</h1>
                    <p class="text-neutral-600 mt-2">{{ $schedule->vehicle->name }} - {{ $schedule->maintenanceType->name }}</p>
                </div>
            </div>
            <div class="flex space-x-3">
                @if($schedule->status === 'pending' || $schedule->status === 'overdue')
                    <a href="{{ route('maintenance.schedules.perform', $schedule) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Thực hiện bảo trì
                    </a>
                @endif
                <a href="{{ route('maintenance.schedules.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto">
        <!-- Schedule Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Vehicle Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin xe</h3>
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 rounded border border-gray-300 flex items-center justify-center text-white font-semibold text-xl" style="background-color: {{ $schedule->vehicle->color }};">
                        {{ $schedule->vehicle->name }}
                    </div>
                    <div class="ml-4">
                        <div class="text-lg font-medium text-gray-900">{{ $schedule->vehicle->name }}</div>
                        <div class="text-sm text-gray-500">{{ $schedule->vehicle->status }}</div>
                    </div>
                </div>
                <div class="space-y-2 text-sm">
                    <div><span class="font-medium text-gray-500">Số ghế:</span> {{ $schedule->vehicle->seats }}</div>
                    <div><span class="font-medium text-gray-500">Công suất:</span> {{ $schedule->vehicle->power }}</div>
                    <div><span class="font-medium text-gray-500">Kích thước lốp:</span> {{ $schedule->vehicle->wheel_size }}</div>
                </div>
            </div>

            <!-- Maintenance Type Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Loại bảo trì</h3>
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-semibold" style="background-color: {{ $schedule->maintenanceType->color }};">
                        {{ substr($schedule->maintenanceType->name, 0, 2) }}
                    </div>
                    <div class="ml-4">
                        <div class="text-lg font-medium text-gray-900">{{ $schedule->maintenanceType->name }}</div>
                        <div class="text-sm text-gray-500">Chu kỳ {{ $schedule->maintenanceType->interval_days }} ngày</div>
                    </div>
                </div>
                @if($schedule->maintenanceType->description)
                    <p class="text-sm text-gray-600">{{ $schedule->maintenanceType->description }}</p>
                @endif
            </div>

            <!-- Schedule Status -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Trạng thái lịch</h3>
                <div class="space-y-4">
                    <div>
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-{{ $schedule->getStatusColor() }}-100 text-{{ $schedule->getStatusColor() }}-800">
                            {{ $schedule->getStatusLabel() }}
                        </span>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div><span class="font-medium text-gray-500">Ngày dự kiến:</span> {{ $schedule->scheduled_date->format('d/m/Y') }}</div>
                        @if($schedule->last_performed)
                            <div><span class="font-medium text-gray-500">Lần cuối:</span> {{ $schedule->last_performed->format('d/m/Y') }}</div>
                        @endif
                        @if($schedule->next_due)
                            <div><span class="font-medium text-gray-500">Lần tiếp theo:</span> {{ $schedule->next_due->format('d/m/Y') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Maintenance Tasks -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Danh sách nhiệm vụ</h3>
            <div class="space-y-3">
                @foreach($schedule->maintenanceType->tasks->sortBy('sort_order') as $task)
                <div class="flex items-start p-3 border border-gray-200 rounded-lg">
                    <div class="flex-shrink-0 mr-3">
                        @if($task->is_required)
                            <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-3 h-3 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @else
                            <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-3 h-3 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $task->task_name }}
                            @if($task->is_required)
                                <span class="text-red-500">*</span>
                            @endif
                        </div>
                        @if($task->description)
                            <div class="text-sm text-gray-500 mt-1">{{ $task->description }}</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Maintenance Records -->
        @if($schedule->records->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Lịch sử thực hiện</h3>
            <div class="space-y-4">
                @foreach($schedule->records->sortByDesc('performed_date') as $record)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $record->performedBy->name }}</div>
                                <div class="text-sm text-gray-500">{{ $record->performed_date->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $record->getStatusColor() }}-100 text-{{ $record->getStatusColor() }}-800">
                                {{ $record->getStatusLabel() }}
                            </span>
                            @if($record->getDuration())
                                <span class="text-xs text-gray-500">{{ $record->getFormattedDuration() }}</span>
                            @endif
                        </div>
                    </div>
                    
                    @if($record->notes)
                        <div class="text-sm text-gray-600 mb-3">{{ $record->notes }}</div>
                    @endif

                    @if($record->task_results && count($record->task_results) > 0)
                        <div class="mt-3">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Kết quả nhiệm vụ:</h4>
                            <div class="space-y-2">
                                @foreach($record->task_results as $taskResult)
                                    @php
                                        $task = $schedule->maintenanceType->tasks->find($taskResult['task_id']);
                                    @endphp
                                    @if($task)
                                        <div class="flex items-center text-sm">
                                            <div class="w-4 h-4 mr-2 {{ $taskResult['completed'] ? 'text-green-600' : 'text-red-600' }}">
                                                @if($taskResult['completed'])
                                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @else
                                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <span class="{{ $taskResult['completed'] ? 'text-green-700' : 'text-red-700' }}">
                                                {{ $task->task_name }}
                                            </span>
                                            @if(isset($taskResult['notes']) && $taskResult['notes'])
                                                <span class="ml-2 text-gray-500">- {{ $taskResult['notes'] }}</span>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Notes -->
        @if($schedule->notes)
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ghi chú</h3>
            <p class="text-gray-600">{{ $schedule->notes }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
