@extends('layouts.app')

@section('title', 'Quản lý xe')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-neutral-900">Quản lý xe</h1>
        <p class="text-neutral-600 mt-2">Quản lý tất cả xe trong hệ thống</p>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6">
        <div class="border-b border-neutral-200">
            <nav class="-mb-px flex space-x-8">
                <a href="{{ route('vehicles.index', ['filter' => 'ready']) }}" 
                   class="py-2 px-1 border-b-2 font-medium text-sm {{ $filter === 'ready' ? 'border-brand-500 text-brand-600' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300' }}">
                    Xe sẵn sàng chạy
                </a>
                <a href="{{ route('vehicles.index', ['filter' => 'waiting']) }}" 
                   class="py-2 px-1 border-b-2 font-medium text-sm {{ $filter === 'waiting' ? 'border-brand-500 text-brand-600' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300' }}">
                    Xe đang chờ
                </a>
                <a href="{{ route('vehicles.index', ['filter' => 'running']) }}" 
                   class="py-2 px-1 border-b-2 font-medium text-sm {{ $filter === 'running' ? 'border-brand-500 text-brand-600' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300' }}">
                    Xe đang chạy
                </a>
                <a href="{{ route('vehicles.index', ['filter' => 'paused']) }}" 
                   class="py-2 px-1 border-b-2 font-medium text-sm {{ $filter === 'paused' ? 'border-brand-500 text-brand-600' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300' }}">
                    Xe tạm dừng
                </a>
                <a href="{{ route('vehicles.index', ['filter' => 'expired']) }}" 
                   class="py-2 px-1 border-b-2 font-medium text-sm {{ $filter === 'expired' ? 'border-brand-500 text-brand-600' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300' }}">
                    Xe hết giờ
                </a>
                <a href="{{ route('vehicles.index', ['filter' => 'workshop']) }}" 
                   class="py-2 px-1 border-b-2 font-medium text-sm {{ $filter === 'workshop' ? 'border-brand-500 text-brand-600' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300' }}">
                    Xe trong xưởng
                </a>
                <a href="{{ route('vehicles.index', ['filter' => 'repairing']) }}" 
                   class="py-2 px-1 border-b-2 font-medium text-sm {{ $filter === 'repairing' ? 'border-brand-500 text-brand-600' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300' }}">
                    Xe đang sửa chữa
                </a>
                <a href="{{ route('vehicles.index', ['filter' => 'maintaining']) }}" 
                   class="py-2 px-1 border-b-2 font-medium text-sm {{ $filter === 'maintaining' ? 'border-brand-500 text-brand-600' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300' }}">
                    Xe đang bảo trì
                </a>
                <a href="{{ route('vehicles.index', ['filter' => 'attributes']) }}" 
                   class="py-2 px-1 border-b-2 font-medium text-sm {{ $filter === 'attributes' ? 'border-brand-500 text-brand-600' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300' }}">
                    Thuộc tính xe
                </a>
                <a href="{{ route('vehicles.index', ['filter' => 'vehicles_list']) }}" 
                   class="py-2 px-1 border-b-2 font-medium text-sm {{ $filter === 'vehicles_list' ? 'border-brand-500 text-brand-600' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300' }}">
                    Danh sách xe
                </a>
            </nav>
        </div>
    </div>

    <!-- Content based on filter -->
    @if($filter === 'ready')
        @include('vehicles.ready_vehicles')
    @elseif($filter === 'waiting')
        @include('vehicles.waiting_vehicles')
    @elseif($filter === 'running')
        @include('vehicles.running_vehicles')
    @elseif($filter === 'paused')
        @include('vehicles.paused_vehicles')
    @elseif($filter === 'expired')
        @include('vehicles.expired_vehicles')
    @elseif($filter === 'workshop')
        @include('vehicles.workshop_vehicles')
    @elseif($filter === 'repairing')
        @include('vehicles.repairing_vehicles')
    @elseif($filter === 'maintaining')
        @include('vehicles.maintaining_vehicles')
    @elseif($filter === 'attributes')
        @include('vehicles.attributes_list')
    @elseif($filter === 'vehicles_list')
        @include('vehicles.vehicles_list')
    @else
        <!-- Default view -->
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-neutral-900">Chọn bộ lọc</h3>
            <p class="mt-1 text-sm text-neutral-500">
                Vui lòng chọn một bộ lọc để xem danh sách xe.
            </p>
        </div>
    @endif
</div>

@include('vehicles.partials.vehicle_modals')

@endsection

@push('scripts')
    <!-- Load VehicleClasses.js for all vehicle functionality -->
    @vite(['resources/js/vehicles/VehicleClasses.js'])
@endpush

@push('styles')
    <!-- Load appropriate CSS based on filter -->
    @if($filter === 'ready')
        @vite(['resources/css/vehicles/ready-vehicles.css'])
    @elseif($filter === 'waiting')
        @vite(['resources/css/vehicles/waiting-vehicles.css'])
    @elseif($filter === 'running')
        @vite(['resources/css/vehicles/running-vehicles.css'])
    @elseif($filter === 'paused')
        @vite(['resources/css/vehicles/paused-vehicles.css'])
    @elseif($filter === 'expired')
        @vite(['resources/css/vehicles/expired-vehicles.css'])
    @elseif($filter === 'workshop')
        @vite(['resources/css/vehicles/workshop-vehicles.css'])
    @elseif($filter === 'repairing')
        @vite(['resources/css/vehicles/repairing-vehicles.css'])
    @elseif($filter === 'maintaining')
        @vite(['resources/css/vehicles/maintaining-vehicles.css'])
    @elseif($filter === 'attributes')
        @vite(['resources/css/vehicles/attributes-list.css'])
    @elseif($filter === 'vehicles_list')
        @vite(['resources/css/vehicles/vehicles-list.css'])
    @endif
@endpush
