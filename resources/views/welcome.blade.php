<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Welcome | Tutee</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    </head>
    @php
        if (Auth::check()) {
            $route = Auth::user()->user_type == 'tutee' ? 'tutee.discover' : 'tutor.discover';
        }
    @endphp
    <body class="relative antialiased font-inter soft-scrollbar bg-white md:bg-[#F3F5F4]">
        {{-- Navigation --}}
        <nav class="sticky top-0 z-10 bg-background/95 backdrop-blur">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center min-h-fit py-3">
                    <div>
                        <h1 class="uppercase font-bold text-4xl px-2 font-anton text-[#0C3B2E]">tutee</h1>
                    </div>
                    <div>
                        @guest('web')
                            @if (Route::has('login'))
                                <livewire:welcome.navigation />
                            @endif
                        @else
                            <p>
                                Welcome {{ Auth::user()->fname . ' ' . Auth::user()->lname  }}!
                            </p>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <div data-aos="fade-down" style="background-image: url('{{ asset('images/grid.svg') }}');" class="hidden md:block -z-10 absolute inset-0 [mask-image:linear-gradient(180deg,white,rgba(255,255,255,0))]"></div>

        {{-- main --}}
        <main class="max-w-6xl mx-auto md:py-10">
            <div class="flex flex-col-reverse md:grid md:grid-cols-2 justify-center items-center my-auto mx-auto">
                <div class="flex flex-col items-center md:items-start my-6 space-y-3 px-8">
                    <div data-aos="fade-right">
                        <p class="text-2xl md:text-5xl text-center md:text-start text-[#0C3B2E] font-black">Revolutionize Your Tutoring Experience.</p>
                    </div>
                    <span data-aos="fade-right" data-aos-duration="500" class="text-[#64748B] text-center md:text-start md:w-4/5">Enhance Learning and Efficiency with Our All-in-One Tutoring Platform.</span>

                    @auth('web')
                        <x-primary-button data-aos="fade-right" data-aos-duration="500" href="{{ route($route) }}" class="sm:w-fit w-full text-center">
                            Dashboard
                        </x-primary-button>
                    @else
                        @if (Route::has('register'))
                            <x-primary-button data-aos="fade-right" data-aos-duration="500" href="{{ route('register') }}" class="sm:w-fit w-full text-center">
                                Get Started
                            </x-primary-button>
                        @endif
                    @endauth
                </div>

                {{-- mobile desktop --}}
                <div data-aos="fade-left" data-aos-duration="500">
                    <div class="md:flex hidden items-center justify-center w-full h-60 md:h-full overflow-hidden">
                        <img class="scale-x-[-1] object-cover md:w-4/5" src="{{ asset('images/landing_desktop.svg') }}" alt="">
                    </div>
                </div>

                {{-- mobile image --}}
                <div data-aos="fade-left" class="md:hidden flex items-center justify-center w-full h-60 overflow-hidden">
                    <img class="object-contain" src="{{ asset('images/landing_mobile.svg') }}" alt="">
                </div>
            </div>
        </main>
    </body>
    <script>
        AOS.init({
            once: true
        });
    </script>
</html>
