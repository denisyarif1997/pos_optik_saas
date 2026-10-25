@props(['name' => 'box', 'class' => 'w-5 h-5'])

@php
$paths = [
    'dashboard' => '<rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/>',
    'pos' => '<rect x="3" y="4" width="18" height="13" rx="2"/><path d="M8 21h8M12 17v4M7 9h4M7 12.5h7"/>',
    'glasses' => '<circle cx="7" cy="15" r="3.2"/><circle cx="17" cy="15" r="3.2"/><path d="M10.2 15h3.6M3.8 15 2 12.5M20.2 15 22 12.5"/>',
    'box' => '<path d="M3.5 7.5 12 3l8.5 4.5v9L12 21l-8.5-4.5v-9Z"/><path d="M3.5 7.5 12 12l8.5-4.5M12 12v9"/>',
    'tag' => '<path d="M3.5 12V4.5A1 1 0 0 1 4.5 3.5H12L20.5 12a1.4 1.4 0 0 1 0 2L14 20.5a1.4 1.4 0 0 1-2 0L3.5 12Z"/><circle cx="8.5" cy="8.5" r="1.3"/>',
    'users' => '<circle cx="9" cy="8" r="3.2"/><path d="M3.5 20c.6-3.4 2.8-5 5.5-5s4.9 1.6 5.5 5M16 4.8a3.2 3.2 0 0 1 0 6.4M17.8 15.3c1.7.7 2.7 2.2 3 4.7"/>',
    'truck' => '<path d="M2.5 6.5h11v10h-11zM13.5 10.5h4l3 3.5v2.5h-7"/><circle cx="7" cy="18.5" r="1.8"/><circle cx="17" cy="18.5" r="1.8"/>',
    'return' => '<path d="M8 5 3.5 9.5 8 14"/><path d="M4 9.5h9a6 6 0 0 1 0 12h-2"/>',
    'shield' => '<path d="M12 3 5 5.8v5.4c0 4.4 2.9 7.4 7 9.3 4.1-1.9 7-4.9 7-9.3V5.8L12 3Z"/><path d="m9 11.5 2.2 2.2L15.5 9"/>',
    'chart' => '<path d="M4 4v15.5a.5.5 0 0 0 .5.5H20"/><path d="M8 15v-4M12 15V8M16 15v-6"/>',
    'wallet' => '<rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18M16 15h2"/>',
    'store' => '<path d="M4 9.5 5.5 4h13L20 9.5M4 9.5V20h16V9.5M4 9.5h16M9.5 20v-5.5h5V20"/>',
    'logout' => '<path d="M14 4H6v16h8M10 12h11M18 8.5 21.5 12 18 15.5"/>',
    'search' => '<circle cx="11" cy="11" r="6.5"/><path d="m20 20-4.2-4.2"/>',
    'plus' => '<path d="M12 5v14M5 12h14"/>',
    'print' => '<path d="M7 8V3.5h10V8M7 16H4.5A1.5 1.5 0 0 1 3 14.5v-6A1.5 1.5 0 0 1 4.5 7h15A1.5 1.5 0 0 1 21 8.5v6a1.5 1.5 0 0 1-1.5 1.5H17"/><rect x="7" y="14" width="10" height="7" rx="1"/>',
    'download' => '<path d="M12 4v11M7.5 11 12 15.5 16.5 11M4.5 19.5h15"/>',
    'check' => '<polyline points="20 6 9 17 4 12"/>',
    'settings' => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>',
];
@endphp

<svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $paths[$name] ?? $paths['box'] !!}</svg>
