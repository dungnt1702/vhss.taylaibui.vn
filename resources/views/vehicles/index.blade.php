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
            @if(in_array($filter, ['active', 'running', 'waiting', 'expired', 'paused']))
                <!-- Grid Display for specific statuses -->
                <!-- DEBUG: Filter: "{{ $filter }}", Total vehicles: {{ $vehicles->count() }} -->
                <div id="vehicle-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @forelse($vehicles as $vehicle)
                        <div class="vehicle-card bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200" data-vehicle-id="{{ $vehicle->id }}" data-vehicle-name="{{ $vehicle->name }}" data-status="{{ $vehicle->status }}" data-start-time="{{ $vehicle->start_time ? strtotime($vehicle->start_time) * 1000 : '' }}" data-end-time="{{ $vehicle->end_time ? strtotime($vehicle->end_time) * 1000 : '' }}" data-paused-at="{{ $vehicle->paused_at ? strtotime($vehicle->paused_at) * 1000 : '' }}" data-paused-remaining-seconds="{{ $vehicle->paused_remaining_seconds ?? '' }}">
                            <!-- DEBUG: Vehicle {{ $vehicle->id }} - Status: "{{ $vehicle->status }}" (Type: {{ gettype($vehicle->status) }}) -->
                            <!-- Vehicle Header - Clickable for collapse/expand -->
                            <div class="vehicle-header cursor-pointer p-4 border-b border-neutral-200 hover:bg-neutral-50 transition-colors duration-200" onclick="toggleVehicleSimple({{ $vehicle->id }})">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-semibold text-neutral-900">
                                        Xe số {{ $vehicle->name }}
                                    </h3>
                                    <div class="w-4 h-4 rounded border border-neutral-300 dark:border-neutral-600" style="background-color: {{ $vehicle->color }};" title="{{ $vehicle->color }}"></div>
                                </div>
                                <!-- Expand/Collapse Icon -->
                                <div class="flex justify-center mt-2">
                                    <svg class="w-4 h-4 text-neutral-500 transform transition-transform {{ $filter === 'running' ? 'rotate-180' : '' }}" id="icon-{{ $vehicle->id }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Vehicle Details - Collapsible -->
                            <div class="vehicle-content {{ $filter === 'running' ? '' : 'hidden' }} p-4" id="content-{{ $vehicle->id }}">
                                <!-- Countdown Timer Display - ALWAYS ON TOP -->
                                <div class="text-center mb-6">
                                    <div class="countdown-display text-6xl font-black {{ $vehicle->status === 'expired' ? 'text-red-600' : 'text-blue-600' }} drop-shadow-lg" id="countdown-{{ $vehicle->id }}">
                                        @if($vehicle->status === 'expired')
                                            <span class="text-red-600 font-black text-6xl drop-shadow-lg">HẾT GIỜ</span>
                                        @elseif($vehicle->end_time)
                                            <!-- Show actual time if vehicle has end_time -->
                                            @php
                                                $endTime = strtotime($vehicle->end_time);
                                                $now = time();
                                                $timeLeft = $endTime - $now;
                                                
                                                if ($timeLeft > 0) {
                                                    $minutesLeft = floor($timeLeft / 60);
                                                    $secondsLeft = $timeLeft % 60;
                                                    $minutesDisplay = str_pad($minutesLeft, 2, '0', STR_PAD_LEFT);
                                                    $secondsDisplay = str_pad($secondsLeft, 2, '0', STR_PAD_LEFT);
                                                } else {
                                                    $minutesDisplay = '00';
                                                    $secondsDisplay = '00';
                                                }
                                            @endphp
                                            <span class="countdown-minutes text-6xl font-black drop-shadow-lg">{{ $minutesDisplay }}</span><span class="text-6xl font-black drop-shadow-lg">:</span><span class="countdown-seconds text-6xl font-black drop-shadow-lg">{{ $secondsDisplay }}</span>
                                        @else
                                            <span class="countdown-minutes text-6xl font-black drop-shadow-lg">00</span><span class="text-6xl font-black drop-shadow-lg">:</span><span class="countdown-seconds text-6xl font-black drop-shadow-lg">00</span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($vehicle->status === 'active')
                                    <!-- Active vehicles (waiting) - 30p, 45p, Về xưởng -->
                                    <!-- DEBUG: Vehicle ID: {{ $vehicle->id }}, Status: {{ $vehicle->status }}, Type: {{ gettype($vehicle->status) }} -->
                                    <div class="flex flex-wrap gap-2 justify-center">
                                        <button onclick="startTimer({{ $vehicle->id }}, 30)" class="btn btn-success btn-sm">
                                            🚗 30p
                                        </button>
                                        <button onclick="startTimer({{ $vehicle->id }}, 45)" class="btn btn-primary btn-sm">
                                            🚙 45p
                                        </button>
                                        <button onclick="vehicleForms.openWorkshopModal({{ $vehicle->id }})" class="btn btn-secondary btn-sm">
                                            🔧 Về xưởng
                                        </button>
                                    </div>
                                @elseif($vehicle->status === 'running')
                                    <!-- Running vehicles - +10p, Tạm dừng, Về bãi -->
                                    <div class="flex flex-wrap gap-2 justify-center">
                                        <button onclick="addTime({{ $vehicle->id }}, 10)" class="btn btn-warning btn-sm">
                                            ⏰ +10p
                                        </button>
                                        <button onclick="pauseVehicle({{ $vehicle->id }})" class="btn btn-info btn-sm">
                                            ⏸️ Tạm dừng
                                        </button>
                                        <button onclick="returnToYard({{ $vehicle->id }})" class="btn btn-primary btn-sm">
                                            🏠 Về bãi
                                        </button>
                                    </div>
                                @elseif($vehicle->status === 'expired')
                                    <!-- Expired vehicles - +10p, Về bãi -->
                                    <div class="flex flex-wrap gap-2 justify-center">
                                        <button onclick="addTime({{ $vehicle->id }}, 10)" class="btn btn-warning btn-sm">
                                            ⏰ +10p
                                        </button>
                                        <button onclick="returnToYard({{ $vehicle->id }})" class="btn btn-primary btn-sm">
                                            🏠 Về bãi
                                        </button>
                                    </div>
                                @elseif($vehicle->status === 'paused')
                                    <!-- Paused vehicles - Tiếp tục, Về bãi -->
                                    <div class="flex flex-wrap gap-2 justify-center">
                                        <button onclick="resumeVehicle({{ $vehicle->id }})" class="btn btn-success btn-sm">
                                            ▶️ Tiếp tục
                                        </button>
                                        <button onclick="returnToYard({{ $vehicle->id }})" class="btn btn-primary btn-sm">
                                            🏠 Về bãi
                                        </button>
                                    </div>
                                @else
                                    <!-- Active vehicles (outside yard) - 30p, 45p -->
                                    <div class="flex flex-wrap gap-2 justify-center">
                                        <button onclick="startTimer({{ $vehicle->id }}, 30)" class="btn btn-success btn-sm">
                                            🚗 30p
                                        </button>
                                        <button onclick="startTimer({{ $vehicle->id }}, 45)" class="btn btn-primary btn-sm">
                                            🚙 45p
                                        </button>
                                    </div>
                                @endif
                                

                            </div>


                        </div>
                    @empty
                        <div class="col-span-full">
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-neutral-900">Không có xe nào</h3>
<p class="mt-1 text-sm text-neutral-500">
                                    Bắt đầu bằng cách thêm xe mới vào hệ thống.
                                </p>
                                @if(auth()->user()->canManageVehicles())
                                    <div class="mt-6">
                                        <button onclick="openVehicleModal()" class="btn btn-success">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Thêm xe mới
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforelse
                </div>
            @else
                <!-- List Display for route and group -->
                @if($filter === 'vehicles_list')
                    @include('vehicles.vehicles_list')
                @elseif($filter === 'vehicle_attributes')
                    @include('vehicles.vehicle_attributes')
                @else
                    @include('vehicles.vehicles_list')
                @endif
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
                                <option value="route">Xe cung đường</option>
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
            
            // Try to get vehicle data from the table row first
            let vehicleRow = document.querySelector(`tr[data-vehicle-id="${vehicleId}"]`);
            console.log('Looking for table row with data-vehicle-id:', vehicleId);
            console.log('Found table row:', vehicleRow);
            
            if (!vehicleRow) {
                // If not found in table, try to get from grid view
                vehicleRow = document.querySelector(`[data-vehicle-id="${vehicleId}"]`);
                console.log('Vehicle row not found in table, trying grid view:', vehicleRow);
            }
            
            if (vehicleRow) {
                console.log('Found vehicle row:', vehicleRow);
                console.log('Vehicle row HTML:', vehicleRow.outerHTML);
                
                // Get data from data attributes
                const nameElement = vehicleRow.querySelector('[data-vehicle-name]');
                const colorElement = vehicleRow.querySelector('[data-vehicle-color]');
                const seatsElement = vehicleRow.querySelector('[data-vehicle-seats]');
                const powerElement = vehicleRow.querySelector('[data-vehicle-power]');
                const wheelSizeElement = vehicleRow.querySelector('[data-vehicle-wheel-size]');
                
                console.log('Found elements:', {
                    nameElement,
                    colorElement,
                    seatsElement,
                    powerElement,
                    wheelSizeElement
                });
                
                const name = nameElement?.dataset.vehicleName || vehicleRow.dataset.vehicleName || '';
                const color = colorElement?.dataset.vehicleColor || vehicleRow.dataset.vehicleColor || '#808080';
                const seats = seatsElement?.dataset.vehicleSeats || vehicleRow.dataset.vehicleSeats || '';
                const power = powerElement?.dataset.vehiclePower || vehicleRow.dataset.vehiclePower || '';
                const wheelSize = wheelSizeElement?.dataset.vehicleWheelSize || vehicleRow.dataset.vehicleWheelSize || '';
                
                console.log('Extracted data:', { name, color, seats, power, wheelSize });
                
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
                    nameField.value = name;
                    console.log('Set name field to:', name);
                }
                if (colorField) {
                    colorField.value = color;
                    console.log('Set color field to:', color);
                }
                if (seatsField) {
                    seatsField.value = seats;
                    console.log('Set seats field to:', seats);
                }
                if (powerField) {
                    powerField.value = power;
                    console.log('Set power field to:', power);
                }
                if (wheelSizeField) {
                    wheelSizeField.value = wheelSize;
                    console.log('Set wheel size field to:', wheelSize);
                }
                
                // Update color preview
                if (colorPreview) {
                    colorPreview.style.backgroundColor = color;
                    console.log('Updated color preview to:', color);
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
                    colorName.textContent = colorOptions[color] || 'Không xác định';
                    console.log('Updated color name to:', colorOptions[color] || 'Không xác định');
                }
                
                console.log('=== Form populated successfully ===');
            } else {
                console.error('Vehicle row not found for ID:', vehicleId);
                console.log('Available vehicle rows:', document.querySelectorAll('[data-vehicle-id]'));
                console.log('All data-vehicle-id elements:', document.querySelectorAll('[data-vehicle-id]'));
            }
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
