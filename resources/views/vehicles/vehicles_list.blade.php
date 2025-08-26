<!-- Vehicles List Table Display -->
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-neutral-200">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Xe số</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Màu sắc</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Chỗ ngồi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Công suất</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Bánh xe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Vị trí</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
                @forelse($vehicles as $vehicle)
                    <tr class="hover:bg-neutral-50" data-vehicle-id="{{ $vehicle->id }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900" data-vehicle-name="{{ $vehicle->name }}">
                            {{ $vehicle->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500" data-vehicle-color="{{ $vehicle->color }}">
                            <div class="w-6 h-6 rounded border border-neutral-300 dark:border-neutral-600" style="background-color: {{ $vehicle->color }};" title="{{ $vehicle->color }}"></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500" data-vehicle-seats="{{ $vehicle->seats }}">
                            {{ $vehicle->seats }} chỗ
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500" data-vehicle-power="{{ $vehicle->power }}">
                            {{ $vehicle->power }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500" data-vehicle-wheel-size="{{ $vehicle->wheel_size }}">
                            {{ $vehicle->wheel_size }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
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
                                        class="text-neutral-600 hover:text-neutral-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </button>
                                @if(auth()->user()->canManageVehicles())
                                    <button onclick="openVehicleModal({{ $vehicle->id }})" class="text-neutral-600 hover:text-neutral-900">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button onclick="deleteVehicle({{ $vehicle->id }})" class="text-red-600 hover:text-red-900">
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
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-neutral-500">
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
