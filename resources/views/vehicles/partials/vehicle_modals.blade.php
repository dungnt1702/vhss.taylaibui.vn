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
                    <input type="hidden" id="workshop-vehicle-id" name="vehicle_id" value="">
                    <div class="mb-4">
                        <label for="workshop-reason" class="block text-sm font-medium text-neutral-700 mb-2">
                            Lý do
                        </label>
                        <select id="workshop-reason" name="reason" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                            <option value="">-- Chọn lý do --</option>
                            <option value="maintenance">Bảo trì</option>
                            <option value="repair">Sửa chữa</option>
                            <option value="inspection">Kiểm tra</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="workshop-notes" class="block text-sm font-medium text-neutral-700 mb-2">
                            Ghi chú <span class="text-red-500">*</span>
                        </label>
                        <textarea id="workshop-notes" name="notes" rows="3" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500" placeholder="Nhập ghi chú (bắt buộc)"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeMoveWorkshopModal()" class="px-4 py-2 text-sm font-medium text-neutral-700 bg-neutral-100 border border-neutral-300 rounded-md hover:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500">
                            Hủy
                        </button>
                        <button type="submit" id="workshop-submit-btn" class="px-4 py-2 text-sm font-medium text-white bg-orange-600 border border-transparent rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 disabled:bg-gray-400 disabled:cursor-not-allowed" disabled style="background-color: #9ca3af; cursor: not-allowed;">
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

<!-- Edit Vehicle Modal (for vehicles_list only) -->
<div id="vehicle-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-neutral-900" id="vehicle-modal-title">Chỉnh sửa thông tin xe</h3>
                    <button onclick="closeVehicleModal()" class="text-neutral-400 hover:text-neutral-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form id="vehicle-form">
                    <input type="hidden" id="vehicle-edit-id" name="vehicle_id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Xe số -->
                        <div>
                            <label for="vehicle-name" class="block text-sm font-medium text-neutral-700 mb-2">
                                Xe số <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="vehicle-name" name="name" required
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        </div>
                        
                        <!-- Màu sắc -->
                        <div>
                            <label for="vehicle-color" class="block text-sm font-medium text-neutral-700 mb-2">
                                Màu sắc <span class="text-red-500">*</span>
                            </label>
                            <input type="color" id="vehicle-color" name="color" required
                                   class="w-full h-10 px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        </div>
                        
                        <!-- Ghế -->
                        <div>
                            <label for="vehicle-seats" class="block text-sm font-medium text-neutral-700 mb-2">
                                Ghế <span class="text-red-500">*</span>
                            </label>
                            <select id="vehicle-seats" name="seats" required
                                    class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                <option value="1">1 ghế</option>
                                <option value="2">2 ghế</option>
                            </select>
                        </div>
                        
                        <!-- Công suất -->
                        <div>
                            <label for="vehicle-power" class="block text-sm font-medium text-neutral-700 mb-2">
                                Công suất <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="vehicle-power" name="power" required
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        </div>
                        
                        <!-- Kích thước bánh xe -->
                        <div>
                            <label for="vehicle-wheel-size" class="block text-sm font-medium text-neutral-700 mb-2">
                                Kích thước bánh xe <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="vehicle-wheel-size" name="wheel_size" required
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        </div>
                        
                        <!-- Vị trí hiện tại -->
                        <div>
                            <label for="vehicle-current-location" class="block text-sm font-medium text-neutral-700 mb-2">
                                Vị trí hiện tại
                            </label>
                            <input type="text" id="vehicle-current-location" name="current_location"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        </div>
                    </div>
                    
                    <!-- Ghi chú -->
                    <div class="mt-6">
                        <label for="vehicle-notes" class="block text-sm font-medium text-neutral-700 mb-2">
                            Ghi chú
                        </label>
                        <textarea id="vehicle-notes" name="notes" rows="3"
                                  class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                                  placeholder="Nhập ghi chú về xe..."></textarea>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeVehicleModal()" 
                                class="px-4 py-2 text-sm font-medium text-neutral-700 bg-neutral-100 border border-neutral-300 rounded-md hover:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500">
                            Hủy
                        </button>
                        <button type="submit" id="vehicle-submit-btn"
                                class="px-4 py-2 text-sm font-medium text-white bg-brand-600 border border-transparent rounded-md hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                            Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Notes Modal (for workshop vehicles only) -->
