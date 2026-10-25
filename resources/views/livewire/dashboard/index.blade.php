<div class="space-y-5">
  <!-- Quick Actions -->
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
    <x-quick-action-card href="{{ route("pos.cashier") }}" label="Transaksi Baru" description="Kasir & penjualan" icon="credit-card" color="accent" />
    <x-quick-action-card href="{{ route("customers.create") }}" label="Tambah Pelanggan" description="Data pelanggan baru" icon="users" color="success" />
    <x-quick-action-card href="{{ route("products.create") }}" label="Tambah Produk" description="Katalog produk" icon="box" color="warning" />
    <x-quick-action-card href="{{ route("inventory.index") }}" label="Cek Stok" description="Inventori lensa" icon="layers" color="info" />
    <x-quick-action-card href="{{ route("optical-orders.index") }}" label="Pesanan Kacamata" description="Servis optik" icon="glasses" color="accent" />
    <x-quick-action-card href="{{ route("sales.index") }}" label="Riwayat Jual" description="Transaksi sebelumnya" icon="wallet" color="success" />
  </div>

  <!-- Stat Cards -->
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
    <x-stat-card label="Penjualan Hari Ini" value="Rp {{ number_format($todaySales, 0, ",", ".") }}" subtext="{{ $todayCount }} transaksi &middot; {{ $growth }}% vs kemarin" icon="wallet" color="accent" :trend="['label' => $growth >= 0 ? 'Naik {{ $growth }}%' : 'Turun {{ abs($growth) }}%', 'class' => $growth >= 0 ? 'text-success' : 'text-error']" />
    <x-stat-card label="Transaksi" value="{{ $todayCount }}" subtext="Hari ini" icon="credit-card" color="accent" />
    <x-stat-card label="Pesanan Aktif" value="{{ $openOrders }}" subtext="Belum selesai" icon="glasses" color="warning" />
    <x-stat-card label="Stok Menipis" value="{{ $lowStock }}" subtext="Perlu restock" icon="box" color="error" />
    <x-stat-card label="Piutang" value="Rp {{ number_format($unpaid, 0, ",", ".") }}" subtext="Belum lunas" icon="wallet" color="info" />
  </div>
<!-- Grid 2 kolom: Low Stock + Pending Orders -->
<div class="grid lg:grid-cols-2 gap-3">
  <div class="stat-card">
    <div class="flex items-center justify-between mb-3">
      <div class="text-sm font-semibold textxx-text-main">Stok Menipis ({{ $lowStock }})</div>
      @if ($lowStock > 0)
        <a href="{{ route("inventory.index") }}" class="text-[11px] text-accent hover:text-accent-hover font-medium">Lihat semua &rarr;</a>
      @endif
    </div>
    @forelse ($lowStockItems as $p)
      <div class="flex justify-between text-sm py-1.5 border-b border-slate-100 last:border-0">
        <span class="text-text-main">{{ $p->name }}</span>
        <span class="text-text-secondary">Sisa {{ $p->stock }}</span>
      </div>
    @empty
      <p class="text-sm text-text-secondary">Stok aman. Semua produk di atas minimum peringatan.</p>
    @endforelse
  </div>

  <div class="stat-card">
    <div class="flex items-center justify-between mb-3">
      <div class="text-sm font-semibold text-text-main">Pesanan Aktif ({{ $openOrders }})</div>
      @if ($openOrders > 0)
        <a href="{{ route("optical-orders.index") }}" class="text-[11px] text-accent hover:text-accent-hover font-medium">Lihat semua &rarr;</a>
      @endif
    </div>
    @forelse ($pendingOrders as $o)
      <div class="flex justify-between text-sm py-1.5 border-b border-slate-100 last:border-0">
        <div>
          <div class="text-text-main">{{ $o->code }} &middot; {{ $o->customer?->name ?? "-" }}</div>
          <div class="text-[10px] text-text-secondary">{{ $o->status }}</div>
        </div>
        <span class="status-badge {{ $o->status === 'siap_diambil' ? 'success' : ($o->status === 'DIBATALKAN' || $o->status === 'dibatalkan' ? 'error' : 'info') }}">
          {{ $o->status }}
        </span>
      </div>
    @empty
      <p class="text-sm text-text-secondary">Tidak ada pesanan aktif.</p>
    @endforelse
  </div>
</div>

<!-- Grid 2 kolom: Weekly Chart + Ready Orders -->
<div class="grid lg:grid-cols-2 gap-3">
  <div class="stat-card">
    <div class="text-sm font-semibold text-text-main mb-3">Penjualan 7 Hari Terakhir</div>
    <div class="flex items-end gap-2 h-32">
      @foreach ($weekly as $w)
        <div class="flex-1 flex flex-col items-center gap-1">
          <div class="w-full bg-accent rounded-t" style="height: {{ $maxWeekly > 0 ? round($w['value'] / $maxWeekly * 100) : 0 }}%"></div>
          <div class="text-[10px] text-text-secondary">{{ $w['label'] }}</div>
        </div>
      @endforeach
    </div>
  </div>

  <div class="stat-card">
    <div class="text-sm font-semibold text-text-main mb-3">Pesanan Siap Diambil ({{ $readyOrders->count() }})</div>
    @forelse ($readyOrders as $o)
      <div class="flex justify-between text-sm py-1.5 border-b border-slate-100 last:border-0">
        <span class="text-text-main">{{ $o->code }} &middot; {{ $o->customer?->name ?? "-" }}</span>
        <span class="status-badge success">Siap</span>
      </div>
    @empty
      <p class="text-sm text-text-secondary">Tidak ada pesanan siap diambil.</p>
    @endforelse
  </div>
</div>

<!-- Recent Sales -->
<div class="stat-card">
  <div class="flex items-center justify-between mb-3">
    <div class="text-sm font-semibold text-text-main">Transaksi Terakhir</div>
    <a href="{{ route("sales.index") }}" class="text-[11px] text-accent hover:text-accent-hover font-medium">Lihat semua &rarr;</a>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="text-left">
          <th class="py-2.5 pr-3 text-xs font-semibold text-text-secondary uppercase">Kode</th>
          <th class="py-2.5 pr-3 text-xs font-semibold text-text-secondary uppercase">Waktu</th>
          <th class="py-2.5 text-right pr-3 text-xs font-semibold text-text-secondary uppercase">Total</th>
          <th class="py-2.5 text-xs font-semibold text-text-secondary uppercase">Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($recentSales as $s)
          <tr class="border-b border-slate-100 last:border-0">
            <td class="py-2.5 pr-3 text-text-main">{{ $s->code }}</td>
            <td class="py-2.5 pr-3 text-text-secondary">{{ $s->sold_at->format("d M H:i") }}</td>
            <td class="py-2.5 text-right pr-3 text-text-main">Rp {{ number_format($s->total, 0, ",", ".") }}</td>
            <td class="py-2.5">
              <span class="status-badge {{ $s->payment_status === 'lunas' ? 'success' : ($s->payment_status === 'dp' ? 'warning' : 'error') }}">
                {{ $s->payment_status }}
              </span>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="py-4 text-center text-text-secondary">Belum ada transaksi.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>