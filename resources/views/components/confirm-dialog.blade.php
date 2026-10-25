@props(['show' => false, 'title' => 'Konfirmasi', 'message' => '', 'confirmLabel' => 'Ya, Hapus', 'cancelLabel' => 'Batal', 'onConfirm' => ''])

<div x-show="{{ $show ? 'true' : 'false' }}"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center"
     x-transition:enter="transition-opacity duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    <div x-show="{{ $show ? 'true' : 'false' }}"
         x-cloak
         class="fixed inset-0 bg-black/40 z-40"
         @click="{{ $onConfirm === '' ? '' : \"$wire.{{ $onConfirm }}(); show = false\" }}">
    </div>

    <div class="soft-card w-full max-w-sm mx-auto z-50"
         x-show="{{ $show ? 'true' : 'false' }}"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4">
        <div class="p-5">
            <h3 class="text-sm font-semibold text-text-main">{{ $title }}</h3>
            <p class="text-[11px] text-text-secondary mt-1">{{ $message }}</p>
        </div>
        <div class="border-t border-line p-4 flex justify-end gap-2">
            <button @click="show = false" class="btn-ghost px-4 py-2 text-xs">{{ $cancelLabel }}</button>
            <button @click="{{ $onConfirm === '' ? '' : \"$wire.{{ $onConfirm }}(); show = false\" }}"
                class="btn-primary px-4 py-2 text-xs">{{ $confirmLabel }}</button>
        </div>
    </div>
</div>