<div id="edit-notes-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-neutral-900" id="edit-notes-modal-title">Chỉnh sửa ghi chú xe</h3>
                    <button onclick="closeEditNotesModal()" class="text-neutral-400 hover:text-neutral-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form id="edit-notes-form">
                    <input type="hidden" id="edit-notes-vehicle-id" name="vehicle_id">
                    
                    <div class="mb-4">
                        <label for="edit-notes-textarea" class="block text-sm font-medium text-neutral-700 mb-2">
                            Ghi chú
                        </label>
                        <textarea id="edit-notes-textarea" name="notes" rows="4"
                                  class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                                  placeholder="Nhập ghi chú về xe..."></textarea>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeEditNotesModal()" 
                                class="px-4 py-2 text-sm font-medium text-neutral-700 bg-neutral-100 border border-neutral-300 rounded-md hover:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500">
                            Hủy
                        </button>
                        <button type="submit" id="edit-notes-submit-btn"
                                class="px-4 py-2 text-sm font-medium text-white bg-brand-600 border border-transparent rounded-md hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                            Cập nhật ghi chú
                        </button>
                    </div>
                </form>
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

<!-- Technical Update Modal (for workshop vehicles) -->
<div id="technical-update-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-neutral-900 mb-4">Cập nhật kỹ thuật xe</h3>
            <form id="technical-update-form">
                <input type="hidden" id="technical-vehicle-id" name="vehicle_id" value="">
                
                <input type="hidden" id="technical-issue-type" name="issue_type" value="repair">
                
                <div class="mb-4">
                    <label for="technical-category" class="block text-sm font-medium text-neutral-700 mb-2">
                        Hạng mục <span class="text-red-500">*</span>
                    </label>
                    <select id="technical-category" name="category" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                        <option value="">-- Chọn hạng mục --</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="technical-description" class="block text-sm font-medium text-neutral-700 mb-2">
                        Mô tả chi tiết
                    </label>
                    <textarea id="technical-description" name="description" rows="3" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500" placeholder="Mô tả chi tiết về vấn đề kỹ thuật..."></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="technical-notes" class="block text-sm font-medium text-neutral-700 mb-2">
                        Ghi chú thêm
                    </label>
                    <textarea id="technical-notes" name="notes" rows="2" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500" placeholder="Ghi chú thêm..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="vehicleOperations.closeTechnicalUpdateModal()" class="px-4 py-2 text-sm font-medium text-neutral-700 bg-neutral-100 border border-neutral-300 rounded-md hover:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500">
                        Hủy
                    </button>
                    <button type="submit" id="technical-update-submit-btn" class="px-4 py-2 text-sm font-medium text-white bg-orange-600 border border-transparent rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 disabled:bg-gray-400 disabled:cursor-not-allowed">
                        Cập nhật kỹ thuật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Process Issue Modal -->
