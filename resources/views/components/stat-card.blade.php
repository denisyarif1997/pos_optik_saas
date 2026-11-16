@props([
    'label' => '',
    'value' => '',
    'subtext' => '',
    'icon' => 'box',
    'iconColor' => 'accent',
    'trend' => null,
])

<div class="stat-card p-4">
    <div class="flex items-center justify-between">
        <div>
            <div class="text-xs font-semibold text-text-secondary uppercase tracking-wider mb-1">{{ $label }}</div>
            <div class="text-xl font-semibold text-text-main">{{ $value }}</div>
            @if ($subtext)
                <div class="text-[11px] text-text-secondary mt-0.5">{{ $subtext }}</div>
            @endif
        </div>
        <div class="w-9 h-9 rounded-lg bg-{{ $iconColor }}/10 flex items-center justify-center text-{{ $iconColor }}">
            <x-icon name="{{ $icon }}" class="w-4 h-4" />
        </div>
    </div>
    @if ($trend)
        <div class="mt-2 text-[11px] font-semibold {{ $trend['class'] }}">{{ $trend['label'] }}</div>
    @endif
</div>