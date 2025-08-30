<!-- Vehicle Modals -->
<!-- Start Timer Modal -->
<div id="start-timer-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Bắt đầu đếm ngược</h3>
                <form id="start-timer-form">
                    <div class="mb-4">
                        <label for="timer-duration" class="block text-sm font-medium text-neutral-700 mb-2">
                            Thời gian (phút)
                        </label>
                        <select id="timer-duration" name="duration" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            <option value="30">30 phút</option>
                            <option value="45">45 phút</option>
                            <option value="60">60 phút</option>
                            <option value="90">90 phút</option>
                            <option value="120">120 phút</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeStartTimerModal()" class="px-4 py-2 text-sm font-medium text-neutral-700 bg-neutral-100 border border-neutral-300 rounded-md hover:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500">
                            Hủy
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-brand-600 border border-transparent rounded-md hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                            Bắt đầu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Assign Route Modal -->
<div id="assign-route-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Phân tuyến xe</h3>
                <form id="assign-route-form">
                    <div class="mb-4">
                        <label for="route-number" class="block text-sm font-medium text-neutral-700 mb-2">
                            Tuyến đường
                        </label>
                        <select id="route-number" name="route_number" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            <option value="1">Đường 1</option>
                            <option value="2">Đường 2</option>
                            <option value="3">Đường 3</option>
                            <option value="4">Đường 4</option>
                            <option value="5">Đường 5</option>
                            <option value="6">Đường 6</option>
                            <option value="7">Đường 7</option>
                            <option value="8">Đường 8</option>
                            <option value="9">Đường 9</option>
                            <option value="10">Đường 10</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeAssignRouteModal()" class="px-4 py-2 text-sm font-medium text-neutral-700 bg-neutral-100 border border-neutral-300 rounded-md hover:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500">
                            Hủy
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-brand-600 border border-transparent rounded-md hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                            Phân tuyến
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Move to Workshop Modal -->
<div id="move-workshop-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Chuyển xe về xưởng</h3>
                <form id="move-workshop-form">
                    <div class="mb-4">
                        <label for="workshop-reason" class="block text-sm font-medium text-neutral-700 mb-2">
                            Lý do
                        </label>
                        <select id="workshop-reason" name="reason" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            <option value="maintenance">Bảo trì</option>
                            <option value="repair">Sửa chữa</option>
                            <option value="inspection">Kiểm tra</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="workshop-notes" class="block text-sm font-medium text-neutral-700 mb-2">
                            Ghi chú
                        </label>
                        <textarea id="workshop-notes" name="notes" rows="3" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500" placeholder="Nhập ghi chú (tùy chọn)"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeMoveWorkshopModal()" class="px-4 py-2 text-sm font-medium text-neutral-700 bg-neutral-100 border border-neutral-300 rounded-md hover:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500">
                            Hủy
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-warning-600 border border-transparent rounded-md hover:bg-warning-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-warning-500">
                            Chuyển về xưởng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmation-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-neutral-900" id="confirmation-title">Xác nhận</h3>
                    </div>
                </div>
                <div class="mb-6">
                    <p class="text-sm text-neutral-700" id="confirmation-message">Bạn có chắc chắn muốn thực hiện hành động này?</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeConfirmationModal()" class="px-4 py-2 text-sm font-medium text-neutral-700 bg-neutral-100 border border-neutral-300 rounded-md hover:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500">
                        Hủy
                    </button>
                    <button type="button" id="confirmation-confirm" class="px-4 py-2 text-sm font-medium text-white bg-brand-600 border border-transparent rounded-md hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                        Xác nhận
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notification Modal -->
<div id="notification-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full" id="notification-icon-container">
                    <svg class="h-6 w-6" id="notification-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-neutral-900" id="notification-title"></h3>
                <p class="mt-2 text-sm text-neutral-700" id="notification-message"></p>
                <div class="mt-6">
                                    <button type="button" id="notification-close-btn" class="px-4 py-2 text-sm font-medium text-white bg-brand-600 border border-transparent rounded-md hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                    Đóng
                </button>
                </div>
            </div>
        </div>
    </div>
</div>
