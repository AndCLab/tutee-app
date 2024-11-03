<div
    x-data="{
        tabSelected: 1,
        tabId: $id('tabs'),
        tabButtonClicked(tabButton){
            this.tabSelected = tabButton.id.replace(this.tabId + '-', '');
            this.tabRepositionMarker(tabButton);
        },
        tabRepositionMarker(tabButton){
            this.$refs.tabMarker.style.width=tabButton.offsetWidth + 'px';
            this.$refs.tabMarker.style.height=tabButton.offsetHeight + 'px';
            this.$refs.tabMarker.style.left=tabButton.offsetLeft + 'px';
        },
        tabContentActive(tabContent){
            return this.tabSelected == tabContent.id.replace(this.tabId + '-content-', '');
        }
    }"

    x-init="tabRepositionMarker($refs.tabButtons.firstElementChild);" class="relative w-full">

    {{-- buttons --}}
    <div x-ref="tabButtons" class="relative inline-grid items-center justify-center w-full h-10 grid-cols-2 p-1 text-gray-500 bg-gray-100 rounded-lg select-none">
        <button :id="$id(tabId)" @click="tabButtonClicked($el);" type="button" class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">Discover Classes</button>
        <button :id="$id(tabId)" @click="tabButtonClicked($el);" type="button" class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">My Posts</button>
        <div x-ref="tabMarker" class="absolute left-0 z-10 w-1/2 h-full duration-300 ease-out" x-cloak><div class="w-full h-full bg-white rounded-md shadow-sm"></div></div>
    </div>

    {{-- tabs --}}
    <div class="relative w-full mt-2 content">
        <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)" class="relative">
            <!-- Tab Content 1 - class posts -->
                <livewire:pages.tutee.post_components.class_posts>
            <!-- End Tab Content 1 -->
        </div>

        <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)" class="relative" x-cloak>
            <!-- Tab Content 2 - post list -->
                <livewire:pages.tutee.post_components.post_list>
            <!-- End Tab Content 2 -->
        </div>
    </div>
</div>
