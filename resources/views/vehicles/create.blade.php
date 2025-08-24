<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-neutral-800 dark:text-neutral-200 leading-tight">
                Thêm xe Gokart mới
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
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form id="vehicle-form" class="space-y-6">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div>
                            <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100 mb-4">Thông tin cơ bản</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Số xe <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="name" name="name" required min="1" max="99"
                                           class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-700 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                                           placeholder="VD: 1, 2, 3...">
                                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">Số thứ tự của xe Gokart</p>
                                </div>
                                
                                <div>
                                    <label for="color" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Màu sắc <span class="text-red-500">*</span>
                                    </label>
                                    <select id="color" name="color" required
                                            class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-700 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                        <option value="">Chọn màu sắc</option>
                                        @foreach($colors as $color)
                                            <option value="{{ $color }}">{{ $color }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="seats" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Số chỗ ngồi <span class="text-red-500">*</span>
                                    </label>
                                    <select id="seats" name="seats" required
                                            class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-700 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                        <option value="">Chọn số chỗ ngồi</option>
                                        @foreach($seats as $seat)
                                            <option value="{{ $seat }}">{{ $seat }} chỗ</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="power" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Công suất <span class="text-red-500">*</span>
                                    </label>
                                    <select id="power" name="power" required
                                            class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-700 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                        <option value="">Chọn công suất</option>
                                        @foreach($powerOptions as $power)
                                            <option value="{{ $power }}">{{ $power }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="wheel_size" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Kích cỡ bánh <span class="text-red-500">*</span>
                                    </label>
                                    <select id="wheel_size" name="wheel_size" required
                                            class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-700 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                        <option value="">Chọn kích cỡ bánh</option>
                                        @foreach($wheelSizes as $wheelSize)
                                            <option value="{{ $wheelSize }}">{{ $wheelSize }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Driver Information -->
                        <div>
                            <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100 mb-4">Thông tin tài xế</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="driver_name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Tên tài xế
                                    </label>
                                    <input type="text" id="driver_name" name="driver_name"
                                           class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-700 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                                           placeholder="Nhập tên tài xế">
                                </div>
                                
                                <div>
                                    <label for="driver_phone" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Số điện thoại tài xế
                                    </label>
                                    <input type="text" id="driver_phone" name="driver_phone"
                                           class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-700 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                                           placeholder="VD: 0943036579">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Information -->
                        <div>
                            <h3 class="text-lg font-medium text-neutral-900 dark:text-neutral-100 mb-4">Thông tin bổ sung</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="current_location" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Vị trí hiện tại
                                    </label>
                                    <input type="text" id="current_location" name="current_location"
                                           class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-700 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                                           placeholder="VD: Bãi xe, Xưởng sửa chữa...">
                                </div>
                                
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                        Ghi chú
                                    </label>
                                    <textarea id="notes" name="notes" rows="3"
                                              class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-700 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                                              placeholder="Nhập ghi chú về xe..."></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-neutral-200 dark:border-neutral-700">
                            <a href="{{ route('vehicles.index') }}" 
                               class="px-6 py-2 bg-neutral-300 dark:bg-neutral-600 hover:bg-neutral-400 dark:hover:bg-neutral-500 text-neutral-700 dark:text-neutral-300 font-semibold rounded-md transition-colors duration-200">
                                Hủy
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-brand-500 hover:bg-brand-600 text-white font-semibold rounded-md transition-colors duration-200">
                                Thêm xe Gokart
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('vehicle-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            // Disable submit button
            submitButton.disabled = true;
            submitButton.textContent = 'Đang thêm...';
            
            fetch('{{ route("vehicles.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    name: formData.get('name'),
                    color: formData.get('color'),
                    seats: formData.get('seats'),
                    power: formData.get('power'),
                    wheel_size: formData.get('wheel_size'),
                    driver_name: formData.get('driver_name'),
                    driver_phone: formData.get('driver_phone'),
                    current_location: formData.get('current_location'),
                    notes: formData.get('notes')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Xe Gokart đã được thêm thành công!');
                    window.location.href = '{{ route("vehicles.index") }}';
                } else {
                    let errorMessage = 'Có lỗi xảy ra:\n';
                    if (data.errors) {
                        Object.values(data.errors).forEach(errors => {
                            errors.forEach(error => {
                                errorMessage += '- ' + error + '\n';
                            });
                        });
                    }
                    alert(errorMessage);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi thêm xe');
            })
            .finally(() => {
                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            });
        });
    </script>
    @endpush
</x-app-layout>
