<button {{ $attributes->merge(['type' => 'button', 'class' => 'rounded border border-gray-300 bg-[#0F172A] px-4 py-2 text-sm text-white shadow-sm transition duration-150 ease-in-out hover:bg-[#0F172A]/90 focus:outline-none focus:ring-2 focus:ring-[#0F172A]/70 focus:ring-offset-2 disabled:opacity-25 active:bg-[#0F172A] disabled:bg-[#0F172A]/70']) }}>
    {{ $slot }}
</button>
