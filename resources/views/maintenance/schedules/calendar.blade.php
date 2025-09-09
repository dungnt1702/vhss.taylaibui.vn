@extends('layouts.app')

@section('title', 'Lịch bảo trì')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900">Lịch bảo trì</h1>
                <p class="text-neutral-600 mt-2">Xem lịch bảo trì theo dạng calendar</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('maintenance.schedules.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    Xem danh sách
                </a>
                <a href="{{ route('maintenance.schedules.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tạo lịch mới
                </a>
            </div>
        </div>
    </div>

    <!-- Month Navigation -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="?date={{ \Carbon\Carbon::parse($date)->subMonth()->format('Y-m') }}" class="p-2 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h2 class="text-xl font-semibold text-gray-900">
                    {{ \Carbon\Carbon::parse($date)->format('F Y') }}
                </h2>
                <a href="?date={{ \Carbon\Carbon::parse($date)->addMonth()->format('Y-m') }}" class="p-2 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
            <div class="flex space-x-2">
                <a href="?date={{ \Carbon\Carbon::now()->format('Y-m') }}" class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200">
                    Hôm nay
                </a>
            </div>
        </div>
    </div>

    <!-- Calendar -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Calendar Header -->
        <div class="grid grid-cols-7 bg-gray-50">
            @foreach(['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'] as $day)
            <div class="px-4 py-3 text-center text-sm font-medium text-gray-500">{{ $day }}</div>
            @endforeach
        </div>

        <!-- Calendar Body -->
        <div class="grid grid-cols-7 divide-x divide-y divide-gray-200">
            @php
                $startOfMonth = \Carbon\Carbon::parse($date)->startOfMonth();
                $endOfMonth = \Carbon\Carbon::parse($date)->endOfMonth();
                $startOfWeek = $startOfMonth->copy()->startOfWeek();
                $endOfWeek = $endOfMonth->copy()->endOfWeek();
                $current = $startOfWeek->copy();
            @endphp

            @while($current <= $endOfWeek)
                @php
                    $isCurrentMonth = $current->month == \Carbon\Carbon::parse($date)->month;
                    $isToday = $current->isToday();
                    $daySchedules = $schedules->get($current->format('Y-m-d'), collect());
                @endphp

                <div class="min-h-[120px] p-2 {{ $isCurrentMonth ? 'bg-white' : 'bg-gray-50' }} {{ $isToday ? 'bg-blue-50' : '' }}">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium {{ $isCurrentMonth ? 'text-gray-900' : 'text-gray-400' }} {{ $isToday ? 'text-blue-600' : '' }}">
                            {{ $current->day }}
                        </span>
                        @if($isCurrentMonth && $daySchedules->count() > 0)
                            <span class="text-xs text-gray-500">{{ $daySchedules->count() }}</span>
                        @endif
                    </div>

                    <div class="space-y-1">
                        @foreach($daySchedules->take(3) as $schedule)
                            <div class="text-xs p-1 rounded truncate cursor-pointer hover:bg-gray-100"
                                 style="background-color: {{ $schedule->maintenanceType->color }}20; border-left: 3px solid {{ $schedule->maintenanceType->color }};"
                                 onclick="showScheduleDetails({{ $schedule->id }})"
                                 title="{{ $schedule->vehicle->name }} - {{ $schedule->maintenanceType->name }}">
                                <div class="font-medium text-gray-900">{{ $schedule->vehicle->name }}</div>
                                <div class="text-gray-600">{{ $schedule->maintenanceType->name }}</div>
                            </div>
                        @endforeach

                        @if($daySchedules->count() > 3)
                            <div class="text-xs text-gray-500 text-center">
                                +{{ $daySchedules->count() - 3 }} khác
                            </div>
                        @endif
                    </div>
                </div>

                @php $current->addDay(); @endphp
            @endwhile
        </div>
    </div>

    <!-- Legend -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Chú thích</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach(\App\Models\MaintenanceType::active()->get() as $type)
            <div class="flex items-center">
                <div class="w-4 h-4 rounded mr-3" style="background-color: {{ $type->color }};"></div>
                <span class="text-sm text-gray-700">{{ $type->name }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Schedule Details Modal -->
<div id="scheduleModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Chi tiết lịch bảo trì</h3>
                    <button onclick="closeScheduleModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="scheduleDetails">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showScheduleDetails(scheduleId) {
    // This would typically make an AJAX call to get schedule details
    // For now, we'll just show a placeholder
    document.getElementById('scheduleDetails').innerHTML = `
        <div class="text-center py-4">
            <p class="text-gray-500">Đang tải thông tin...</p>
        </div>
    `;
    document.getElementById('scheduleModal').classList.remove('hidden');
}

function closeScheduleModal() {
    document.getElementById('scheduleModal').classList.add('hidden');
}
</script>
@endsection
