@props(['title' => 'Belum ada data.', 'description' => '', 'actionHref' => null, 'actionLabel' => null])

<div class="empty-state">
    @if ($actionHref)
        <a href="{{ $actionHref }}" class="btn-primary inline-block w-auto">{{ $actionLabel ?? 'Buat baru' }}</a>
    @endif
    <h3 class="text-sm font-semibold text-text-main mt-3">{{ $title }}</h3>
    @if ($description)
        <p class="text-[11px] text-text-secondary mt-1">{{ $description }}</p>
    @endif
</div>