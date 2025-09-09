@extends('layouts.app')

@section('title', 'Dashboard Bảo trì')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-neutral-900">Dashboard Bảo trì</h1>
        <p class="text-neutral-600 mt-2">Tổng quan về tình trạng bảo trì xe</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Tổng lịch bảo trì</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_schedules'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Hôm nay</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_today'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Quá hạn</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['overdue'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Tuần này</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['due_this_week'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Tháng này</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['completed_this_month'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Upcoming Schedules -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Lịch bảo trì sắp tới</h3>
            </div>
            <div class="p-6">
                @if($upcomingSchedules->count() > 0)
                    <div class="space-y-4">
                        @foreach($upcomingSchedules as $schedule)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded border border-gray-300 flex items-center justify-center text-white font-semibold text-sm" style="background-color: {{ $schedule->vehicle->color }};">
                                    {{ $schedule->vehicle->name }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $schedule->maintenanceType->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $schedule->scheduled_date->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $schedule->getStatusColor() }}-100 text-{{ $schedule->getStatusColor() }}-800">
                                {{ $schedule->getStatusLabel() }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Không có lịch bảo trì sắp tới</p>
                @endif
            </div>
        </div>

        <!-- Overdue Schedules -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Lịch bảo trì quá hạn</h3>
            </div>
            <div class="p-6">
                @if($overdueSchedules->count() > 0)
                    <div class="space-y-4">
                        @foreach($overdueSchedules as $schedule)
                        <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded border border-gray-300 flex items-center justify-center text-white font-semibold text-sm" style="background-color: {{ $schedule->vehicle->color }};">
                                    {{ $schedule->vehicle->name }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $schedule->maintenanceType->name }}</p>
                                    <p class="text-xs text-red-500">Quá hạn {{ $schedule->scheduled_date->diffForHumans() }}</p>
                                </div>
                            </div>
                            <a href="{{ route('maintenance.schedules.perform', $schedule) }}" class="px-3 py-1 text-xs font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                                Thực hiện
                            </a>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Không có lịch bảo trì quá hạn</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-4">
        <a href="{{ route('maintenance.schedules.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            Xem tất cả lịch bảo trì
        </a>
        <a href="{{ route('maintenance.schedules.calendar') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
            Xem lịch
        </a>
        <a href="{{ route('maintenance.schedules.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
            Tạo lịch bảo trì mới
        </a>
    </div>
</div>
@endsection
