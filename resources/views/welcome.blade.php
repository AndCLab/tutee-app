<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-inter soft-scrollbar bg-white md:bg-[#F3F5F4]">
        {{-- Navigation --}}
        <nav class="sticky top-0 z-10 bg-background/95 backdrop-blur">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center min-h-fit py-3">
                    <div>
                        <h1 class="uppercase font-bold text-4xl px-2 font-anton text-[#0C3B2E]">tutee</h1>
                    </div>
                    <div>
                        @guest
                            @if (Route::has('login'))
                                <livewire:welcome.navigation />
                            @endif
                        @else
                            <p class="bg-red-100">
                                {{ Auth::user()->fname . ' ' . Auth::user()->lname  }}
                            </p>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        {{-- main --}}
        <main class="max-w-6xl mx-auto py-10">
            <div class="flex flex-col-reverse md:grid md:grid-cols-2 justify-center items-center my-auto mx-auto">
                <div class="flex flex-col items-center md:items-start my-6 space-y-3 px-5">
                    <p class="text-2xl md:text-5xl text-center md:text-start text-[#0C3B2E] font-extrabold">Revolutionize Your Tutoring Experience.</p>
                    <span class="text-[#64748B] text-center md:text-start md:w-4/5">Enhance Learning and Efficiency with Our All-in-One Tutoring Platform.</span>
                    
                    @auth
                        <x-primary-button href="{{ url('/dashboard') }}" class="sm:w-fit w-full text-center">
                            Dashboard
                        </x-primary-button>
                    @else
                        @if (Route::has('register'))
                            <x-primary-button href="{{ route('register') }}" class="sm:w-fit w-full text-center">
                                Get Started
                            </x-primary-button>
                        @endif
                    @endauth
                </div>

                {{-- mobile desktop --}}
                <div class="md:flex hidden items-center justify-center w-full h-60 md:h-full overflow-hidden">
                    <img class="scale-x-[-1] object-cover md:w-4/5" src="{{ asset('images/landing_desktop.svg') }}" alt="">
                </div>
                
                {{-- mobile image --}}
                <div class="md:hidden flex items-center justify-center w-full h-60 overflow-hidden">
                    <img class="object-contain" src="{{ asset('images/landing_mobile.svg') }}" alt="">
                </div>
            </div>
        </main>
    </body>
</html>
