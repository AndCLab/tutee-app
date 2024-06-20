<button {{ $attributes->merge(['type' => 'button', 'class' => 'rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-[#0F172A] shadow-sm transition duration-150 ease-in-out hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-white/70 focus:ring-offset-2 disabled:opacity-25']) }}>
    {{ $slot }}
</button>
