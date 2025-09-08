@extends('layouts.app')

@section('title', 'Xe đang sửa chữa')

@section('content')
<div class="container mx-auto px-4 py-6">

    <!-- Header for Repair Issues History -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-neutral-900">Lịch sử sửa chữa</h1>
        <p class="text-neutral-600 mt-2">Báo cáo và lịch sử các vấn đề sửa chữa</p>
    </div>

    <!-- Grid Display for repair issues -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="repair-issues-grid">
        @foreach($repairIssues as $issue)
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-orange-500" data-issue-id="{{ $issue->id }}">
            <div class="flex justify-between items-start mb-3">
                <h3 class="text-lg font-semibold text-neutral-900">{{ $issue->vehicle->name }}</h3>
                <span class="px-2 py-1 text-xs font-medium rounded-full {{ \App\Models\VehicleTechnicalIssue::getStatusLabels()[$issue->status] ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ \App\Models\VehicleTechnicalIssue::getStatusLabels()[$issue->status] ?? $issue->status }}
                </span>
            </div>
            
            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-neutral-600">
                    <div class="w-4 h-4 rounded-full mr-2" style="background-color: {{ $issue->vehicle->color }};"></div>
                    <span>{{ $issue->vehicle->color }}</span>
                </div>
                
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-3">
                    <div class="text-sm font-medium text-orange-800 mb-1">
                        {{ \App\Models\VehicleTechnicalIssue::getRepairCategories()[$issue->category] ?? $issue->category }}
                    </div>
                    @if($issue->description)
                        <div class="text-xs text-orange-700 mb-1">
                            {{ Str::limit($issue->description, 100) }}
                        </div>
                    @endif
                    @if($issue->notes)
                        <div class="text-xs text-orange-600">
                            <span class="font-medium">Ghi chú:</span> {{ Str::limit($issue->notes, 80) }}
                        </div>
                    @endif
                    <div class="text-xs text-orange-600 mt-1">
                        <span class="font-medium">Báo cáo:</span> {{ $issue->reported_at->format('d/m/Y H:i') }}
                    </div>
                    @if($issue->reporter)
                        <div class="text-xs text-orange-600">
                            <span class="font-medium">Người báo cáo:</span> {{ $issue->reporter->name }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2" id="issue-buttons-{{ $issue->id }}">
                @if($issue->status !== 'completed')
                    <button onclick="vehicleOperations.completeRepairIssue({{ $issue->id }})" class="btn btn-success btn-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Hoàn thành
                    </button>
                @endif
                <button onclick="vehicleOperations.viewIssueDetails({{ $issue->id }})" class="btn btn-info btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Chi tiết
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

@push('scripts')
    <!-- Load VehicleClasses.js for all vehicle functionality -->
    @vite(['resources/js/vehicles/VehicleClasses.js'])
@endpush

@push('styles')
@vite(['resources/css/vehicles/repairing-vehicles.css'])
@endpush
