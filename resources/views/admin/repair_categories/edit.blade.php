@extends('layouts.app')

@section('title', 'Chỉnh sửa hạng mục sửa chữa')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.repair-categories.index') }}" 
               class="text-neutral-600 hover:text-neutral-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-neutral-900">Chỉnh sửa hạng mục sửa chữa</h1>
                <p class="text-neutral-600 mt-2">Cập nhật thông tin hạng mục: {{ $repairCategory->name }}</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.repair-categories.update', $repairCategory) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tên hạng mục -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-neutral-700 mb-2">
                        Tên hạng mục <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $repairCategory->name) }}"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                           placeholder="Ví dụ: Động cơ, Hệ thống phanh..."
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mô tả -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-neutral-700 mb-2">
                        Mô tả
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="3"
                              class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                              placeholder="Mô tả chi tiết về hạng mục...">{{ old('description', $repairCategory->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Thứ tự sắp xếp -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-neutral-700 mb-2">
                        Thứ tự sắp xếp
                    </label>
                    <input type="number" 
                           id="sort_order" 
                           name="sort_order" 
                           value="{{ old('sort_order', $repairCategory->sort_order) }}"
                           min="0"
                           class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sort_order') border-red-500 @enderror">
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Trạng thái -->
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">
                        Trạng thái
                    </label>
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', $repairCategory->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-neutral-300 rounded">
                        <label for="is_active" class="ml-2 text-sm text-neutral-700">
                            Hoạt động
                        </label>
                    </div>
                </div>
            </div>

            <!-- Current Key -->
            <div class="mt-6 p-4 bg-neutral-50 rounded-lg">
                <label class="block text-sm font-medium text-neutral-700 mb-2">
                    Key hiện tại:
                </label>
                <code class="text-sm text-neutral-600">{{ $repairCategory->key }}</code>
                <p class="text-xs text-neutral-500 mt-1">Key sẽ được cập nhật tự động khi thay đổi tên hạng mục</p>
            </div>

            <!-- Buttons -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.repair-categories.index') }}" 
                   class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-neutral-500">
                    Hủy
                </a>
                <button type="submit" 
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Cập nhật hạng mục
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const keyPreview = document.querySelector('code');
    
    function updateKeyPreview() {
        const name = nameInput.value;
        if (name) {
            const key = name.toLowerCase()
                .replace(/[^a-z0-9\s]/g, '')
                .replace(/\s+/g, '_')
                .trim();
            keyPreview.textContent = key || '{{ $repairCategory->key }}';
        } else {
            keyPreview.textContent = '{{ $repairCategory->key }}';
        }
    }
    
    nameInput.addEventListener('input', updateKeyPreview);
    updateKeyPreview();
});
</script>
@endsection
