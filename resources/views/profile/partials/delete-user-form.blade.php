<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 ">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 ">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6" id="delete-user-form">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 ">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 ">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <div class="relative">
                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-3/4 pr-10"
                        placeholder="{{ __('Password') }}"
                    />
                    
                    <!-- Eye Icon -->
                    <button type="button" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 top-1"
                            onclick="togglePassword('password')">
                        <svg id="eye-icon-password" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="eye-slash-icon-password" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                        </svg>
                    </button>
                </div>

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>

        <script>
            function togglePassword(inputId) {
                const input = document.getElementById(inputId);
                const eyeIcon = document.getElementById('eye-icon-' + inputId);
                const eyeSlashIcon = document.getElementById('eye-slash-icon-' + inputId);
                
                if (input.type === 'password') {
                    input.type = 'text';
                    eyeIcon.classList.add('hidden');
                    eyeSlashIcon.classList.remove('hidden');
                } else {
                    input.type = 'password';
                    eyeIcon.classList.remove('hidden');
                    eyeSlashIcon.classList.add('hidden');
                }
            }
        </script>
    </x-modal>
</section>
