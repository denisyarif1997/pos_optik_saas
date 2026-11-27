{{--
    resources/views/components/custom-pagination.blade.php

    Pemakaian (Livewire, default):
        <x-custom-pagination :paginator="$items" />

    Pemakaian (Blade biasa / non-Livewire):
        <x-custom-pagination :paginator="$items" :livewire="false" />

    Props:
        paginator   : LengthAwarePaginator (hasil ->paginate())
        onEachSide  : jumlah nomor halaman di kiri/kanan halaman aktif (default 2)
        livewire    : true = pakai wire:click, false = pakai link biasa
        pageName    : nama query page (default 'page'), sesuaikan jika pakai paginate(10, ['*'], 'xxx')
        showInfo    : tampilkan teks "Menampilkan x - y dari z"
--}}
@props([
    'paginator',
    'onEachSide' => 2,
    'livewire' => true,
    'pageName' => 'page',
    'showInfo' => true,
])

@if ($paginator->hasPages() || $showInfo)
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();

        // Susun daftar halaman: 1 ... (current-n .. current+n) ... last
        $pages = collect([1, $last])
            ->merge(range(max(1, $current - $onEachSide), min($last, $current + $onEachSide)))
            ->unique()
            ->sort()
            ->values();

        $items = [];
        $prev = null;
        foreach ($pages as $p) {
            if ($prev !== null && $p - $prev > 1) {
                $items[] = '...';
            }
            $items[] = $p;
            $prev = $p;
        }

        $base = 'inline-flex items-center justify-center min-w-[2.25rem] h-9 px-3 text-sm font-medium rounded-lg border transition';
        $idle = 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50 hover:text-emerald-600 hover:border-emerald-300';
        $active = 'bg-emerald-600 text-white border-emerald-600 shadow-sm cursor-default';
        $disabled = 'bg-gray-50 text-gray-300 border-gray-200 cursor-not-allowed';
    @endphp

    <nav role="navigation" aria-label="Pagination"
         class="flex flex-col items-center gap-3 sm:flex-row sm:justify-between">

        {{-- Info jumlah data --}}
        @if ($showInfo)
            <p class="text-sm text-gray-500">
                @if ($paginator->total() > 0)
                    Menampilkan
                    <span class="font-semibold text-gray-700">{{ $paginator->firstItem() }}</span>
                    -
                    <span class="font-semibold text-gray-700">{{ $paginator->lastItem() }}</span>
                    dari
                    <span class="font-semibold text-gray-700">{{ number_format($paginator->total(), 0, ',', '.') }}</span>
                    data
                @else
                    Tidak ada data
                @endif
            </p>
        @endif

        @if ($paginator->hasPages())
            <div class="flex flex-wrap items-center justify-center gap-1.5">

                {{-- Sebelumnya --}}
                @if ($paginator->onFirstPage())
                    <span class="{{ $base }} {{ $disabled }}" aria-disabled="true">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </span>
                @elseif ($livewire)
                    <button type="button" wire:click="previousPage('{{ $pageName }}')" wire:loading.attr="disabled"
                            class="{{ $base }} {{ $idle }}" aria-label="Halaman sebelumnya">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                       class="{{ $base }} {{ $idle }}" aria-label="Halaman sebelumnya">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                @endif

                {{-- Nomor halaman --}}
                @foreach ($items as $item)
                    @if ($item === '...')
                        <span class="inline-flex items-center justify-center min-w-[2.25rem] h-9 text-gray-400">…</span>
                    @elseif ($item === $current)
                        <span class="{{ $base }} {{ $active }}" aria-current="page">{{ $item }}</span>
                    @elseif ($livewire)
                        <button type="button" wire:key="page-{{ $pageName }}-{{ $item }}"
                                wire:click="gotoPage({{ $item }}, '{{ $pageName }}')"
                                class="{{ $base }} {{ $idle }}" aria-label="Ke halaman {{ $item }}">
                            {{ $item }}
                        </button>
                    @else
                        <a href="{{ $paginator->url($item) }}"
                           class="{{ $base }} {{ $idle }}" aria-label="Ke halaman {{ $item }}">
                            {{ $item }}
                        </a>
                    @endif
                @endforeach

                {{-- Berikutnya --}}
                @if ($paginator->hasMorePages())
                    @if ($livewire)
                        <button type="button" wire:click="nextPage('{{ $pageName }}')" wire:loading.attr="disabled"
                                class="{{ $base }} {{ $idle }}" aria-label="Halaman berikutnya">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    @else
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                           class="{{ $base }} {{ $idle }}" aria-label="Halaman berikutnya">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endif
                @else
                    <span class="{{ $base }} {{ $disabled }}" aria-disabled="true">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </span>
                @endif
            </div>
        @endif
    </nav>
@endif