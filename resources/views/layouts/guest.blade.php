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

<body class="font-inter text-gray-900 antialiased">
    <div class="mx-10">
        <div class="grid grid-rows-1 md:grid-cols-2 items-center md:justify-center h-screen mx-auto md:max-w-6xl">
            <div class="md:flex hidden justify-center items-center bg-teal-200">
                <img src="https://img.freepik.com/free-vector/programming-concept-illustration_114360-1351.jpg?t=st=1718551941~exp=1718555541~hmac=53a20c933c12d92c52e67bf9e33fe51300813eaf920711e81455ff77a6c43fcb&w=740"
                    alt="">
            </div>
            <div>
                <h1 class="max-w-sm mx-auto uppercase font-bold text-6xl text-[#0C3B2E] font-anton mb-4">tutee</h1>
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>
