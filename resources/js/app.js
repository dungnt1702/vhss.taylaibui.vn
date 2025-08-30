import './bootstrap';

import Alpine from 'alpinejs';

// Import the vehicle classes entry point for proper dependency management
// This will load all vehicle-related functionality through the class-based architecture
import './vehicles/VehicleClasses';

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
