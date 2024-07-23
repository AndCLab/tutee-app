<button {{ $attributes->merge(['type' => 'submit', 'class' => 'items-center px-4 py-2 disabled:bg-red-400 bg-red-600 border border-transparent rounded text-sm text-white hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
