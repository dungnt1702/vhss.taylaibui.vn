{{-- This file is included by vehicles_management.blade.php, so no need to extend layout --}}
<div>

    <!-- Header for Vehicle Attributes -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-neutral-900">Danh sách thuộc tính xe</h1>
        <p class="text-neutral-600 mt-2">Quản lý các thuộc tính và cấu hình của xe</p>
    </div>

    <!-- Action Buttons -->
    <div class="mb-6 flex flex-wrap gap-3">
        <button onclick="addAttribute('color')" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Thêm màu sắc
        </button>
        <button onclick="addAttribute('seat')" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Thêm số ghế
        </button>
        <button onclick="addAttribute('power')" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Thêm công suất
        </button>
        <button onclick="addAttribute('wheel_size')" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Thêm kích thước bánh
        </button>
    </div>

    <!-- Attributes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Colors -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z" />
                </svg>
                Màu sắc
            </h3>
            <div class="space-y-2">
                @foreach($colors as $color)
                <div class="flex items-center justify-between p-2 bg-neutral-50 rounded">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full mr-2" style="background-color: {{ $color }};"></div>
                        <span class="text-sm text-neutral-700">{{ $color }}</span>
                    </div>
                    <button class="text-red-500 hover:text-red-700" onclick="deleteAttribute('color', '{{ $color }}')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
                @endforeach
            </div>
            <button class="btn btn-primary btn-sm w-full mt-4" onclick="addAttribute('color')">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Thêm màu sắc
            </button>
        </div>

        <!-- Seats -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Số ghế
            </h3>
            <div class="space-y-2">
                @foreach($seats as $seat)
                <div class="flex items-center justify-between p-2 bg-neutral-50 rounded">
                    <span class="text-sm text-neutral-700">{{ $seat }}</span>
                    <button class="text-red-500 hover:text-red-700" onclick="deleteAttribute('seat', '{{ $seat }}')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
                @endforeach
            </div>
            <button class="btn btn-primary btn-sm w-full mt-4" onclick="addAttribute('seat')">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Thêm số ghế
            </button>
        </div>

        <!-- Power -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Công suất
            </h3>
            <div class="space-y-2">
                @foreach($powerOptions as $power)
                <div class="flex items-center justify-between p-2 bg-neutral-50 rounded">
                    <span class="text-sm text-neutral-700">{{ $power }}</span>
                    <button class="text-red-500 hover:text-red-700" onclick="deleteAttribute('power', '{{ $power }}')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
                @endforeach
            </div>
            <button class="btn btn-primary btn-sm w-full mt-4" onclick="addAttribute('power')">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Thêm công suất
            </button>
        </div>

        <!-- Wheel Size -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
                Kích thước bánh
            </h3>
            <div class="space-y-2">
                @foreach($wheelSizes as $wheelSize)
                <div class="flex items-center justify-between p-2 bg-neutral-50 rounded">
                    <span class="text-sm text-neutral-700">{{ $wheelSize }}</span>
                    <button class="text-red-500 hover:text-red-700" onclick="deleteAttribute('wheel_size', '{{ $wheelSize }}')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
                @endforeach
            </div>
            <button class="btn btn-primary btn-sm w-full mt-4" onclick="addAttribute('wheel_size')">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Thêm kích thước
            </button>
        </div>
    </div>
</div>


{{-- Modals are included by vehicles_management.blade.php --}}

