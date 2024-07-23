@props(['href' => null])

@php
    $tag = $href ? 'a' : 'button';
    $defaultAttributes = $href ? ['href' => $href] : ['type' => 'submit'];
@endphp

<{{ $tag }} {{ $attributes->merge(array_merge($defaultAttributes, ['class' => 'rounded border border-transparent bg-[#0C3B2E] px-4 py-2 text-sm text-white transition duration-150 ease-in-out hover:bg-[#0C3B2E]/90 focus:bg-[#0C3B2E]/90 focus:outline-none focus:ring-2 focus:ring-[#0C3B2E]/70 focus:ring-offset-2 active:bg-[#0C3B2E] disabled:bg-[#0C3B2E]/70'])) }}>
    {{ $slot }}
</{{ $tag }}>