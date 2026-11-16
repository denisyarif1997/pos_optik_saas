@props(['title' => '', 'description' => null, 'actions' => null])

<div class="page-header">
    <div>
        <h1 class="text-sm font-semibold text-text-main">{{ $title }}</h1>
        @if ($description)
            <div class="text-[11px] text-text-secondary mt-0.5">{{ $description }}</div>
        @endif
    </div>
    @if ($actions)
        <div class="flex items-center gap-2">
            {{ $actions }}
        </div>
    @endif
</div>