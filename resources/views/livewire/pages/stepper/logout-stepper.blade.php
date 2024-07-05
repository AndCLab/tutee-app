<nav x-data="{ open: false }" class="md:absolute top-0 w-full">
    <div class="md:max-w-4xl mx-auto">
        <div class="flex justify-end min-h-fit py-4">
            <div class="flex sm:items-center sm:ms-6">
                <x-wui-button label='logout' flat icon='logout' wire:click="logout" class="w-full text-start"/>
            </div>
        </div>
    </div>
</nav>
