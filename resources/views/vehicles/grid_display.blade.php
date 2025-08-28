<!-- Grid Display for specific statuses (waiting, running, paused, expired) -->
<div id="vehicle-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse($vehicles as $vehicle)
        <div class="vehicle-card bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200" data-vehicle-id="{{ $vehicle->id }}" data-vehicle-name="{{ $vehicle->name }}" data-status="{{ $vehicle->status }}" data-start-time="{{ $vehicle->start_time ? strtotime($vehicle->start_time) * 1000 : '' }}" data-end-time="{{ $vehicle->end_time ? strtotime($vehicle->end_time) * 1000 : '' }}" data-paused-at="{{ $vehicle->paused_at ? strtotime($vehicle->paused_at) * 1000 : '' }}" data-paused-remaining-seconds="{{ $vehicle->paused_remaining_seconds ?? '' }}">
            <!-- Vehicle Header - Clickable for collapse/expand -->
            <div class="vehicle-header cursor-pointer p-4 border-b border-neutral-200 hover:bg-neutral-50 transition-colors duration-200" onclick="toggleVehicleSimple({{ $vehicle->id }})">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-neutral-900">
                        Xe số {{ $vehicle->name }}
                    </h3>
                    <div class="w-4 h-4 rounded border border-neutral-300 " style="background-color: {{ $vehicle->color }};" title="{{ $vehicle->color }}"></div>
                </div>
                <!-- Expand/Collapse Icon -->
                <div class="flex justify-center mt-2">
                    <svg class="w-4 h-4 text-neutral-500 transform transition-transform {{ $filter === 'running' ? 'rotate-180' : '' }}" id="icon-{{ $vehicle->id }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>

            <!-- Vehicle Details - Collapsible -->
            <div class="vehicle-content {{ $filter === 'running' ? '' : 'hidden' }} p-4" id="content-{{ $vehicle->id }}">
                <!-- Countdown Timer Display - ALWAYS ON TOP -->
                <div class="text-center mb-6">
                    <div class="countdown-display text-6xl font-black {{ $vehicle->status === 'expired' ? 'text-red-600' : 'text-blue-600' }} drop-shadow-lg" id="countdown-{{ $vehicle->id }}">
                        @if($vehicle->status === 'expired')
                            <span class="text-red-600 font-black text-6xl drop-shadow-lg">HẾT GIỜ</span>
                        @elseif($vehicle->end_time)
                            <!-- Show actual time if vehicle has end_time -->
                            @php
                                $endTime = strtotime($vehicle->end_time);
                                $now = time();
                                $timeLeft = $endTime - $now;
                                
                                if ($timeLeft > 0) {
                                    $minutesLeft = floor($timeLeft / 60);
                                    $secondsLeft = $timeLeft % 60;
                                    $minutesDisplay = str_pad($minutesLeft, 2, '0', STR_PAD_LEFT);
                                    $secondsDisplay = str_pad($secondsLeft, 2, '0', STR_PAD_LEFT);
                                } else {
                                    $minutesDisplay = '00';
                                    $secondsDisplay = '00';
                                }
                            @endphp
                            <span class="countdown-minutes text-6xl font-black drop-shadow-lg">{{ $minutesDisplay }}</span><span class="text-6xl font-black drop-shadow-lg">:</span><span class="countdown-seconds text-6xl font-black drop-shadow-lg">{{ $secondsDisplay }}</span>
                        @else
                            <span class="countdown-minutes text-6xl font-black drop-shadow-lg">00</span><span class="text-6xl font-black drop-shadow-lg">:</span><span class="countdown-seconds text-6xl font-black drop-shadow-lg">00</span>
                        @endif
                    </div>
                </div>
                
                                        @if($vehicle->status === 'ready')
                    <!-- Active vehicles (waiting) - 30p, 45p, Về xưởng -->
                    <div class="flex flex-wrap gap-2 justify-center">
                        <button onclick="startTimer({{ $vehicle->id }}, 30)" class="btn btn-success btn-sm">
                            🚗 30p
                        </button>
                        <button onclick="startTimer({{ $vehicle->id }}, 45)" class="btn btn-primary btn-sm">
                            🚙 45p
                        </button>
                        <button onclick="vehicleForms.openWorkshopModal({{ $vehicle->id }})" class="btn btn-secondary btn-sm">
                            🔧 Về xưởng
                        </button>
                    </div>
                @elseif($vehicle->status === 'running')
                    <!-- Running vehicles - +10p, Tạm dừng, Về bãi -->
                    <div class="flex flex-wrap gap-2 justify-center">
                        <button onclick="addTime({{ $vehicle->id }}, 10)" class="btn btn-warning btn-sm">
                            ⏰ +10p
                        </button>
                        <button onclick="pauseVehicle({{ $vehicle->id }})" class="btn btn-info btn-sm">
                            ⏸️ Tạm dừng
                        </button>
                        <button onclick="returnToYard({{ $vehicle->id }})" class="btn btn-primary btn-sm">
                            🏠 Về bãi
                        </button>
                    </div>
                @elseif($vehicle->status === 'expired')
                    <!-- Expired vehicles - +10p, Về bãi -->
                    <div class="flex flex-wrap gap-2 justify-center">
                        <button onclick="addTime({{ $vehicle->id }}, 10)" class="btn btn-warning btn-sm">
                            ⏰ +10p
                        </button>
                        <button onclick="returnToYard({{ $vehicle->id }})" class="btn btn-primary btn-sm">
                            🏠 Về bãi
                        </button>
                    </div>
                @elseif($vehicle->status === 'paused')
                    <!-- Paused vehicles - Tiếp tục, Về bãi -->
                    <div class="flex flex-wrap gap-2 justify-center">
                        <button onclick="resumeVehicle({{ $vehicle->id }})" class="btn btn-success btn-sm">
                            ▶️ Tiếp tục
                        </button>
                        <button onclick="returnToYard({{ $vehicle->id }})" class="btn btn-primary btn-sm">
                            🏠 Về bãi
                        </button>
                    </div>
                @else
                    <!-- Active vehicles (outside yard) - 30p, 45p -->
                    <div class="flex flex-wrap gap-2 justify-center">
                        <button onclick="startTimer({{ $vehicle->id }}, 30)" class="btn btn-success btn-sm">
                            🚗 30p
                        </button>
                        <button onclick="startTimer({{ $vehicle->id }}, 45)" class="btn btn-primary btn-sm">
                            🚙 45p
                        </button>
                    </div>
                @endif
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
                    Bắt đầu bằng cách thêm xe mới vào hệ thống.
                </p>
                @if(auth()->user()->canManageVehicles())
                    <div class="mt-6">
                        <button onclick="openEditVehicleModal()" class="btn btn-success">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Thêm xe mới
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endforelse
</div>
