<nav x-data="{ open: false, vehicleMenuOpen: false }" 
     x-init="
        $watch('open', value => {
            if (value) {
                document.body.style.overflow = 'hidden';
                // Add click event to body to close menu when clicking outside
                setTimeout(() => {
                    document.body.addEventListener('click', closeMenuOnOutsideClick);
                }, 100);
            } else {
                document.body.style.overflow = '';
                document.body.removeEventListener('click', closeMenuOnOutsideClick);
            }
        });
        
        function closeMenuOnOutsideClick(event) {
            const sidebar = document.querySelector('[data-mobile-sidebar]');
            const overlay = document.querySelector('[data-mobile-overlay]');
            
            // Don't close if clicking on sidebar or overlay
            if (sidebar && sidebar.contains(event.target)) return;
            if (overlay && overlay.contains(event.target)) return;
            
            // Close menu if clicking anywhere else
            open = false;
        }
     "
     class="bg-white  border-b border-neutral-100  sticky-nav shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Mobile Menu Toggle Button - Left side -->
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-neutral-400 hover:text-neutral-500 hover:bg-neutral-100 focus:outline-none focus:bg-neutral-100 focus:text-neutral-500 transition duration-150 ease-in-out sm:hidden mr-3">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-brand-500" />
                    </a>
                </div>
                
                <!-- Page Title - Center -->
                <div class="hidden sm:flex flex-1 justify-center">
                    <h1 class="text-xl font-bold text-brand-600">TAY LÁI BỤI</h1>
                </div>

                <!-- Navigation Links - Full menu on desktop -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="px-4 py-2">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="px-4 py-2">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    <!-- Vehicle Management Section - Dropdown -->
                    <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button class="inline-flex items-center px-4 py-2 border-b-2 border-transparent text-sm font-medium leading-5 text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 focus:outline-none focus:text-neutral-700 focus:border-neutral-300 transition duration-200 ease-in-out rounded-md hover:bg-neutral-50">
                            Điều phối xe
                            <svg class="ml-2 h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                             x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 transform scale-95 -translate-y-2"
                             class="absolute left-0 mt-2 w-64 bg-white  rounded-md shadow-lg z-50 border border-neutral-200 ">
                            <div class="py-2">

                                

                                
                                <a href="{{ route('vehicles.index', ['filter' => 'active']) }}" class="block px-4 py-2 text-sm text-neutral-700  hover:bg-neutral-100  hover:text-neutral-900  transition-colors duration-150">
                                    <div class="flex items-center">
                                        <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Xe ngoài bãi
                                    </div>
                                </a>
                                <a href="{{ route('vehicles.index', ['filter' => 'waiting']) }}" class="block px-4 py-2 text-sm text-neutral-700  hover:bg-neutral-100  hover:text-neutral-900  transition-colors duration-150">
                                    <div class="flex items-center">
                                        <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Xe đang chờ
                                    </div>
                                </a>
                                <a href="{{ route('vehicles.index', ['filter' => 'running']) }}" class="block px-4 py-2 text-sm text-neutral-700  hover:bg-neutral-100  hover:text-neutral-900  transition-colors duration-150">
                                    <div class="flex items-center">
                                        <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Xe đang chạy
                                    </div>
                                </a>
                                <a href="{{ route('vehicles.index', ['filter' => 'paused']) }}" class="block px-4 py-2 text-sm text-neutral-700  hover:bg-neutral-100  hover:text-neutral-900  transition-colors duration-150">
                                    <div class="flex items-center">
                                        <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Xe tạm dừng
                                    </div>
                                </a>
                                <a href="{{ route('vehicles.index', ['filter' => 'expired']) }}" class="block px-4 py-2 text-sm text-neutral-700  hover:bg-neutral-100  hover:text-neutral-900  transition-colors duration-150">
                                    <div class="flex items-center">
                                        <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                        </svg>
                                        Xe hết giờ
                                    </div>
                                </a>

                                
                                
                            </div>
                        </div>
                                           </div>
                       
                       <!-- Vehicle Management Section - New standalone menu -->
                       <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                           <button class="inline-flex items-center px-4 py-2 border-b-2 border-transparent text-sm font-medium leading-5 text-neutral-500  hover:text-neutral-700  hover:border-neutral-300  focus:outline-none focus:text-neutral-700  focus:border-neutral-300  transition duration-200 ease-in-out rounded-md hover:bg-neutral-50 /50">
                               Quản lý xe
                               <svg class="ml-2 h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                               </svg>
                           </button>
                           
                           <!-- Vehicle Management Dropdown Menu -->
                           <div x-show="open" 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                                x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 transform scale-95 -translate-y-2"
                                class="absolute left-0 mt-2 w-48 bg-white  rounded-md shadow-lg z-50 border border-neutral-200 ">
                               <div class="py-2">
                                   <a href="/vehicles?filter=vehicles_list" class="block px-4 py-2 text-sm text-neutral-700  hover:bg-neutral-100  hover:text-neutral-900  transition-colors duration-150">
                                       <div class="flex items-center">
                                           <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                           </svg>
                                           Danh sách xe
                                       </div>
                                   </a>

                                   <a href="/vehicles?filter=attributes" class="block px-4 py-2 text-sm text-neutral-700  hover:bg-neutral-100  hover:text-neutral-900  transition-colors duration-150">
                                       <div class="flex items-center">
                                           <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                           </svg>
                                           Thuộc tính xe
                                       </div>
                                   </a>
                               </div>
                           </div>
                       </div>
                       
                       <!-- Workshop Management Section - New standalone menu -->
                       <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                           <button class="inline-flex items-center px-4 py-2 border-b-2 border-transparent text-sm font-medium leading-5 text-neutral-500  hover:text-neutral-700  hover:border-neutral-300  focus:outline-none focus:text-neutral-700  focus:border-neutral-300  transition duration-200 ease-in-out rounded-md hover:bg-neutral-50 /50">
                               Xưởng sửa chữa
                               <svg class="ml-2 h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                               </svg>
                           </button>
                           
                           <!-- Workshop Dropdown Menu -->
                           <div x-show="open" 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                                x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 transform scale-95 -translate-y-2"
                                class="absolute left-0 mt-2 w-48 bg-white  rounded-md shadow-lg z-50 border border-neutral-200 ">
                               <div class="py-2">
                                   <a href="{{ route('vehicles.index', ['filter' => 'inactive']) }}" class="block px-4 py-2 text-sm text-neutral-700  hover:bg-neutral-100  hover:text-neutral-900  transition-colors duration-150">
                                       <div class="flex items-center">
                                           <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                           </svg>
                                           Danh sách xe
                                       </div>
                                   </a>
                                   <a href="{{ route('vehicles.index', ['filter' => 'inactive']) }}" class="block px-4 py-2 text-sm text-neutral-700  hover:bg-neutral-100  hover:text-neutral-900  transition-colors duration-150">
                                       <div class="flex items-center">
                                           <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826-3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                           </svg>
                                           Sửa chữa
                                       </div>
                                   </a>
                                   <a href="{{ route('vehicles.index', ['filter' => 'inactive']) }}" class="block px-4 py-2 text-sm text-neutral-700  hover:bg-neutral-100  hover:text-neutral-900  transition-colors duration-150">
                                       <div class="flex items-center">
                                           <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                           </svg>
                                           Bảo dưỡng
                                       </div>
                                   </a>
                               </div>
                           </div>
                       </div>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-neutral-500 bg-white hover:text-neutral-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}" id="logout-form-nav">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>

    <!-- Mobile Sidebar Menu - Smooth slide animation -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="transform -translate-x-full opacity-0"
         x-transition:enter-end="transform translate-x-0 opacity-100"
         x-transition:leave="transition ease-in duration-400"
         x-transition:leave-start="transform translate-x-0 opacity-100"
         x-transition:leave-end="transform -translate-x-full opacity-0"
         class="fixed inset-y-0 left-0 z-50 w-64 bg-white  shadow-2xl sm:hidden"
         style="backdrop-filter: blur(10px);"
         @click.stop
         data-mobile-sidebar>
                <!-- Mobile Menu Header - Fixed -->
        <div class="flex items-center justify-between p-4 border-b border-neutral-200  bg-white  mobile-menu-header">
            <div class="flex items-center">
                <x-application-logo class="block h-8 w-auto fill-current text-brand-500" />
                <span class="ml-3 text-lg font-semibold text-neutral-900">TAY LÁI BỤI</span>
            </div>
            <button @click="open = false" class="text-neutral-400 hover:text-neutral-600 transition-colors duration-200">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu Content - Scrollable with smooth animations -->
        <div class="flex-1 overflow-y-auto bg-white " style="height: calc(100vh - 80px);">
            <div class="px-4 py-6 pb-32 bg-white ">
                <!-- User Info -->
                <div class="mb-6 p-4 bg-neutral-100  rounded-lg border border-neutral-200 ">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-brand-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-neutral-900 ">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-neutral-500 ">{{ Auth::user()->phone }}</p>
                        </div>
                    </div>
                </div>

                <!-- Main Menu Items -->
                <nav class="space-y-2">
                    <a href="{{ route('home') }}" class="flex items-center px-3 py-2 text-sm font-medium text-neutral-700 rounded-md hover:bg-neutral-100 hover:text-neutral-900 transition-all duration-200 {{ request()->routeIs('home') ? 'bg-brand-100 text-brand-700' : '' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Trang chủ
                    </a>

                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium text-neutral-700 rounded-md hover:bg-neutral-100 hover:text-neutral-900 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-brand-100 text-brand-700' : '' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Dashboard
                    </a>

                    <!-- Vehicle Management Section - Collapsible on mobile -->
                    <div class="pt-4 border-t border-neutral-200  bg-neutral-50 /50 rounded-lg p-3">
                        <button @click="vehicleMenuOpen = !vehicleMenuOpen" 
                                class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-neutral-700 rounded-md hover:bg-neutral-100 hover:text-neutral-900 transition-all duration-200">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                Điều phối xe
                            </div>
                            <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': vehicleMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <!-- Collapsible Submenu -->
                        <div x-show="vehicleMenuOpen" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="ml-6 mt-2 space-y-1">
                            




                            <a href="{{ route('vehicles.index', ['filter' => 'active']) }}" class="flex items-center px-3 py-2 text-sm font-medium text-neutral-600 rounded-md hover:bg-neutral-100 hover:text-neutral-900 transition-all duration-200">
                                <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Xe ngoài bãi
                            </a>

                            <a href="{{ route('vehicles.index', ['filter' => 'waiting']) }}" class="flex items-center px-3 py-2 text-sm font-medium text-neutral-600 rounded-md hover:bg-neutral-100 hover:text-neutral-900 transition-all duration-200">
                                <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Xe đang chờ
                            </a>

                            <a href="{{ route('vehicles.index', ['filter' => 'running']) }}" class="flex items-center px-3 py-2 text-sm font-medium text-neutral-600 rounded-md hover:bg-neutral-100 hover:text-neutral-900 transition-all duration-200">
                                <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Xe đang chạy
                            </a>

                            <a href="{{ route('vehicles.index', ['filter' => 'paused']) }}" class="flex items-center px-3 py-2 text-sm font-medium text-neutral-600 rounded-md hover:bg-neutral-100 hover:text-neutral-900 transition-all duration-200">
                                <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Xe tạm dừng
                            </a>

                            <a href="{{ route('vehicles.index', ['filter' => 'expired']) }}" class="flex items-center px-3 py-2 text-sm font-medium text-neutral-600 rounded-md hover:bg-neutral-100 hover:text-neutral-900 transition-all duration-200">
                                <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                Xe hết giờ
                            </a>


                            
                            @if(auth()->user()->canManageVehicleAttributes())
                            <div class="border-t border-neutral-200 my-2"></div>
                            <a href="{{ route('vehicles.attributes') }}" class="flex items-center px-3 py-2 text-sm font-medium text-neutral-700 rounded-md hover:bg-neutral-100 hover:text-neutral-900 transition-all duration-200">
                                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Thuộc tính xe
                            </a>
                            @endif
                        </div>
                    </div>

                    <!-- Vehicle Management Section - Mobile menu -->
                    <div class="pt-4 border-t border-neutral-200  bg-neutral-50 /50 rounded-lg p-3">
                        <h3 class="px-3 mb-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider">Quản lý xe</h3>
                        
                        <a href="{{ route('vehicles.index', ['filter' => 'vehicles_list']) }}" class="flex items-center px-3 py-2 text-sm font-medium text-neutral-700 rounded-md hover:bg-neutral-100 hover:text-neutral-900 transition-all duration-200">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Danh sách xe
                        </a>
                        

                        
                        @if(auth()->user()->canManageVehicleAttributes())
                        <a href="{{ route('vehicles.attributes') }}" class="flex items-center px-3 py-2 text-sm font-medium text-neutral-700 rounded-md hover:bg-neutral-100 hover:text-neutral-900 transition-all duration-200">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Thuộc tính xe
                        </a>
                        @endif
                    </div>
                    
                    <div class="pt-4 border-t border-neutral-200  bg-neutral-50 /50 rounded-lg p-3">
                        <h3 class="px-3 mb-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider">Xưởng sửa chữa</h3>
                        
                        <a href="{{ route('vehicles.index', ['filter' => 'inactive']) }}" class="flex items-center px-3 py-2 text-sm font-medium text-neutral-700 rounded-md hover:bg-neutral-100 hover:text-neutral-900 transition-all duration-200">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Danh sách xe
                        </a>
                        <a href="{{ route('vehicles.index', ['filter' => 'inactive']) }}" class="flex items-center px-3 py-2 text-sm font-medium text-neutral-700 rounded-md hover:bg-neutral-100 hover:text-neutral-900 transition-all duration-200">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826-3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Bảo dưỡng
                        </a>

                        <a href="{{ route('vehicles.index', ['filter' => 'inactive']) }}" class="flex items-center px-3 py-2 text-sm font-medium text-neutral-700 rounded-md hover:bg-neutral-100 hover:text-neutral-900 transition-all duration-200">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                            Sửa chữa
                        </a>
            </div>

                    <!-- User Management Section -->
                    <div class="pt-4 border-t border-neutral-200  bg-neutral-50 /50 rounded-lg p-3">
                        <h3 class="px-3 mb-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider">Quản lý</h3>
                        
                        <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2 text-sm font-medium text-neutral-700 rounded-md hover:bg-neutral-100 hover:text-neutral-900 transition-all duration-200">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Hồ sơ cá nhân
                        </a>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}" class="mt-2" id="logout-form-mobile">
                    @csrf
                            <button type="submit" class="w-full flex items-center px-3 py-2 text-sm font-medium text-red-600 rounded-md hover:bg-red-50 hover:text-red-700 transition-all duration-200">
                                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Đăng xuất
                            </button>
                </form>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <!-- Overlay for mobile menu - Smooth fade animation -->
    <div x-show="open" 
         x-transition:enter="transition-opacity ease-out duration-500"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-400"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false"
         class="fixed inset-0 z-40 bg-neutral-800 bg-opacity-30 sm:hidden"
         style="width: 100vw; height: 100vh; backdrop-filter: blur(2px);"
         data-mobile-overlay></div>
</nav>
