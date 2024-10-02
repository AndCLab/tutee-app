{{-- resources\views\livewire\pages\bookmark\bookmarkbutton.blade.php --}}
<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Bookmark;

new class extends Component {
    public $tutorId;
    public $isBookmarked;

    public function mount($tutorId)
    {
        $this->tutorId = $tutorId;
        $this->isBookmarked = Bookmark::where('tutor_id', $this->tutorId)
            ->where('user_id', Auth::id())
            ->exists();
    }

    public function toggleBookmark()
    {
        if ($this->isBookmarked) {
            // Remove bookmark
            Bookmark::where('tutor_id', $this->tutorId)
                ->where('user_id', Auth::id())
                ->delete();
            $this->isBookmarked = false;
        } else {
            // Add bookmark
            Bookmark::create([
                'tutor_id' => $this->tutorId,
                'user_id' => Auth::id(),
            ]);
            $this->isBookmarked = true;
        }
    }

}; ?>

<section>
    <div x-data="{ isBookmarked: <?= json_encode($this->isBookmarked) ?>, isHovered: false }" wire:loading.remove wire:target="toggleBookmark">
        <button @click="isBookmarked = !isBookmarked; $wire.toggleBookmark()" @mouseover="isHovered = true" @mouseleave="isHovered = false" wire:click.defer="toggleBookmark">
            <template x-if="!isBookmarked">
                <svg :class="isHovered ? 'text-green-500' : 'text-gray-500'" class="bookMark size-5 cursor-pointer" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 10.5H14M12 8.5V12.5M8.25 5H15.75C16.4404 5 17 5.58763 17 6.3125V19L12 15.5L7 19V6.3125C7 5.58763 7.55964 5 8.25 5Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </template>
            <template x-if="isBookmarked && !isHovered">
                <svg class="text-gray-500 bookMark size-5 cursor-pointer" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.5 10.5L11.5 11.5L14 9M8.25 5H15.75C16.4404 5 17 5.58763 17 6.3125V19L12 15.5L7 19V6.3125C7 5.58763 7.55964 5 8.25 5Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </template>
            <template x-if="isBookmarked && isHovered">
                <svg class="text-red-500 bookMark size-5 cursor-pointer" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.5 8.56L12 10.06M12 10.06L13.5 11.56M12 10.06L13.5 8.56M12 10.06L10.5 11.56M8.25 5H15.75C16.4404 5 17 5.58763 17 6.3125V19L12 15.5L7 19V6.3125C7 5.58763 7.55964 5 8.25 5Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </template>
        </button>
    </div>
</section>
