<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
                {{ $pageTitle }}
            </h2>
            <div class="flex items-center space-x-4">
                <!-- Rows per page selector and add vehicle button -->
                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-2">
                        <label for="per-page" class="text-sm text-neutral-600">Hiển thị:</label>
                        <select id="per-page" class="px-3 py-1 border border-neutral-300 rounded-md bg-white text-neutral-900 text-sm" style="appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: none;">
                            <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('per_page', 10) == 20 ? 'selected' : '' }}>20</option>
                            <option value="30" {{ request('per_page', 10) == 30 ? 'selected' : '' }}>30</option>
                        </select>
                        <span class="text-sm text-neutral-600">/{{ $vehicles->total() }} xe</span>
                    </div>
                    
                    @if(auth()->user()->canManageVehicles() && !in_array($filter, ['active', 'running', 'waiting', 'expired', 'paused']))
                    <button onclick="openVehicleModal()" class="btn btn-success btn-sm" title="Thêm xe mới">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>
    


    <!-- Page Content -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Tabs -->
            @if(in_array($filter, ['waiting', 'running', 'expired', 'paused']))
                <!-- Grid Display for specific statuses -->
                @include('vehicles.grid_display')
            @elseif($filter === 'active')
                <!-- Active Vehicles Display - Xe ngoài bãi -->
                @include('vehicles.active_vehicles')
            @elseif($filter === 'vehicles_list')
                <!-- Vehicles List Display -->
                @include('vehicles.vehicles_list')
            @elseif($filter === 'attributes')
                <!-- Vehicle Attributes Display -->
                @include('vehicles.vehicle_attributes')
            @else
                <!-- Default to active vehicles -->
                @include('vehicles.active_vehicles')
            @endif
        </div>
    </div>

    <!-- Vehicle Modal -->
    <div id="vehicle-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative min-h-screen flex items-center justify-center p-2 sm:p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-sm sm:max-w-md md:max-w-2xl max-h-[90vh] flex flex-col">
                <!-- Header -->
                <div class="p-6 pb-4 border-b border-neutral-200 flex-shrink-0">
                    <div class="flex justify-between items-center">
                        <h3 id="vehicle-modal-title" class="text-lg font-semibold text-neutral-900">
                            Thêm xe mới
                        </h3>
                        <button onclick="closeVehicleModal()" class="text-neutral-400 hover:text-neutral-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Form Content with Scroll -->
                <div class="flex-1 overflow-y-auto p-6 modal-scroll">
                    <form id="vehicle-form">
                        @csrf
                        <input type="hidden" id="vehicle-edit-id" name="vehicle_id">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="vehicle-name" class="block text-sm font-medium text-neutral-700 mb-2">
                                    Xe số <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="vehicle-name" name="name" required
                                       class="w-full px-3 py-2 border border-neutral-300 rounded-md bg-white text-neutral-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                                       placeholder="Nhập số xe">
                            </div>
                            
                            <div>
                                <label for="vehicle-color" class="block text-sm font-medium text-neutral-700 mb-2">
                                    Màu sắc <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center space-x-3">
                                    <button type="button" onclick="openColorPicker()" class="px-4 py-2 border border-neutral-300 rounded-md bg-white text-neutral-700 hover:bg-neutral-50 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                        Chọn màu
                                    </button>
                                    <div id="selected-color-display" class="flex items-center space-x-2">
                                        <div id="color-preview" class="w-6 h-6 rounded border border-neutral-300" style="background-color: #808080;"></div>
                                        <span id="color-name" class="text-sm text-neutral-600">Chưa chọn màu</span>
                                    </div>
                                </div>
                                <input type="hidden" id="vehicle-color" name="color" value="#808080" required>
                            </div>
                            
                            <div>
                                <label for="vehicle-seats" class="block text-sm font-medium text-neutral-700 mb-2">
                                    Số chỗ ngồi <span class="text-red-500">*</span>
                                </label>
                                <select id="vehicle-seats" name="seats" required
                                        class="w-full px-3 py-2 border border-neutral-300 rounded-md bg-white text-neutral-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                    <option value="">Chọn số chỗ</option>
                                    @if($seats && count($seats) > 0)
                                        @foreach($seats as $seat)
                                            <option value="{{ $seat }}">{{ $seat }}</option>
                                        @endforeach
                                    @else
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    @endif
                                </select>
                            </div>
                            
                            <div>
                                <label for="vehicle-power" class="block text-sm font-medium text-neutral-700 mb-2">
                                    Công suất <span class="text-red-500">*</span>
                                </label>
                                <select id="vehicle-power" name="power" required
                                        class="w-full px-3 py-2 border border-neutral-300 rounded-md bg-white text-neutral-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                    <option value="">Chọn công suất</option>
                                    @if($powerOptions && count($powerOptions) > 0)
                                        @foreach($powerOptions as $power)
                                            <option value="{{ $power }}">{{ $power }}</option>
                                        @endforeach
                                    @else
                                        <option value="48V-1000W">48V-1000W</option>
                                        <option value="60V-1200W">60V-1200W</option>
                                    @endif
                                </select>
                            </div>
                            
                            <div>
                                <label for="vehicle-wheel-size" class="block text-sm font-medium text-neutral-700 mb-2">
                                    Kích cỡ bánh <span class="text-red-500">*</span>
                                </label>
                                <select id="vehicle-wheel-size" name="wheel_size" required
                                        class="w-full px-3 py-2 border border-neutral-300 rounded-md bg-white text-neutral-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                    <option value="">Chọn kích cỡ bánh</option>
                                    @if($wheelSizes && count($wheelSizes) > 0)
                                        @foreach($wheelSizes as $wheelSize)
                                            <option value="{{ $wheelSize }}">{{ $wheelSize }}</option>
                                        @endforeach
                                    @else
                                        <option value="7inch">7inch</option>
                                        <option value="8inch">8inch</option>
                                    @endif
                                </select>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="vehicle-notes" class="block text-sm font-medium text-neutral-700 mb-2">
                                    Ghi chú
                                </label>
                                <textarea id="vehicle-notes" name="notes" rows="3"
                                          class="w-full px-3 py-2 border border-neutral-300 rounded-md bg-white text-neutral-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                                          placeholder="Nhập ghi chú về xe..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Footer - Fixed at bottom -->
                <div class="p-6 pt-4 border-t border-neutral-200 flex-shrink-0">
                    <div class="flex space-x-3">
                        <button type="submit" form="vehicle-form" id="vehicle-submit-btn" class="btn btn-primary flex-1">
                            Thêm xe
                        </button>
                        <button type="button" onclick="closeVehicleModal()" class="btn btn-secondary flex-1">
                            Hủy
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div id="status-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4">
                        Cập nhật trạng thái xe
                    </h3>
                    
                    <form id="status-form">
                        @csrf
                        <input type="hidden" id="vehicle-id" name="vehicle_id">
                        
                        <div class="mb-4">
                            <label for="status-select" class="block text-sm font-medium text-neutral-700 mb-2">
                                Trạng thái mới
                            </label>
                            <select id="status-select" name="status" class="w-full px-3 py-2 border border-neutral-300 rounded-md bg-white text-neutral-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                <option value="active">Xe ngoài bãi</option>
                                <option value="inactive">Xe trong xưởng</option>
                                <option value="running">Xe đang chạy</option>
                                <option value="waiting">Xe đang chờ</option>
                                <option value="expired">Xe hết giờ</option>
                                <option value="paused">Xe tạm dừng</option>
                                
                                <option value="group">Xe ngoài bãi</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="status-notes" class="block text-sm font-medium text-neutral-700 mb-2">
                                Ghi chú
                            </label>
                            <textarea id="status-notes" name="notes" rows="3" class="w-full px-3 py-2 border border-neutral-300 rounded-md bg-white text-neutral-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" placeholder="Nhập ghi chú về trạng thái xe..."></textarea>
                        </div>
                        
                        <div class="flex space-x-3">
                                                    <button type="submit" class="btn btn-primary flex-1">
                            Cập nhật
                        </button>
                        <button type="button" onclick="closeStatusModal()" class="btn btn-secondary flex-1">
                            Hủy
                        </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Workshop Modal -->
    <div id="workshop-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4">
                        Chuyển xe về xưởng
                    </h3>
                    
                    <form id="workshop-form">
                        @csrf
                        <input type="hidden" id="workshop-vehicle-id" name="vehicle_id">
                        
                        <div class="mb-4">
                            <label for="workshop-reason" class="block text-sm font-medium text-neutral-700 mb-2">
                                Lý do chuyển xe về xưởng
                            </label>
                            <textarea id="workshop-reason" name="reason" rows="4" class="w-full px-3 py-2 border border-neutral-300 rounded-md bg-white text-neutral-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" placeholder="Nhập lý do chuyển xe về xưởng kiểm tra..." required></textarea>
                        </div>
                        
                        <div class="flex space-x-3">
                                                    <button type="submit" class="btn btn-danger flex-1">
                            Chuyển về xưởng
                        </button>
                        <button type="button" onclick="closeWorkshopModal()" class="btn btn-secondary flex-1">
                            Hủy
                        </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Color Picker Modal -->
    <div id="color-picker-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-neutral-900">
                            Chọn màu xe
                        </h3>
                        <button onclick="closeColorPicker()" class="text-neutral-400 hover:text-neutral-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="max-h-96 overflow-y-auto">
                        <div class="grid grid-cols-6 gap-3 mb-4">
                            @php
                                $colorOptions = [
                                    '#FF0000' => 'Đỏ',
                                    '#FF4500' => 'Cam đỏ',
                                    '#FF8C00' => 'Cam',
                                    '#FFD700' => 'Vàng',
                                    '#32CD32' => 'Xanh lá',
                                    '#00CED1' => 'Xanh dương',
                                    '#4169E1' => 'Xanh hoàng gia',
                                    '#8A2BE2' => 'Xanh tím',
                                    '#FF69B4' => 'Hồng',
                                    '#FF1493' => 'Hồng đậm',
                                    '#FF6347' => 'Cà chua',
                                    '#20B2AA' => 'Xanh biển nhạt',
                                    '#228B22' => 'Xanh rừng',
                                    '#DC143C' => 'Đỏ đậm',
                                    '#000000' => 'Đen',
                                    '#FFFFFF' => 'Trắng',
                                    '#808080' => 'Xám',
                                    '#C0C0C0' => 'Bạc',
                                    '#D2691E' => 'Nâu',
                                    '#4B0082' => 'Tím',
                                    '#FF00FF' => 'Magenta',
                                    '#FF6B6B' => 'Hồng san hô',
                                    '#4ECDC4' => 'Xanh ngọc',
                                    '#45B7D1' => 'Xanh dương nhạt',
                                    '#96CEB4' => 'Xanh mint',
                                    '#FFEAA7' => 'Vàng kem',
                                    '#DDA0DD' => 'Tím nhạt',
                                    '#98D8C8' => 'Xanh lá nhạt',
                                    '#F7DC6F' => 'Vàng đậm',
                                    '#BB8FCE' => 'Tím lavender',
                                    '#85C1E9' => 'Xanh dương bầu trời',
                                    '#F8C471' => 'Cam nhạt',
                                    '#82E0AA' => 'Xanh lá tươi',
                                    '#F1948A' => 'Hồng đào',
                                    '#85C1E9' => 'Xanh dương nhạt',
                                    '#D7BDE2' => 'Tím nhạt',
                                    '#FAD7A0' => 'Cam kem',
                                    '#A9DFBF' => 'Xanh lá nhạt',
                                    '#F9E79F' => 'Vàng nhạt',
                                    '#D5A6BD' => 'Hồng nhạt',
                                    '#A3E4D7' => 'Xanh ngọc nhạt',
                                    '#F8C471' => 'Cam kem',
                                    '#D2B4DE' => 'Tím lavender nhạt'
                                ];
                            @endphp
                            @foreach($colorOptions as $hex => $name)
                                <div class="color-option cursor-pointer text-center" data-color="{{ $hex }}" data-name="{{ $name }}" onclick="selectColor('{{ $hex }}', '{{ $name }}')" title="{{ $name }}">
                                    <div class="w-10 h-10 rounded-lg border-2 border-neutral-300 hover:border-brand-500 transition-all hover:scale-110 mx-auto mb-1" style="background-color: {{ $hex }};"></div>
                                    <div class="text-xs text-neutral-600">{{ $name }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button onclick="closeColorPicker()" class="px-4 py-2 border border-neutral-300 rounded-md bg-white text-neutral-700 hover:bg-neutral-50">
                            Hủy
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    @push('scripts')
    <!-- All JavaScript functionality moved to separate modules:
         - vehicles.js: Main vehicle management logic
         - vehicle-forms.js: Form handling and modals
         - vehicle-operations.js: Vehicle control operations
         - vehicle-wrappers.js: Wrapper functions for HTML onclick events
    -->
    
    <!-- Vehicle modal and color picker functionality -->
    <style>
        /* Color picker modal styles */
        #color-picker-modal .max-h-96 {
            max-height: 24rem;
        }
        
        .color-option {
            transition: all 0.2s ease-in-out;
        }
        
        .color-option:hover {
            transform: scale(1.05);
        }
        
        .color-option:active {
            transform: scale(0.95);
        }
        
        /* Custom scrollbar for color grid */
        #color-picker-modal .overflow-y-auto::-webkit-scrollbar {
            width: 8px;
        }
        
        #color-picker-modal .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        #color-picker-modal .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        #color-picker-modal .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
    
    <script>
        // Vehicle modal functions
        function openVehicleModal(vehicleId = null) {
            console.log('=== openVehicleModal called with vehicleId:', vehicleId, '===');
            
            const modal = document.getElementById('vehicle-modal');
            const modalTitle = document.getElementById('vehicle-modal-title');
            const form = document.getElementById('vehicle-form');
            const editIdInput = document.getElementById('vehicle-edit-id');
            
            console.log('Modal elements found:', { modal, modalTitle, form, editIdInput });
            
            if (vehicleId) {
                // Edit mode
                modalTitle.textContent = 'Sửa thông tin xe';
                editIdInput.value = vehicleId;
                
                console.log('Edit mode - loading data for vehicle:', vehicleId);
                
                // Debug: Check all data attributes in the table
                const allRows = document.querySelectorAll('tr[data-vehicle-id]');
                console.log('All table rows with data-vehicle-id:', allRows);
                
                allRows.forEach((row, index) => {
                    const rowId = row.dataset.vehicleId;
                    const name = row.querySelector('[data-vehicle-name]')?.dataset.vehicleName;
                    const color = row.querySelector('[data-vehicle-color]')?.dataset.vehicleColor;
                    console.log(`Row ${index}: ID=${rowId}, Name=${name}, Color=${color}`);
                });
                
                // Load vehicle data
                loadVehicleData(vehicleId);
            } else {
                // Add mode
                modalTitle.textContent = 'Thêm xe mới';
                editIdInput.value = '';
                form.reset();
                
                // Reset color picker
                document.getElementById('vehicle-color').value = '#808080';
                document.getElementById('color-preview').style.backgroundColor = '#808080';
                document.getElementById('color-name').textContent = 'Chưa chọn màu';
                
                console.log('Add mode - form reset');
            }
            
            modal.classList.remove('hidden');
            console.log('Modal opened');
        }
        
        function closeVehicleModal() {
            document.getElementById('vehicle-modal').classList.add('hidden');
        }
        
        function loadVehicleData(vehicleId) {
            console.log('=== loadVehicleData called with vehicleId:', vehicleId, '===');
            
            // Call API to get vehicle data from database
            fetch(`/api/vehicles/${vehicleId}/data`)
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        const vehicleData = result.data;
                        console.log('Vehicle data from API:', vehicleData);
                        
                        // Populate form fields with data from API
                        populateVehicleForm(vehicleData);
                    } else {
                        console.error('Failed to get vehicle data:', result.message);
                        alert('Không thể lấy thông tin xe: ' + result.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching vehicle data:', error);
                    alert('Lỗi khi lấy thông tin xe: ' + error.message);
                });
        }
        
        function populateVehicleForm(vehicleData) {
            console.log('=== populateVehicleForm called with:', vehicleData, '===');
                
                // Populate form fields
                const nameField = document.getElementById('vehicle-name');
                const colorField = document.getElementById('vehicle-color');
                const seatsField = document.getElementById('vehicle-seats');
                const powerField = document.getElementById('vehicle-power');
                const wheelSizeField = document.getElementById('vehicle-wheel-size');
                const colorPreview = document.getElementById('color-preview');
                const colorName = document.getElementById('color-name');
                
                console.log('Form fields found:', {
                    nameField,
                    colorField,
                    seatsField,
                    powerField,
                    wheelSizeField,
                    colorPreview,
                    colorName
                });
                
                if (nameField) {
                    nameField.value = vehicleData.name || '';
                    console.log('Set name field to:', vehicleData.name);
                }
                if (colorField) {
                    colorField.value = vehicleData.color || '#808080';
                    console.log('Set color field to:', vehicleData.color);
                }
                if (seatsField) {
                    seatsField.value = vehicleData.seats || '';
                    console.log('Set seats field to:', vehicleData.seats);
                }
                if (powerField) {
                    powerField.value = vehicleData.power || '';
                    console.log('Set power field to:', vehicleData.power);
                }
                if (wheelSizeField) {
                    wheelSizeField.value = vehicleData.wheel_size || '';
                    console.log('Set wheel size field to:', vehicleData.wheel_size);
                }
                
                // Update color preview
                if (colorPreview) {
                    colorPreview.style.backgroundColor = vehicleData.color || '#808080';
                    console.log('Updated color preview to:', vehicleData.color);
                }
                
                // Find color name for display
                const colorOptions = {
                    '#FF0000': 'Đỏ', '#FF4500': 'Cam đỏ', '#FF8C00': 'Cam', '#FFD700': 'Vàng',
                    '#32CD32': 'Xanh lá', '#00CED1': 'Xanh dương', '#4169E1': 'Xanh hoàng gia',
                    '#8A2BE2': 'Xanh tím', '#FF69B4': 'Hồng', '#FF1493': 'Hồng đậm',
                    '#FF6347': 'Cà chua', '#20B2AA': 'Xanh biển nhạt', '#228B22': 'Xanh rừng',
                    '#DC143C': 'Đỏ đậm', '#000000': 'Đen', '#FFFFFF': 'Trắng', '#808080': 'Xám',
                    '#C0C0C0': 'Bạc', '#D2691E': 'Nâu', '#4B0082': 'Tím', '#FF00FF': 'Magenta',
                    '#FF6B6B': 'Hồng san hô', '#4ECDC4': 'Xanh ngọc', '#45B7D1': 'Xanh dương nhạt',
                    '#96CEB4': 'Xanh mint', '#FFEAA7': 'Vàng kem', '#DDA0DD': 'Tím nhạt',
                    '#98D8C8': 'Xanh lá nhạt', '#F7DC6F': 'Vàng đậm', '#BB8FCE': 'Tím lavender',
                    '#85C1E9': 'Xanh dương bầu trời', '#F8C471': 'Cam nhạt', '#82E0AA': 'Xanh lá tươi',
                    '#F1948A': 'Hồng đào', '#D7BDE2': 'Tím nhạt', '#FAD7A0': 'Cam kem',
                    '#A9DFBF': 'Xanh lá nhạt', '#F9E79F': 'Vàng nhạt', '#D5A6BD': 'Hồng nhạt',
                    '#A3E4D7': 'Xanh ngọc nhạt', '#D2B4DE': 'Tím lavender nhạt'
                };
                
                if (colorName) {
                    colorName.textContent = colorOptions[vehicleData.color] || 'Không xác định';
                    console.log('Updated color name to:', colorOptions[vehicleData.color] || 'Không xác định');
                }
                
                console.log('=== Form populated successfully ===');
        }
        
        // Color picker functions
        function openColorPicker() {
            document.getElementById('color-picker-modal').classList.remove('hidden');
        }
        
        function closeColorPicker() {
            document.getElementById('color-picker-modal').classList.add('hidden');
        }
        
        function selectColor(hex, name) {
            // Update hidden input
            document.getElementById('vehicle-color').value = hex;
            
            // Update color preview
            document.getElementById('color-preview').style.backgroundColor = hex;
            document.getElementById('color-name').textContent = name;
            
            // Close modal
            closeColorPicker();
        }
        
        // Close modals with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeColorPicker();
                closeVehicleModal();
            }
        });
        
        // Close modals when clicking outside
        document.getElementById('color-picker-modal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeColorPicker();
            }
        });
        
        document.getElementById('vehicle-modal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeVehicleModal();
            }
        });
    </script>
    
    <!-- Auto-expand all vehicle cards when filter is 'running' -->
    @if($filter === 'running')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Use the global function to auto-expand all running vehicles
            if (typeof window.autoExpandRunningVehicles === 'function') {
                const expandedCount = window.autoExpandRunningVehicles();
                console.log(`Auto-expanded ${expandedCount} vehicle cards for running filter`);
            } else {
                // Fallback if function is not available
                console.warn('autoExpandRunningVehicles function not available, using fallback');
                const allVehicleCards = document.querySelectorAll('.vehicle-card');
                
                allVehicleCards.forEach(function(card) {
                    const vehicleId = card.dataset.vehicleId;
                    const content = document.getElementById(`content-${vehicleId}`);
                    const icon = document.getElementById(`icon-${vehicleId}`);
                    
                    if (content && icon) {
                        content.classList.remove('hidden');
                        icon.style.transform = 'rotate(180deg)';
                    }
                });
            }
        });
    </script>
    @endif
    @endpush
</x-app-layout>
