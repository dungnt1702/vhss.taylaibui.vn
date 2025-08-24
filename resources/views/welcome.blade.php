<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Welcome to VHSS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-neutral-900">
                    <div class="text-center">
                        <div class="mb-6">
                            <x-application-logo class="w-24 h-24 fill-current text-brand-500 mx-auto" />
                        </div>
                        
                        <h1 class="text-3xl font-bold text-brand-600 mb-4">
                            Chào mừng đến với VHSS
                        </h1>
                        
                        <p class="text-lg text-neutral-600 mb-8">
                            Hệ thống quản lý thông tin và dịch vụ
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                            <div class="bg-brand-50 p-6 rounded-lg border border-brand-200">
                                <div class="text-brand-600 mb-2">
                                    <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-neutral-800 mb-2">Quản lý thông tin</h3>
                                <p class="text-neutral-600">Truy cập và cập nhật thông tin cá nhân</p>
                            </div>
                            
                            <div class="bg-brand-50 p-6 rounded-lg border border-brand-200">
                                <div class="text-brand-600 mb-2">
                                    <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-neutral-800 mb-2">Cài đặt hệ thống</h3>
                                <p class="text-neutral-600">Tùy chỉnh cài đặt tài khoản</p>
                            </div>
                            
                            <div class="bg-brand-50 p-6 rounded-lg border border-brand-200">
                                <div class="text-brand-600 mb-2">
                                    <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-neutral-800 mb-2">Hỗ trợ</h3>
                                <p class="text-neutral-600">Liên hệ và nhận hỗ trợ từ chúng tôi</p>
                            </div>
                        </div>
                        
                        <div class="mt-8">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-brand-500 hover:bg-brand-600 text-white font-semibold rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                                Đi đến Dashboard
                            </a>
                        </div>
                    </div>
                </div>
                </div>
        </div>
    </div>
</x-app-layout>
