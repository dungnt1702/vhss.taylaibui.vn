@extends('layouts.app')

@section('title', 'Quản lý quyền hạn - ' . $role->display_name)

@php
    $header = '<h2 class="font-semibold text-xl text-gray-800 leading-tight">Quản lý quyền hạn - ' . $role->display_name . '</h2>';
@endphp

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Role Info -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900">{{ $role->display_name }}</h3>
                    <p class="text-sm text-gray-600">{{ $role->description ?? 'Không có mô tả' }}</p>
                    <p class="text-sm text-gray-500">Mã: {{ $role->name }}</p>
                </div>

                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('roles.permissions.update', $role) }}">
                    @csrf
                    
                    <!-- Permissions by Module -->
                    @foreach($permissions as $module => $modulePermissions)
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-medium text-gray-900 capitalize">
                                    {{ ucfirst($module) }}
                                </h4>
                                <div class="flex items-center space-x-4">
                                    <button type="button" 
                                            class="text-sm text-blue-600 hover:text-blue-800 select-all-module"
                                            data-module="{{ $module }}">
                                        Chọn tất cả
                                    </button>
                                    <button type="button" 
                                            class="text-sm text-red-600 hover:text-red-800 deselect-all-module"
                                            data-module="{{ $module }}">
                                        Bỏ chọn tất cả
                                    </button>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($modulePermissions as $permission)
                                    <div class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                                        <input type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ $permission->id }}"
                                               id="permission_{{ $permission->id }}"
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded module-{{ $module }}"
                                               {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                        <div class="ml-3 flex-1">
                                            <label for="permission_{{ $permission->id }}" 
                                                   class="text-sm font-medium text-gray-900 cursor-pointer">
                                                {{ $permission->display_name }}
                                            </label>
                                            @if($permission->description)
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ $permission->description }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('roles.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Quay lại
                        </a>
                        
                        <div class="flex flex-wrap gap-3">
                            <button type="button" 
                                    class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    onclick="selectAll()">
                                Chọn tất cả
                            </button>
                            <button type="button" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    onclick="deselectAll()">
                                Bỏ chọn tất cả
                            </button>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cập nhật quyền hạn
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function selectAll() {
    document.querySelectorAll('input[type="checkbox"][name="permissions[]"]').forEach(checkbox => {
        checkbox.checked = true;
    });
}

function deselectAll() {
    document.querySelectorAll('input[type="checkbox"][name="permissions[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
}

// Module-specific select/deselect
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.select-all-module').forEach(button => {
        button.addEventListener('click', function() {
            const module = this.dataset.module;
            document.querySelectorAll(`.module-${module}`).forEach(checkbox => {
                checkbox.checked = true;
            });
        });
    });

    document.querySelectorAll('.deselect-all-module').forEach(button => {
        button.addEventListener('click', function() {
            const module = this.dataset.module;
            document.querySelectorAll(`.module-${module}`).forEach(checkbox => {
                checkbox.checked = false;
            });
        });
    });
});
</script>
@endsection
