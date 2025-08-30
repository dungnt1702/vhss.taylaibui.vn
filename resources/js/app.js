import './bootstrap';

import Alpine from 'alpinejs';

// Import the vehicle classes entry point for proper dependency management
// This will load all vehicle-related functionality through the class-based architecture
import './vehicles/VehicleClasses';

// Global function to toggle vehicle content visibility
window.toggleVehicleSimple = function(vehicleId) {
    console.log('toggleVehicleSimple called for vehicle:', vehicleId);
    
    const content = document.getElementById(`content-${vehicleId}`);
    const icon = document.getElementById(`icon-${vehicleId}`);
    
    if (content && icon) {
        if (content.classList.contains('hidden')) {
            // Show content
            content.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
            console.log('Vehicle content shown for vehicle:', vehicleId);
        } else {
            // Hide content
            content.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
            console.log('Vehicle content hidden for vehicle:', vehicleId);
        }
    } else {
        console.error('Content or icon not found for vehicle:', vehicleId);
    }
};

// Global function to return vehicle to yard
window.returnVehicleToYard = async function(vehicleId, button) {
    try {
        // Show loading state
        if (button) {
            const originalText = button.textContent;
            button.textContent = 'Đang xử lý...';
            button.disabled = true;
        }
        
        const response = await fetch('/api/vehicles/return-yard', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({
                vehicle_ids: [vehicleId]
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Simple success - just reload page
            window.location.reload();
        } else {
            // Show error if needed
            console.error('Return to yard failed:', result.message);
            if (button) {
                button.textContent = 'Lỗi - Thử lại';
                setTimeout(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                }, 2000);
            }
        }
    } catch (error) {
        console.error('Error returning vehicle to yard:', error);
        if (button) {
            button.textContent = 'Lỗi - Thử lại';
            setTimeout(() => {
                button.textContent = originalText;
                button.disabled = false;
            }, 2000);
        }
    }
};

// Navigation JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Dropdown menu toggle
    const dropdownButtons = document.querySelectorAll('[data-dropdown-toggle]');
    
    dropdownButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('data-dropdown-toggle');
            const targetDropdown = document.getElementById(targetId);
            
            if (targetDropdown) {
                // Close all other dropdowns
                document.querySelectorAll('[data-dropdown-toggle]').forEach(otherButton => {
                    if (otherButton !== button) {
                        const otherId = otherButton.getAttribute('data-dropdown-toggle');
                        const otherDropdown = document.getElementById(otherId);
                        if (otherDropdown) {
                            otherDropdown.classList.add('hidden');
                        }
                    }
                });
                
                // Toggle current dropdown
                targetDropdown.classList.toggle('hidden');
            }
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('[data-dropdown-toggle]') && !e.target.closest('[id*="dropdown"]')) {
            document.querySelectorAll('[id*="dropdown"]').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (mobileMenu && !e.target.closest('#mobile-menu-button') && !e.target.closest('#mobile-menu')) {
            mobileMenu.classList.add('hidden');
        }
    });

    // Close mobile menu when pressing Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (mobileMenu) {
                mobileMenu.classList.add('hidden');
            }
            document.querySelectorAll('[id*="dropdown"]').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }
    });

    // Submenu toggle for vehicle management
    const vehicleSubmenuButtons = document.querySelectorAll('[data-submenu-toggle]');
    
    vehicleSubmenuButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('data-submenu-toggle');
            const targetSubmenu = document.getElementById(targetId);
            
            if (targetSubmenu) {
                // Close all other submenus
                document.querySelectorAll('[data-submenu-toggle]').forEach(otherButton => {
                    if (otherButton !== button) {
                        const otherId = otherButton.getAttribute('data-submenu-toggle');
                        const otherSubmenu = document.getElementById(otherId);
                        if (otherSubmenu) {
                            otherSubmenu.classList.add('hidden');
                        }
                    }
                });
                
                // Toggle current submenu
                targetSubmenu.classList.toggle('hidden');
            }
        });
    });

    // Vehicle toggle functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('[data-action="toggle-vehicle"]')) {
            e.preventDefault();
            const vehicleHeader = e.target.closest('[data-action="toggle-vehicle"]');
            const vehicleId = vehicleHeader.getAttribute('data-vehicle-id');
            if (vehicleId) {
                window.toggleVehicleSimple(vehicleId);
            }
        }
        
        // Handle other vehicle actions
        if (e.target.closest('[data-action]')) {
            e.preventDefault();
            const button = e.target.closest('[data-action]');
            const action = button.getAttribute('data-action');
            const vehicleId = button.getAttribute('data-vehicle-id');
            
            console.log('Vehicle action clicked:', action, 'for vehicle:', vehicleId);
            
            switch(action) {
                case 'start-timer':
                    const duration = button.getAttribute('data-duration');
                    console.log('Starting timer for vehicle', vehicleId, 'with duration', duration, 'minutes');
                    // TODO: Implement timer start logic
                    break;
                    
                case 'resume-vehicle':
                    console.log('Resuming vehicle', vehicleId);
                    // TODO: Implement resume logic
                    break;
                    
                case 'return-yard':
                    console.log('Returning vehicle', vehicleId, 'to yard - handled by VehicleBase');
                    // Use VehicleBase function for single vehicle return
                    if (window.vehicleBase) {
                        window.vehicleBase.returnSingleVehicleToYard(vehicleId, button);
                    } else {
                        console.error('VehicleBase not available');
                    }
                    break;
                    
                case 'add-time':
                    const addDuration = button.getAttribute('data-duration');
                    console.log('Adding', addDuration, 'minutes to vehicle', vehicleId);
                    // TODO: Implement add time logic
                    break;
                    
                case 'pause-vehicle':
                    console.log('Pausing vehicle', vehicleId);
                    // TODO: Implement pause logic
                    break;
                    
                case 'open-workshop-modal':
                    console.log('Opening workshop modal for vehicle', vehicleId);
                    // TODO: Implement workshop modal logic
                    break;
                    
                default:
                    console.log('Unknown action:', action);
            }
        }
    });

    // Active link highlighting
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('nav a');
    
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('bg-blue-700', 'text-white');
        } else {
            link.classList.remove('bg-blue-700', 'text-white');
        }
    });

    // Sidebar toggle for dashboard
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
        });
    }

    // Close sidebar when clicking outside on mobile
    if (sidebar) {
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 768 && !e.target.closest('#sidebar') && !e.target.closest('#sidebar-toggle')) {
                sidebar.classList.add('-translate-x-full');
            }
        });
    }

    console.log('Navigation JavaScript loaded successfully');
});



// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();
