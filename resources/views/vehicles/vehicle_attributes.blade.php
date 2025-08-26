<!-- Vehicle Attributes Management -->
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-neutral-900 mb-4">
            Quản lý thuộc tính xe
        </h3>
        
        <!-- Color Management -->
        <div class="mb-8">
            <h4 class="text-md font-medium text-neutral-700 mb-3">Quản lý màu sắc</h4>
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
                        '#D7BDE2' => 'Tím nhạt',
                        '#FAD7A0' => 'Cam kem',
                        '#A9DFBF' => 'Xanh lá nhạt',
                        '#F9E79F' => 'Vàng nhạt',
                        '#D5A6BD' => 'Hồng nhạt',
                        '#A3E4D7' => 'Xanh ngọc nhạt',
                        '#D2B4DE' => 'Tím lavender nhạt'
                    ];
                @endphp
                @foreach($colorOptions as $hex => $name)
                    <div class="text-center">
                        <div class="w-12 h-12 rounded-lg border-2 border-neutral-300 mx-auto mb-1" style="background-color: {{ $hex }};"></div>
                        <div class="text-xs text-neutral-600">{{ $name }}</div>
                        <div class="text-xs text-neutral-400">{{ $hex }}</div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Seats Management -->
        <div class="mb-8">
            <h4 class="text-md font-medium text-neutral-700 mb-3">Quản lý số chỗ ngồi</h4>
            <div class="flex space-x-4">
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 bg-neutral-100 text-neutral-700 rounded">1 chỗ</span>
                    <span class="px-3 py-1 bg-neutral-100 text-neutral-700 rounded">2 chỗ</span>
                </div>
            </div>
        </div>
        
        <!-- Power Management -->
        <div class="mb-8">
            <h4 class="text-md font-medium text-neutral-700 mb-3">Quản lý công suất</h4>
            <div class="flex space-x-4">
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 bg-neutral-100 text-neutral-700 rounded">48V-1000W</span>
                    <span class="px-3 py-1 bg-neutral-100 text-neutral-700 rounded">60V-1200W</span>
                </div>
            </div>
        </div>
        
        <!-- Wheel Size Management -->
        <div class="mb-8">
            <h4 class="text-md font-medium text-neutral-700 mb-3">Quản lý kích cỡ bánh</h4>
            <div class="flex space-x-4">
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 bg-neutral-100 text-neutral-700 rounded">10 inch</span>
                    <span class="px-3 py-1 bg-neutral-100 text-neutral-700 rounded">12 inch</span>
                    <span class="px-3 py-1 bg-neutral-100 text-neutral-700 rounded">14 inch</span>
                </div>
            </div>
        </div>
        
        <!-- Status Management -->
        <div class="mb-8">
            <h4 class="text-md font-medium text-neutral-700 mb-3">Quản lý trạng thái</h4>
            <div class="grid grid-cols-2 gap-4">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                    <span class="text-sm text-neutral-600">Active (Sẵn sàng)</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                    <span class="text-sm text-neutral-600">Running (Đang chạy)</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                    <span class="text-sm text-neutral-600">Waiting (Đang chờ)</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                    <span class="text-sm text-neutral-600">Expired (Hết giờ)</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 bg-gray-500 rounded-full"></span>
                    <span class="text-sm text-neutral-600">Paused (Tạm dừng)</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 bg-purple-500 rounded-full"></span>
                    <span class="text-sm text-neutral-600">Inactive (Trong xưởng)</span>
                </div>
            </div>
        </div>
    </div>
</div>
