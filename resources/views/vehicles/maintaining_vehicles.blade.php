@extends('layouts.app')

@section('title', 'Xe đang bảo trì')

@section('content')
<div class="container mx-auto px-4 py-6">

    <!-- Header for Maintenance Issues History -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-neutral-900">Lịch sử bảo trì</h1>
        <p class="text-neutral-600 mt-2">Báo cáo và lịch sử các vấn đề bảo trì</p>
    </div>

    <!-- Table Display for maintenance issues -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Xe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hạng mục</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mô tả</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Người báo cáo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($maintenanceIssues as $issue)
                    <tr class="hover:bg-gray-50" data-issue-id="{{ $issue->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $issue->vehicle->color }};"></div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $issue->vehicle->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $issue->vehicle->color }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ \App\Models\VehicleTechnicalIssue::getMaintenanceCategories()[$issue->category] ?? $issue->category }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                @if($issue->description)
                                    {{ Str::limit($issue->description, 50) }}
                                @else
                                    <span class="text-gray-400">Không có mô tả</span>
                                @endif
                            </div>
                            @if($issue->notes)
                                <div class="text-xs text-gray-500 mt-1">
                                    <span class="font-medium">Ghi chú:</span> {{ Str::limit($issue->notes, 30) }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ \App\Models\VehicleTechnicalIssue::getStatusLabels()[$issue->status] ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ \App\Models\VehicleTechnicalIssue::getStatusLabels()[$issue->status] ?? $issue->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $issue->reporter->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $issue->reported_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                @if($issue->status !== 'completed')
                                    <button onclick="vehicleOperations.completeMaintenanceIssue({{ $issue->id }})" class="text-green-600 hover:text-green-900">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                @endif
                                <button onclick="vehicleOperations.viewIssueDetails({{ $issue->id }})" class="text-blue-600 hover:text-blue-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
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

    <!-- Empty State -->
    @if($maintenanceIssues->isEmpty())
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-neutral-900">Chưa có báo cáo bảo trì</h3>
        <p class="mt-1 text-sm text-neutral-500">Chưa có vấn đề bảo trì nào được báo cáo.</p>
    </div>
    @endif
</div>

<!-- Modals -->
@include('vehicles.partials.vehicle_modals')

@endsection

@push('scripts')
    <!-- Load VehicleClasses.js for all vehicle functionality -->
    @vite(['resources/js/vehicles/VehicleClasses.js'])
@endpush

@push('styles')
@vite(['resources/css/vehicles/maintaining-vehicles.css'])
@endpush
