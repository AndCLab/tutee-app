<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <wireui:scripts />
</head>

<body class="font-inter antialiased bg-[#FAFAFA]">
    <div class="min-h-screen">

        <!-- Page Heading -->
        @if (isset($header))
            <div class="flex flex-row">
                <livewire:layout.sidenav />
                <div class="w-full">
                    <livewire:layout.navigation />
        @endif
        <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>

    </div>
</body>

</html>