<div id="process-issue-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Xử lý vấn đề kỹ thuật</h3>
                <form id="process-issue-form">
                    <input type="hidden" id="process-issue-id" name="issue_id">
                    
                    <div class="mb-4">
                        <label for="process-assigned-to" class="block text-sm font-medium text-neutral-700 mb-2">
                            Người thực hiện <span class="text-red-500">*</span>
                        </label>
                        <select id="process-assigned-to" name="assigned_to" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                            <option value="">Chọn người thực hiện</option>
                            @foreach(\App\Models\User::where('role', '!=', 'admin')->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="process-status" class="block text-sm font-medium text-neutral-700 mb-2">
                            Trạng thái <span class="text-red-500">*</span>
                        </label>
                        <select id="process-status" name="status" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                            <option value="pending">Chờ xử lý</option>
                            <option value="in_progress">Đang xử lý</option>
                            <option value="completed">Hoàn thành</option>
                            <option value="cancelled">Đã hủy</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="process-result" class="block text-sm font-medium text-neutral-700 mb-2">
                            Kết quả xử lý
                        </label>
                        <textarea id="process-result" name="result" rows="4" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500" placeholder="Mô tả kết quả xử lý..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeProcessModal()" class="px-4 py-2 text-sm font-medium text-neutral-700 bg-neutral-100 border border-neutral-300 rounded-md hover:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500">
                            Hủy
                        </button>
                        <button type="submit" id="process-issue-submit-btn" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Description Detail Modal -->
<div id="description-detail-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-neutral-900">Chi tiết mô tả</h3>
                    <button onclick="closeDescriptionModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả chi tiết:</label>
                        <div id="description-content" class="p-3 bg-gray-50 rounded-md text-sm text-gray-900 whitespace-pre-wrap max-h-64 overflow-y-auto">
                            <!-- Content will be populated by JavaScript -->
                        </div>
                    </div>
                    
                    <div id="notes-section" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ghi chú thêm:</label>
                        <div id="notes-content" class="p-3 bg-gray-50 rounded-md text-sm text-gray-900 whitespace-pre-wrap max-h-32 overflow-y-auto">
                            <!-- Content will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end mt-6">
                    <button onclick="closeDescriptionModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Return to Yard Modal -->
<div id="return-to-yard-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-neutral-900">Đưa xe về bãi</h3>
                    <button onclick="vehicleOperations.closeReturnToYardModal()" class="text-neutral-400 hover:text-neutral-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form id="return-to-yard-form">
                    <input type="hidden" id="return-vehicle-id" name="vehicle_id">
                    
                    <div class="mb-4">
                        <label for="return-notes" class="block text-sm font-medium text-neutral-700 mb-2">
                            Ghi chú về tình trạng xe
                        </label>
                        <textarea 
                            id="return-notes" 
                            name="notes" 
                            rows="4" 
                            class="w-full px-3 py-2 border border-neutral-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="Nhập ghi chú về tình trạng xe..."
                        >Xe hoạt động tốt</textarea>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <button 
                            type="button" 
                            onclick="vehicleOperations.resetReturnNotes()" 
                            class="text-sm text-neutral-500 hover:text-neutral-700 underline"
                        >
                            Xóa nội dung
                        </button>
                        
                        <div class="flex space-x-3">
                            <button 
                                type="button" 
                                onclick="vehicleOperations.closeReturnToYardModal()" 
                                class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-neutral-500"
                            >
                                Hủy
                            </button>
                            <button 
                                type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                            >
                                Đưa về bãi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Repair Modal -->
<div id="add-repair-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-neutral-900 mb-4">Thêm báo cáo sửa chữa</h3>
            <form id="add-repair-form">
                <input type="hidden" id="add-repair-issue-type" name="issue_type" value="repair">
                
                @if(!request('vehicle_id'))
                <!-- Vehicle selection (only show when not filtered by specific vehicle) -->
                <div class="mb-4">
                    <label for="add-repair-vehicle-id" class="block text-sm font-medium text-neutral-700 mb-2">
                        Chọn xe <span class="text-red-500">*</span>
                    </label>
                    <select id="add-repair-vehicle-id" name="vehicle_id" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">-- Chọn xe --</option>
                        @foreach(\App\Models\Vehicle::inactive()->get() as $vehicle)
                            <option value="{{ $vehicle->id }}">{{ $vehicle->name }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                <input type="hidden" id="add-repair-vehicle-id" name="vehicle_id" value="{{ request('vehicle_id') }}">
                @endif
                
                <div class="mb-4">
                    <label for="add-repair-category" class="block text-sm font-medium text-neutral-700 mb-2">
                        Hạng mục <span class="text-red-500">*</span>
                    </label>
                    <select id="add-repair-category" name="category" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">-- Chọn hạng mục --</option>
                        @foreach(\App\Models\VehicleTechnicalIssue::getRepairCategories() as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="add-repair-description" class="block text-sm font-medium text-neutral-700 mb-2">
                        Mô tả vấn đề <span class="text-red-500">*</span>
                    </label>
                    <textarea id="add-repair-description" name="description" rows="3" 
                              class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                              placeholder="Mô tả chi tiết vấn đề cần sửa chữa..." required></textarea>
                </div>
                
                <div class="mb-6">
                    <label for="add-repair-notes" class="block text-sm font-medium text-neutral-700 mb-2">
                        Ghi chú thêm
                    </label>
                    <textarea id="add-repair-notes" name="notes" rows="2" 
                              class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                              placeholder="Ghi chú bổ sung (tùy chọn)"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAddRepairModal()" 
                            class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-neutral-500">
                        Hủy
                    </button>
                    <button type="submit" id="add-repair-submit-btn"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Thêm báo cáo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Attribute Modal -->
<div id="add-attribute-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Thêm thuộc tính mới</h3>
                <form id="add-attribute-form">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Loại thuộc tính</label>
                        <select id="attribute-type" class="w-full px-3 py-2 border border-neutral-300 rounded-md">
                            <option value="color">Màu sắc</option>
                            <option value="seat">Số ghế</option>
                            <option value="power">Công suất</option>
                            <option value="wheel_size">Kích thước bánh</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Giá trị</label>
                        <input type="text" id="attribute-value" class="w-full px-3 py-2 border border-neutral-300 rounded-md" placeholder="Nhập giá trị...">
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeAddAttributeModal()" class="btn btn-secondary btn-sm">Hủy</button>
                        <button type="submit" class="btn btn-primary btn-sm">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Vehicle Detail Modal -->
<div id="vehicle-detail-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-neutral-900">Chi tiết xe</h3>
                    <button onclick="closeVehicleDetailModal()" class="text-neutral-400 hover:text-neutral-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div id="vehicle-detail-content">
                    <!-- Nội dung sẽ được populate bởi JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Modal -->
<div id="status-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-neutral-900">Chỉnh sửa ghi chú xe</h3>
                    <button onclick="closeStatusModal()" class="text-neutral-400 hover:text-neutral-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form id="status-form">
                    @csrf
                    <input type="hidden" id="status-vehicle-id" name="vehicle_id">
                    
                    <div class="mb-6">
                        <label for="status-notes" class="block text-sm font-medium text-neutral-700 mb-2">
                            Ghi chú
                        </label>
                        <textarea id="status-notes" name="notes" rows="4"
                                  class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                                  placeholder="Nhập ghi chú về xe..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeStatusModal()" 
                                class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                            Hủy
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-brand-600 border border-transparent rounded-md hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                            Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Issue Modal -->
<div id="edit-issue-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Chỉnh sửa báo cáo</h3>
                <form id="edit-issue-form">
                    <input type="hidden" id="edit-issue-id" name="issue_id">
                    
                    <div class="mb-4">
                        <label for="edit-category" class="block text-sm font-medium text-neutral-700 mb-2">
                            Hạng mục <span class="text-red-500">*</span>
                        </label>
                        <select id="edit-category" name="category" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                            <option value="">Chọn hạng mục</option>
                            @foreach(\App\Models\VehicleTechnicalIssue::getRepairCategories() as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                            @foreach(\App\Models\VehicleTechnicalIssue::getMaintenanceCategories() as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit-description" class="block text-sm font-medium text-neutral-700 mb-2">
                            Mô tả chi tiết <span class="text-red-500">*</span>
                        </label>
                        <textarea id="edit-description" name="description" rows="4" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500" placeholder="Mô tả chi tiết vấn đề..." required></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit-notes" class="block text-sm font-medium text-neutral-700 mb-2">
                            Ghi chú thêm
                        </label>
                        <textarea id="edit-notes" name="notes" rows="3" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500" placeholder="Ghi chú thêm (tùy chọn)..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-sm font-medium text-neutral-700 bg-neutral-100 border border-neutral-300 rounded-md hover:bg-neutral-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500">
                            Hủy
                        </button>
                        <button type="submit" id="edit-issue-submit-btn" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
