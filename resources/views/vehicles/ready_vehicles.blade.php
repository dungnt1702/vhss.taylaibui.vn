@extends('layouts.app')

@section('title', 'Xe sẵn sàng chạy')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <!-- Hidden data for JavaScript -->
    <div id="vehicle-data" data-vehicles='@json($vehicles)' style="display: none;"></div>

    <!-- Three Column Layout -->
    <div class="xl:grid xl:grid-cols-3 gap-6">
        <!-- Block 1: Xe đang chờ -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Xe đang chờ</h2>
                
                @if($vehicles && count($vehicles) > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 text-left">
                                    <input type="checkbox" id="select-all-waiting" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                                </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Xe số</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Màu sắc</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Chỗ ngồi</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Tình trạng</th>
                            </tr>
                        </thead>
                        <tbody id="waiting-vehicles" class="divide-y divide-gray-200">
                            @foreach($vehicles as $vehicle)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2">
                                        <input type="checkbox" value="{{ $vehicle->id }}" class="waiting-checkbox rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-900">{{ $vehicle->name }}</td>
                                    <td class="px-3 py-2">
                                        <div class="w-6 h-6 rounded border border-gray-300" style="background-color: {{ $vehicle->color }};" title="{{ $vehicle->color }}"></div>
                                    </td>
                                    <td class="px-3 py-2 text-sm text-gray-500">{{ $vehicle->seats }}</td>
                                    <td class="px-3 py-2">
                                        @if($vehicle->notes)
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $vehicle->notes }}
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Không có ghi chú
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">Không có xe nào đang chờ</p>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="mt-6">
                    <div class="flex flex-col items-center gap-4 [1028px:flex-row] [1028px:justify-center] [1280px:flex-col] [1280px:items-center]">
                        <!-- Time Selection Button Group -->
                        <div class="flex items-stretch w-full max-w-xs [1028px:max-w-none] [1280px:max-w-xs]">
                            <select id="time-select" class="flex-1 px-4 py-2 border border-gray-300 border-r-0 rounded-l-md bg-white text-sm outline-none h-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="45">45 phút</option>
                                <option value="30" selected>30 phút</option>
                                <option value="10">10 phút</option>
                            </select>
                            <button data-action="start-timer" class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-r-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 min-w-[120px] h-10">
                                Đếm
                            </button>
                        </div>
                        
                        <!-- Route Selection Button Group -->
                        <div class="flex items-stretch w-full max-w-xs [1028px:max-w-none] [1280px:max-w-xs]">
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
                            <button data-action="assign-route" class="px-6 py-2 bg-green-600 text-white text-sm font-medium rounded-r-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 min-w-[120px] h-10">
                                Chạy
                            </button>
                        </div>
                    </div>
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
                    <button data-action="return-yard" class="w-full px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
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
    @vite(['resources/js/vehicles/ready-vehicles.js'])
@endpush

@push('styles')
    <!-- Load ready-vehicles.css -->
    @vite(['resources/css/vehicles/ready-vehicles.css'])
@endpush
