@extends('layouts.app')

@section('title', 'Sửa vai trò')

@section('content')
@php
    $header = '<h2 class="font-semibold text-xl text-gray-800 leading-tight">Sửa vai trò</h2>';
@endphp

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <form method="POST" action="{{ route('roles.update', $role) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Tên vai trò')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" 
                                         :value="old('name', $role->name)" required autofocus autocomplete="name" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Display Name -->
                        <div>
                            <x-input-label for="display_name" :value="__('Tên hiển thị')" />
                            <x-text-input id="display_name" name="display_name" type="text" class="mt-1 block w-full" 
                                         :value="old('display_name', $role->display_name)" required autocomplete="display_name" />
                            <x-input-error class="mt-2" :messages="$errors->get('display_name')" />
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <x-input-label for="description" :value="__('Mô tả')" />
                            <textarea id="description" name="description" rows="3" 
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    placeholder="Nhập mô tả vai trò...">{{ old('description', $role->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <!-- Is Active -->
                        <div class="md:col-span-2">
                            <div class="flex items-center">
                                <input id="is_active" name="is_active" type="checkbox" value="1" 
                                       {{ old('is_active', $role->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Vai trò đang hoạt động
                                </label>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('is_active')" />
                        </div>
                    </div>

                    <!-- Permissions Section -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quyền hạn</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($permissions as $module => $modulePermissions)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900 mb-3 capitalize">{{ $module }}</h4>
                                    <div class="space-y-2">
                                        @foreach($modulePermissions as $permission)
                                            <div class="flex items-center">
                                                <input id="permission_{{ $permission->id }}" 
                                                       name="permissions[]" 
                                                       type="checkbox" 
                                                       value="{{ $permission->id }}"
                                                       {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}
                                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                <label for="permission_{{ $permission->id }}" class="ml-2 text-sm text-gray-700">
                                                    {{ $permission->display_name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('permissions')" />
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end mt-6 space-x-4">
                        <a href="{{ route('roles.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Hủy
                        </a>
                        
                        <x-primary-button>
                            {{ __('Cập nhật vai trò') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
