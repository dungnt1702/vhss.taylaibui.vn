@extends('layouts.app')

@section('title', 'Xe tạm dừng')

@section('content')
<div class="container mx-auto px-4 py-6" id="vehicle-page" data-page-type="paused">

    <!-- Header for Paused Vehicles -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-neutral-900">Xe tạm dừng</h1>
        <p class="text-neutral-600 mt-2">Quản lý xe đang tạm dừng hoạt động</p>
    </div>

    <!-- Grid Display for paused vehicles -->
    <div id="vehicle-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($vehicles as $vehicle)
            <div class="vehicle-card bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200 border-l-4 border-orange-500" data-vehicle-id="{{ $vehicle->id }}" data-vehicle-name="{{ $vehicle->name }}" data-status="{{ $vehicle->status }}" data-start-time="{{ $vehicle->start_time ? strtotime($vehicle->start_time) * 1000 : '' }}" data-end-time="{{ $vehicle->end_time ? strtotime($vehicle->end_time) * 1000 : '' }}" data-paused-at="{{ $vehicle->paused_at ? strtotime($vehicle->paused_at) * 1000 : '' }}" data-paused-remaining-seconds="{{ $vehicle->paused_remaining_seconds ?? '' }}">
                <!-- Vehicle Header - Clickable for collapse/expand -->
                <div class="vehicle-header cursor-pointer p-4 border-b border-neutral-200 hover:bg-neutral-50 transition-colors duration-200" data-action="toggle-vehicle" data-vehicle-id="{{ $vehicle->id }}">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-neutral-900">
                            Xe số {{ $vehicle->name }}
                        </h3>
                        <div class="w-4 h-4 rounded border border-neutral-300 " style="background-color: {{ $vehicle->color }};" title="{{ $vehicle->color }}"></div>
                    </div>
                    <!-- Expand/Collapse Icon -->
                    <div class="flex justify-center mt-2">
                        <svg class="w-4 h-4 text-neutral-500 transform transition-transform" id="icon-{{ $vehicle->id }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                <!-- Vehicle Details - Collapsible -->
                <div class="vehicle-content hidden p-4" id="content-{{ $vehicle->id }}">
                    <!-- Countdown Timer Display -->
                    <div class="text-center mb-6">
                        <div class="countdown-display text-6xl font-black text-orange-600 drop-shadow-lg" id="countdown-{{ $vehicle->id }}">
                            @if($vehicle->paused_remaining_seconds)
                                @php
                                    $minutesLeft = floor($vehicle->paused_remaining_seconds / 60);
                                    $secondsLeft = $vehicle->paused_remaining_seconds % 60;
                                    $minutesDisplay = str_pad($minutesLeft, 2, '0', STR_PAD_LEFT);
                                    $secondsDisplay = str_pad($secondsLeft, 2, '0', STR_PAD_LEFT);
                                @endphp
                                <span class="countdown-minutes text-6xl font-black drop-shadow-lg">{{ $minutesDisplay }}</span><span class="text-6xl font-black drop-shadow-lg">:</span><span class="countdown-seconds text-6xl font-black drop-shadow-lg">{{ $secondsDisplay }}</span>
                            @else
                                <span class="countdown-minutes text-6xl font-black drop-shadow-lg">00</span><span class="text-6xl font-black drop-shadow-lg">:</span><span class="countdown-seconds text-6xl font-black drop-shadow-lg">00</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Action Buttons for paused vehicles -->
                    <div class="flex flex-wrap gap-2 justify-center">
                        <button data-action="resume-vehicle" data-vehicle-id="{{ $vehicle->id }}" class="btn btn-success btn-sm">
                            ▶️ Tiếp tục
                        </button>
                        <button data-action="return-yard" data-vehicle-id="{{ $vehicle->id }}" class="btn btn-primary btn-sm">
                            🏠 Về bãi
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-neutral-900">Không có xe nào</h3>
                    <p class="mt-1 text-sm text-neutral-500">
                        Hiện tại không có xe nào đang tạm dừng.
                    </p>
                </div>
            </div>
        @endforelse
    </div>
</div>

@include('vehicles.partials.vehicle_modals')

@endsection

