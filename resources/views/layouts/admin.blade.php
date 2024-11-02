<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>{{ config('app.name', 'Laravel') }}</title> --}}

    <title>@stack('title', 'Default Title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <wireui:scripts />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-inter antialiased soft-scrollbar">
    <div class="min-h-screen">
        <!-- Page Heading -->
        @if (isset($header))
            <div class="flex flex-row">
                <livewire:layout.sidenav_admin.sidenav />
                <div class="w-full">
                    <livewire:layout.topnav_admin.navigation />
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
