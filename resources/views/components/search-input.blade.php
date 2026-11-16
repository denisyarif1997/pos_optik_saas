@props(['model' => '', 'placeholder' => 'Cari...', 'label' => null])

<div>
    @if ($label)
        <label class="label block mb-1.5">{{ $label }}</label>
    @endif
    <input type="text" wire:model.live="{{ $model }}"
        placeholder="{{ $placeholder }}"
        class="input">
</div>