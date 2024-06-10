<div>
    Tutee
    <x-wui-radio name="tutee" id="tutee" value="tutee" wire:model="user_type" />
    Tutor
    <x-wui-radio name="tutor" id="tutor" value="tutor" wire:model="user_type" />
</div>
@error('user_type')
    {{ $message }}
@enderror
