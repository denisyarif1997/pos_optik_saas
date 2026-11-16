@props([
    'href' => '#',
    'label' => '',
    'description' => '',
    'icon' => 'box',
    'color' => 'accent',
])

<a href="{{ $href }}" class="quick-action-card">
    <div class="w-10 h-10 rounded-xl bg-{{ $color }}/10 flex items-center justify-center mb-2">
        <x-icon name="{{ $icon }}" class="w-5 h-5 text-{{ $color }}" />
    </div>
    <div class="text-sm font-semibold text-text-main">{{ $label }}</div>
    <div class="text-[11px] text-text-secondary">{{ $description }}</div>
</a>