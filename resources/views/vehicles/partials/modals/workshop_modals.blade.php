<!-- Workshop Vehicles Modals -->
{{-- Debug: Workshop modals loaded --}}

@include('vehicles.partials.modals.common_modals')

<!-- Technical Update Modal -->
<div id="technical-update-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-neutral-900 mb-4">Cập nhật kỹ thuật xe</h3>
            <form id="technical-update-form">
                <input type="hidden" id="technical-vehicle-id" name="vehicle_id" value="">
                
                <div class="mb-4">
                    <label for="technical-category" class="block text-sm font-medium text-neutral-700 mb-2">
                        Hạng mục <span class="text-red-500">*</span>
                    </label>
                    <select id="technical-category" name="category" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">-- Chọn hạng mục --</option>
                        @foreach(\App\Models\VehicleTechnicalIssue::getRepairCategories() as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="technical-description" class="block text-sm font-medium text-neutral-700 mb-2">
                        Mô tả vấn đề <span class="text-red-500">*</span>
                    </label>
                    <textarea id="technical-description" name="description" rows="3" 
                              class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                              placeholder="Mô tả chi tiết vấn đề..." required></textarea>
                </div>
                
                <div class="mb-6">
                    <label for="technical-notes" class="block text-sm font-medium text-neutral-700 mb-2">
                        Ghi chú thêm
                    </label>
                    <textarea id="technical-notes" name="notes" rows="2" 
                              class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                              placeholder="Ghi chú bổ sung (tùy chọn)"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeTechnicalUpdateModal()" 
                            class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-neutral-500">
                        Hủy
                    </button>
                    <button type="submit" id="technical-update-submit-btn"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Cập nhật
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
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-neutral-900">Xử lý vấn đề</h3>
                    <button onclick="closeProcessIssueModal()" class="text-neutral-400 hover:text-neutral-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form id="process-issue-form">
                    <input type="hidden" id="process-issue-id" name="issue_id">
                    
                    <div class="mb-4">
                        <label for="process-status" class="block text-sm font-medium text-neutral-700 mb-2">
                            Trạng thái xử lý <span class="text-red-500">*</span>
                        </label>
                        <select id="process-status" name="status" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="in_progress">Đang xử lý</option>
                            <option value="completed">Hoàn thành</option>
                            <option value="cancelled">Hủy bỏ</option>
                        </select>
                    </div>
                    
                    <div class="mb-6">
                        <label for="process-notes" class="block text-sm font-medium text-neutral-700 mb-2">
                            Ghi chú xử lý
                        </label>
                        <textarea id="process-notes" name="notes" rows="4" 
                                  class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500" 
                                  placeholder="Nhập ghi chú xử lý..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeProcessIssueModal()" 
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

<!-- Description Detail Modal -->
<div id="description-detail-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-neutral-900">Chi tiết mô tả</h3>
                    <button onclick="closeDescriptionDetailModal()" class="text-neutral-400 hover:text-neutral-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div id="description-detail-content" class="prose max-w-none">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Return to Yard Modal -->
<div id="return-to-yard-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-neutral-900">Đưa xe về bãi</h3>
                <button onclick="closeReturnToYardModal()" class="text-neutral-400 hover:text-neutral-600">
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
                        onclick="resetReturnNotes()" 
                        class="text-sm text-neutral-500 hover:text-neutral-700 underline"
                    >
                        Xóa nội dung
                    </button>
                    
                    <div class="flex space-x-3">
                        <button 
                            type="button" 
                            onclick="closeReturnToYardModal()" 
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

<!-- Edit Notes Modal -->
<div id="edit-notes-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 id="edit-notes-modal-title" class="text-lg font-semibold text-neutral-900">Chỉnh sửa ghi chú xe</h3>
                <button onclick="closeEditNotesModal()" class="text-neutral-400 hover:text-neutral-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form id="edit-notes-form">
                <input type="hidden" id="edit-notes-vehicle-id" name="vehicle_id">
                
                <div class="mb-6">
                    <label for="edit-notes-textarea" class="block text-sm font-medium text-neutral-700 mb-2">
                        Ghi chú xe
                    </label>
                    <textarea id="edit-notes-textarea" name="notes" rows="4" 
                              class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                              placeholder="Nhập ghi chú về xe..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditNotesModal()" 
                            class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-neutral-500">
                        Hủy
                    </button>
                    <button type="submit" id="edit-notes-submit-btn"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Cập nhật
                    </button>
                </div>
            </form>
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
                        <label for="edit-issue-category" class="block text-sm font-medium text-neutral-700 mb-2">
                            Hạng mục <span class="text-red-500">*</span>
                        </label>
                        <select id="edit-issue-category" name="category" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">-- Chọn hạng mục --</option>
                            @foreach(\App\Models\VehicleTechnicalIssue::getRepairCategories() as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit-issue-description" class="block text-sm font-medium text-neutral-700 mb-2">
                            Mô tả vấn đề <span class="text-red-500">*</span>
                        </label>
                        <textarea id="edit-issue-description" name="description" rows="3" 
                                  class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                  placeholder="Mô tả chi tiết vấn đề..." required></textarea>
                    </div>
                    
                    <div class="mb-6">
                        <label for="edit-issue-notes" class="block text-sm font-medium text-neutral-700 mb-2">
                            Ghi chú thêm
                        </label>
                        <textarea id="edit-issue-notes" name="notes" rows="2" 
                                  class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                  placeholder="Ghi chú bổ sung (tùy chọn)"></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeEditIssueModal()" 
                                class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-neutral-500">
                            Hủy
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

