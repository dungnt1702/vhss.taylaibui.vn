<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-neutral-800 dark:text-neutral-200 leading-tight">
                Quản lý thuộc tính xe
            </h2>
            <a href="{{ route('vehicles.index') }}" class="inline-flex items-center px-4 py-2 bg-neutral-500 hover:bg-neutral-600 text-white font-semibold rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Quay lại
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Colors Management -->
            <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100 mb-4">Quản lý màu sắc</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($colors as $color)
                            <div class="flex items-center justify-between p-3 bg-neutral-50 dark:bg-neutral-700 rounded-lg">
                                <span class="text-neutral-700 dark:text-neutral-300">{{ $color }}</span>
                                <div class="flex space-x-2">
                                    <button onclick="editAttribute({{ $color->id }}, '{{ $color->value }}')" 
                                            class="text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button onclick="toggleAttribute({{ $color->id }}, {{ $color->is_active ? 'false' : 'true' }})" 
                                            class="{{ $color->is_active ? 'text-green-600' : 'text-red-600' }} hover:opacity-75">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                        <button onclick="addAttribute('color')" 
                                class="p-3 border-2 border-dashed border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-500 dark:text-neutral-400 hover:border-neutral-400 dark:hover:border-neutral-500 hover:text-neutral-600 dark:hover:text-neutral-300 transition-colors duration-200">
                            <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Thêm màu mới
                        </button>
                    </div>
                </div>
            </div>

            <!-- Seats Management -->
            <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100 mb-4">Quản lý số chỗ ngồi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($seats as $seat)
                            <div class="flex items-center justify-between p-3 bg-neutral-50 dark:bg-neutral-700 rounded-lg">
                                <span class="text-neutral-700 dark:text-neutral-300">{{ $seat }} chỗ</span>
                                <div class="flex space-x-2">
                                    <button onclick="editAttribute({{ $seat->id }}, '{{ $seat->value }}')" 
                                            class="text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button onclick="toggleAttribute({{ $seat->id }}, {{ $seat->is_active ? 'false' : 'true' }})" 
                                            class="{{ $seat->is_active ? 'text-green-600' : 'text-red-600' }} hover:opacity-75">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                        <button onclick="addAttribute('seats')" 
                                class="p-3 border-2 border-dashed border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-500 dark:text-neutral-400 hover:border-neutral-400 dark:hover:border-neutral-500 hover:text-neutral-600 dark:hover:text-neutral-300 transition-colors duration-200">
                            <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Thêm chỗ ngồi mới
                        </button>
                    </div>
                </div>
            </div>

            <!-- Power Management -->
            <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100 mb-4">Quản lý công suất</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($powerOptions as $power)
                            <div class="flex items-center justify-between p-3 bg-neutral-50 dark:bg-neutral-700 rounded-lg">
                                <span class="text-neutral-700 dark:text-neutral-300">{{ $power }}</span>
                                <div class="flex space-x-2">
                                    <button onclick="editAttribute({{ $power->id }}, '{{ $power->value }}')" 
                                            class="text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button onclick="toggleAttribute({{ $power->id }}, {{ $power->is_active ? 'false' : 'true' }})" 
                                            class="{{ $power->is_active ? 'text-green-600' : 'text-red-600' }} hover:opacity-75">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                        <button onclick="addAttribute('power')" 
                                class="p-3 border-2 border-dashed border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-500 dark:text-neutral-400 hover:border-neutral-400 dark:hover:border-neutral-500 hover:text-neutral-600 dark:hover:text-neutral-300 transition-colors duration-200">
                            <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Thêm công suất mới
                        </button>
                    </div>
                </div>
            </div>

            <!-- Wheel Size Management -->
            <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100 mb-4">Quản lý kích cỡ bánh</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($wheelSizes as $wheelSize)
                            <div class="flex items-center justify-between p-3 bg-neutral-50 dark:bg-neutral-700 rounded-lg">
                                <span class="text-neutral-700 dark:text-neutral-300">{{ $wheelSize }}</span>
                                <div class="flex space-x-2">
                                    <button onclick="editAttribute({{ $wheelSize->id }}, '{{ $wheelSize->value }}')" 
                                            class="text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button onclick="toggleAttribute({{ $wheelSize->id }}, {{ $wheelSize->is_active ? 'false' : 'true' }})" 
                                            class="{{ $wheelSize->is_active ? 'text-green-600' : 'text-red-600' }} hover:opacity-75">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                        <button onclick="addAttribute('wheel_size')" 
                                class="p-3 border-2 border-dashed border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-500 dark:text-neutral-400 hover:border-neutral-400 dark:hover:border-neutral-500 hover:text-neutral-600 dark:hover:text-neutral-300 transition-colors duration-200">
                            <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Thêm kích cỡ bánh mới
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attribute Modal -->
    <div id="attribute-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 id="modal-title" class="text-lg font-semibold text-neutral-900 dark:text-neutral-100 mb-4">
                        Thêm thuộc tính mới
                    </h3>
                    
                    <form id="attribute-form">
                        @csrf
                        <input type="hidden" id="attribute-id" name="attribute_id">
                        <input type="hidden" id="attribute-type" name="attribute_type">
                        
                        <div class="mb-4">
                            <label for="attribute-value" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Giá trị
                            </label>
                            <input type="text" id="attribute-value" name="value" required
                                   class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-700 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                                   placeholder="Nhập giá trị thuộc tính">
                        </div>
                        
                        <div class="flex space-x-3">
                            <button type="submit" class="flex-1 px-4 py-2 bg-brand-500 hover:bg-brand-600 text-white font-semibold rounded-md transition-colors duration-200">
                                Lưu
                            </button>
                            <button type="button" onclick="closeAttributeModal()" class="flex-1 px-4 py-2 bg-neutral-300 dark:bg-neutral-600 hover:bg-neutral-400 dark:hover:bg-neutral-500 text-neutral-700 dark:text-neutral-300 font-semibold rounded-md transition-colors duration-200">
                                Hủy
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function addAttribute(type) {
            document.getElementById('modal-title').textContent = 'Thêm thuộc tính mới';
            document.getElementById('attribute-id').value = '';
            document.getElementById('attribute-type').value = type;
            document.getElementById('attribute-value').value = '';
            document.getElementById('attribute-modal').classList.remove('hidden');
        }

        function editAttribute(id, value) {
            document.getElementById('modal-title').textContent = 'Chỉnh sửa thuộc tính';
            document.getElementById('attribute-id').value = id;
            document.getElementById('attribute-type').value = '';
            document.getElementById('attribute-value').value = value;
            document.getElementById('attribute-modal').classList.remove('hidden');
        }

        function closeAttributeModal() {
            document.getElementById('attribute-modal').classList.add('hidden');
        }

        function toggleAttribute(id, active) {
            // Implementation for toggling attribute status
            console.log('Toggle attribute:', id, 'to:', active);
        }

        document.getElementById('attribute-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            // Disable submit button
            submitButton.disabled = true;
            submitButton.textContent = 'Đang lưu...';
            
            // Implementation for saving attribute
            console.log('Save attribute:', formData.get('value'));
            
            // Re-enable submit button
            submitButton.disabled = false;
            submitButton.textContent = originalText;
            
            closeAttributeModal();
        });
    </script>
    @endpush
</x-app-layout>
