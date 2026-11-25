

<?php
    $flash = collect(['success', 'error', 'warning', 'info'])
        ->filter(fn ($type) => session()->has($type))
        ->map(fn ($type) => ['type' => $type, 'message' => session($type)])
        ->values();
?>

<div
    x-data="notification(<?php echo \Illuminate\Support\Js::from($flash)->toHtml() ?>)"
    x-on:notify.window="add($event.detail)"
    class="pointer-events-none fixed inset-x-0 top-0 z-[100] flex flex-col items-end gap-3 px-4 py-4 sm:inset-x-auto sm:right-0 sm:w-[26rem] sm:px-5"
    role="region"
    aria-label="Notifikasi"
>
    <template x-for="item in items" :key="item.id">
        <div
            x-show="item.visible"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 scale-95"
            x-on:mouseenter="pause(item)"
            x-on:mouseleave="resume(item)"
            :role="item.type === 'error' ? 'alert' : 'status'"
            :aria-live="item.type === 'error' ? 'assertive' : 'polite'"
            class="pointer-events-auto relative w-full overflow-hidden rounded-xl border bg-white shadow-lg shadow-slate-900/10"
            :class="styles[item.type].border"
        >
            <div class="flex items-start gap-3 p-4">
                
                <span class="mt-0.5 grid size-8 shrink-0 place-items-center rounded-full" :class="styles[item.type].icon">
                    <template x-if="item.type === 'success'">
                        <svg class="size-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"/></svg>
                    </template>
                    <template x-if="item.type === 'error'">
                        <svg class="size-4" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/></svg>
                    </template>
                    <template x-if="item.type === 'warning'">
                        <svg class="size-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/></svg>
                    </template>
                    <template x-if="item.type === 'info'">
                        <svg class="size-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd"/></svg>
                    </template>
                </span>

                
                <div class="min-w-0 flex-1 pt-0.5">
                    <p class="text-sm font-semibold text-slate-900" x-text="item.title"></p>
                    <p class="mt-0.5 break-words text-sm text-slate-600" x-show="item.message" x-text="item.message"></p>
                </div>

                
                <button type="button" x-on:click="dismiss(item)"
                        class="-mr-1 -mt-1 grid size-7 shrink-0 place-items-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400"
                        aria-label="Tutup notifikasi">
                    <svg class="size-4" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/></svg>
                </button>
            </div>

            
            <div class="h-1 bg-slate-100" x-show="item.duration > 0">
                <div class="ntf-line h-full origin-left" :class="styles[item.type].bar"
                     :style="`animation: ntf-shrink ${item.duration}ms linear forwards; animation-play-state: ${item.paused ? 'paused' : 'running'}`"></div>
            </div>
        </div>
    </template>
</div>

<?php if (! $__env->hasRenderedOnce('eac870fb-90fd-4412-9cb1-bd24b5508882')): $__env->markAsRenderedOnce('eac870fb-90fd-4412-9cb1-bd24b5508882'); ?>
    <style>
        @keyframes ntf-shrink {
            from { transform: scaleX(1); }
            to   { transform: scaleX(0); }
        }
        @media (prefers-reduced-motion: reduce) {
            .ntf-line { animation: none !important; }
        }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('notification', (initial = []) => ({
                items: [],
                seq: 0,
                maxVisible: 4,

                titles: {
                    success: 'Berhasil',
                    error: 'Terjadi kesalahan',
                    warning: 'Perhatian',
                    info: 'Informasi',
                },

                styles: {
                    success: { border: 'border-emerald-200', icon: 'bg-emerald-100 text-emerald-600', bar: 'bg-emerald-500' },
                    error:   { border: 'border-rose-200',    icon: 'bg-rose-100 text-rose-600',       bar: 'bg-rose-500' },
                    warning: { border: 'border-amber-200',   icon: 'bg-amber-100 text-amber-600',     bar: 'bg-amber-500' },
                    info:    { border: 'border-sky-200',     icon: 'bg-sky-100 text-sky-600',         bar: 'bg-sky-500' },
                },

                init() {
                    initial.forEach((n) => this.add(n));
                },

                add(detail) {
                    // Livewire 3 bisa mengirim detail sebagai objek atau array
                    const d = Array.isArray(detail) ? detail[0] : detail;
                    if (!d || (!d.message && !d.title)) return;

                    const type = this.styles[d.type] ? d.type : 'info';
                    const duration = d.sticky ? 0 : (d.duration ?? (type === 'error' ? 8000 : 4000));

                    this.items.push({
                        id: ++this.seq,
                        type,
                        title: d.title ?? this.titles[type],
                        message: d.message ?? '',
                        duration,
                        remaining: duration,
                        paused: false,
                        visible: true,
                        timer: null,
                        startedAt: 0,
                    });

                    // Ambil versi reaktif dari item agar perubahan (paused, visible) ikut menggerakkan UI
                    const item = this.items[this.items.length - 1];

                    // Batasi jumlah toast agar layar tidak penuh
                    const active = this.items.filter((i) => i.visible);
                    if (active.length > this.maxVisible) this.dismiss(active[0]);

                    this.resume(item);
                },

                resume(item) {
                    if (item.duration <= 0 || !item.visible) return;
                    item.paused = false;
                    item.startedAt = Date.now();
                    clearTimeout(item.timer);
                    item.timer = setTimeout(() => this.dismiss(item), item.remaining);
                },

                pause(item) {
                    if (item.duration <= 0 || !item.visible) return;
                    clearTimeout(item.timer);
                    item.remaining = Math.max(0, item.remaining - (Date.now() - item.startedAt));
                    item.paused = true;
                },

                dismiss(item) {
                    clearTimeout(item.timer);
                    item.visible = false;
                    setTimeout(() => {
                        this.items = this.items.filter((i) => i.id !== item.id);
                    }, 200);
                },
            }));
        });
    </script>
<?php endif; ?><?php /**PATH C:\Webproject\Laravel\optik_app_saas\resources\views/components/notification.blade.php ENDPATH**/ ?>