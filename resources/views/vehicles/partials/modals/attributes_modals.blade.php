{{-- Include common modals first --}}
@include('vehicles.partials.modals.common_modals')

{{-- Add/Edit Attribute Modal --}}
<div id="attributeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900" id="attributeModalTitle">
                    Thêm thuộc tính mới
                </h3>
                <button onclick="closeAttributeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="mt-4">
                <form id="attributeForm">
                    <input type="hidden" id="attributeType" name="type">
                    <input type="hidden" id="attributeOldValue" name="old_value">
                    
                    <div class="mb-4">
                        <label for="attributeValue" class="block text-sm font-medium text-gray-700 mb-2">
                            <span id="attributeLabel">Giá trị thuộc tính</span>
                        </label>
                        <input type="text" 
                               id="attributeValue" 
                               name="value" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Nhập giá trị thuộc tính"
                               required>
                        <div id="colorPreview" class="mt-2 hidden">
                            <div class="w-8 h-8 rounded-full border border-gray-300" id="colorPreviewBox"></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="attributeSortOrder" class="block text-sm font-medium text-gray-700 mb-2">
                            Thứ tự sắp xếp
                        </label>
                        <input type="number" 
                               id="attributeSortOrder" 
                               name="sort_order" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Thứ tự hiển thị"
                               min="1"
                               value="1">
                    </div>

                    <div class="flex items-center mb-4">
                        <input type="checkbox" 
                               id="attributeIsActive" 
                               name="is_active" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                               checked>
                        <label for="attributeIsActive" class="ml-2 block text-sm text-gray-700">
                            Kích hoạt thuộc tính
                        </label>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button type="button" 
                        onclick="closeAttributeModal()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Hủy
                </button>
                <button type="button" 
                        onclick="saveAttribute()" 
                        id="saveAttributeBtn"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Lưu
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteAttributeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">
                    Xác nhận xóa
                </h3>
                <button onclick="closeDeleteAttributeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="mt-4">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">
                            Bạn có chắc chắn muốn xóa thuộc tính này?
                        </h4>
                        <p class="text-sm text-gray-500 mt-1" id="deleteAttributeMessage">
                            Hành động này không thể hoàn tác.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button type="button" 
                        onclick="closeDeleteAttributeModal()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Hủy
                </button>
                <button type="button" 
                        onclick="confirmDeleteAttribute()" 
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                    Xóa
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Bulk Add Modal --}}
<div id="bulkAddModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">
                    Thêm hàng loạt
                </h3>
                <button onclick="closeBulkAddModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="mt-4">
                <form id="bulkAddForm">
                    <input type="hidden" id="bulkAttributeType" name="type">
                    
                    <div class="mb-4">
                        <label for="bulkAttributeValues" class="block text-sm font-medium text-gray-700 mb-2">
                            <span id="bulkAttributeLabel">Danh sách giá trị (mỗi dòng một giá trị)</span>
                        </label>
                        <textarea id="bulkAttributeValues" 
                                  name="values" 
                                  rows="6"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Nhập các giá trị, mỗi dòng một giá trị..."></textarea>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button type="button" 
                        onclick="closeBulkAddModal()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Hủy
                </button>
                <button type="button" 
                        onclick="saveBulkAttributes()" 
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Thêm tất cả
                </button>
            </div>
        </div>
    </div>
</div>