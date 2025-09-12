{{-- This file is included by vehicles_management.blade.php, so no need to extend layout --}}
<div>
    <!-- Page identifier for VehicleClasses.js -->
    <div id="vehicle-page" data-page-type="workshop" style="display: none;"></div>

    <!-- Header for Workshop Vehicles -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-neutral-900">Xe trong xưởng</h1>
        <p class="text-neutral-600 mt-2">Quản lý xe đang ở trong xưởng</p>
    </div>

    <!-- Vehicle Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="vehicle-table w-full">
                <thead class="bg-neutral-50">
                    <tr class="hidden md:table-row">
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Xe số</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Sửa chữa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Công suất</th>
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
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded border border-neutral-300 flex items-center justify-center text-xs font-bold text-white" style="background-color: {{ $vehicle->color }};" title="{{ $vehicle->color }}">
                                        {{ $vehicle->name }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                @if(($vehicle->repair_count ?? 0) > 0)
                                    <a href="{{ route('vehicles.repairing.vehicle', ['vehicle_id' => $vehicle->id]) }}" 
                                       class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 hover:bg-red-200 transition-colors duration-200 cursor-pointer"
                                       title="Xem lịch sử sửa chữa của xe {{ $vehicle->name }}">
                                        {{ $vehicle->repair_count ?? 0 }}
                                    </a>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $vehicle->repair_count ?? 0 }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ $vehicle->power }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ $vehicle->notes ?? 'Không có ghi chú' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                <div class="flex items-center space-x-2">
                                    <!-- Nút Về bãi -->
                                    <button onclick="vehicleOperations.openReturnToYardModal({{ $vehicle->id }})" 
                                            class="text-green-600 hover:text-green-900 transition-colors duration-200 p-1 rounded hover:bg-green-50"
                                            title="Về bãi">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                        </svg>
                                    </button>
                                    
                                    <!-- Nút Chỉnh sửa -->
                                    <button onclick="vehicleOperations.editVehicle({{ $vehicle->id }})" 
                                            class="text-blue-600 hover:text-blue-900 transition-colors duration-200 p-1 rounded hover:bg-blue-50"
                                            title="Chỉnh sửa">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    
                                    <!-- Nút Cập nhật kỹ thuật -->
                                    <button onclick="vehicleOperations.openTechnicalUpdateModal({{ $vehicle->id }})" 
                                            class="text-orange-600 hover:text-orange-900 transition-colors duration-200 p-1 rounded hover:bg-orange-50"
                                            title="Cập nhật kỹ thuật">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
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
                            <!-- Dòng 1: Xe số, Sửa chữa, Công suất, Thao tác -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded border border-neutral-300 flex items-center justify-center text-xs font-bold text-white" style="background-color: {{ $vehicle->color }};" title="{{ $vehicle->color }}">
                                        {{ $vehicle->name }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                @if(($vehicle->repair_count ?? 0) > 0)
                                    <a href="{{ route('vehicles.repairing.vehicle', ['vehicle_id' => $vehicle->id]) }}" 
                                       class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 hover:bg-red-200 transition-colors duration-200 cursor-pointer"
                                       title="Xem lịch sử sửa chữa của xe {{ $vehicle->name }}">
                                        {{ $vehicle->repair_count ?? 0 }}
                                    </a>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $vehicle->repair_count ?? 0 }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ $vehicle->power }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                <div class="flex items-center space-x-2">
                                    <!-- Nút Về bãi -->
                                    <button onclick="vehicleOperations.openReturnToYardModal({{ $vehicle->id }})" 
                                            class="text-green-600 hover:text-green-900 transition-colors duration-200 p-1 rounded hover:bg-green-50"
                                            title="Về bãi">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                        </svg>
                                    </button>
                                    
                                    <!-- Nút Chỉnh sửa -->
                                    <button onclick="vehicleOperations.editVehicle({{ $vehicle->id }})" 
                                            class="text-blue-600 hover:text-blue-900 transition-colors duration-200 p-1 rounded hover:bg-blue-50"
                                            title="Chỉnh sửa">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    
                                    <!-- Nút Cập nhật kỹ thuật -->
                                    <button onclick="vehicleOperations.openTechnicalUpdateModal({{ $vehicle->id }})" 
                                            class="text-orange-600 hover:text-orange-900 transition-colors duration-200 p-1 rounded hover:bg-orange-50"
                                            title="Cập nhật kỹ thuật">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Dòng 2: Ghi chú -->
                        <tr class="md:hidden {{ $loop->even ? 'bg-neutral-100' : 'bg-white' }}">
                            <td colspan="4" class="px-6 py-3">
                                <div class="flex items-start space-x-2">
                                    <span class="text-xs font-medium text-neutral-500 uppercase tracking-wider flex-shrink-0">Ghi chú:</span>
                                    <span class="text-sm text-neutral-700">{{ $vehicle->notes ?? 'Không có ghi chú' }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-neutral-500">
                                Không có xe nào trong xưởng
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
