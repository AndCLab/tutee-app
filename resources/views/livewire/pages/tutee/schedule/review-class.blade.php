@if ($review_class)
    <x-wui-modal.card title="How was {{ $review_class->tutor->user->fname }}'s class?" align='start' max-width='md' wire:model="review_class_modal">
        <div class="grid grid-row-1 sm:grid-row-2">

            <div x-data="{
                disabled: false,
                max_stars: 5,
                stars: 0,
                value: 0,
                hoverStar(star) {
                    if (this.disabled) {
                        return;
                    }
                    this.stars = star;
                },
                mouseLeftStar() {
                    if (this.disabled) {
                        return;
                    }
                    this.stars = this.value;
                },
                rate(star) {
                    if (this.disabled) {
                        return;
                    }
                    this.stars = star;
                    this.value = star;
                    @this.rating = this.value
                    $refs.rated.classList.remove('opacity-0');
                    setTimeout(function() {
                        $refs.rated.classList.add('opacity-0');
                    }, 2000);
                },
                reset() {
                    if (this.disabled) {
                        return;
                    }
                    this.value = 0;
                    this.stars = 0;
                }
            }" x-init="this.stars = this.value" class="pt-3">
                <div class="flex flex-col items-center max-w-6xl mx-auto jusitfy-center">
                    <div x-ref="rated"
                        class="absolute -mt-1 text-xs text-gray-400 duration-300 ease-out -translate-y-full opacity-0">Rated
                        <span x-text="value"></span> Stars
                    </div>
                    <ul class="flex">
                        <template x-for="star in max_stars">
                            <li @mouseover="hoverStar(star)" @mouseleave="mouseLeftStar" @click="rate(star)"
                                class="px-1 cursor-pointer" :class="{ 'text-gray-400 cursor-not-allowed': disabled }">
                                <svg x-show="star > stars" class="w-6 h-6 text-gray-300 fill-current"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256">
                                    <rect width="256" height="256" fill="none" />
                                    <path
                                        d="M234.29,114.85l-45,38.83L203,211.75a16.4,16.4,0,0,1-24.5,17.82L128,198.49,77.47,229.57A16.4,16.4,0,0,1,53,211.75l13.76-58.07-45-38.83A16.46,16.46,0,0,1,31.08,86l59-4.76,22.76-55.08a16.36,16.36,0,0,1,30.27,0l22.75,55.08,59,4.76a16.46,16.46,0,0,1,9.37,28.86Z" />
                                </svg>
                                <svg x-show="star <= stars" class="w-6 h-6 text-yellow-400 fill-current"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256">
                                    <rect width="256" height="256" fill="none" />
                                    <path
                                        d="M234.29,114.85l-45,38.83L203,211.75a16.4,16.4,0,0,1-24.5,17.82L128,198.49,77.47,229.57A16.4,16.4,0,0,1,53,211.75l13.76-58.07-45-38.83A16.46,16.46,0,0,1,31.08,86l59-4.76,22.76-55.08a16.36,16.36,0,0,1,30.27,0l22.75,55.08,59,4.76a16.46,16.46,0,0,1,9.37,28.86Z" />
                                </svg>
                            </li>
                        </template>
                    </ul>
                    <button @click="reset"
                        class="inline-flex items-center px-2 py-1 mt-3 text-xs text-gray-600 bg-gray-200 rounded-full hover:bg-black hover:text-white">
                        <svg class="w-3 h-3 mr-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256">
                            <rect width="256" height="256" fill="none" />
                            <polyline points="24 56 24 104 72 104" fill="none" stroke="currentColor"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="24" />
                            <path d="M67.59,192A88,88,0,1,0,65.77,65.77L24,104" fill="none" stroke="currentColor"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="24" />
                        </svg>
                        <span>Reset</span>
                    </button>
                </div>
            </div>

            {{-- remarks --}}
            <x-wui-textarea wire:model='remarks' label="Remarks" placeholder="Your thoughts about your tutor..." shadowless errorless/>

            <x-wui-errors class="mt-2" only='rating|remarks'/>

        </div>

        <x-slot name="footer">
            <div class="grid grid-cols-2 gap-2 items-center">
                <x-wui-button class="w-full" flat label="Close" x-on:click="close" />
                <x-wui-button class="w-full" primary label="Review Class" wire:click='reviewClass' spinner='reviewClass' />
            </div>
        </x-slot>
    </x-wui-modal.card>
@endif
