<!-- Vehicles List Table Display -->
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="overflow-x-auto max-w-full">
        <style>
            /* Đảm bảo bảng không vượt quá chiều rộng màn hình */
            .vehicle-table {
                width: 100%;
                max-width: 100vw;
            }
            
            /* Desktop: table-layout fixed cho 5 cột */
            @media (min-width: 768px) {
                .vehicle-table {
                    table-layout: fixed;
                }
                
                .vehicle-table th:nth-child(1),
                .vehicle-table td:nth-child(1) {
                    width: 15%;
                }
                
                .vehicle-table th:nth-child(2),
                .vehicle-table td:nth-child(2) {
                    width: 15%;
                }
                
                .vehicle-table th:nth-child(3),
                .vehicle-table td:nth-child(3) {
                    width: 20%;
                }
                
                .vehicle-table th:nth-child(4),
                .vehicle-table td:nth-child(4) {
                    width: 25%;
                }
                
                .vehicle-table th:nth-child(5),
                .vehicle-table td:nth-child(5) {
                    width: 25%;
                }
            }
            
            /* Mobile: 3 cột với kích thước phù hợp */
            @media (max-width: 767px) {
                .vehicle-table {
                    font-size: 14px;
                }
                
                .vehicle-table th,
                .vehicle-table td {
                    padding: 8px 12px;
                }
                
                .vehicle-table th:nth-child(1),
                .vehicle-table td:nth-child(1) {
                    width: 25%;
                }
                
                .vehicle-table th:nth-child(2),
                .vehicle-table td:nth-child(2) {
                    width: 25%;
                }
                
                .vehicle-table th:nth-child(3),
                .vehicle-table td:nth-child(3) {
                    width: 50%;
                }
            }
        </style>
        <table class="min-w-full divide-y divide-neutral-200 vehicle-table">
                            <thead class="bg-neutral-50">
                    <!-- Desktop Header: 5 cột -->
                    <tr class="hidden md:table-row">
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Xe số</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Màu sắc</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Ghi chú</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                    <!-- Mobile Header: 3 cột -->
                    <tr class="md:hidden">
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Xe số</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Màu sắc</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Trạng thái</th>
                    </tr>
                </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
                                    @forelse($vehicles as $vehicle)
                        <!-- Desktop Layout: 1 dòng cho 1 xe -->
                        <tr class="hover:bg-neutral-50 hidden md:table-row" data-vehicle-id="{{ $vehicle->id }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900" data-vehicle-name="{{ $vehicle->name }}">
                                {{ $vehicle->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500" data-vehicle-color="{{ $vehicle->color }}">
                                <div class="w-6 h-6 rounded border border-neutral-300 dark:border-neutral-600" style="background-color: {{ $vehicle->color }};" title="{{ $vehicle->color }}"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $vehicle->status_color_class }}">
                                    {{ $vehicle->status_display_name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ $vehicle->notes ?? 'Không có ghi chú' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
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
                                        <button onclick="openVehicleModal({{ $vehicle->id }})" 
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
                        <tr class="md:hidden {{ $loop->even ? 'bg-neutral-100' : 'bg-white' }}" data-vehicle-id="{{ $vehicle->id }}">
                            <!-- Dòng 1: Xe số, Màu sắc, Trạng thái -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900" data-vehicle-name="{{ $vehicle->name }}">
                                {{ $vehicle->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500" data-vehicle-color="{{ $vehicle->color }}">
                                <div class="w-6 h-6 rounded border border-neutral-300 dark:border-neutral-600" style="background-color: {{ $vehicle->color }};" title="{{ $vehicle->color }}"></div>
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
                                        <button onclick="openStatusModal({{ $vehicle->id }}, '{{ $vehicle->status }}', '{{ $vehicle->status }}', '{{ $vehicle->notes }}')" 
                                                class="text-neutral-600 hover:text-neutral-900 transition-colors duration-200 p-1 rounded hover:bg-neutral-100"
                                                title="Tình trạng xe">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                        
                                        <!-- Nút Sửa thông tin xe - Chỉ Admin mới thấy được -->
                                        @if(auth()->user()->canManageVehicles())
                                            <button onclick="openVehicleModal({{ $vehicle->id }})" 
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
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-sm text-neutral-500">
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
                        @php
                            $prevUrl = $vehicles->previousPageUrl();
                            if (request('per_page')) {
                                $prevUrl = $prevUrl . (str_contains($prevUrl, '?') ? '&' : '?') . 'per_page=' . request('per_page');
                            }
                        @endphp
                        <a href="{{ $prevUrl }}" class="px-2 py-1 text-xs text-neutral-600 bg-white border border-neutral-300 rounded hover:bg-neutral-50 transition-colors duration-200">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                    @endif

                    <!-- Page Numbers -->
                    @foreach($vehicles->getUrlRange(1, min(3, $vehicles->lastPage())) as $page => $url)
                        @php
                            $pageUrl = $url;
                            if (request('per_page')) {
                                $pageUrl = $pageUrl . (str_contains($pageUrl, '?') ? '&' : '?') . 'per_page=' . request('per_page');
                            }
                        @endphp
                        <a href="{{ $pageUrl }}" class="px-2 py-1 text-xs rounded transition-colors duration-200 {{ $page == $vehicles->currentPage() ? 'bg-brand-500 text-white' : 'text-neutral-600 bg-white border border-neutral-300 hover:bg-neutral-50' }}">
                            {{ $page }}
                        </a>
                    @endforeach

                    <!-- Next Page -->
                    @if($vehicles->hasMorePages())
                        @php
                            $nextUrl = $vehicles->nextPageUrl();
                            if (request('per_page')) {
                                $nextUrl = $nextUrl . (str_contains($nextUrl, '?') ? '&' : '?') . 'per_page=' . request('per_page');
                            }
                        @endphp
                        <a href="{{ $nextUrl }}" class="px-2 py-1 text-xs text-neutral-600 bg-white border border-neutral-300 rounded hover:bg-neutral-50 transition-colors duration-200">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <span class="px-2 py-1 text-xs text-neutral-400 bg-neutral-100 rounded cursor-not-allowed">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
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
    <div class="relative min-h-screen flex items-center justify-center p-2 sm:p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
            <!-- Header -->
            <div class="p-6 pb-4 border-b border-neutral-200 flex-shrink-0">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-neutral-900">
                        Chi tiết xe
                    </h3>
                    <button onclick="closeVehicleDetailModal()" class="text-neutral-400 hover:text-neutral-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-6">
                <div id="vehicle-detail-content">
                    <!-- Nội dung chi tiết xe sẽ được load động -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Functions để xử lý modal chi tiết xe
function openVehicleDetailModal(vehicleId) {
    // Lấy thông tin xe từ data attributes
    const vehicleRow = document.querySelector(`tr[data-vehicle-id="${vehicleId}"]`);
    if (!vehicleRow) return;
    
    const vehicleName = vehicleRow.querySelector('[data-vehicle-name]')?.textContent?.trim() || 'N/A';
    const vehicleColor = vehicleRow.querySelector('[data-vehicle-color]')?.dataset?.vehicleColor || 'N/A';
    const vehicleSeats = vehicleRow.querySelector('[data-vehicle-seats]')?.dataset?.vehicleSeats || 'N/A';
    const vehiclePower = vehicleRow.querySelector('[data-vehicle-power]')?.dataset?.vehiclePower || 'N/A';
    const vehicleWheelSize = vehicleRow.querySelector('[data-vehicle-wheel-size]')?.dataset?.vehicleWheelSize || 'N/A';
    
    // Lấy thông tin từ các cột khác
    const statusElement = vehicleRow.querySelector('td:nth-child(3) span');
    const status = statusElement?.textContent?.trim() || 'N/A';
    const notesElement = vehicleRow.querySelector('td:nth-child(4)');
    const notes = notesElement?.textContent?.trim() || 'Không có ghi chú';
    
    // Tạo nội dung HTML
    const content = `
        <div class="space-y-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Thông tin cơ bản</h4>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm font-medium text-neutral-700">Xe số:</span>
                            <span class="ml-2 text-sm text-neutral-900">${vehicleName}</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-neutral-700">Màu sắc:</span>
                            <div class="ml-2 flex items-center space-x-2">
                                <div class="w-6 h-6 rounded border border-neutral-300" style="background-color: ${vehicleColor};"></div>
                                <span class="text-sm text-neutral-900">${vehicleColor}</span>
                            </div>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-neutral-700">Số chỗ ngồi:</span>
                            <span class="ml-2 text-sm text-neutral-900">${vehicleSeats}</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-neutral-700">Công suất:</span>
                            <span class="ml-2 text-sm text-neutral-900">${vehiclePower}</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-neutral-700">Kích cỡ bánh:</span>
                            <span class="ml-2 text-sm text-neutral-900">${vehicleWheelSize}</span>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Trạng thái & Ghi chú</h4>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm font-medium text-neutral-700">Trạng thái:</span>
                            <span class="ml-2 text-sm text-neutral-900">${status}</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-neutral-700">Ghi chú:</span>
                            <div class="ml-2 mt-1 p-3 bg-neutral-50 rounded-lg">
                                <span class="text-sm text-neutral-900">${notes}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Hiển thị nội dung
    document.getElementById('vehicle-detail-content').innerHTML = content;
    
    // Hiển thị modal
    document.getElementById('vehicle-detail-modal').classList.remove('hidden');
}

function closeVehicleDetailModal() {
    document.getElementById('vehicle-detail-modal').classList.add('hidden');
}

// Đóng modal khi click bên ngoài
document.addEventListener('click', function(event) {
    const modal = document.getElementById('vehicle-detail-modal');
    if (event.target === modal) {
        closeVehicleDetailModal();
    }
});

// Đóng modal khi nhấn phím Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeVehicleDetailModal();
    }
});
</script>
