<!-- List Display for Xe ngoài bãi (group filter) -->
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Xe số
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Màu sắc
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Số chỗ
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Công suất
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Bánh xe
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Trạng thái
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Thao tác
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    @forelse($vehicles as $vehicle)
                        <tr class="hover:bg-neutral-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                                {{ $vehicle->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 rounded border border-neutral-300 mr-2" style="background-color: {{ $vehicle->color }};" title="{{ $vehicle->color }}"></div>
                                    <span class="text-sm text-neutral-900">{{ $vehicle->color }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900">
                                {{ $vehicle->seats }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900">
                                {{ $vehicle->power }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900">
                                {{ $vehicle->wheel_size }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($vehicle->status === 'active') bg-green-100 text-green-800
                                    @elseif($vehicle->status === 'running') bg-blue-100 text-blue-800
                                    @elseif($vehicle->status === 'paused') bg-yellow-100 text-yellow-800
                                    @elseif($vehicle->status === 'expired') bg-red-100 text-red-800
                                    @elseif($vehicle->status === 'route') bg-purple-100 text-purple-800
                                    @else bg-neutral-100 text-neutral-800
                                    @endif">
                                    {{ $vehicle->status_display_name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    @if($vehicle->status === 'active')
                                        <button onclick="startTimer({{ $vehicle->id }}, 30)" class="btn btn-success btn-xs">
                                            🚗 30p
                                        </button>
                                        <button onclick="startTimer({{ $vehicle->id }}, 45)" class="btn btn-primary btn-xs">
                                            🚙 45p
                                        </button>
                                        <button onclick="vehicleForms.openWorkshopModal({{ $vehicle->id }})" class="btn btn-secondary btn-xs">
                                            🔧 Về xưởng
                                        </button>
                                    @elseif($vehicle->status === 'running')
                                        <button onclick="addTime({{ $vehicle->id }}, 10)" class="btn btn-warning btn-xs">
                                            ⏰ +10p
                                        </button>
                                        <button onclick="pauseVehicle({{ $vehicle->id }})" class="btn btn-info btn-xs">
                                            ⏸️ Tạm dừng
                                        </button>
                                        <button onclick="returnToYard({{ $vehicle->id }})" class="btn btn-primary btn-xs">
                                            🏠 Về bãi
                                        </button>
                                    @elseif($vehicle->status === 'expired')
                                        <button onclick="addTime({{ $vehicle->id }}, 10)" class="btn btn-warning btn-xs">
                                            ⏰ +10p
                                        </button>
                                        <button onclick="returnToYard({{ $vehicle->id }})" class="btn btn-primary btn-xs">
                                            🏠 Về bãi
                                        </button>
                                    @elseif($vehicle->status === 'paused')
                                        <button onclick="resumeVehicle({{ $vehicle->id }})" class="btn btn-success btn-xs">
                                            ▶️ Tiếp tục
                                        </button>
                                        <button onclick="returnToYard({{ $vehicle->id }})" class="btn btn-primary btn-xs">
                                            🏠 Về bãi
                                        </button>
                                    @else
                                        <span class="text-neutral-500">-</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-neutral-900">Không có xe nào</h3>
                                    <p class="mt-1 text-sm text-neutral-500">
                                        Không có xe nào ngoài bãi.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
