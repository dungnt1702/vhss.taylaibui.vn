<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-brand-500 dark:bg-brand-600 border border-transparent rounded-md font-semibold text-xs text-white dark:text-white uppercase tracking-widest hover:bg-brand-600 dark:hover:bg-brand-700 focus:bg-brand-600 dark:focus:bg-brand-700 active:bg-brand-700 dark:active:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
