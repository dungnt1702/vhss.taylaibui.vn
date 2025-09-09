<!-- Common Modals - Used by all filters -->

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

<!-- Confirmation Modal -->
<div id="confirmation-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-neutral-900" id="confirmation-title">Xác nhận</h3>
                <p class="mt-2 text-sm text-neutral-700" id="confirmation-message">Bạn có chắc chắn muốn thực hiện hành động này?</p>
                <div class="mt-6 flex justify-center space-x-3">
                    <button type="button" id="confirmation-cancel-btn" class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500">
                        Hủy
                    </button>
                    <button type="button" id="confirmation-confirm-btn" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Xác nhận
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vehicle Modal -->
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
                    @csrf
                    <input type="hidden" id="vehicle-id" name="id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="vehicle-name" class="block text-sm font-medium text-neutral-700 mb-2">
                                Tên xe <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="vehicle-name" name="name" 
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500" 
                                   required>
                        </div>
                        
                        <div>
                            <label for="vehicle-license_plate" class="block text-sm font-medium text-neutral-700 mb-2">
                                Biển số xe <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="vehicle-license_plate" name="license_plate" 
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500" 
                                   required>
                        </div>
                        
                        <div>
                            <label for="vehicle-type" class="block text-sm font-medium text-neutral-700 mb-2">
                                Loại xe
                            </label>
                            <select id="vehicle-type" name="type" 
                                    class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                <option value="bus">Xe buýt</option>
                                <option value="minibus">Xe minibus</option>
                                <option value="van">Xe van</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="vehicle-seats" class="block text-sm font-medium text-neutral-700 mb-2">
                                Số ghế
                            </label>
                            <input type="number" id="vehicle-seats" name="seats" 
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeVehicleModal()" 
                                class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                            Hủy
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-brand-600 border border-transparent rounded-md hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                            Lưu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
