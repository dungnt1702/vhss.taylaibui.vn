<!-- Page detection element for JavaScript -->
<div id="vehicle-page" data-page-type="vehicles_list" style="display: none;"></div>

<!-- Header for Vehicles List -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-neutral-900">Danh sách xe</h1>
    <p class="text-neutral-600 mt-2">Quản lý và xem danh sách tất cả xe trong hệ thống</p>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="vehicle-table w-full">
            <thead class="bg-neutral-50">
                <tr class="hidden md:table-row">
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Xe số</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Màu sắc</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Ghi chú</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
                @forelse($vehicles as $vehicle)
                    <!-- Desktop Layout: 1 dòng cho 1 xe -->
                    <tr class="hover:bg-neutral-50 hidden md:table-row" 
                        data-vehicle-id="{{ $vehicle->id }}"
                        data-vehicle-name="{{ $vehicle->name }}"
                        data-vehicle-color="{{ $vehicle->color }}"
                        data-vehicle-seats="{{ $vehicle->seats }}"
                        data-vehicle-power="{{ $vehicle->power }}"
                        data-vehicle-wheel-size="{{ $vehicle->wheel_size }}"
                        data-vehicle-notes="{{ $vehicle->notes ?? 'Không có ghi chú' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                            {{ $vehicle->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                            <div class="w-6 h-6 rounded border border-neutral-300 " style="background-color: {{ $vehicle->color }};" title="{{ $vehicle->color }}"></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $vehicle->status_color_class }}">
                                {{ $vehicle->status_display_name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                            {{ $vehicle->notes ?? 'Không có ghi chú' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                            <div class="flex items-center space-x-2">
                                <!-- Nút Xem chi tiết xe - Tất cả user đều thấy được -->
                                <button onclick="openVehicleDetailModal({{ $vehicle->id }})" 
                                        class="text-green-600 hover:text-green-900 transition-colors duration-200 p-1 rounded hover:bg-green-50"
                                        title="Xem chi tiết xe">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                
                                <!-- Nút Tình trạng xe - Tất cả user đều thấy được -->
                                <button onclick="openStatusModal({{ $vehicle->id }}, '{{ $vehicle->status }}', '{{ $vehicle->notes }}')" 
                                        class="text-neutral-600 hover:text-neutral-900 transition-colors duration-200 p-1 rounded hover:bg-neutral-100"
                                        title="Tình trạng xe">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                                
                                <!-- Nút Sửa thông tin xe - Chỉ Admin mới thấy được -->
                                @if(auth()->user()->canManageVehicles())
                                    <button onclick="openEditVehicleModal({{ $vehicle->id }})" 
                                            class="text-blue-600 hover:text-blue-900 transition-colors duration-200 p-1 rounded hover:bg-blue-50"
                                            title="Sửa thông tin xe">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    
                                    <!-- Nút Xóa xe - Chỉ Admin mới thấy được -->
                                    <button onclick="deleteVehicle({{ $vehicle->id }})" 
                                            class="text-red-600 hover:text-red-900 transition-colors duration-200 p-1 rounded hover:bg-red-50"
                                            title="Xóa xe">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Mobile Layout: 3 dòng cho 1 xe với màu xen kẽ -->
                    <tr class="md:hidden {{ $loop->even ? 'bg-neutral-100' : 'bg-white' }}" 
                        data-vehicle-id="{{ $vehicle->id }}"
                        data-vehicle-name="{{ $vehicle->name }}"
                        data-vehicle-color="{{ $vehicle->color }}"
                        data-vehicle-seats="{{ $vehicle->seats }}"
                        data-vehicle-power="{{ $vehicle->power }}"
                        data-vehicle-wheel-size="{{ $vehicle->wheel_size }}"
                        data-vehicle-notes="{{ $vehicle->notes ?? 'Không có ghi chú' }}">
                        <!-- Dòng 1: Xe số, Màu sắc, Trạng thái -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                            {{ $vehicle->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                            <div class="w-6 h-6 rounded border border-neutral-300 " style="background-color: {{ $vehicle->color }};" title="{{ $vehicle->color }}"></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $vehicle->status_color_class }}">
                                {{ $vehicle->status_display_name }}
                            </span>
                        </td>
                    </tr>
                    
                    <!-- Dòng 2: Ghi chú -->
                    <tr class="md:hidden {{ $loop->even ? 'bg-neutral-100' : 'bg-white' }}">
                        <td colspan="3" class="px-6 py-3">
                            <div class="flex items-start space-x-2">
                                <span class="text-xs font-medium text-neutral-500 uppercase tracking-wider flex-shrink-0">Ghi chú:</span>
                                <span class="text-sm text-neutral-700">{{ $vehicle->notes ?? 'Không có ghi chú' }}</span>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Dòng 3: Các nút thao tác -->
                    <tr class="md:hidden {{ $loop->even ? 'bg-neutral-100' : 'bg-white' }}">
                        <td colspan="3" class="px-6 py-3">
                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-medium text-neutral-500 uppercase tracking-wider flex-shrink-0">Thao tác:</span>
                                <div class="flex space-x-2">
                                    <!-- Nút Xem chi tiết - Tất cả user đều thấy được -->
                                    <button onclick="openVehicleDetailModal({{ $vehicle->id }})" 
                                            class="text-green-600 hover:text-green-900 transition-colors duration-200 p-1 rounded hover:bg-green-50"
                                            title="Xem chi tiết xe">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    
                                    <!-- Nút Tình trạng xe - Tất cả user đều thấy được -->
                                    <button onclick="openStatusModal({{ $vehicle->id }}, '{{ $vehicle->status }}', '{{ $vehicle->notes }}')" 
                                            class="text-neutral-600 hover:text-neutral-900 transition-colors duration-200 p-1 rounded hover:bg-neutral-100"
                                            title="Tình trạng xe">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                    
                                    <!-- Nút Sửa thông tin xe - Chỉ Admin mới thấy được -->
                                    @if(auth()->user()->canManageVehicles())
                                        <button onclick="openEditVehicleModal({{ $vehicle->id }})" 
                                                class="text-blue-600 hover:text-blue-900 transition-colors duration-200 p-1 rounded hover:bg-blue-50"
                                                title="Sửa thông tin xe">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        
                                        <!-- Nút Xóa xe - Chỉ Admin mới thấy được -->
                                        <button onclick="deleteVehicle({{ $vehicle->id }})" 
                                                class="text-red-600 hover:text-red-900 transition-colors duration-200 p-1 rounded hover:bg-red-50"
                                                title="Xóa xe">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-neutral-500">
                        Không có xe nào
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-3 border-t border-neutral-200">
        <div class="flex justify-center">
            @if($vehicles->hasPages())
                <div class="flex items-center space-x-1">
                    <!-- Previous Page -->
                    @if($vehicles->onFirstPage())
                        <span class="px-2 py-1 text-xs text-neutral-400 bg-neutral-100 rounded cursor-not-allowed">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $vehicles->previousPageUrl() }}" class="px-2 py-1 text-xs text-neutral-600 bg-white border border-neutral-300 rounded hover:bg-neutral-50">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                    @endif

                    <!-- Page Numbers -->
                    @foreach($vehicles->getUrlRange(1, $vehicles->lastPage()) as $page => $url)
                        @if($page == $vehicles->currentPage())
                            <span class="px-2 py-1 text-xs text-white bg-brand-600 border border-brand-600 rounded">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-2 py-1 text-xs text-neutral-600 bg-white border border-neutral-300 rounded hover:bg-neutral-50">{{ $page }}</a>
                        @endif
                    @endforeach

                    <!-- Next Page -->
                    @if($vehicles->hasMorePages())
                        <a href="{{ $vehicles->nextPageUrl() }}" class="px-2 py-1 text-xs text-neutral-600 bg-white border border-neutral-300 rounded hover:bg-neutral-50">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <span class="px-2 py-1 text-xs text-neutral-400 bg-neutral-100 rounded cursor-not-allowed">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5l7 7-7 7" />
                            </svg>
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Vehicle Detail Modal -->
<div id="vehicle-detail-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-neutral-900">Chi tiết xe</h3>
                    <button onclick="closeVehicleDetailModal()" class="text-neutral-400 hover:text-neutral-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div id="vehicle-detail-content">
                    <!-- Nội dung sẽ được populate bởi JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Modal -->
<div id="status-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-neutral-900">Chỉnh sửa ghi chú xe</h3>
                    <button onclick="closeStatusModal()" class="text-neutral-400 hover:text-neutral-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form id="status-form">
                    @csrf
                    <input type="hidden" id="status-vehicle-id" name="vehicle_id">
                    
                    <div class="mb-6">
                        <label for="status-notes" class="block text-sm font-medium text-neutral-700 mb-2">
                            Ghi chú
                        </label>
                        <textarea id="status-notes" name="notes" rows="4"
                                  class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                                  placeholder="Nhập ghi chú về xe..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeStatusModal()" 
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