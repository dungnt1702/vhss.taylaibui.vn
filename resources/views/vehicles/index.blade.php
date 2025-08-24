<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-neutral-800 dark:text-neutral-200 leading-tight">
                {{ $pageTitle }}
            </h2>
            @if(auth()->user()->canManageVehicles())
                <a href="{{ route('vehicles.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-500 hover:bg-brand-600 text-white font-semibold rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Thêm xe mới
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Navigation Tabs -->
            <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4">
                    <nav class="flex flex-wrap gap-2" aria-label="Vehicle navigation">
                        <a href="{{ route('vehicles.index', ['filter' => 'all']) }}" 
                           class="px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ $filter === 'all' ? 'bg-brand-500 text-white' : 'text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-100 hover:bg-neutral-100 dark:hover:bg-neutral-700' }}">
                            Tất cả xe
                        </a>
                        <a href="{{ route('vehicles.index', ['filter' => 'inactive']) }}" 
                           class="px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ $filter === 'inactive' ? 'bg-red-500 text-white' : 'text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-100 hover:bg-neutral-100 dark:hover:bg-neutral-700' }}">
                            Xe trong xưởng
                        </a>
                        <a href="{{ route('vehicles.index', ['filter' => 'active']) }}" 
                           class="px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ $filter === 'active' ? 'bg-green-500 text-white' : 'text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-100 hover:bg-neutral-100 dark:hover:bg-neutral-700' }}">
                            Xe ngoài bãi
                        </a>
                        <a href="{{ route('vehicles.index', ['filter' => 'running']) }}" 
                           class="px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ $filter === 'running' ? 'bg-blue-500 text-white' : 'text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-100 hover:bg-neutral-100 dark:hover:bg-neutral-700' }}">
                            Xe đang chạy
                        </a>
                        <a href="{{ route('vehicles.index', ['filter' => 'waiting']) }}" 
                           class="px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ $filter === 'waiting' ? 'bg-yellow-500 text-white' : 'text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-100 hover:bg-neutral-100 dark:hover:bg-neutral-700' }}">
                            Xe đang chờ
                        </a>
                        <a href="{{ route('vehicles.index', ['filter' => 'expired']) }}" 
                           class="px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ $filter === 'expired' ? 'bg-orange-500 text-white' : 'text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-100 hover:bg-neutral-100 dark:hover:bg-neutral-700' }}">
                            Xe hết giờ
                        </a>
                        <a href="{{ route('vehicles.index', ['filter' => 'paused']) }}" 
                           class="px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ $filter === 'paused' ? 'bg-gray-500 text-white' : 'text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-100 hover:bg-neutral-100 dark:hover:bg-neutral-700' }}">
                            Xe tạm dừng
                        </a>
                        <a href="{{ route('vehicles.index', ['filter' => 'route']) }}" 
                           class="px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ $filter === 'route' ? 'bg-purple-500 text-white' : 'text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-100 hover:bg-neutral-100 dark:hover:bg-neutral-700' }}">
                            Xe cung đường
                        </a>
                        <a href="{{ route('vehicles.index', ['filter' => 'group']) }}" 
                           class="px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ $filter === 'group' ? 'bg-indigo-500 text-white' : 'text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-100 hover:bg-neutral-100 dark:hover:bg-neutral-700' }}">
                            Xe khách đoàn
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Vehicle List -->
            @if($displayMode === 'grid')
                <!-- Grid Display for most statuses -->
                <div id="vehicle-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @forelse($vehicles as $vehicle)
                        <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                            <!-- Vehicle Header -->
                            <div class="p-4 border-b border-neutral-200 dark:border-neutral-700">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                                        Xe số {{ $vehicle->name }}
                                    </h3>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $vehicle->status_color_class }}">
                                        {{ $vehicle->status_display_name }}
                                    </span>
                                </div>
                                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                    {{ $vehicle->color }} - {{ $vehicle->seats }} chỗ
                                </p>
                            </div>

                            <!-- Vehicle Details -->
                            <div class="p-4">
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-neutral-500 dark:text-neutral-400">Công suất:</span>
                                        <span class="text-neutral-900 dark:text-neutral-100">{{ $vehicle->power }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-neutral-500 dark:text-neutral-400">Bánh xe:</span>
                                        <span class="text-neutral-900 dark:text-neutral-100">{{ $vehicle->wheel_size }}</span>
                                    </div>
                                    @if($vehicle->driver_name)
                                        <div class="flex justify-between">
                                            <span class="text-neutral-500 dark:text-neutral-400">Tài xế:</span>
                                            <span class="text-neutral-900 dark:text-neutral-100">{{ $vehicle->driver_name }}</span>
                                        </div>
                                    @endif
                                    @if($vehicle->current_location)
                                        <div class="flex justify-between">
                                            <span class="text-neutral-500 dark:text-neutral-400">Vị trí:</span>
                                            <span class="text-neutral-900 dark:text-neutral-100">{{ $vehicle->current_location }}</span>
                                        </div>
                                    @endif
                                    @if($vehicle->route_number)
                                        <div class="flex justify-between">
                                            <span class="text-neutral-500 dark:text-neutral-400">Cung đường:</span>
                                            <span class="text-neutral-900 dark:text-neutral-100">{{ $vehicle->route_number }}</span>
                                        </div>
                                    @endif
                                </div>

                                @if($vehicle->notes)
                                    <div class="mt-3 p-2 bg-neutral-50 dark:bg-neutral-700 rounded">
                                        <p class="text-xs text-neutral-600 dark:text-neutral-400">{{ $vehicle->notes }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Vehicle Actions -->
                            <div class="px-4 pb-4">
                                <div class="flex space-x-2">
                                    <button onclick="openStatusModal({{ $vehicle->id }}, '{{ $vehicle->status }}', '{{ $vehicle->notes }}')" 
                                            class="flex-1 px-3 py-2 text-xs font-medium text-neutral-700 dark:text-neutral-300 bg-neutral-100 dark:bg-neutral-700 rounded-md hover:bg-neutral-200 dark:hover:bg-neutral-600 transition-colors duration-200">
                                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                    @if(auth()->user()->canManageVehicles())
                                        <a href="{{ route('vehicles.edit', $vehicle) }}" 
                                           class="flex-1 px-3 py-2 text-xs font-medium text-neutral-700 dark:text-neutral-300 bg-neutral-100 dark:bg-neutral-700 rounded-md hover:bg-neutral-200 dark:hover:bg-neutral-600 transition-colors duration-200">
                                            <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <button onclick="deleteVehicle({{ $vehicle->id }})" 
                                                class="flex-1 px-3 py-2 text-xs font-medium text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/20 rounded-md hover:bg-red-200 dark:hover:bg-red-900/40 transition-colors duration-200">
                                            <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full">
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-neutral-900 dark:text-neutral-100">Không có xe nào</h3>
                                <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                                    Bắt đầu bằng cách thêm xe mới vào hệ thống.
                                </p>
                                @if(auth()->user()->canManageVehicles())
                                    <div class="mt-6">
                                        <a href="{{ route('vehicles.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-500 hover:bg-brand-600 text-white font-semibold rounded-lg transition-colors duration-200">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Thêm xe mới
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforelse
                </div>
            @else
                <!-- List Display for route and group -->
                <div class="bg-white dark:bg-neutral-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                            <thead class="bg-neutral-50 dark:bg-neutral-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Xe số</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Màu sắc</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Chỗ ngồi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Công suất</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Bánh xe</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Tài xế</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Vị trí</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Trạng thái</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 dark:text-neutral-300 uppercase tracking-wider">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-neutral-800 divide-y divide-neutral-200 dark:divide-neutral-700">
                                @forelse($vehicles as $vehicle)
                                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                            {{ $vehicle->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                            {{ $vehicle->color }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                            {{ $vehicle->seats }} chỗ
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                            {{ $vehicle->power }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                            {{ $vehicle->wheel_size }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                            {{ $vehicle->driver_name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                                            {{ $vehicle->current_location ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $vehicle->status_color_class }}">
                                                {{ $vehicle->status_display_name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button onclick="openStatusModal({{ $vehicle->id }}, '{{ $vehicle->status }}', '{{ $vehicle->notes }}')" 
                                                        class="text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-100">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                </button>
                                                @if(auth()->user()->canManageVehicles())
                                                    <a href="{{ route('vehicles.edit', $vehicle) }}" class="text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-neutral-100">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    <button onclick="deleteVehicle({{ $vehicle->id }})" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-4 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                            Không có xe nào
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Status Update Modal -->
    <div id="status-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100 mb-4">
                        Cập nhật trạng thái xe
                    </h3>
                    
                    <form id="status-form">
                        @csrf
                        <input type="hidden" id="vehicle-id" name="vehicle_id">
                        
                        <div class="mb-4">
                            <label for="status-select" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Trạng thái mới
                            </label>
                            <select id="status-select" name="status" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-700 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                                <option value="active">Xe ngoài bãi</option>
                                <option value="inactive">Xe trong xưởng</option>
                                <option value="running">Xe đang chạy</option>
                                <option value="waiting">Xe đang chờ</option>
                                <option value="expired">Xe hết giờ</option>
                                <option value="paused">Xe tạm dừng</option>
                                <option value="route">Xe cung đường</option>
                                <option value="group">Xe khách đoàn</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="status-notes" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                Ghi chú
                            </label>
                            <textarea id="status-notes" name="notes" rows="3" class="w-full px-3 py-2 border border-neutral-300 dark:border-neutral-600 rounded-md bg-white dark:bg-neutral-700 text-neutral-900 dark:text-neutral-100 focus:ring-2 focus:ring-brand-500 focus:border-brand-500" placeholder="Nhập ghi chú về trạng thái xe..."></textarea>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button type="submit" class="flex-1 px-4 py-2 bg-brand-500 hover:bg-brand-600 text-white font-semibold rounded-md transition-colors duration-200">
                                Cập nhật
                            </button>
                            <button type="button" onclick="closeStatusModal()" class="flex-1 px-4 py-2 bg-neutral-300 dark:bg-neutral-600 hover:bg-neutral-400 dark:hover:bg-neutral-500 text-neutral-700 dark:text-neutral-300 font-semibold rounded-md transition-colors duration-200">
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
        function openStatusModal(vehicleId, currentStatus, currentNotes) {
            document.getElementById('vehicle-id').value = vehicleId;
            document.getElementById('status-select').value = currentStatus;
            document.getElementById('status-notes').value = currentNotes || '';
            document.getElementById('status-modal').classList.remove('hidden');
        }

        function closeStatusModal() {
            document.getElementById('status-modal').classList.add('hidden');
        }

        document.getElementById('status-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const vehicleId = document.getElementById('vehicle-id').value;
            const formData = new FormData(this);
            
            fetch(`/vehicles/${vehicleId}/status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    status: formData.get('status'),
                    notes: formData.get('notes')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeStatusModal();
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi cập nhật trạng thái xe');
            });
        });

        function deleteVehicle(vehicleId) {
            if (confirm('Bạn có chắc chắn muốn xóa xe này?')) {
                fetch(`/vehicles/${vehicleId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Có lỗi xảy ra: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi xóa xe');
                });
            }
        }
    </script>
    @endpush
</x-app-layout>
