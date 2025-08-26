
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
                            Số tuyến
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
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                    Tuyến {{ $vehicle->route_number }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="openRouteModal({{ $vehicle->id }}, {{ $vehicle->route_number }})" class="btn btn-info btn-xs">
                                        🔄 Đổi tuyến
                                    </button>
                                    <button onclick="returnToYard({{ $vehicle->id }})" class="btn btn-primary btn-xs">
                                        🏠 Về bãi
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-neutral-900">Không có xe nào</h3>
                                    <p class="mt-1 text-sm text-neutral-500">
                                        Không có xe nào chạy tuyến cố định.
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
