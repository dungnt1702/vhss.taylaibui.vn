@extends('layouts.app')

@section('title', 'Xe đang sửa chữa')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page identifier for VehicleClasses.js -->
    <div id="vehicle-page" data-page-type="repairing" style="display: none;"></div>


    <!-- Header for Repair Issues History -->
    <div class="mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900">Lịch sử sửa chữa</h1>
                @if(request('vehicle_id') && $repairIssues->isNotEmpty())
                    @php
                        $vehicle = $repairIssues->first()->vehicle;
                    @endphp
                    <p class="text-neutral-600 mt-2">
                        Lịch sử sửa chữa của xe 
                        <span class="inline-flex items-center px-2 py-1 rounded text-sm font-medium text-white" style="background-color: {{ $vehicle->color }};">
                            {{ $vehicle->name }}
                        </span>
                        <a href="{{ route('vehicles.repairing') }}" class="ml-2 text-blue-600 hover:text-blue-800 underline">
                            (Xem tất cả)
                        </a>
                    </p>
                @else
                    <p class="text-neutral-600 mt-2">Báo cáo và lịch sử các vấn đề sửa chữa</p>
                @endif
            </div>
            
            <!-- Add Repair Button -->
            <button id="add-repair-btn" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>Thêm sửa chữa</span>
            </button>
        </div>
    </div>

    <!-- Desktop Table Display -->
    <div class="hidden md:block bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Xe</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hạng mục</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mô tả</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Người báo cáo</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Người thực hiện</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kết quả</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($repairIssues as $issue)
                    <tr class="hover:bg-gray-50" data-issue-id="{{ $issue->id }}">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="vehicle-number-with-color flex items-center">
                                <div class="w-8 h-8 rounded border border-gray-300 flex items-center justify-center text-white font-semibold text-sm" style="background-color: {{ $issue->vehicle->color }};" title="{{ $issue->vehicle->color }}">
                                    {{ $issue->vehicle->name }}
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ \App\Models\VehicleTechnicalIssue::getRepairCategories()[$issue->category] ?? $issue->category }}
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-sm text-gray-900">
                                @if($issue->description)
                                    @if(strlen($issue->description) > 50)
                                        {{ Str::limit($issue->description, 50) }}
                                        <button onclick="openDescriptionModal('{{ addslashes($issue->description) }}', '{{ addslashes($issue->notes ?? '') }}')" class="text-blue-600 hover:text-blue-900 ml-1" title="Xem chi tiết">
                                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    @else
                                        {{ $issue->description }}
                                    @endif
                                @else
                                    <span class="text-gray-400">Không có mô tả</span>
                                @endif
                            </div>
                            @if($issue->notes)
                                <div class="text-xs text-gray-500 mt-1">
                                    <span class="font-medium">Ghi chú:</span> 
                                    @if(strlen($issue->notes) > 30)
                                        {{ Str::limit($issue->notes, 30) }}
                                        <button onclick="openDescriptionModal('{{ addslashes($issue->description ?? '') }}', '{{ addslashes($issue->notes) }}')" class="text-blue-600 hover:text-blue-900 ml-1" title="Xem chi tiết">
                                            <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    @else
                                        {{ $issue->notes }}
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $issue->getStatusColorClass() }}">
                                {{ \App\Models\VehicleTechnicalIssue::getStatusLabels()[$issue->status] ?? $issue->status }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $issue->reporter->name ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $issue->assignee->name ?? 'Chưa giao' }}
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-sm text-gray-900">
                                @if($issue->result)
                                    {{ Str::limit($issue->result, 50) }}
                                @else
                                    <span class="text-gray-400">Chưa có kết quả</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $issue->reported_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                @if($issue->reported_by == auth()->id())
                                    <button onclick="openEditModal({{ $issue->id }}, '{{ addslashes($issue->description) }}', '{{ addslashes($issue->notes ?? '') }}', '{{ $issue->category }}')" class="text-yellow-600 hover:text-yellow-900" title="Chỉnh sửa">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                @endif
                                <button onclick="openProcessModal({{ $issue->id }})" class="text-blue-600 hover:text-blue-900" title="Xử lý">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card Display -->
    <div class="md:hidden space-y-4">
        @foreach($repairIssues as $issue)
        <div class="bg-white rounded-lg shadow-md p-4" data-issue-id="{{ $issue->id }}">
            <!-- Header with vehicle and status -->
            <div class="flex items-center justify-between mb-3">
                <div class="vehicle-number-with-color flex items-center">
                    <div class="w-8 h-8 rounded border border-gray-300 flex items-center justify-center text-white font-semibold text-sm" style="background-color: {{ $issue->vehicle->color }};" title="{{ $issue->vehicle->color }}">
                        {{ $issue->vehicle->name }}
                    </div>
                </div>
                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $issue->getStatusColorClass() }}">
                    {{ \App\Models\VehicleTechnicalIssue::getStatusLabels()[$issue->status] ?? $issue->status }}
                </span>
            </div>
            
            <!-- Content -->
            <div class="space-y-2">
                <div>
                    <span class="text-xs font-medium text-gray-500">Hạng mục:</span>
                    <div class="text-sm text-gray-900">
                        {{ \App\Models\VehicleTechnicalIssue::getRepairCategories()[$issue->category] ?? $issue->category }}
                    </div>
                </div>
                
                <div>
                    <span class="text-xs font-medium text-gray-500">Mô tả:</span>
                    <div class="text-sm text-gray-900">
                        @if($issue->description)
                            @if(strlen($issue->description) > 100)
                                {{ Str::limit($issue->description, 100) }}
                                <button onclick="openDescriptionModal('{{ addslashes($issue->description) }}', '{{ addslashes($issue->notes ?? '') }}')" class="text-blue-600 hover:text-blue-900 ml-1" title="Xem chi tiết">
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            @else
                                {{ $issue->description }}
                            @endif
                        @else
                            <span class="text-gray-400">Không có mô tả</span>
                        @endif
                    </div>
                    @if($issue->notes)
                        <div class="text-xs text-gray-500 mt-1">
                            <span class="font-medium">Ghi chú:</span> 
                            @if(strlen($issue->notes) > 50)
                                {{ Str::limit($issue->notes, 50) }}
                                <button onclick="openDescriptionModal('{{ addslashes($issue->description ?? '') }}', '{{ addslashes($issue->notes) }}')" class="text-blue-600 hover:text-blue-900 ml-1" title="Xem chi tiết">
                                    <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            @else
                                {{ $issue->notes }}
                            @endif
                        </div>
                    @endif
                </div>
                
                <div>
                    <span class="text-xs font-medium text-gray-500">Người báo cáo:</span>
                    <div class="text-sm text-gray-900">{{ $issue->reporter->name ?? 'N/A' }}</div>
                </div>
                
                <div>
                    <span class="text-xs font-medium text-gray-500">Người thực hiện:</span>
                    <div class="text-sm text-gray-900">{{ $issue->assignee->name ?? 'Chưa giao' }}</div>
                </div>
                
                <div>
                    <span class="text-xs font-medium text-gray-500">Kết quả:</span>
                    <div class="text-sm text-gray-900">
                        @if($issue->result)
                            {{ Str::limit($issue->result, 100) }}
                        @else
                            <span class="text-gray-400">Chưa có kết quả</span>
                        @endif
                    </div>
                </div>
                
                <div class="flex justify-between text-xs text-gray-500">
                    <span>{{ $issue->reported_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex justify-end space-x-2 mt-3 pt-3 border-t border-gray-200">
                @if($issue->reported_by == auth()->id())
                    <button onclick="openEditModal({{ $issue->id }}, '{{ addslashes($issue->description) }}', '{{ addslashes($issue->notes ?? '') }}', '{{ $issue->category }}')" class="text-yellow-600 hover:text-yellow-900 p-1" title="Chỉnh sửa">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                @endif
                <button onclick="openProcessModal({{ $issue->id }})" class="text-blue-600 hover:text-blue-900 p-1" title="Xử lý">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Empty State -->
    @if($repairIssues->isEmpty())
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-neutral-900">Chưa có báo cáo sửa chữa</h3>
        <p class="mt-1 text-sm text-neutral-500">Chưa có vấn đề sửa chữa nào được báo cáo.</p>
    </div>
    @endif
</div>

<!-- Modals -->
@include('vehicles.partials.vehicle_modals')

@endsection


<!-- Add Repair Modal -->
<div id="add-repair-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-neutral-900 mb-4">Thêm báo cáo sửa chữa</h3>
            <form id="add-repair-form">
                <input type="hidden" id="add-repair-issue-type" name="issue_type" value="repair">
                
                @if(!request('vehicle_id'))
                <!-- Vehicle selection (only show when not filtered by specific vehicle) -->
                <div class="mb-4">
                    <label for="add-repair-vehicle-id" class="block text-sm font-medium text-neutral-700 mb-2">
                        Chọn xe <span class="text-red-500">*</span>
                    </label>
                    <select id="add-repair-vehicle-id" name="vehicle_id" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">-- Chọn xe --</option>
                        @foreach(\App\Models\Vehicle::inactive()->get() as $vehicle)
                            <option value="{{ $vehicle->id }}">{{ $vehicle->name }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                <input type="hidden" id="add-repair-vehicle-id" name="vehicle_id" value="{{ request('vehicle_id') }}">
                @endif
                
                <div class="mb-4">
                    <label for="add-repair-category" class="block text-sm font-medium text-neutral-700 mb-2">
                        Hạng mục <span class="text-red-500">*</span>
                    </label>
                    <select id="add-repair-category" name="category" class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">-- Chọn hạng mục --</option>
                        @foreach(\App\Models\VehicleTechnicalIssue::getRepairCategories() as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="add-repair-description" class="block text-sm font-medium text-neutral-700 mb-2">
                        Mô tả vấn đề <span class="text-red-500">*</span>
                    </label>
                    <textarea id="add-repair-description" name="description" rows="3" 
                              class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                              placeholder="Mô tả chi tiết vấn đề cần sửa chữa..." required></textarea>
                </div>
                
                <div class="mb-6">
                    <label for="add-repair-notes" class="block text-sm font-medium text-neutral-700 mb-2">
                        Ghi chú thêm
                    </label>
                    <textarea id="add-repair-notes" name="notes" rows="2" 
                              class="w-full px-3 py-2 border border-neutral-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                              placeholder="Ghi chú bổ sung (tùy chọn)"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAddRepairModal()" 
                            class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-neutral-500">
                        Hủy
                    </button>
                    <button type="submit" id="add-repair-submit-btn"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Thêm báo cáo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


