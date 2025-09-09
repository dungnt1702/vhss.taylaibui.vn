@extends('layouts.app')

@section('title', 'Xe đang bảo trì')

@section('content')
<div class="container mx-auto px-4 py-6">

    <!-- Header for Maintenance Issues History -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-neutral-900">Lịch sử bảo trì</h1>
        <p class="text-neutral-600 mt-2">Báo cáo và lịch sử các vấn đề bảo trì</p>
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
                    @foreach($maintenanceIssues as $issue)
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
                                {{ \App\Models\VehicleTechnicalIssue::getMaintenanceCategories()[$issue->category] ?? $issue->category }}
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
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ \App\Models\VehicleTechnicalIssue::getStatusLabels()[$issue->status] ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
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
        @foreach($maintenanceIssues as $issue)
        <div class="bg-white rounded-lg shadow-md p-4" data-issue-id="{{ $issue->id }}">
            <!-- Header with vehicle and status -->
            <div class="flex items-center justify-between mb-3">
                <div class="vehicle-number-with-color flex items-center">
                    <div class="w-8 h-8 rounded border border-gray-300 flex items-center justify-center text-white font-semibold text-sm" style="background-color: {{ $issue->vehicle->color }};" title="{{ $issue->vehicle->color }}">
                        {{ $issue->vehicle->name }}
                    </div>
                </div>
                <span class="px-2 py-1 text-xs font-medium rounded-full {{ \App\Models\VehicleTechnicalIssue::getStatusLabels()[$issue->status] ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ \App\Models\VehicleTechnicalIssue::getStatusLabels()[$issue->status] ?? $issue->status }}
                </span>
            </div>
            
            <!-- Content -->
            <div class="space-y-2">
                <div>
                    <span class="text-xs font-medium text-gray-500">Hạng mục:</span>
                    <div class="text-sm text-gray-900">
                        {{ \App\Models\VehicleTechnicalIssue::getMaintenanceCategories()[$issue->category] ?? $issue->category }}
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
    @if($maintenanceIssues->isEmpty())
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-neutral-900">Chưa có báo cáo bảo trì</h3>
        <p class="mt-1 text-sm text-neutral-500">Chưa có vấn đề bảo trì nào được báo cáo.</p>
    </div>
    @endif
</div>

<!-- Modals -->
@include('vehicles.partials.vehicle_modals')

@endsection

<script>
    // Global function to open description modal
    function openDescriptionModal(description, notes) {
        const modal = document.getElementById('description-detail-modal');
        const descriptionContent = document.getElementById('description-content');
        const notesContent = document.getElementById('notes-content');
        const notesSection = document.getElementById('notes-section');
        
        if (modal && descriptionContent) {
            // Set description content
            descriptionContent.textContent = description || 'Không có mô tả';
            
            // Set notes content if available
            if (notes && notes.trim()) {
                notesContent.textContent = notes;
                notesSection.classList.remove('hidden');
            } else {
                notesSection.classList.add('hidden');
            }
            
            modal.classList.remove('hidden');
        }
    }

    // Global function to close description modal
    function closeDescriptionModal() {
        const modal = document.getElementById('description-detail-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // Global function to open edit modal
    function openEditModal(issueId, description, notes, category) {
        const modal = document.getElementById('edit-issue-modal');
        const issueIdInput = document.getElementById('edit-issue-id');
        const descriptionInput = document.getElementById('edit-description');
        const notesInput = document.getElementById('edit-notes');
        const categorySelect = document.getElementById('edit-category');
        
        if (modal && issueIdInput) {
            issueIdInput.value = issueId;
            descriptionInput.value = description;
            notesInput.value = notes;
            categorySelect.value = category;
            modal.classList.remove('hidden');
        }
    }

    // Global function to close edit modal
    function closeEditModal() {
        const modal = document.getElementById('edit-issue-modal');
        if (modal) {
            modal.classList.add('hidden');
            // Reset form
            document.getElementById('edit-issue-form').reset();
        }
    }

    // Global function to open process modal
    function openProcessModal(issueId) {
        const modal = document.getElementById('process-issue-modal');
        const issueIdInput = document.getElementById('process-issue-id');
        
        if (modal && issueIdInput) {
            issueIdInput.value = issueId;
            modal.classList.remove('hidden');
            
            // Load current issue data
            loadIssueData(issueId);
        }
    }

    // Global function to close process modal
    function closeProcessModal() {
        const modal = document.getElementById('process-issue-modal');
        if (modal) {
            modal.classList.add('hidden');
            // Reset form
            document.getElementById('process-issue-form').reset();
        }
    }

    // Load issue data into modal
    function loadIssueData(issueId) {
        // This would typically make an AJAX call to get issue data
        // For now, we'll just set the form to be ready for submission
        console.log('Loading issue data for ID:', issueId);
    }

    // Handle form submission
    document.addEventListener('DOMContentLoaded', function() {
        const processForm = document.getElementById('process-issue-form');
        if (processForm) {
            processForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(processForm);
                const issueId = formData.get('issue_id');
                
                // Make AJAX request to update issue
                fetch(`/api/technical-issues/${issueId}/process`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        assigned_to: formData.get('assigned_to'),
                        status: formData.get('status'),
                        result: formData.get('result')
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close modal
                        closeProcessModal();
                        // Reload page to show updated data
                        window.location.reload();
                    } else {
                        alert('Có lỗi xảy ra: ' + (data.message || 'Không thể cập nhật'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi cập nhật');
                });
            });
        }

        const editForm = document.getElementById('edit-issue-form');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(editForm);
                const issueId = formData.get('issue_id');
                
                // Make AJAX request to update issue
                fetch(`/api/technical-issues/${issueId}/edit`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        description: formData.get('description'),
                        notes: formData.get('notes'),
                        category: formData.get('category')
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close modal
                        closeEditModal();
                        // Reload page to show updated data
                        window.location.reload();
                    } else {
                        alert('Có lỗi xảy ra: ' + (data.message || 'Không thể cập nhật'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi cập nhật');
                });
            });
        }
    });
    </script>
