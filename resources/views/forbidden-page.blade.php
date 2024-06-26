<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    user type: {{ Auth::user()->user_type }}
                </h2>
                <div class="p-6 text-gray-900">
                    {{ __("you are forbidden to access this route") }}
                    {{ url()->previous() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
