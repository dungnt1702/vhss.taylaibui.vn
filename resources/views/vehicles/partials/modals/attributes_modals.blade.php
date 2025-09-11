<!-- Attributes List Modals -->

@include('vehicles.partials.modals.common_modals')

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

