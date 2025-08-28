<!-- Navigation Component -->
<nav x-data="{ open: false, mobileOpen: false }" class="bg-white border-b border-neutral-200 sticky top-0 z-[9999]" style="position: sticky !important; top: 0 !important;">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Hamburger for Mobile -->
                <div class="flex items-center sm:hidden mr-3">
                    <button @click="mobileOpen = !mobileOpen" class="inline-flex items-center justify-center p-2 rounded-md text-neutral-400 hover:text-neutral-500 hover:bg-neutral-100 focus:outline-none focus:bg-neutral-100 focus:text-neutral-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': mobileOpen, 'inline-flex': ! mobileOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! mobileOpen, 'inline-flex': mobileOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-neutral-900" />
                    </a>
                </div>

                <!-- Mobile Title -->
                <div class="sm:hidden ml-4 flex-1 flex justify-center">
                    <h1 class="text-lg font-bold text-neutral-900">TAY LÁI BỤI SÓC SƠN</h1>
                </div>

                <!-- Navigation Links - All on same row -->
                <div class="hidden sm:flex sm:ml-10 space-x-8">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="px-4 py-2">
                        Trang chủ
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="px-4 py-2">
                        Dashboard
                    </x-nav-link>
                    
                    <!-- Điều phối xe -->
                    <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button class="inline-flex items-center px-4 py-2 border-b-2 border-transparent text-sm font-medium leading-5 text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 focus:outline-none focus:text-neutral-700 focus:border-neutral-300 transition duration-200 ease-in-out rounded-md hover:bg-neutral-50">
                            Điều phối xe
                            <svg class="ml-2 h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <!-- Điều phối xe Dropdown Menu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                             x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 transform scale-95 -translate-y-2"
                             class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 border border-neutral-200">
                            <div class="py-2">
                                <a href="{{ route('vehicles.index', ['filter' => 'ready']) }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100 hover:text-neutral-900 transition-colors duration-150">
                                    <div class="flex items-center">
                                        <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Xe sẵn sàng chạy
                                    </div>
                                </a>
                                <a href="{{ route('vehicles.index', ['filter' => 'waiting']) }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100 hover:text-neutral-900 transition-colors duration-150">
                                    <div class="flex items-center">
                                        <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Xe đang chờ
                                    </div>
                                </a>
                                <a href="{{ route('vehicles.index', ['filter' => 'running']) }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100 hover:text-neutral-900 transition-colors duration-150">
                                    <div class="flex items-center">
                                        <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Xe đang chạy
                                    </div>
                                </a>
                                <a href="{{ route('vehicles.index', ['filter' => 'paused']) }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100 hover:text-neutral-900 transition-colors duration-150">
                                    <div class="flex items-center">
                                        <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        </svg>
                                        Xe tạm dừng
                                    </div>
                                </a>
                                <a href="{{ route('vehicles.index', ['filter' => 'expired']) }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100 hover:text-neutral-900 transition-colors duration-150">
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
                    
                    <!-- Quản lý xe -->
                    <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button class="inline-flex items-center px-4 py-2 border-b-2 border-transparent text-sm font-medium leading-5 text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 focus:outline-none focus:text-neutral-700 focus:border-neutral-300 transition duration-200 ease-in-out rounded-md hover:bg-neutral-50">
                            Quản lý xe
                            <svg class="ml-2 h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <!-- Quản lý xe Dropdown Menu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                             x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 transform scale-95 -translate-y-2"
                             class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 border border-neutral-200">
                            <div class="py-2">
                                <a href="/vehicles?filter=vehicles_list" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100 hover:text-neutral-900 transition-colors duration-150">
                                    <div class="flex items-center">
                                        <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        Danh sách xe
                                    </div>
                                </a>
                                <a href="/vehicles?filter=attributes" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100 hover:text-neutral-900 transition-colors duration-150">
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
                    
                    <!-- Xưởng sửa chữa -->
                    <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button class="inline-flex items-center px-4 py-2 border-b-2 border-transparent text-sm font-medium leading-5 text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 focus:outline-none focus:text-neutral-700 focus:border-neutral-300 transition duration-200 ease-in-out rounded-md hover:bg-neutral-50">
                            Xưởng sửa chữa
                            <svg class="ml-2 h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <!-- Xưởng sửa chữa Dropdown Menu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                             x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 transform scale-95 -translate-y-2"
                             class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 border border-neutral-200">
                            <div class="py-2">
                                <a href="{{ route('vehicles.index', ['filter' => 'workshop']) }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100 hover:text-neutral-900 transition-colors duration-150">
                                    <div class="flex items-center">
                                        <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        Xe trong xưởng
                                    </div>
                                </a>
                                <a href="{{ route('vehicles.index', ['filter' => 'repairing']) }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100 hover:text-neutral-900 transition-colors duration-150">
                                    <div class="flex items-center">
                                        <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                        Sửa chữa
                                    </div>
                                </a>
                                <a href="{{ route('vehicles.index', ['filter' => 'maintaining']) }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100 hover:text-neutral-900 transition-colors duration-150">
                                    <div class="flex items-center">
                                        <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        </svg>
                                        Bảo trì
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-neutral-500 bg-white hover:text-neutral-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
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

        <!-- Mobile Navigation Menu - Slides from left -->
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-700"
         x-transition:enter-start="opacity-0 transform -translate-x-full"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-600"
         x-transition:leave-start="opacity-100 transform translate-x-0"
         x-transition:leave-end="opacity-0 transform -translate-x-full"
         class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-2xl transform sm:hidden">
         <div class="flex items-center justify-between p-4 border-b border-neutral-200">
             <h2 class="text-lg font-semibold text-neutral-900">Menu</h2>
             <button @click="mobileOpen = false" class="text-neutral-400 hover:text-neutral-600 transition-colors duration-200">
                 <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                 </svg>
             </button>
         </div>
         
         <div class="p-4 space-y-4">
             <!-- Main Menu Items -->
             <div>
                 <a href="{{ route('home') }}" class="block py-2 text-sm font-medium text-neutral-700 hover:text-neutral-900 hover:bg-neutral-50 rounded-md px-3 transition-all duration-200">
                     Trang chủ
                 </a>
                 <a href="{{ route('dashboard') }}" class="block py-2 text-sm font-medium text-neutral-700 hover:text-neutral-900 hover:bg-neutral-50 rounded-md px-3 transition-all duration-200">
                     Dashboard
                 </a>
             </div>
             
             <!-- Điều phối xe Section -->
             <div>
                 <h3 class="px-3 mb-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider">Điều phối xe</h3>
                 <div class="space-y-1">
                     <a href="{{ route('vehicles.index', ['filter' => 'ready']) }}" class="block py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded-md px-6 transition-all duration-200">
                         Xe sẵn sàng chạy
                     </a>
                     <a href="{{ route('vehicles.index', ['filter' => 'waiting']) }}" class="block py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded-md px-6 transition-all duration-200">
                         Xe đang chờ
                     </a>
                     <a href="{{ route('vehicles.index', ['filter' => 'running']) }}" class="block py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded-md px-6 transition-all duration-200">
                         Xe đang chạy
                     </a>
                     <a href="{{ route('vehicles.index', ['filter' => 'paused']) }}" class="block py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded-md px-6 transition-all duration-200">
                         Xe tạm dừng
                     </a>
                     <a href="{{ route('vehicles.index', ['filter' => 'expired']) }}" class="block py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded-md px-6 transition-all duration-200">
                         Xe hết giờ
                     </a>
                 </div>
             </div>

             <!-- Quản lý xe Section -->
             <div>
                 <h3 class="px-3 mb-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider">Quản lý xe</h3>
                 <div class="space-y-1">
                     <a href="/vehicles?filter=vehicles_list" class="block py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded-md px-6 transition-all duration-200">
                         Danh sách xe
                     </a>
                     <a href="/vehicles?filter=attributes" class="block py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded-md px-6 transition-all duration-200">
                         Thuộc tính xe
                     </a>
                 </div>
             </div>

             <!-- Xưởng sửa chữa Section -->
             <div>
                 <h3 class="px-3 mb-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider">Xưởng sửa chữa</h3>
                 <div class="space-y-1">
                     <a href="{{ route('vehicles.index', ['filter' => 'workshop']) }}" class="block py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded-md px-6 transition-all duration-200">
                         Xe trong xưởng
                     </a>
                     <a href="{{ route('vehicles.index', ['filter' => 'repairing']) }}" class="block py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded-md px-6 transition-all duration-200">
                         Sửa chữa
                     </a>
                     <a href="{{ route('vehicles.index', ['filter' => 'maintaining']) }}" class="block py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded-md px-6 transition-all duration-200">
                         Bảo trì
                     </a>
                 </div>
             </div>

             <!-- User Info & Settings -->
             <div class="pt-4 border-t border-neutral-200">
                 <div class="px-3 mb-3">
                     <div class="font-medium text-base text-neutral-800">{{ Auth::user()->name }}</div>
                     <div class="font-medium text-sm text-neutral-500">{{ Auth::user()->email }}</div>
                 </div>
                 
                 <div class="space-y-1">
                     <a href="{{ route('profile.edit') }}" class="block py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded-md px-3 transition-all duration-200">
                         Profile
                     </a>
                     
                     <form method="POST" action="{{ route('logout') }}" class="mt-2">
                         @csrf
                         <button type="submit" class="w-full text-left block py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded-md px-3 transition-all duration-200">
                             Log Out
                         </button>
                     </form>
                 </div>
             </div>
         </div>
     </div>

     <!-- Mobile Overlay - Semi-transparent gray covering entire screen -->
     <div x-show="mobileOpen" 
          x-transition:enter="transition-opacity ease-out duration-700"
          x-transition:enter-start="opacity-0"
          x-transition:enter-end="opacity-100"
          x-transition:leave="transition-opacity ease-in duration-600"
          x-transition:leave-start="opacity-100"
          x-transition:leave-end="opacity-0"
          @click="mobileOpen = false"
          class="sm:hidden"
          style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(61, 59, 59, 0.3); z-index: 45; cursor: pointer;"></div>
</nav>
