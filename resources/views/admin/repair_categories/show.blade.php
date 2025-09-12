@extends('layouts.app')

@section('title', 'Chi tiết hạng mục sửa chữa')

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
                <h1 class="text-2xl font-bold text-neutral-900">Chi tiết hạng mục sửa chữa</h1>
                <p class="text-neutral-600 mt-2">{{ $repairCategory->name }}</p>
            </div>
        </div>
    </div>

    <!-- Category Details -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Tên hạng mục -->
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">
                    Tên hạng mục
                </label>
                <p class="text-lg font-semibold text-neutral-900">{{ $repairCategory->name }}</p>
            </div>

            <!-- Key -->
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">
                    Key
                </label>
                <code class="bg-neutral-100 px-3 py-1 rounded text-sm">{{ $repairCategory->key }}</code>
            </div>

            <!-- Mô tả -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-neutral-700 mb-2">
                    Mô tả
                </label>
                <p class="text-neutral-900">{{ $repairCategory->description ?: 'Không có mô tả' }}</p>
            </div>

            <!-- Thứ tự sắp xếp -->
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">
                    Thứ tự sắp xếp
                </label>
                <p class="text-neutral-900">{{ $repairCategory->sort_order }}</p>
            </div>

            <!-- Trạng thái -->
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">
                    Trạng thái
                </label>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $repairCategory->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $repairCategory->is_active ? 'Hoạt động' : 'Tạm dừng' }}
                </span>
            </div>

            <!-- Ngày tạo -->
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">
                    Ngày tạo
                </label>
                <p class="text-neutral-900">{{ $repairCategory->created_at->format('d/m/Y H:i') }}</p>
            </div>

            <!-- Ngày cập nhật -->
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">
                    Ngày cập nhật
                </label>
                <p class="text-neutral-900">{{ $repairCategory->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Usage Statistics -->
        <div class="mt-8 pt-6 border-t border-neutral-200">
            <h3 class="text-lg font-medium text-neutral-900 mb-4">Thống kê sử dụng</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">
                        {{ \App\Models\VehicleTechnicalIssue::where('category', $repairCategory->key)->count() }}
                    </div>
                    <div class="text-sm text-blue-800">Tổng số vấn đề</div>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">
                        {{ \App\Models\VehicleTechnicalIssue::where('category', $repairCategory->key)->where('status', 'completed')->count() }}
                    </div>
                    <div class="text-sm text-green-800">Đã hoàn thành</div>
                </div>
                <div class="bg-orange-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-orange-600">
                        {{ \App\Models\VehicleTechnicalIssue::where('category', $repairCategory->key)->whereIn('status', ['pending', 'in_progress'])->count() }}
                    </div>
                    <div class="text-sm text-orange-800">Đang xử lý</div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-end space-x-3">
            <a href="{{ route('admin.repair-categories.index') }}" 
               class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-neutral-500">
                Quay lại
            </a>
            <a href="{{ route('admin.repair-categories.edit', $repairCategory) }}" 
               class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Chỉnh sửa
            </a>
        </div>
    </div>
</div>
@endsection
