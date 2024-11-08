<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>{{ config('app.name', 'Laravel') }}</title> --}}
    <title>@stack('title', 'Tutee')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


    <wireui:scripts />
</head>

<body class="font-inter text-gray-900 antialiased soft-scrollbar">
    <div class="mx-10">
        <div class="grid grid-rows-1 md:grid-cols-2 h-screen md:h-full items-start md:justify-center mx-auto md:max-w-6xl">
            @if (Route::is('login'))
                <div class="md:flex hidden justify-center items-center h-screen sticky top-0">
                    <img class="size-3/4" src="{{ asset('images/login_image.svg') }}" alt="">
                </div>
                <div class="my-auto py-10 md:py-32">
                    <h1 class="max-w-sm mx-auto uppercase font-bold text-6xl text-[#0C3B2E] font-anton mb-4">tutee</h1>
                    {{ $slot }}
                </div>
            @elseif (Route::is('register'))
                <div class="my-auto py-10 md:py-32">
                    <h1 class="max-w-sm mx-auto uppercase font-bold text-6xl text-[#0C3B2E] font-anton mb-4">tutee</h1>
                    {{ $slot }}
                </div>
                <div class="md:flex hidden justify-center items-center h-screen sticky top-0">
                    <img class="size-3/4" src="{{ asset('images/register_image.svg') }}" alt="">
                </div>
            @elseif (Route::is('password.request'))
                <div class="my-auto py-10 md:py-32">
                    <h1 class="max-w-sm mx-auto uppercase font-bold text-6xl text-[#0C3B2E] font-anton mb-4">tutee</h1>
                    {{ $slot }}
                </div>
                <div class="md:flex hidden justify-center items-center h-screen sticky top-0">
                    <img class="size-2/3" src="{{ asset('images/forgot-password.svg') }}" alt="">
                </div>
            @elseif (Route::is('login.admin'))
                <div class="md:flex hidden justify-center items-center h-screen sticky top-0">
                    <img class="size-4/5" src="{{ asset('images/admin-login.svg') }}" alt="">
                </div>
                <div class="my-auto py-10 md:py-32">
                    <h1 class="max-w-sm mx-auto uppercase font-bold text-6xl text-[#0F172A] font-anton mb-4">tutee</h1>
                    {{ $slot }}
                </div>
            @endif
        </div>
    </div>
    {{-- request()->path() == 'register' --}}
</body>

</html>
