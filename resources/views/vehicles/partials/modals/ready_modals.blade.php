<!-- Ready Vehicles Modals -->

@include('vehicles.partials.modals.common_modals')

<!-- Start Timer Modal -->
<div id="start-timer-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-neutral-900">Bấm giờ xe</h3>
                    <button onclick="closeStartTimerModal()" class="text-neutral-400 hover:text-neutral-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form id="start-timer-form">
                    <input type="hidden" id="timer-vehicle-id" name="vehicle_id">
                    
                    <div class="mb-4">
                        <label for="timer-duration" class="block text-sm font-medium text-neutral-700 mb-2">
                            Thời gian (phút) <span class="text-red-500">*</span>
                        </label>
                        <select id="timer-duration" name="duration" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                            <option value="">-- Chọn thời gian --</option>
                            <option value="30">30 phút</option>
                            <option value="60">1 giờ</option>
                            <option value="90">1.5 giờ</option>
                            <option value="120">2 giờ</option>
                            <option value="180">3 giờ</option>
                            <option value="240">4 giờ</option>
                        </select>
                    </div>
                    
                    <div class="mb-6">
                        <label for="timer-notes" class="block text-sm font-medium text-neutral-700 mb-2">
                            Ghi chú
                        </label>
                        <textarea id="timer-notes" name="notes" rows="3" 
                                  class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500" 
                                  placeholder="Nhập ghi chú..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeStartTimerModal()" 
                                class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                            Hủy
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-brand-600 border border-transparent rounded-md hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                            Bấm giờ
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
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-neutral-900">Phân tuyến</h3>
                    <button onclick="closeAssignRouteModal()" class="text-neutral-400 hover:text-neutral-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form id="assign-route-form">
                    <input type="hidden" id="route-vehicle-id" name="vehicle_id">
                    
                    <div class="mb-4">
                        <label for="route-name" class="block text-sm font-medium text-neutral-700 mb-2">
                            Tên tuyến <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="route-name" name="route_name" 
                               class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500" 
                               placeholder="Nhập tên tuyến..." required>
                    </div>
                    
                    <div class="mb-6">
                        <label for="route-notes" class="block text-sm font-medium text-neutral-700 mb-2">
                            Ghi chú
                        </label>
                        <textarea id="route-notes" name="notes" rows="3" 
                                  class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500" 
                                  placeholder="Nhập ghi chú..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeAssignRouteModal()" 
                                class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                            Hủy
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-brand-600 border border-transparent rounded-md hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
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
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-neutral-900">Chuyển xưởng</h3>
                    <button onclick="closeMoveWorkshopModal()" class="text-neutral-400 hover:text-neutral-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form id="move-workshop-form">
                    <input type="hidden" id="workshop-vehicle-id" name="vehicle_id">
                    
                    <div class="mb-4">
                        <label for="workshop-reason" class="block text-sm font-medium text-neutral-700 mb-2">
                            Lý do chuyển xưởng <span class="text-red-500">*</span>
                        </label>
                        <select id="workshop-reason" name="reason" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                            <option value="">-- Chọn lý do --</option>
                            <option value="maintenance">Bảo trì định kỳ</option>
                            <option value="repair">Sửa chữa</option>
                            <option value="inspection">Kiểm tra kỹ thuật</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                    
                    <div class="mb-6">
                        <label for="workshop-notes" class="block text-sm font-medium text-neutral-700 mb-2">
                            Ghi chú
                        </label>
                        <textarea id="workshop-notes" name="notes" rows="3" 
                                  class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500" 
                                  placeholder="Nhập ghi chú..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeMoveWorkshopModal()" 
                                class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                            Hủy
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-brand-600 border border-transparent rounded-md hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                            Chuyển xưởng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

