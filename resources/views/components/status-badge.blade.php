@props(['status' => '', 'class' => 'info'])

<span class="status-badge {{ $class }}">
    {{ $status }}
</span>