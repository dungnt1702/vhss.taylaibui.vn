<!-- Expired Vehicles Modals -->

@include('vehicles.partials.modals.common_modals')

<!-- Edit Notes Modal -->
<div id="edit-notes-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-neutral-900" id="edit-notes-modal-title">Chỉnh sửa ghi chú xe</h3>
                    <button onclick="closeEditNotesModal()" class="text-neutral-400 hover:text-neutral-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form id="edit-notes-form">
                    <input type="hidden" id="notes-vehicle-id" name="vehicle_id">
                    
                    <div class="mb-6">
                        <label for="notes-content" class="block text-sm font-medium text-neutral-700 mb-2">
                            Ghi chú
                        </label>
                        <textarea id="notes-content" name="notes" rows="4" 
                                  class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500" 
                                  placeholder="Nhập ghi chú..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeEditNotesModal()" 
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

