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
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Trạng thái</th>
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
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Ngoài bãi
                                        </span>
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
                    <div style="display: flex; flex-direction: row; align-items: center; gap: 16px; justify-content: center;">
                        <!-- Time Selection Button Group -->
                        <div style="display: flex; align-items: stretch;">
                            <select id="time-select" style="padding: 8px 12px; border: 1px solid #d1d5db; border-right: none; border-radius: 6px 0 0 6px; background: white; font-size: 14px; outline: none; height: 40px; box-sizing: border-box;">
                                <option value="45">45 phút</option>
                                <option value="30" selected>30 phút</option>
                                <option value="10">10 phút</option>
                            </select>
                            <button onclick="startTimer()" style="padding: 8px 16px; background: #2563eb; color: white; border: none; border-radius: 0 6px 6px 0; font-size: 14px; font-weight: 500; cursor: pointer; min-width: 120px; transition: background-color 0.2s; outline: none; height: 40px; box-sizing: border-box;" onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                                Bấm giờ
                            </button>
                        </div>
                        <!-- Route Selection Button Group -->
                        <div style="display: flex; align-items: stretch;">
                            <select id="route-select" style="padding: 8px 12px; border: 1px solid #d1d5db; border-right: none; border-radius: 6px 0 0 6px; background: white; font-size: 14px; outline: none; height: 40px; box-sizing: border-box;">
                                <option value="1">Cung đường 1</option>
                                <option value="2">Cung đường 2</option>
                                <option value="3" selected>Cung đường 3</option>
                                <option value="4">Cung đường 4</option>
                                <option value="5">Cung đường 5</option>
                                <option value="6">Cung đường 6</option>
                                <option value="7">Cung đường 7</option>
                                <option value="8">Cung đường 8</option>
                                <option value="9">Cung đường 9</option>
                                <option value="10">Cung đường 10</option>
                            </select>
                            <button onclick="assignRoute()" style="padding: 8px 16px; background: #16a34a; color: white; border: none; border-radius: 0 6px 6px 0; font-size: 14px; font-weight: 500; cursor: pointer; min-width: 120px; transition: background-color 0.2s; outline: none; height: 40px; box-sizing: border-box;" onmouseover="this.style.background='#15803d'" onmouseout="this.style.background='#16a34a'">
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
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Chỗ ngồi</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Thời gian bắt đầu</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Thời gian kết thúc</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Đếm ngược</th>
                        </tr>
                    </thead>
                    <tbody id="timer-vehicles" class="divide-y divide-gray-200">
                        <!-- Timer vehicles will be populated by JavaScript -->
                    </tbody>
                </table>

                <div class="mt-4">
                    <button onclick="returnToYard()" class="w-full px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Về bãi
                    </button>
                </div>
            </div>
        </div>

        <!-- Block 3: Xe theo cung đường -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Xe theo cung đường</h2>
                
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
    <button onclick="closeNotification()" style="padding: 8px 24px; background: #3b82f6; color: white; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
        Đóng
    </button>
</div>
<!-- Overlay for popup -->
<div id="popup-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999;"></div>

@push('scripts')
    <!-- Load active-vehicles.js -->
    <script type="module" src="{{ asset('build/assets/active-vehicles-8BipxnE-.js') }}"></script>
@endpush

@push('styles')
    <!-- Load active-vehicles.css -->
    <link rel="stylesheet" href="{{ asset('build/assets/active-vehicles-CvjbaTot.css') }}">
@endpush
