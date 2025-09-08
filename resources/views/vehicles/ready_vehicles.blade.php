@extends('layouts.app')

@section('title', 'Xe sẵn sàng')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page identifier for VehicleClasses.js -->
    <div id="vehicle-page" data-page-type="ready" style="display: none;"></div>

    <!-- Header for Ready Vehicles -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-neutral-900">Xe sẵn sàng</h1>
        <p class="text-neutral-600 mt-2">Quản lý xe sẵn sàng để được phân công tuyến đường</p>
    </div>

    <!-- Grid Display for ready vehicles -->
    <div id="vehicle-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($vehicles as $vehicle)
            <div class="vehicle-card bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200 border-l-4 border-yellow-500" data-vehicle-id="{{ $vehicle->id }}" data-vehicle-name="{{ $vehicle->name }}" data-status="{{ $vehicle->status }}" data-start-time="{{ $vehicle->start_time ? strtotime($vehicle->start_time) * 1000 : '' }}" data-end-time="{{ $vehicle->end_time ? strtotime($vehicle->end_time) * 1000 : '' }}" data-paused-at="{{ $vehicle->paused_at ? strtotime($vehicle->paused_at) * 1000 : '' }}" data-paused-remaining-seconds="{{ $vehicle->paused_remaining_seconds ?? '' }}">
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
                        <div class="countdown-display text-6xl font-black text-blue-600 drop-shadow-lg" id="countdown-{{ $vehicle->id }}">
                            <span class="countdown-minutes text-6xl font-black drop-shadow-lg">00</span><span class="text-6xl font-black drop-shadow-lg">:</span><span class="countdown-seconds text-6xl font-black drop-shadow-lg">00</span>
            </div>
        </div>

                    <!-- Action Buttons for waiting vehicles -->
                    <div class="flex flex-wrap gap-2 justify-center">
                        <button data-action="assign-timer" data-vehicle-id="{{ $vehicle->id }}" data-duration="30" class="btn btn-success btn-sm">
                            🚗 30p
                        </button>
                        <button data-action="assign-timer" data-vehicle-id="{{ $vehicle->id }}" data-duration="45" class="btn btn-primary btn-sm">
                            🚙 45p
                        </button>
                        <button data-action="open-workshop-modal" data-vehicle-id="{{ $vehicle->id }}" class="btn btn-secondary btn-sm">
                            🔧 Về xưởng
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
                        Hiện tại không có xe nào đang chờ.
                    </p>
                </div>
            </div>
        @endforelse
    </div>
</div>

@include('vehicles.partials.vehicle_modals')

@endsection

@push('scripts')
    <!-- Load VehicleClasses.js for all vehicle functionality -->
    @vite(['resources/js/vehicles/VehicleClasses.js'])
    
    <script>
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
        
        // Global function for setup workshop form validation
        function setupWorkshopFormValidation() {
            console.log('Setting up workshop form validation (ready)...');
            const form = document.getElementById('move-workshop-form');
            const reasonSelect = document.getElementById('workshop-reason');
            const notesTextarea = document.getElementById('workshop-notes');
            const submitButton = document.getElementById('workshop-submit-btn');
            
            console.log('Elements found (ready):', { form: !!form, reasonSelect: !!reasonSelect, notesTextarea: !!notesTextarea, submitButton: !!submitButton });
            
            if (!form || !reasonSelect || !notesTextarea || !submitButton) {
                console.log('Some elements not found, validation setup failed (ready)');
                return;
            }

            // Function to validate form
            const validateForm = () => {
                const reason = reasonSelect.value;
                const notes = notesTextarea.value.trim();
                
                console.log('Validation check (ready):', { reason, notes, disabled: submitButton.disabled });
                
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
            reasonSelect.addEventListener('change', validateForm);
            notesTextarea.addEventListener('input', validateForm);
            
            // Initial validation
            validateForm();
        }
    </script>
@endpush

@push('styles')
@vite(['resources/css/vehicles/ready-vehicles.css'])
@endpush
