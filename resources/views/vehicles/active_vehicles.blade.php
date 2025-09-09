@extends('layouts.app')

@section('title', 'Xe hoạt động')

@section('content')
<div class="container mx-auto px-4 py-6" id="vehicle-page" data-page-type="active">

    <!-- Header for Active Vehicles -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-neutral-900">Xe hoạt động</h1>
        <p class="text-neutral-600 mt-2">Quản lý xe đã sẵn sàng để hoạt động</p>
    </div>

    <!-- Grid Display for active vehicles -->
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Hidden data for JavaScript -->
        <div id="vehicle-data" data-vehicles='@json($vehicles)' style="display: none;"></div>
        <div id="running-vehicles-data" data-vehicles='@json($runningVehicles)' style="display: none;"></div>
        <div id="paused-vehicles-data" data-vehicles='@json($pausedVehicles)' style="display: none;"></div>
        <div id="expired-vehicles-data" data-vehicles='@json($expiredVehicles)' style="display: none;"></div>

        <!-- Single Column Layout -->
        <div class="space-y-6">
            <!-- Block 1: Xe đang chờ -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 cursor-pointer flex items-center justify-between section-header px-3 py-2 rounded-md" onclick="toggleSection('ready-section')">
                        <span>Xe sẵn sàng</span>
                        <svg id="ready-arrow" class="w-5 h-5 arrow-rotate" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </h2>
                    
                    <div id="ready-section" class="transition-all duration-300 ease-in-out">
                        @if($vehicles && count($vehicles) > 0)
                            <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 text-left">
                                        <input type="checkbox" id="select-all-ready" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                                    </th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Xe số</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Tình trạng</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">🔧</th>
                                </tr>
                            </thead>
                            <tbody id="ready-vehicles" class="divide-y divide-gray-200">
                                @foreach($vehicles as $vehicle)
                                    <tr class="hover:bg-gray-50 clickable-row">
                                        <td class="px-3 py-2">
                                            <input type="checkbox" value="{{ $vehicle->id }}" class="ready-checkbox rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                                        </td>
                                        <td class="px-3 py-2">
                                            <div class="vehicle-number-with-color flex items-center">
                                                <div class="w-8 h-8 rounded border border-gray-300 flex items-center justify-center text-white font-semibold text-sm" style="background-color: {{ $vehicle->color }};" title="{{ $vehicle->color }}">
                                                    {{ $vehicle->name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-2">
                                            @if($vehicle->notes)
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $vehicle->notes }}
                                                </span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Không có ghi chú
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2">
                                            <button onclick="openWorkshopModal({{ $vehicle->id }})" class="text-gray-600 hover:text-gray-900 transition-colors duration-200" title="Về xưởng">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            </table>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500">Không có xe nào đang chờ</p>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="mt-6">
                        <div class="flex flex-col items-center gap-4 [1028px:flex-row] [1028px:justify-center] [1280px:flex-col] [1280px:items-center]">
                            <!-- Time Selection Button Group -->
                            <div class="flex items-stretch w-full max-w-xs [1028px:max-w-none] [1280px:max-w-xs]">
                                <select id="time-select" class="flex-1 px-4 py-2 border border-gray-300 border-r-0 rounded-l-md bg-white text-sm outline-none h-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="45">45 phút</option>
                                    <option value="30" selected>30 phút</option>
                                    <option value="10">10 phút</option>
                                </select>
                                <button data-action="assign-timer" class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-r-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 min-w-[120px] h-10">
                                    Đếm
                                </button>
                            </div>
                            
                            <!-- Route Selection Button Group -->
                            <div class="flex items-stretch w-full max-w-xs [1028px:max-w-none] [1280px:max-w-xs]">
                                <select id="route-select" class="flex-1 px-4 py-2 border border-gray-300 border-r-0 rounded-l-md bg-white text-sm outline-none h-10 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="1">Đường 1</option>
                                    <option value="2">Đường 2</option>
                                    <option value="3" selected>Đường 3</option>
                                    <option value="4">Đường 4</option>
                                    <option value="5">Đường 5</option>
                                    <option value="6">Đường 6</option>
                                    <option value="7">Đường 7</option>
                                    <option value="8">Đường 8</option>
                                    <option value="9">Đường 9</option>
                                    <option value="10">Đường 10</option>
                                </select>
                                <button data-action="assign-route" class="px-6 py-2 bg-green-600 text-white text-sm font-medium rounded-r-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200 min-w-[120px] h-10">
                                    Chạy
                                </button>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Block 2: Xe chạy theo thời gian -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 cursor-pointer flex items-center justify-between section-header px-3 py-2 rounded-md" onclick="toggleSection('timer-section')">
                        <span>Xe chạy theo thời gian</span>
                        <svg id="timer-arrow" class="w-5 h-5 arrow-rotate" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </h2>
                    
                    <div id="timer-section" class="transition-all duration-300 ease-in-out">
                        <table class="table min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 text-left">
                                        <input type="checkbox" id="select-all-timer" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                                    </th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Xe số</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Trạng thái</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Bắt đầu</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Kết thúc</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Đếm ngược</th>
                                </tr>
                            </thead>
                            <tbody id="timer-vehicles" class="divide-y divide-gray-200">
                                <!-- Timer vehicles will be populated by JavaScript -->
                            </tbody>
                        </table>

                        <div class="mt-4">
                            <button onclick="returnSelectedVehiclesToYard()" class="w-full px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                Về bãi
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Block 3: Xe chạy theo cung đường -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 cursor-pointer flex items-center justify-between section-header px-3 py-2 rounded-md" onclick="toggleSection('route-section')">
                        <span>Xe chạy theo cung đường</span>
                        <svg id="route-arrow" class="w-5 h-5 arrow-rotate" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </h2>
                    
                    <div id="route-section" class="transition-all duration-300 ease-in-out">
                        <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-3 py-2 text-left">
                                            <input type="checkbox" id="select-all-routing" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                                        </th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Xe số</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Cung đường</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Bắt đầu</th>
                                    </tr>
                                </thead>
                                <tbody id="routing-vehicles" class="divide-y divide-gray-200">
                                    @if($routingVehicles && count($routingVehicles) > 0)
                                        @foreach($routingVehicles as $vehicle)
                                        <tr class="hover:bg-gray-50 clickable-row">
                                            <td class="px-3 py-2">
                                                <input type="checkbox" value="{{ $vehicle->id }}" class="routing-checkbox rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                                            </td>
                                            <td class="px-3 py-2">
                                                <div class="vehicle-number-with-color flex items-center">
                                                    <div class="w-8 h-8 rounded border border-gray-300 flex items-center justify-center text-white font-semibold text-sm" style="background-color: {{ $vehicle->color }};" title="{{ $vehicle->color }}">
                                                        {{ $vehicle->name }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Đường {{ $vehicle->route_number }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($vehicle->start_time)->format('H:i') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="px-3 py-8 text-center text-gray-500">
                                                Không có xe nào đang theo cung đường
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            
                            @if($routingVehicles && count($routingVehicles) > 0)
                                <div class="mt-4">
                                    <button onclick="returnSelectedRoutingVehiclesToYard()" class="w-full px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                        Về bãi
                                    </button>
                                </div>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notification modal is now included from vehicle_modals.blade.php -->

@include('vehicles.partials.vehicle_modals')

@endsection

<script>
        // Global function wrapper for returnSelectedRoutingVehiclesToYard
        function returnSelectedRoutingVehiclesToYard() {
            console.log('Global function called');
            if (window.activeVehicles) {
                window.activeVehicles.returnSelectedRoutingVehiclesToYard();
            } else {
                console.error('window.activeVehicles not found');
            }
        }

        // Function to toggle section collapse/expand
        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            const arrow = document.getElementById(sectionId.replace('-section', '-arrow'));
            
            if (section.classList.contains('collapsed')) {
                // Show section
                section.classList.remove('collapsed');
                section.style.maxHeight = section.scrollHeight + 'px';
                arrow.style.transform = 'rotate(180deg)';
                
                // Remove maxHeight after animation to allow content to grow
                setTimeout(() => {
                    section.style.maxHeight = 'none';
                }, 300);
            } else {
                // Hide section
                section.style.maxHeight = section.scrollHeight + 'px';
                // Force reflow
                section.offsetHeight;
                section.classList.add('collapsed');
                section.style.maxHeight = '0px';
                arrow.style.transform = 'rotate(0deg)';
            }
        }
        
        // Global function for return to yard button
        function returnSelectedVehiclesToYard() {
            // Check if ActiveVehicles instance exists
            if (window.activeVehicles) {
                window.activeVehicles.returnSelectedVehiclesToYard();
            } else {
                console.error('ActiveVehicles instance not found');
                alert('Lỗi: Không thể thực hiện thao tác này');
            }
        }
        
        // Global function for return routing vehicles to yard button
        function returnSelectedRoutingVehiclesToYard() {
            // Check if ActiveVehicles instance exists
            if (window.activeVehicles) {
                window.activeVehicles.returnSelectedRoutingVehiclesToYard();
            } else {
                console.error('ActiveVehicles instance not found');
                alert('Lỗi: Không thể thực hiện thao tác này');
            }
        }
        
        // Global function for open workshop modal
        function openWorkshopModal(vehicleId) {
            console.log('Opening workshop modal for vehicle:', vehicleId);
            const modal = document.getElementById('move-workshop-modal');
            const vehicleIdInput = document.getElementById('workshop-vehicle-id');
            
            if (modal) {
                console.log('Modal found, setting up...');
                // Store vehicle ID in a hidden input or data attribute
                if (vehicleIdInput) {
                    vehicleIdInput.value = vehicleId;
                } else {
                    // Create hidden input if it doesn't exist
                    const form = modal.querySelector('#move-workshop-form');
                    if (form) {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.id = 'workshop-vehicle-id';
                        hiddenInput.name = 'vehicle_id';
                        hiddenInput.value = vehicleId;
                        form.appendChild(hiddenInput);
                    }
                }
                
                // Setup form validation with delay to ensure DOM is ready
                console.log('Calling setupWorkshopFormValidation...');
                setTimeout(() => {
                    setupWorkshopFormValidation();
                }, 100);
                
                modal.classList.remove('hidden');
                console.log('Modal opened successfully');
            } else {
                console.error('Workshop modal not found');
                alert('Lỗi: Không thể mở modal');
            }
        }
        
        // Global function for setup workshop form validation
        function setupWorkshopFormValidation() {
            console.log('Setting up workshop form validation...');
            const form = document.getElementById('move-workshop-form');
            const reasonSelect = document.getElementById('workshop-reason');
            const notesTextarea = document.getElementById('workshop-notes');
            const submitButton = document.getElementById('workshop-submit-btn');
            
            console.log('Elements found:', { form: !!form, reasonSelect: !!reasonSelect, notesTextarea: !!notesTextarea, submitButton: !!submitButton });
            
            if (!form || !reasonSelect || !notesTextarea || !submitButton) {
                console.log('Some elements not found, validation setup failed');
                return;
            }

            // Function to validate form
            const validateForm = () => {
                const reason = reasonSelect.value;
                const notes = notesTextarea.value.trim();
                
                console.log('Validation check:', { reason, notes, disabled: submitButton.disabled });
                
                // If no reason selected, disable button
                if (!reason || reason === '') {
                    submitButton.disabled = true;
                    submitButton.style.backgroundColor = '#9ca3af';
                    submitButton.style.cursor = 'not-allowed';
                    console.log('Button disabled - no reason selected');
                    return false;
                }
                
                // If no notes provided, disable button (regardless of reason)
                if (!notes) {
                    submitButton.disabled = true;
                    submitButton.style.backgroundColor = '#9ca3af';
                    submitButton.style.cursor = 'not-allowed';
                    console.log('Button disabled - no notes provided');
                    return false;
                }
                
                // If both reason and notes are provided, enable button
                if (reason && notes) {
                    submitButton.disabled = false;
                    submitButton.style.backgroundColor = '#ea580c';
                    submitButton.style.cursor = 'pointer';
                    console.log('Button enabled - both reason and notes provided');
                    return true;
                }
                
                // Default: disable button
                submitButton.disabled = true;
                submitButton.style.backgroundColor = '#9ca3af';
                submitButton.style.cursor = 'not-allowed';
                console.log('Button disabled - default state');
                return false;
            };

            // Remove existing event listeners to avoid duplicates
            reasonSelect.removeEventListener('change', validateForm);
            notesTextarea.removeEventListener('input', validateForm);
            
            // Add event listeners
            console.log('Adding event listeners...');
            reasonSelect.addEventListener('change', function() {
                console.log('Reason changed to:', reasonSelect.value);
                validateForm();
            });
            notesTextarea.addEventListener('input', function() {
                console.log('Notes changed to:', notesTextarea.value);
                validateForm();
            });
            
            // Initial validation
            console.log('Running initial validation...');
            validateForm();
        }
        
        // Global function for close workshop modal
        function closeMoveWorkshopModal() {
            const modal = document.getElementById('move-workshop-modal');
            if (modal) {
                modal.classList.add('hidden');
                // Reset form
                const form = modal.querySelector('#move-workshop-form');
                if (form) {
                    form.reset();
                    // Reset dropdown to empty value
                    const reasonSelect = document.getElementById('workshop-reason');
                    if (reasonSelect) {
                        reasonSelect.value = '';
                    }
                    // Reset button state
                    const submitButton = document.getElementById('workshop-submit-btn');
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.style.backgroundColor = '#9ca3af';
                        submitButton.style.cursor = 'not-allowed';
                    }
                }
            }
        }
        
        // Initialize all sections as expanded by default
        document.addEventListener('DOMContentLoaded', function() {
            const sections = ['ready-section', 'timer-section', 'route-section'];
            sections.forEach(sectionId => {
                const section = document.getElementById(sectionId);
                if (section) {
                    section.classList.add('collapsible-section');
                    section.style.maxHeight = 'none';
                }
            });
        });
    </script>
