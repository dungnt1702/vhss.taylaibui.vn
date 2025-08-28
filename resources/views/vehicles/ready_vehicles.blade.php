@extends('layouts.app')

@section('title', 'Xe sẵn sàng chạy')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-neutral-900 mb-2">Xe sẵn sàng chạy</h1>
        <p class="text-neutral-600">Quản lý xe sẵn sàng chạy</p>
    </div>

    <!-- Block 1: Xe ngoài bãi -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Xe ngoài bãi</h2>
            
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-3 py-2 text-left">
                            <input type="checkbox" id="select-all-waiting" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                        </th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Xe số</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Màu sắc</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Trạng thái</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="waiting-vehicles" class="divide-y divide-gray-200">
                    @forelse($activeVehicles as $vehicle)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2">
                                <input type="checkbox" class="vehicle-checkbox rounded border-gray-300 text-brand-600 focus:ring-brand-500" value="{{ $vehicle->id }}">
                            </td>
                            <td class="px-3 py-2 text-sm text-gray-900">{{ $vehicle->name }}</td>
                            <td class="px-3 py-2">
                                <div class="w-4 h-4 rounded border border-gray-300" style="background-color: {{ $vehicle->color }};" title="{{ $vehicle->color }}"></div>
                            </td>
                            <td class="px-3 py-2 text-sm text-gray-500">{{ $vehicle->status_display_name }}</td>
                            <td class="px-3 py-2 text-sm text-gray-500">
                                <div class="flex space-x-2">
                                    <button data-action="start-timer" data-vehicle-id="{{ $vehicle->id }}" data-duration="30" class="btn btn-success btn-sm">
                                        🚗 30p
                                    </button>
                                    <button data-action="start-timer" data-vehicle-id="{{ $vehicle->id }}" data-duration="45" class="btn btn-primary btn-sm">
                                        🚙 45p
                                    </button>
                                    <button data-action="assign-route" data-vehicle-id="{{ $vehicle->id }}" class="btn btn-info btn-sm">
                                        🛣️ Phân tuyến
                                    </button>
                                    <button data-action="move-workshop" data-vehicle-id="{{ $vehicle->id }}" class="btn btn-secondary btn-sm">
                                        🔧 Về xưởng
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-4 text-center text-sm text-gray-500">
                                Không có xe nào sẵn sàng chạy.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4 flex flex-wrap gap-2">
                <button data-action="start-timer-bulk" data-duration="30" class="btn btn-success btn-sm">
                    🚗 Bắt đầu 30p cho xe đã chọn
                </button>
                <button data-action="start-timer-bulk" data-duration="45" class="btn btn-primary btn-sm">
                    🚙 Bắt đầu 45p cho xe đã chọn
                </button>
                <button data-action="assign-route-bulk" class="btn btn-info btn-sm">
                    🛣️ Phân tuyến xe đã chọn
                </button>
                <button data-action="move-workshop-bulk" class="btn btn-secondary btn-sm">
                    🔧 Chuyển về xưởng xe đã chọn
                </button>
            </div>
            
            <!-- Route Selection Button Group -->
            <div class="flex items-stretch w-full max-w-xs [1028px:max-w-none] [1280px:max-w-xs] mt-4">
                <select id="route-select" class="flex-1 px-4 py-2 border border-gray-300 border-r-0 rounded-l-md bg-white text-sm outline-none h-10 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="1">Đường 1</option>
                    <option value="2">Đường 2</option>
                    <option value="3" selected>Đường 3</option>
                    <option value="4">Đường 4</option>
                    <option value="5">Đường 5</option>
                    <option value="6">Đường 6</option>
                    <option value="7">Đường 7</option>
                    <option value="8">Đường 8</option>
                    <option value="9">Đường 9</option>
                    <option value="10">Đường 10</option>
                </select>
                <button data-action="assign-route-selected" class="px-6 py-2 bg-green-600 text-white text-sm font-medium rounded-r-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 min-w-[120px] h-10">
                    Chạy
                </button>
            </div>
        </div>
    </div>

    <!-- Block 2: Xe chạy đường 1-2 -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Xe chạy đường 1-2</h2>
            
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-3 py-2 text-left">
                            <input type="checkbox" id="select-all-timer" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                        </th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Xe số</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Màu sắc</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Bắt đầu</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Kết thúc</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Đếm ngược</th>
                    </tr>
                </thead>
                <tbody id="timer-vehicles" class="divide-y divide-gray-200">
                    <!-- Timer vehicles will be populated by JavaScript -->
                </tbody>
            </table>

            <div class="mt-4">
                <button data-action="return-to-yard" class="w-full px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    Về bãi
                </button>
            </div>
        </div>
    </div>

    <!-- Block 3: Xe theo Đường -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Xe theo Đường</h2>
            
            <div id="route-groups" class="space-y-4">
                <!-- Route groups will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Notification Popup -->
<div id="notification-popup" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); z-index: 1000; min-width: 300px; text-align: center;">
    <div style="margin-bottom: 16px;">
        <svg style="width: 48px; height: 48px; color: #f59e0b; margin: 0 auto;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
        </svg>
    </div>
    <h3 style="margin: 0 0 12px 0; font-size: 18px; font-weight: 600; color: #111827;">Thông báo</h3>
    <p id="notification-message" style="margin: 0 0 20px 0; color: #6b7280; line-height: 1.5;"></p>
    <button data-action="close-notification" style="padding: 8px 24px; background: #3b82f6; color: white; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
        Đóng
    </button>
</div>
<!-- Overlay for popup -->
<div id="popup-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999;"></div>

@include('vehicles.partials.vehicle_modals')

@endsection

@push('scripts')
    <!-- Load ready-vehicles.js -->
    @vite(['resources/js/ready-vehicles.js'])
@endpush

@push('styles')
    <!-- Load ready-vehicles.css -->
    @vite(['resources/css/ready-vehicles.css'])
@endpush
