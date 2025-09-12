<!-- Repairing Vehicles Modals -->

@include('vehicles.partials.modals.common_modals')

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
                        @foreach(\App\Models\RepairCategory::getActiveCategories() as $key => $label)
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
                            @foreach(\App\Models\RepairCategory::getActiveCategories() as $key => $label)
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

