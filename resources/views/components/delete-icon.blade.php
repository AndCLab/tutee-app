<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'focus:ring-offset-background-white dark:focus:ring-offset-background-dark group inline-flex h-9 w-9 items-center justify-center rounded-full text-sm text-gray-600 outline-none transition-all duration-200 ease-in-out hover:bg-red-400 hover:bg-opacity-25 hover:text-red-700 hover:shadow-sm focus:bg-red-400 focus:bg-opacity-25 focus:text-red-700 focus:ring-2 focus:ring-red-600 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-80 dark:text-gray-400 dark:hover:bg-red-600 dark:hover:bg-opacity-15 dark:hover:text-red-500 dark:focus:bg-red-600 dark:focus:bg-opacity-15 dark:focus:text-red-500 dark:focus:ring-red-700']) }}>
    {{ $slot }}
</button>
