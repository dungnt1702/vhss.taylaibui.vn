@extends('layouts.app')

@section('title', 'Quản lý vai trò')

@php
    $header = '<h2 class="font-semibold text-xl text-gray-800 leading-tight">Quản lý vai trò</h2>';
@endphp

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header Actions -->
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Danh sách vai trò</h3>
                    @if(auth()->user()->hasPermission('roles.create'))
                        <a href="{{ route('roles.create') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Tạo vai trò mới
                        </a>
                    @endif
                </div>

                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tên vai trò
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mô tả
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Số quyền hạn
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Số người dùng
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Trạng thái
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Thao tác
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($roles as $role)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $role->display_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $role->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ $role->description ?? 'Không có mô tả' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $role->permissions ? $role->permissions->count() : 0 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $role->users ? $role->users->count() : 0 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $role->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $role->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            @if(auth()->user()->hasPermission('roles.view'))
                                                <a href="{{ route('roles.show', $role) }}" 
                                                   class="text-blue-600 hover:text-blue-900">Xem</a>
                                            @endif
                                            
                                            @if(auth()->user()->hasPermission('roles.edit'))
                                                <a href="{{ route('roles.edit', $role) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900">Sửa</a>
                                            @endif
                                            
                                            @if(auth()->user()->hasPermission('roles.manage'))
                                                <a href="{{ route('roles.permissions', $role) }}" 
                                                   class="text-green-600 hover:text-green-900">Quyền hạn</a>
                                            @endif
                                            
                                            @if(auth()->user()->hasPermission('roles.delete') && (!$role->users || $role->users->count() == 0))
                                                <form method="POST" action="{{ route('roles.destroy', $role) }}" 
                                                      class="inline" 
                                                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa vai trò này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        Xóa
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Không có vai trò nào.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-4">
                    @forelse($roles as $role)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                            <!-- Role Header -->
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900">{{ $role->display_name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $role->name }}</p>
                                </div>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $role->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $role->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                </span>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <p class="text-sm text-gray-600">{{ $role->description ?? 'Không có mô tả' }}</p>
                            </div>

                            <!-- Stats -->
                            <div class="flex space-x-4 mb-4">
                                <div class="text-center">
                                    <div class="text-lg font-semibold text-blue-600">{{ $role->permissions ? $role->permissions->count() : 0 }}</div>
                                    <div class="text-xs text-gray-500">Quyền hạn</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-semibold text-green-600">{{ $role->users ? $role->users->count() : 0 }}</div>
                                    <div class="text-xs text-gray-500">Người dùng</div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-wrap gap-2">
                                @if(auth()->user()->hasPermission('roles.view'))
                                    <a href="{{ route('roles.show', $role) }}" 
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100">
                                        Xem
                                    </a>
                                @endif
                                @if(auth()->user()->hasPermission('roles.edit'))
                                    <a href="{{ route('roles.edit', $role) }}" 
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-md hover:bg-indigo-100">
                                        Sửa
                                    </a>
                                @endif
                                @if(auth()->user()->hasPermission('roles.manage'))
                                    <a href="{{ route('roles.permissions', $role) }}" 
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-600 bg-green-50 rounded-md hover:bg-green-100">
                                        Quyền hạn
                                    </a>
                                @endif
                                @if(auth()->user()->hasPermission('roles.delete') && (!$role->users || $role->users->count() == 0))
                                    <form method="POST" action="{{ route('roles.destroy', $role) }}" 
                                          class="inline" 
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa vai trò này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100">
                                            Xóa
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Không có vai trò nào</h3>
                            <p class="mt-1 text-sm text-gray-500">Bắt đầu bằng cách tạo vai trò mới.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
